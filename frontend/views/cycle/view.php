<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Cycle $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Циклдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cycle-view">

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
        'model' => $model,
        'attributes' => [
            'id',
            'course_id',
            'title_kz',
            'title_ru',
            'month',
            'duration',
        ],
    ]) ?>

</div>
