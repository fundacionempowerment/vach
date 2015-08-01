<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$isCoach = Yii::$app->user->identity->is_coach;
$isAdministrator = Yii::$app->user->identity->is_administrator;

$items[] = ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']];
if ($isAdministrator) {
    $admininistratorMenu[] = ['label' => Yii::t('user', 'Users'), 'url' => ['/user']];
    $admininistratorMenu[] = ['label' => Yii::t('wheel', 'Wheel Questions'), 'url' => ['/wheel/questions']];
    $admininistratorMenu[] = ['label' => Yii::t('feedback', 'Feedbacks'), 'url' => ['admin/feedback']];
    $items[] = ['label' => Yii::t('app', 'Admin'), 'items' => $admininistratorMenu];
}
if ($isCoach) {
    $items[] = ['label' => Yii::t('dashboard', 'Dashboard'), 'url' => ['/dashboard']];
    $items[] = ['label' => Yii::t('company', 'Companies'), 'url' => ['/company']];
    $items[] = ['label' => Yii::t('user', 'Persons'), 'url' => ['/person']];
    $items[] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
}
$items[] = ['label' => Yii::t('user', 'My account'), 'url' => ['/user/my-account']];
$items[] = ['label' => Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
    'url' => ['/site/logout'],
    'linkOptions' => ['data-method' => 'post']];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <link rel="icon" type="image/x-icon" href="<?= Url::to('@web/favicon.png') ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="wrap">
            <?php
            NavBar::begin([
                'options' => [
                    'class' => 'navbar-default navbar-fixed-top',
                ],
            ]);
            echo Html::img('@web/images/logo.png', ['alt' => 'logo',
                'class' => 'image-responsive', 'height' => '35px', 'style' => 'margin-top: 6px',]);

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $items,
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        <footer class="footer">
            <?= $this->render('_footer') ?>
        </footer>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
