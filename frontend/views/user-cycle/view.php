<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\UserCycle $model */

$this->title = $model->user->name;
$this->params['breadcrumbs'][] = ['label' => 'Қатысушылар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-cycle-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Өзгерту', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Өшіру', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model->user,
        'attributes' => [
            'username',
            'name',
            'organization',
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'course_id',
                'value' => $model->course ? $model->course->title_kz : null,
                'label' => 'Курс',
            ],
            [
                'attribute' => 'cycle_id',
                'value' => $model->cycle ? $model->cycle->title_kz : null,
                'label' => 'Цикл',
            ],
            'type',
            'status',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $fileDataProvider,
        'showHeader' => false,
        'summary' => false,
        'columns' => [
            [
                'attribute' => 'file_type_id',
                'value' => function ($model) {
                    return $model->fileType ? $model->fileType->file : null;
                },
            ],
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        basename($model->path),
                        Yii::getAlias('@web/' . $model->path),
                        ['target' => '_blank']
                    );
                },
            ],
        ],
    ]); ?>

    <div class="text-end mt-2">
        <?= Html::a(
            'Барлығын жүктеп алу',
            ['user-cycle/download-all', 'user_id' => $model->user_id], // your custom action
            ['class' => 'btn btn-secondary', 'target' => '_blank']
        ) ?>
    </div>



</div>
