<?php

use common\models\Cycle;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Test $model */

$this->title = 'Қосу';
$this->params['breadcrumbs'][] = ['label' => 'Тесттер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Get only cycles with course.category_id = 1 or 2
$cycles = Cycle::find()
    ->joinWith('course') // assumes Cycle has getCourse() relation
    ->andWhere(['course.category_id' => [1, 2]])
    ->all();

$cycleMap = ArrayHelper::map($cycles, 'id', function ($model) {
    return $model->title_kz . ' (' . $model->course->title_kz . ')';
});
?>
<div class="test-create">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'duration')->textInput([
        'type' => 'time',
        'step' => 1
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сақтау', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
