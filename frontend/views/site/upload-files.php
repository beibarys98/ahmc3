<?php

use common\models\File;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Файлдарды жүктеңіз';
?>
<div class="site-index">

    <h1>Файлдарды жүктеңіз</h1>
    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'showHeader' => false,
        'columns' => [
            'file', // shows file title
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function ($model) {
                    $link = '';
                    $deleteButton = '';

                    $file = File::find()
                        ->andWhere([
                            'user_id' => Yii::$app->user->id,
                            'file_type_id' => $model->id,
                        ])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();

                    if ($file && $file->path) {
                        $url = Yii::getAlias('@web') . '/' . ltrim($file->path, '/');
                        $link = Html::a('Файл', $url, ['target' => '_blank', 'class' => 'me-2']);

                        $deleteButton = Html::a('X', ['file/delete', 'id' => $file->id], [
                            'class' => 'text-danger',
                            'data' => [
                                'method' => 'post',
                            ],
                        ]);
                    }

                    return $link . $deleteButton;
                },
            ],

            [
                'header' => '',
                'format' => 'raw',
                'content' => function ($model) {
                    $formId = 'form-' . $model->id;
                    $inputId = 'file-input-' . $model->id;

                    ob_start();
                    echo Html::beginTag('div', ['class' => 'text-end']); // Align right

                    $form = ActiveForm::begin([
                        'id' => $formId,
                        'action' => ['site/upload-single-file', 'id' => $model->id],
                        'options' => ['enctype' => 'multipart/form-data', 'style' => 'display:inline']
                    ]);

                    echo Html::fileInput('singleFile', null, [
                        'id' => $inputId,
                        'class' => 'd-none',
                        'onchange' => "document.getElementById('$formId').submit();"
                    ]);

                    echo Html::button('Жүктеу', [
                        'class' => 'btn btn-primary btn-sm',
                        'onclick' => "document.getElementById('$inputId').click();"
                    ]);

                    ActiveForm::end();

                    echo Html::endTag('div');
                    return ob_get_clean();
                }
            ]

        ],
    ]); ?>

    <div class="text-center mt-4">
        <?= Html::a('Сақтау', ['site/check-files'], ['class' => 'btn btn-success']) ?>
    </div>


</div>
