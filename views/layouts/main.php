<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\models\User;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$logo = Html::img('@web/images/logo.png', ['alt' => 'Логотип', 'class' => 'navbar-brand logo']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'LostPet',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    $items = [
        ['label' => $logo, 'url' => ['/site/index'], 'encode' => false],
        ['label' => 'Главная', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $items[] = ['label' => 'Логин', 'url' => ['/site/login']];
        $items[] = ['label' => 'Регистрация', 'url' => ['/site/register']];
    } else {
        if (!User::getInstance()->isAdmin()) {
            $items[] = ['label' => 'Добавить заявление', 'url' => ['/pet-requests/create']];
        }
        $items[] = ['label' => 'Заявления', 'url' => ['/pet-requests/index']];
        $items[] = '<li class="nav-item">'
        . Html::beginForm(['/site/logout'])
        . Html::submitButton(
            'Выход (' . Yii::$app->user->identity->name . ')',
            ['class' => 'nav-link btn btn-link logout']
        )
        . Html::endForm()
    . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav align-items-center'],
        'items' => $items
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['homeLink' => ['label' => 'Главная', 'url' => '/'], 'links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">
            <ul>    
                <li class="nav-item"><b>Контактные данные:</b></li>
                <li class="nav-item"><u>Адрес:</u> Санкт-Петербург, ул. Руставели 33</li>
                <li class="nav-item"><u>Телефон:</u> 8(812)456-65-76</li>
            </ul>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <ul class="nav-item">
                <?php if (Yii::$app->user->isGuest) : ?> 
                    <li class="nav-item"> 
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/site/index']) ?>">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>">Вход</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/site/register']) ?>">Регистрация</a>
                    </li>
                <?php elseif  (!User::getInstance()->isAdmin()) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/site/index']) ?>">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/pet-requests/index']) ?>">Заявления</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/site/index']) ?>">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/pet-requests/index']) ?>">Заявления</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/pet-requests/create']) ?>">Добавить заявление</a>
                    </li>
                <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
