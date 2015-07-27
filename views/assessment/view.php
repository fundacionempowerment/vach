<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use app\models\Assessment;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$individualQuestionCount = WheelQuestion::getQuestionCount(Wheel::TYPE_INDIVIDUAL);
$groupQuestionCount = WheelQuestion::getQuestionCount(Wheel::TYPE_GROUP);
$organizationalQuestionCount = WheelQuestion::getQuestionCount(Wheel::TYPE_ORGANIZATIONAL);

$this->title = $assessment->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = $this->title;
$wheel_count = 0;

$mail_icon = '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>';
$file_icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-6">
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?><br />
        <?= Yii::t('team', 'Company') ?>: <?= Html::label($assessment->team->company->name) ?><br />
        <?= Yii::t('team', 'Team') ?>: <?= Html::label($assessment->team->name) ?><br />
        <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($assessment->team->sponsor->fullname) ?>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-5">
        <h2><?= Yii::t('assessment', 'Individual wheels') ?></h2>
        <table class="table table-bordered table-hover">
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->user_id, 'type' => Wheel::TYPE_INDIVIDUAL]), ['class' => 'btn btn-default btn-xs']) ?>
                        <?php
                        foreach ($assessment->individualWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->user_id) {
                                ?>
                                <button type="button" class="btn btn-default btn-xs" onclick="showToken('<?= $observerMember->member->fullname ?>', '<?= $wheel->token ?>');" >
                                    <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
                                </button>
                                <?php
                                break;
                            }
                        ?>
                    </th>
                    <td>
                        <?php
                        foreach ($assessment->individualWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->user_id) {
                                echo $wheel->answerStatus . '&nbsp;';
                                echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']);
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-12">
        <h2><?= Yii::t('assessment', 'Group wheels') ?></h2>
        <table width="100%" class="table table-bordered table-hover">
            <tr>
                <th style="text-align: right;">
                    <?= Yii::t('wheel', "Observer \\ Observed") ?>
                </th>
                <?php foreach ($assessment->team->members as $teamMember): ?>
                    <th>
                        <?= $teamMember->member->fullname ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->user_id, 'type' => Wheel::TYPE_GROUP]), ['class' => 'btn btn-default btn-xs']) ?>
                        <?php
                        foreach ($assessment->groupWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->user_id) {
                                ?>
                                <button type="button" class="btn btn-default btn-xs" onclick="showToken('<?= $observerMember->member->fullname ?>', '<?= $wheel->token ?>');" >
                                    <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
                                </button>
                                <?php
                                break;
                            }
                        ?>
                    </th>
                    <?php foreach ($assessment->team->members as $observedMember) { ?>
                        <td>
                            <?php
                            foreach ($assessment->groupWheels as $wheel)
                                if ($wheel->observer_id == $observerMember->user_id && $wheel->observed_id == $observedMember->user_id) {
                                    echo $wheel->answerStatus . '&nbsp;';
                                    echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']);
                                }
                            ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-12">
        <h2><?= Yii::t('assessment', 'Organizational wheels') ?></h2>
        <table width="100%" class="table table-bordered table-hover">
            <tr>
                <th style="text-align: right;">
                    <?= Yii::t('wheel', "Observer \\ Observed") ?>
                </th>
                <?php foreach ($assessment->team->members as $teamMember): ?>
                    <th>
                        <?= $teamMember->member->fullname ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->user_id, 'type' => Wheel::TYPE_ORGANIZATIONAL]), ['class' => 'btn btn-default btn-xs']) ?>
                        <?php
                        foreach ($assessment->organizationalWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->user_id) {
                                ?>
                                <button type="button" class="btn btn-default btn-xs" onclick="showToken('<?= $observerMember->member->fullname ?>', '<?= $wheel->token ?>');" >
                                    <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
                                </button>
                                <?php
                                break;
                            }
                        ?>
                    </th>
                    <?php foreach ($assessment->team->members as $observedMember) { ?>
                        <td>
                            <?php
                            foreach ($assessment->organizationalWheels as $wheel)
                                if ($wheel->observer_id == $observerMember->user_id && $wheel->observed_id == $observedMember->user_id) {
                                    echo $wheel->answerStatus . '&nbsp;';
                                    echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']);
                                }
                            ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
        <?= Html::a(\Yii::t('app', 'Refresh'), Url::to(['assessment/view', 'id' => $assessment->id,]), ['class' => 'btn btn-default']) ?>
        <?=
        Html::a(\Yii::t('assessment', 'Go to dashboard...'), Url::to(['assessment/go-to-dashboard', 'id' => $assessment->id,]), [
            'class' => ($wheel_count == count($assessment->team->members) * 3 ? 'btn btn-success' : 'btn btn-default')
        ])
        ?>
    </div>
    <?php Modal::begin(['header' => '<h4>' . Yii::t('assessment', 'Run on smartphone') . '</h4>']); ?>
    <div class="text-center">
        <h3><?= Yii::t('assessment', 'In order to run this wheel via smartphone, please ask') ?></h3>
        <h2 id="member"></h2>
        <h3><?= Yii::t('assessment', 'to enter this site in his/her phone browser') ?></h3>
        <h2><?= Url::to('@web/', true); ?></h2>
        <h3><?= Yii::t('assessment', 'and enter this token in "Wheel Token" field') ?></h3>
        <h2 id="token"></h2>
        <h3><?= Yii::t('assessment', 'and click over "Run" button') ?></h3>
        <?php Modal::end(); ?>
    </div>
</div>
<script type="text/javascript">
                                    function showToken(member, token)
                                    {
                                        $('#w0').modal('show');
                                        $('#member').html(member);
                                        $('#token').html(token);

                                    }
</script>
