<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UserSurvey $model */

$this->title = 'Update User Survey: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Surveys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-survey-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
