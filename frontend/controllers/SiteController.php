<?php

namespace frontend\controllers;

use common\models\Answer;
use common\models\File;
use common\models\Question;
use common\models\search\CycleSearch;
use common\models\search\FileTypeSearch;
use common\models\Test;
use common\models\User;
use common\models\UserAnswer;
use common\models\UserCycle;
use common\models\UserTest;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\SignupForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }

        if(Yii::$app->user->identity->username == 'admin') {
            return $this->redirect('/user-cycle/index');
        }

        $user_cycle = UserCycle::findOne(['user_id' => Yii::$app->user->identity->id]);

        if($user_cycle->status == 'enrolled') {
            return $this->redirect(['cycle', 'id' => $user_cycle->cycle_id]);
        }

        $course_id = $user_cycle->course_id;
        $searchModel = new CycleSearch();
        $params = $this->request->queryParams;
        $params['CycleSearch']['course_id'] = $course_id;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChooseType($id){

        $user_cycle = UserCycle::findOne(['user_id' => Yii::$app->user->identity->id]);
        $user_cycle->cycle_id = $id;
        $user_cycle->save();

        $data = [
            [
                'label' => 'Бюджет негізінде',
                'file' => '',
                'checkbox' => false,
                'type' => 'budget',
            ],
            [
                'label' => 'Келісім шарт негізінде',
                'file' => 'uploads/contract.pdf',
                'checkbox' => true,
                'type' => 'contract',
            ],
        ];

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);

        return $this->render('choose-type', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUploadFiles($type)
    {
        $user_id = Yii::$app->user->identity->id;
        $user_cycle = UserCycle::findOne(['user_id' => $user_id]);

        if ($user_cycle) {
            $user_cycle->type = $type;
            $user_cycle->save();
        }

        // Determine which file type IDs to use
        if ($type === 'budget') {
            $fileTypeIds = [1, 2, 3, 4, 5, 6];
        } else {
            $fileTypeIds = [1, 2, 3, 4, 5, 7];
        }

        // Modify the search model to filter by those IDs
        $searchModel = new FileTypeSearch();
        $params = $this->request->queryParams;
        $params['FileTypeSearch']['id'] = $fileTypeIds;
        $dataProvider = $searchModel->search($params);

        return $this->render('upload-files', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUploadSingleFile($id)
    {
        // Get the uploaded file
        $uploadedFile = UploadedFile::getInstanceByName('singleFile');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('Файл табылған жоқ.');
        }

        $filePath = 'uploads/' . uniqid() . '.' . $uploadedFile->extension;

        // Save the file
        if ($uploadedFile->saveAs($filePath)) {

            $file = new File();
            $user_id = Yii::$app->user->identity->id;
            $file->user_id = $user_id;
            $user_cycle = UserCycle::findOne(['user_id' => $user_id]);
            $file->cycle_id = $user_cycle->cycle_id;
            $file->file_type_id = $id;
            $file->path = $filePath;
            $file->save(false);
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    public function actionCheckFiles(){
        $userId = Yii::$app->user->id;
        $userCycle = UserCycle::findOne(['user_id' => $userId]);
        $requiredFileTypeIds = ($userCycle->type == 'budget')
            ? [1, 2, 3, 4, 6]
            : [1, 2, 3, 4, 7];
        $uploadedCount = File::find()
            ->andWhere([
                'user_id' => $userId,
                'cycle_id' => $userCycle->cycle_id,
            ])
            ->andWhere(['in', 'file_type_id', $requiredFileTypeIds])
            ->select('file_type_id')
            ->distinct()
            ->count();

        if ($uploadedCount == count($requiredFileTypeIds)) {
            return $this->redirect(['cycle', 'id' => $userCycle->cycle_id]);
        } else {
            Yii::$app->session->setFlash('danger', 'Барлық файлдар жүктелмеген.');
            return $this->redirect(['upload-files', 'type' => $userCycle->type]);
        }
    }

    public function actionCycle($id){
        $userId = Yii::$app->user->id;
        $user = new ActiveDataProvider([
            'query' => User::find()->andWhere(['id' => $userId]),
        ]);

        $test = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['cycle_id' => $id])->andWhere(['status' => ['public', 'finished', 'certificated']]),
        ]);

        return $this->render('cycle', [
            'user' => $user,
            'test' => $test,
        ]);
    }

    public function actionTest($id, $qid = null){

        $test = Test::findOne($id);
        if($qid){
            $question = Question::findOne($qid);
        }else{
            $question = Question::findOne(['test_id' => $test->id]);
        }

        $participant = UserTest ::findone(['user_id' => Yii::$app->user->id, 'test_id' => $test->id]);

        if(!$participant){
            $participant = new UserTest();
            $participant->user_id = Yii::$app->user->id;
            $participant->test_id = $test->id;
            $participant->start_time = date('Y-m-d H:i:s');
            $participant->save(false);
        }

        return $this->render('test', [
            'test' => $test,
            'question' => $question,
            'participant' => $participant,
        ]);
    }

    public function actionSubmit()
    {
        $answerId = Yii::$app->request->get('answer_id');
        $questionId = Yii::$app->request->get('question_id');
        $participantId = UserTest::findOne(['user_id' => Yii::$app->user->id])->id;
        $participantAnswer = UserAnswer::findOne([
            'user_id' => $participantId,
            'question_id' => $questionId,
        ]);
        if (!$participantAnswer) {
            $participantAnswer = new UserAnswer();
            $participantAnswer->user_id = $participantId;
            $participantAnswer->question_id = $questionId;
        }
        $participantAnswer->answer_id = $answerId;
        $participantAnswer->save(false);

        $nextQuestion = Question::find()
            ->andWhere(['test_id' => Question::findOne($questionId)->test_id])
            ->andWhere(['>', 'id', $questionId])
            ->orderBy(['id' => SORT_ASC])
            ->one();
        if (!$nextQuestion) {
            $nextQuestion = Question::findOne(['test_id' => Question::findOne($questionId)->test_id]);
        }

        return $this->redirect(['test', 'id' => $nextQuestion->test_id, 'qid' => $nextQuestion->id]);
    }

    public function actionEnd($qid)
    {
        $test_id = Question::findOne($qid)->test_id;
        $test = Test::findOne($test_id);
        $participant = UserTest::findOne(['user_id' => Yii::$app->user->id]);

        //unanswered questions? return to test
        $now = new DateTime();
        $startTime = new DateTime($participant->start_time);
        $testDuration = new DateTime($test->duration);
        $h = (int)$testDuration->format('H') * 3600;
        $i = (int)$testDuration->format('i') * 60;
        $s = (int)$testDuration->format('s');
        $durationInSeconds = $h + $i + $s;
        $timeElapsed = $now->getTimestamp() - $startTime->getTimestamp();
        if ($timeElapsed < $durationInSeconds) {
            $totalQuestions = Question::find()
                ->andWhere(['test_id' => $test->id])
                ->count();
            $answeredQuestions = UserAnswer::find()
                ->joinWith('question')
                ->andWhere(['user_answer.user_id' => $participant->id])
                ->andWhere(['question.test_id' => $test->id])
                ->andWhere(['IS NOT', 'user_answer.answer_id', null])
                ->count();
            if ($answeredQuestions != $totalQuestions) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Барлық сұрақтарға жауап беріңіз!'));
                return $this->redirect(['test', 'id' => $test_id, 'qid' => $qid]);
            }
        }

        //save end time
        $participant->end_time = (new \DateTime())->format('Y-m-d H:i:s');
        $participant->save(false);

        //save results in db
        $questions = Question::find()->andWhere(['test_id' => $test->id])->all();
        $score = 0;
        foreach ($questions as $q) {
            $participantAnswer = UserAnswer::findOne([
                'user_id' => $participant->id,
                'question_id' => $q->id]);

            if ($participantAnswer !== null) {;
                if ($participantAnswer->answer_id == $q->answer) {
                    $score++;
                }
            }
        }
        $participant->result = $score;
        $participant->save(false);

        $user_cycle = UserCycle::findOne(['user_id' => $participant->user_id]);

        return $this->redirect(['cycle', 'id' => $user_cycle->cycle_id]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/site/index');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('/site/index');
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect('/site/login');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Сәтті тіркелдіңіз!');
            return $this->redirect('/site/login');
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
