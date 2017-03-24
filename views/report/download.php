<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use yii\bootstrap\Button;
use app\models\Wheel;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

if (!empty($assessment) && $assessment->version == 2) {
    $version = 2;
} else {
    $version = 1;
}

$this->title = Yii::t('report', 'Report');
?>
<div class="report-technical row">
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/logo_cpc.png', ['class' => 'image-responsive']) ?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?= Html::img('@web/images/logo_vach.png', ['class' => 'image-responsive']) ?>
        </p>
        <h2>
            <?= Html::label($assessment->team->company->name) ?>
            <?= Html::label($assessment->team->name) ?>
        </h2>
        <h3>
            <?= Yii::t('report', 'Report') ?>
        </h3>
        <h3>
            <?= Yii::$app->formatter->asDate('now') ?>
        </h3>
    </div>
    <div class="col-lg-12">
        <h3>
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($assessment->team->company->name) ?>
            <br/>
            <?= Yii::t('team', 'Team') ?>: <?= Html::label($assessment->team->name) ?>
            <br/>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?>
            <br/>
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($assessment->team->sponsor->fullname) ?>
            <br/>
            <?= Yii::t('team', 'Members') ?>:
            <ul>
                <?php foreach ($assessment->team->members as $teamMember) { ?>
                    <li>
                        <?= Html::label($teamMember->member->fullname) ?>
                    </li>
                <?php } ?>
            </ul>
        </h3>
    </div>
    <div class="col-lg-12">
        <h2>
            <?= Yii::t('report', 'Index') ?>
        </h2>
        <h3>
            <ol>
                <li><?= Yii::t('report', 'Introduction') ?></li>
                <li><?= Yii::t('report', 'Fundaments') ?></li>
                <li><?= Yii::t('report', 'Group and Organizational Analysis') ?></li>
                <li><?= Yii::t('report', 'Individual Analysis') ?></li>
                <li><?= Yii::t('report', 'Summary') ?></li>
                <li><?= Yii::t('report', 'Action Plan') ?></li>
            </ol>
        </h3>
    </div>
    <div class="col-lg-12">
        <h2>
            1. <?= Yii::t('report', 'Introduction') ?>
        </h2>
        <div id="view-introduction">
            <?= $assessment->report->introduction ?>
        </div>        
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12">
        <?= $this->render('_fundaments', []) ?>
    </div>
    <div class="col-lg-12">
        <h2>
            3. <?= Yii::t('report', 'Group and Organizational Analysis') ?>
        </h2>
        <?=
        $this->render('_effectiveness', [
            'assessment' => $assessment,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'members' => $members,
        ])
        ?>
        <?=
        $this->render('_performance', [
            'assessment' => $assessment,
            'groupPerformanceMatrix' => $groupPerformanceMatrix,
            'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
            'members' => $members,
        ])
        ?>
        <?=
        $this->render('_relations', [
            'assessment' => $assessment,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'members' => $members,
        ])
        ?>
        <?=
        $this->render('_competences', [
            'assessment' => $assessment,
            'groupGauges' => $groupGauges,
            'organizationalGauges' => $organizationalGauges,
            'members' => $members,
        ])
        ?>
        <?=
        $this->render('_emergents', [
            'assessment' => $assessment,
            'groupEmergents' => $groupEmergents,
            'organizationalEmergents' => $organizationalEmergents,
            'members' => $members,
        ])
        ?>
    </div>
    <h2>
        4. <?= Yii::t('report', 'Individual Analysis') ?>
    </h2>
    <div class="col-lg-12">
        <?php
        foreach ($assessment->report->individualReports as $individualReport) {
            if ($individualReport->teamMember->active) {
                $projectedGroupWheel = Wheel::getProjectedGroupWheel($assessment->id, $individualReport->person_id);
                $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($assessment->id, $individualReport->person_id);
                $reflectedGroupWheel = Wheel::getReflectedGroupWheel($assessment->id, $individualReport->person_id);
                $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($assessment->id, $individualReport->person_id);
                $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
                $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);
                $groupGauges = Wheel::getMemberGauges($assessment->id, $individualReport->person_id, Wheel::TYPE_GROUP);
                $organizationalGauges = Wheel::getMemberGauges($assessment->id, $individualReport->person_id, Wheel::TYPE_ORGANIZATIONAL);
                $groupEmergents = Wheel::getMemberEmergents($assessment->id, $individualReport->person_id, Wheel::TYPE_GROUP);
                $organizationalEmergents = Wheel::getMemberEmergents($assessment->id, $individualReport->person_id, Wheel::TYPE_ORGANIZATIONAL);
                $subtitle_number = 97; // letter 'a'
                ?>
                <h1>
                    <?= $individualReport->member->fullname ?>
                </h1>
                <?php
                if ($individualReport->performance != '') {
                    echo $this->render('_individual_performance', [
                        'report' => $individualReport,
                        'assessment' => $assessment,
                        'groupPerformanceMatrix' => $groupPerformanceMatrix,
                        'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
                        'subtitle_letter' => chr($subtitle_number),
                        'member' => $individualReport->member,
                    ]);
                    $subtitle_number++;
                }
                echo $this->render('_individual_perception', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'projectedGroupWheel' => $projectedGroupWheel,
                    'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                    'reflectedGroupWheel' => $reflectedGroupWheel,
                    'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
                    'subtitle_letter' => chr($subtitle_number),
                ]);
                $subtitle_number++;
                echo $this->render('_individual_relations', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                    'members' => $members,
                    'subtitle_letter' => chr($subtitle_number),
                ]);
                $subtitle_number++;
                echo $this->render('_individual_competences', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupGauges' => $groupGauges,
                    'organizationalGauges' => $organizationalGauges,
                    'members' => $members,
                    'subtitle_letter' => chr($subtitle_number),
                ]);
                $subtitle_number++;
                echo $this->render('_individual_emergents', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupEmergents' => $groupEmergents,
                    'organizationalEmergents' => $organizationalEmergents,
                    'members' => $members,
                    'subtitle_letter' => chr($subtitle_number),
                ]);
            }
        }
        ?>
    </div>
    <div class="col-lg-12">
        <h2>
            5. <?= Yii::t('report', 'Summary') ?>
        </h2>
        <div id="view-introduction">
            <?= $assessment->report->summary ?>
        </div>  
    </div>
    <div class="col-lg-12">
        <h2>
            6. <?= Yii::t('report', 'Action Plan') ?>
        </h2>
        <div id="view-introduction">
            <?= $assessment->report->action_plan ?>
        </div>  
    </div>
</div>
