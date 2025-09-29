<?php

use common\models\Course;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Cycle $model */
/** @var yii\widgets\ActiveForm $form */

$courses = Course::find()->select(['id', 'title_kz', 'category_id'])->asArray()->all();
$courseMap = ArrayHelper::map($courses, 'id', 'title_kz');
$courseCategories = ArrayHelper::map($courses, 'id', 'category_id');
$courseCategoriesJson = json_encode($courseCategories);

$durations = [
    1 => [
        '1 апта' => '1 апта',
        '2 апта' => '2 апта',
        '3 апта' => '3 апта',
    ],
    2 => [
        '1 ай' => '1 ай',
        '1,5 ай' => '1,5 ай',
    ],
];
$durationsJson = json_encode($durations);
?>

<div class="cycle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_id')->widget(Select2::class, [
        'data' => $courseMap,
        'language' => 'kk',
        'options' => [
            'placeholder' => '',
            'id' => 'course-id-select',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'title_kz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>

    <div id="month-field">
        <?= $form->field($model, 'month')->widget(Select2::class, [
            'data' => [
                'Қаңтар' => 'Қаңтар',
                'Ақпан' => 'Ақпан',
                'Наурыз' => 'Наурыз',
                'Сәуір' => 'Сәуір',
                'Мамыр' => 'Мамыр',
                'Маусым' => 'Маусым',
                'Шілде' => 'Шілде',
                'Тамыз' => 'Тамыз',
                'Қыркүйек' => 'Қыркүйек',
                'Қазан' => 'Қазан',
                'Қараша' => 'Қараша',
                'Желтоқсан' => 'Желтоқсан',
            ],
            'language' => 'kk',
            'options' => ['placeholder' => ''],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>

    <div id="duration-field">
        <?= $form->field($model, 'duration')->widget(Select2::class, [
            'data' => [],
            'options' => ['id' => 'duration-select', 'placeholder' => ''],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сақтау', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("
    let courseCategories = {$courseCategoriesJson};
    let durations = {$durationsJson};

    function toggleFields(categoryId) {
        if (categoryId === 1 || categoryId === 2) {
            $('#month-field').show();
            $('#duration-field').show();

            let options = '<option></option>';
            for (let val in durations[categoryId]) {
                options += `<option value='\${val}'>\${durations[categoryId][val]}</option>`;
            }
            $('#duration-select').html(options).val(null).trigger('change');
        } else {
            $('#month-field').hide();
            $('#duration-field').hide();
            $('#duration-select').html('<option></option>').val(null).trigger('change');
        }
    }

    // Initial state if editing existing model
    let initialCourseId = $('#course-id-select').val();
    if (initialCourseId) {
        let initialCategoryId = courseCategories[initialCourseId];
        toggleFields(initialCategoryId);
    }

    // On change
    $('#course-id-select').on('change', function() {
        let courseId = $(this).val();
        let categoryId = courseCategories[courseId];
        toggleFields(categoryId);
    });
");
?>
