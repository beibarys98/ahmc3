<?php

namespace frontend\controllers;

use common\models\Answer;
use common\models\Question;
use common\models\search\UserSearch;
use common\models\search\UserTestSearch;
use common\models\Test;
use common\models\search\TestSearch;
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
class TestController extends Controller
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
        return $this->redirect(['test/view',
            'id' => $id,
            'mode' => 'test'
        ]);
    }

    public function actionReady($id){
        $model = $this->findModel($id);
        $model->status = 'ready';
        $model->save(false);
        return $this->redirect(['test/view',
            'id' => $id
        ]);
    }

    public function actionPublish($id){
        $model = $this->findModel($id);
        $model->status = 'public';
        $model->save(false);
        return $this->redirect(['test/view',
            'id' => $id
        ]);
    }

    public function actionEnd($id){
        $model = $this->findModel($id);
        $model->status = 'finished';
        $model->save(false);
        return $this->redirect(['test/view',
            'id' => $id
        ]);
    }

    public function actionResult($id)
    {
        //save results in xlsx
        $participants = UserTest::find()
            ->andWhere(['test_id' => $id])
            ->orderBy(['result' => SORT_DESC])
            ->all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Есімі');
        $sheet->setCellValue('B1', 'Мекеме');
        $sheet->setCellValue('C1', 'Нәтиже');

        $row = 2;
        foreach ($participants as $participant) {
            $sheet->setCellValue('A' . $row, $participant->user->name);
            $sheet->setCellValue('B' . $row, $participant->user->organization);
            $sheet->setCellValue('C' . $row, $participant->result);
            $row++;
        }

        $filePath = 'uploads/' . time() . '.xlsx'; // Construct the file path
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        $test = Test::findOne($id);
        $filename = 'Нәтиже - ' . $test->title_kz . '.xlsx';

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
                if ($currentQuestionText !== '' && !empty($answers)) {
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
        if ($currentQuestionText !== '' && !empty($answers)) {
            $this->saveQuestion($currentQuestionText, $answers, $test_id);
        }
    }

    private function saveQuestion($questionText, $answers, $test_id)
    {
        $question = new Question();
        $question->test_id = $test_id;
        $question->question = trim($questionText);
        $question->save(false);

        $firstAnswerId = null; // Store the first answer's ID

        foreach ($answers as $index => $ansText) {
            $answer = new Answer();
            $answer->question_id = $question->id;
            $answer->answer = trim($ansText);
            $answer->save(false);

            if ($index === 0) {
                $firstAnswerId = $answer->id; // Save first answer's ID
            }
        }

        // Update question->answer with the first answer's ID
        if ($firstAnswerId !== null) {
            $question->answer = $firstAnswerId;
            $question->save(false, ['answer']); // Save only 'answer' field
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
