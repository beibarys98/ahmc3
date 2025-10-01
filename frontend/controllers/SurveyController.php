<?php

namespace frontend\controllers;

use common\models\Answer;
use common\models\Question;
use common\models\search\UserSearch;
use common\models\search\UserTestSearch;
use common\models\Test;
use common\models\search\TestSearch;
use common\models\UserSurvey;
use common\models\UserTest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TestController implements the CRUD actions for Test model.
 */
class SurveyController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Test models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->query->andWhere(['type' => 'survey']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id, $mode = 'participants')
    {
        $searchModel = new UserTestSearch();
        $queryParams = $this->request->queryParams;
        $queryParams['UserTestSearch']['test_id'] = $id;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'mode' => $mode,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNew($id){
        $model = $this->findModel($id);
        $model->status = 'new';
        $model->save(false);
        return $this->redirect(['survey/view',
            'id' => $id,
            'mode' => 'test'
        ]);
    }

    public function actionReady($id){
        $model = $this->findModel($id);
        $model->status = 'ready';
        $model->save(false);
        return $this->redirect(['survey/view',
            'id' => $id
        ]);
    }

    public function actionPublish($id){
        $model = $this->findModel($id);
        $model->status = 'public';
        $model->save(false);
        return $this->redirect(['survey/view',
            'id' => $id
        ]);
    }

    public function actionEnd($id){
        $model = $this->findModel($id);
        $model->status = 'finished';
        $model->save(false);
        return $this->redirect(['survey/view',
            'id' => $id
        ]);
    }

    public function actionResult($id)
    {
        $test = Test::findOne($id);
        if (!$test) {
            throw new NotFoundHttpException("Test not found");
        }

        // Get participants
        $participants = UserTest::find()
            ->andWhere(['test_id' => $id])
            ->with('user') // preload users
            ->all();

        // Get all questions of the test
        $questions = Question::find()
            ->andWhere(['test_id' => $id])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header row (first cell empty, then participant names)
        $sheet->setCellValue('A1', 'Сұрақ / Қатысушы');
        $col = 'B';
        foreach ($participants as $p) {
            $sheet->setCellValue($col . '1', $p->user->name);
            $col++;
        }

        // Fill questions + answers
        $row = 2;
        foreach ($questions as $q) {
            // First column = question title
            $sheet->setCellValue('A' . $row, $q->question ?? ('Q' . $q->id));

            $col = 'B';
            foreach ($participants as $p) {
                // Find participant's answer for this question
                $userSurvey = UserSurvey::find()
                    ->andWhere([
                        'user_id' => $p->user_id,
                        'question_id' => $q->id,
                    ])
                    ->one();

                $answerText = '';
                if ($userSurvey) {
                    if ($userSurvey->answer_id) {
                        // load Answer model text
                        $answer = Answer::findOne($userSurvey->answer_id);
                        $answerText = $answer ? $answer->answer : '';
                    } elseif ($userSurvey->answer_text) {
                        $answerText = $userSurvey->answer_text;
                    }
                }

                $sheet->setCellValue($col . $row, $answerText);
                $col++;
            }

            $row++;
        }

        // Save and return file
        $filePath = 'uploads/' . time() . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        $filename = 'Анкета Жауаптары - ' . $test->title_kz . '.xlsx';
        return Yii::$app->response->sendFile($filePath, $filename);
    }


    public function actionCreate()
    {
        $model = new Test();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->file  = UploadedFile::getInstance($model, 'file');

                if($model->file){
                    $filePath = 'uploads/'
                        . $this->uniqueId
                        . '.' . $model->file->extension;

                    $model->file->saveAs($filePath);
                    $model->status = 'new';
                    $model->type = 'survey';
                    $model->save(false);

                    $this->parse($filePath, $model->id);

                    unlink($filePath);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    private function parse($filePath, $test_id)
    {
        $zip = new \ZipArchive();
        if ($zip->open($filePath) === true) {
            $xmlContent = $zip->getFromName('word/document.xml');
            $zip->close();

            // Remove MathML (entire <m:oMath> blocks)
            $xmlContent = preg_replace('/<m:oMath[^>]*>.*?<\/m:oMath>/s', '', $xmlContent);

            // Load the corrected XML into DOMDocument
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true); // Prevent warnings
            $dom->loadXML($xmlContent);
            libxml_clear_errors();

            // Extract text (ignoring formulas and images)
            $paragraphs = $dom->getElementsByTagName('p');
            $text = '';
            foreach ($paragraphs as $p) {
                $text .= trim($p->textContent) . "\n";
            }

            // Call function to process extracted text
            $this->processText($text, $test_id);
        } else {
            throw new \Exception('Failed to open the .docx file.');
        }
    }

    private function processText($text, $test_id)
    {
        $lines = explode("\n", $text);
        $currentQuestionText = '';
        $answers = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Check if the line is an answer
            if (preg_match('/^[\p{Latin}\p{Cyrillic}]\s*[.)]\s*(.*)/u', $line, $match)) {
                $answers[] = trim($match[1]);
            }
            // Check if the line is a question number
            elseif (preg_match('/^\s*\d+\s*[.)]\s*(.*)/', $line, $match)) {
                if ($currentQuestionText !== '') {
                    $this->saveQuestion($currentQuestionText, $answers, $test_id);
                }
                // Start a new question
                $currentQuestionText = $match[1];
                $answers = [];
            }
            // Otherwise, it's part of the question text
            else {
                $currentQuestionText .= "\n" . $line;
            }
        }

        // Save the last question
        if ($currentQuestionText !== '') {
            $this->saveQuestion($currentQuestionText, $answers, $test_id);
        }
    }

    private function saveQuestion($questionText, $answers, $test_id)
    {
        $question = new Question();
        $question->test_id = $test_id;
        $question->question = trim($questionText);

        $question->save(false);

        if (!empty($answers)) {
            $firstAnswerId = null;

            foreach ($answers as $index => $ansText) {
                $answer = new Answer();
                $answer->question_id = $question->id;
                $answer->answer = trim($ansText);
                $answer->save(false);

                if ($index === 0) {
                    $firstAnswerId = $answer->id;
                }
            }

            if ($firstAnswerId !== null) {
                $question->answer = $firstAnswerId;
                $question->save(false, ['answer']);
            }
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save(false)) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Test::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
