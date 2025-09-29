<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Answer $model */

$this->title = 'Өзгерту: ' . $model->id;
?>
<div class="answer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
