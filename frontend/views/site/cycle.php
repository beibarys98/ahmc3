<?php

/** @var yii\web\View $this */

use common\models\Test;
use common\models\User;
use common\models\UserTest;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="card p-3 shadow">
        <h1>Қатысушы</h1>

        <?= GridView::widget([
            'dataProvider' => $user,
            'summary' => false,
            'showHeader' => false,
            'columns' => [
                'username',
                'name',
                'organization',
                [
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-end'],
                    'content' => function ($model) {
                        return Html::a('Өзгерту', ['user/update', 'id' => $model->id], [
                            'class' => 'btn btn-outline-secondary'
                        ]);
                    },
                ],
            ],
        ]); ?>
    </div>

    <br>

    <div class="card p-3 shadow">
        <h1>Тесттер</h1>

        <?= GridView::widget([
            'dataProvider' => $test,
            'summary' => false,
            'showHeader' => false,
            'columns' => [
                'title_kz',
                'status',
                'duration',
                [
                    'header' => '',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-end'],
                    'content' => function ($model) {
                        $user_id = Yii::$app->user->id;
                        $participant = UserTest::findOne(['user_id' => $user_id, 'test_id' => $model->id]);
                        $isDisabled = $participant && $participant->end_time;

                        if ($isDisabled) {
                            return Html::tag('span', 'Тапсырылды', [
                                'class' => 'btn btn-outline-secondary disabled'
                            ]);
                        }

                        return Html::a('Бастау', ['test', 'id' => $model->id], [
                            'class' => 'btn btn-outline-secondary',
                            'data' => [
                                'confirm' => 'Сенімдісіз бе?',
                            ],
                        ]);
                    },
                ]



            ],
        ]); ?>
    </div>

    <br>

    <div class="card p-3 shadow">
        <h1>Анкета</h1>
    </div>

    <br>

    <div class="card p-3 shadow">
        <h1>Сертификат</h1>
    </div>
</div>
