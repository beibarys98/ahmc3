<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cycle $model */

$this->title = 'Өзгерту: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Циклдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Өзгерту';
?>
<div class="cycle-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
