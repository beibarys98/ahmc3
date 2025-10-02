<?php

use common\models\Answer;
use common\models\Question;
use common\models\User;
use common\models\UserCycle;
use common\models\UserTest;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Test $model */

$this->title = $model->title_kz;
$this->params['breadcrumbs'][] = ['label' => 'Анкеталар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr>

    <div>
        <?= Html::a('жаңа',
            ['survey/new', 'id' => $model->id],
            ['class' => $model->status == 'new' ? 'btn btn-primary' : 'btn btn-outline-primary']) ?>
        <?= Html::a('дайын',
            ['survey/ready', 'id' => $model->id],
            ['class' => $model->status == 'ready' ? 'btn btn-primary' : 'btn btn-outline-primary']) ?>
        <?= Html::a('жариялау',
            ['survey/publish', 'id' => $model->id],
            ['class' => $model->status == 'public' ? 'btn btn-primary' : 'btn btn-outline-primary']) ?>
        <?= Html::a('аяқтау',
            ['survey/end', 'id' => $model->id],
            ['class' => $model->status == 'finished' ? 'btn btn-primary' : 'btn btn-outline-primary']) ?>
    </div>

    <hr>

    <div>
        <?= Html::a('анкета',
            ['survey/view', 'id' => $model->id, 'mode' => 'test'],
            ['class' => $mode == 'test' ? 'btn btn-secondary' : 'btn btn-outline-secondary']) ?>
        <?= Html::a('қатысушылар',
            ['survey/view', 'id' => $model->id, 'mode' => 'participants'],
            ['class' => $mode == 'participants' ? 'btn btn-secondary' : 'btn btn-outline-secondary']) ?>
    </div>

    <hr>

    <div>
        <?= Html::a('нәтиже',
            ['survey/result', 'id' => $model->id],
            ['class' => 'btn btn-outline-danger']) ?>
    </div>

    <hr>

    <?php if($mode == 'test'): ?>
        <div style="font-size: 20px;">
            <?php
            $questions = Question::find()->andWhere(['test_id' => $model->id])->all();

            foreach ($questions as $index => $question) {
                echo Html::a('_/',
                        ['question/update', 'id' => $question->id],
                        ['class' => 'btn btn-sm btn-outline-primary']) . ' ';
                echo Html::a('Х',
                        ['question/delete', 'id' => $question->id],
                        [
                            'class' => 'btn btn-sm btn-outline-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                                'method' => 'post',
                            ],
                        ]) . ' ';
                echo $index + 1 . '. ';
                if($question->img_path){
                    echo Html::img(Yii::getAlias('@web/') . $question->img_path, ['style' => 'max-width: 80%; padding: 10px;']) . '<br>';
                }else{
                    echo $question->question . '<br>';
                }
                $answers = Answer::find()->andWhere(['question_id' => $question->id])->all();
                $alphabet = range('A', 'Z');
                foreach ($answers as $index2 => $answer) {
                    echo '<span style="margin: 15px;"></span>'
                        . Html::a('_/',
                            ['answer/update', 'id' => $answer->id],
                            ['class' => 'btn btn-sm btn-outline-primary']) . ' ';
                    echo Html::a('Х',
                            ['answer/delete', 'id' => $answer->id],
                            [
                                'class' => 'btn btn-sm btn-outline-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Сенімдісіз бе?'),
                                    'method' => 'post',
                                ],
                            ]) . ' ';
                    echo $alphabet[$index2] . '. ';
                    if($answer->img_path){
                        echo Html::img(Yii::getAlias('@web/') . $answer->img_path, ['style' => 'max-width: 80%; padding: 10px;']) . '<br>';
                    }else{
                        echo $answer->answer . '<br>';
                    }
                }
                echo '<span style="margin: 15px;"></span>'
                    . Html::a('+ жауап',
                        ['answer/create', 'id' => $question->id],
                        ['class' => 'btn btn-sm btn-outline-primary']) . '<br>';

            }
            echo Html::a('+ сұрақ',
                    ['question/create', 'id' => $model->id],
                    ['class' => 'btn btn-sm btn-outline-primary']) . '<br>';
            ?>
        </div>
    <?php else: ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['width' => '5%'],
                ],
                [
                    'attribute' => 'name',
                    'value' => 'user.name',
                    'label' => 'Аты-жөні',
                ],
                [
                    'attribute' => 'organization',
                    'value' => 'user.organization',
                    'label' => 'Мекеме',
                ],
                'start_time',
                'end_time',
                [
                    'class' => ActionColumn::className(),
                    'template' => '{view} {update} {delete}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        if (in_array($action, ['view', 'update'])) {
                            $userCycle = UserCycle::find()->andWhere(['user_id' => $model->user_id])->one();
                            return Url::toRoute(["user-cycle/$action", 'id' => $userCycle->id]);
                        }
                        if ($action === 'delete') {
                            return Url::toRoute(["user-test/delete", 'id' => $model->id]);
                        }
                    },
                ]
            ],
        ]); ?>
    <?php endif; ?>

</div>
