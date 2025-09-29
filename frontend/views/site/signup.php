<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use common\models\Course;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Тіркелу';
?>
<div style="margin: 0 auto; max-width: 400px;">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">

        <!-- Logo -->
        <div class="text-center mb-3">
            <?= Html::img('@web/logo.png', [
                'alt' => Yii::$app->name,
                'style' => 'height:300px;'
            ]) ?>
        </div>

        <!-- Form -->
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')
                ->textInput(['autofocus' => true])
                ->label('Телефон') ?>

            <?= $form->field($model, 'name')->label('Аты - жөні') ?>

            <?= $form->field($model, 'organization')->label('Мекеме') ?>

            <?= $form->field($model, 'course_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Course::find()->all(), 'id', 'title_kz'),
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Мамандық') ?>

            <div class="form-group text-center mt-3">
                <?= Html::submitButton('Тіркелу', [
                    'class' => 'btn btn-success',
                    'name' => 'signup-button'
                ]) ?>

                <div class="text-end mt-2">
                    <?= Html::a('Қайту', ['site/login'], [
                        'class' => 'btn btn-secondary'
                    ]) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
