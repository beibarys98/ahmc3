<?php

use common\models\UserSurvey;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\UserSurveySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'User Surveys';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-survey-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User Survey', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'question_id',
            'answer_id',
            'answer_text:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, UserSurvey $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
