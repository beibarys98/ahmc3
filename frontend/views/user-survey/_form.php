<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\UserSurvey $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-survey-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'question_id')->textInput() ?>

    <?= $form->field($model, 'answer_id')->textInput() ?>

    <?= $form->field($model, 'answer_text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
