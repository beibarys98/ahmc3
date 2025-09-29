<?php

use common\models\Course;
use common\models\Cycle;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\UserCycle $model */

$this->title = 'Қосу';
$this->params['breadcrumbs'][] = ['label' => 'Қатысушылар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-cycle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <!-- User fields -->
    <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'organization')->textInput(['maxlength' => true]) ?>

    <!-- UserCycle fields -->
    <?= $form->field($model, 'course_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Course::find()->all(), 'id', 'title_kz'),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'cycle_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Cycle::find()->all(), 'id', 'title_kz'),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сақтау', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
