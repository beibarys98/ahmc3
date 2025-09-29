<?php

use common\models\Cycle;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\CycleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Циклдер';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cycle-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Қосу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '5'],
            ],
            [
				'attribute' => 'course_title',
				'value' => 'course.title_kz',
				'label' => 'Мамандық',
			],
			[
				'attribute' => 'title_kz',
				'label' => 'Атауы',
			],
			[
				'attribute' => 'title_ru',
				'label' => 'Название',
			],
			[
				'attribute' => 'month',
				'label' => 'Айы',
			],
			[
				'attribute' => 'duration',
				'label' => 'Ұзақтығы',
			],

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Cycle $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
