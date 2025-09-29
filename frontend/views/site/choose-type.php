<?php

/** @var yii\web\View $this */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Оқу түрін таңдаңыз';
?>
<div class="site-index">

    <h1>Оқу түрін таңдаңыз</h1>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'showHeader' => false,
        'columns' => [
            [
                'attribute' => 'label',
            ],
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model['file'] ? Html::a('Келісім шарт', Yii::getAlias('@web/') . $model['file'], [
                        'target' => '_blank',
                        'class' => 'btn btn-link p-0'
                    ]) : '';
                },
            ],
            [
                'attribute' => 'checkbox',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    if (!$model['file']) return '';

                    return Html::tag('label',
                        Html::checkbox("select_{$index}", false, [
                            'class' => 'grid-check me-1', // Bootstrap spacing
                            'data-index' => $index
                        ]) . 'Келісемін' // or 'I agree'
                    );
                },
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    $disabled = $model['type'] == 'budget' ? 'active' : 'disabled';
                    return Html::tag('div',
                        Html::a('Таңдау', ['site/upload-files', 'type' => $model['type']], [
                            'class' => 'btn btn-primary btn-sm grid-button ' . $disabled,
                            'id' => "btn-$index",
                        ]),
                        ['class' => 'text-end']
                    );
                },
            ],


        ],
    ]); ?>
</div>

<?php
$this->registerJs(<<<JS
    $('.grid-check').on('change', function () {
        const index = $(this).data('index');
        const btn = $('#btn-' + index);
        if (this.checked) {
            btn.removeClass('disabled');
        } else {
            btn.addClass('disabled');
        }
    });
JS);
?>


