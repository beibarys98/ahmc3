<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Кіру';
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
		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

			<?= $form->field($model, 'username')
				->textInput(['autofocus' => true, 'id' => 'username-input'])
				->label('Телефон') ?>

			<div id="password-field" style="display: none;">
				<?= $form->field($model, 'password')->passwordInput()->label('Құпия сөз') ?>
			</div>

			<div class="form-group text-center mt-3">
				<?= Html::submitButton('Кіру', [
					'class' => 'btn btn-primary mb-2',
					'name' => 'login-button'
				]) ?>

				<div class="text-end">
					<?= Html::a('Тіркелу', ['site/signup'], [
						'class' => 'btn btn-success'
					]) ?>
				</div>
			</div>

		<?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$js = <<<JS
document.getElementById('username-input').addEventListener('input', function() {
    const passwordField = document.getElementById('password-field');
    if (this.value.trim().toLowerCase() === 'admin') {
        passwordField.style.display = 'block';
    } else {
        passwordField.style.display = 'none';
    }
});
JS;

$this->registerJs($js);
?>
