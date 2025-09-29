<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UserAnswer $model */

$this->title = 'Create User Answer';
$this->params['breadcrumbs'][] = ['label' => 'User Answers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-answer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
