<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php
    $this->registerLinkTag([
        'rel' => 'icon',
        'type' => 'image/png',
        'href' => Yii::getAlias('@web/logo.png'),
    ]);
    ?>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/logo.png', [
			'alt' => Yii::$app->name,
			'style' => 'height:30px; margin-right:3px;',
		]) . Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-light bg-light fixed-top shadow',
        ],
    ]);
    $menuItems = [];

    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'admin') {
        $menuItems[] = ['label' => 'Қатысушылар', 'url' => ['/user-cycle/index']];
        $menuItems[] = ['label' => 'Категориялар', 'url' => ['/category/index']];
        $menuItems[] = ['label' => 'Мамандықтар', 'url' => ['/course/index']];
        $menuItems[] = ['label' => 'Циклдер', 'url' => ['/cycle/index']];
        $menuItems[] = ['label' => 'Тесттер', 'url' => ['/test/index']];
        $menuItems[] = [
            'label' => Html::tag('span', 'Анкеталар', ['class' => 'text-danger']),
            'encode' => false
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (!Yii::$app->user->isGuest){
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Шығу (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none text-danger']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => [
                'label' => 'Басты бет',
                'url' => Yii::$app->homeUrl,
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= Alert::widget() ?>

        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
