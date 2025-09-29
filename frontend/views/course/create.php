<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Course $model */

$this->title = 'Қосу';
$this->params['breadcrumbs'][] = ['label' => 'Мамандықтар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
