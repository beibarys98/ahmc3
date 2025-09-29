<?php

use common\models\Cycle;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Test $model */
/** @var yii\widgets\ActiveForm $form */

// Get only cycles with course.category_id = 1 or 2
$cycles = Cycle::find()
    ->joinWith('course') // assumes Cycle has getCourse() relation
    ->andWhere(['course.category_id' => [1, 2]])
    ->all();

$cycleMap = ArrayHelper::map($cycles, 'id', function ($model) {
    return $model->title_kz . ' (' . $model->course->title_kz . ')';
});
?>

<div class="test-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cycle_id')->widget(Select2::class, [
        'data' => $cycleMap,
        'language' => 'kk',
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'title_kz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'duration')->textInput([
        'type' => 'time',
        'step' => 1
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сақтау', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
