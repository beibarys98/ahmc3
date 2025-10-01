<?php

namespace frontend\controllers;

use common\models\File;
use common\models\UserCycle;
use common\models\search\UserCycleSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserCycleController implements the CRUD actions for UserCycle model.
 */
class UserCycleController extends Controller
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
     * Lists all UserCycle models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserCycleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserCycle model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // подзапрос: для каждого file_type_id берём максимальный id
        $subQuery = File::find()
            ->select(['MAX(id)'])
            ->andWhere(['user_id' => $model->user->id])
            ->groupBy('file_type_id');

        $fileQuery = File::find()
            ->andWhere(['id' => $subQuery]); // только последние по типу

        $fileDataProvider = new ActiveDataProvider([
            'query' => $fileQuery,
            'pagination' => false, // можно отключить, если точно один на тип
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'fileDataProvider' => $fileDataProvider,
        ]);
    }



    /**
     * Creates a new UserCycle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $user = new \common\models\User();
        $userCycle = new \common\models\UserCycle();

        if (Yii::$app->request->isPost) {
            $user->load(Yii::$app->request->post());
            $userCycle->load(Yii::$app->request->post());

            if ($user->save(false)) {
                $userCycle->user_id = $user->id;
                if ($userCycle->save(false)) {
                    return $this->redirect(['view', 'id' => $userCycle->id]);
                }
            }
        }

        return $this->render('create', [
            'user' => $user,
            'model' => $userCycle,
        ]);
    }

    /**
     * Updates an existing UserCycle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->user->load($this->request->post()) && $model->save() && $model->user->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserCycle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->user) {
            $model->user->delete();
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionDownloadAll($user_id)
    {
        // подзапрос: для каждого file_type_id берём максимальный id
        $subQuery = File::find()
            ->select(['MAX(id)'])
            ->andWhere(['user_id' => $user_id])
            ->groupBy('file_type_id');

        // основной запрос: только последние по типу
        $files = File::find()
            ->andWhere(['id' => $subQuery])
            ->all();

        $zip = new \ZipArchive();
        $zipFile = Yii::getAlias('@runtime') . "/files_$user_id.zip";

        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach ($files as $file) {
                $path = Yii::getAlias('@webroot/' . $file->path);
                if (file_exists($path)) {
                    $zip->addFile($path, basename($path));
                }
            }
            $zip->close();
        }

        return Yii::$app->response->sendFile($zipFile);
    }

    /**
     * Finds the UserCycle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return UserCycle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserCycle::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
