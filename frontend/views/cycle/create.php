<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cycle $model */

$this->title = 'Қосу';
$this->params['breadcrumbs'][] = ['label' => 'Циклдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cycle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
