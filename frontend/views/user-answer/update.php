<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UserAnswer $model */

$this->title = 'Update User Answer: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Answers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-answer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
