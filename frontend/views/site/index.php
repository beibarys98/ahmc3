<?php

/** @var yii\web\View $this */

use yii\grid\GridView;

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <h1>Цикл таңдаңыз</h1>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'showHeader' => false,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '5'],
            ],
            'title_kz',
            'month',
            'duration',
            [
                'format' => 'raw',
                'value' => function ($model) {
                    return \yii\helpers\Html::tag('div',
                        \yii\helpers\Html::a(
                            'Таңдау',
                            ['site/choose-type', 'id' => $model->id],
                            ['class' => 'btn btn-sm btn-success']
                        ),
                        ['class' => 'text-end'] // Bootstrap 5 right-align class
                    );
                },
            ],

        ],
    ]); ?>
</div>
