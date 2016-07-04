<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * LoginForm is the model behind the login form.
 */
class Person extends ActiveRecord {

    public $fullname;
    public $deletable;

    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;
    const GENDER_OTHER = 2;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
// username and password are both required
            [['name', 'surname', 'email', 'coach_id', 'gender'], 'required'],
            [['phone'], 'safe'],
            [['name', 'surname', 'email', 'phone'], 'filter', 'filter' => 'trim'],
            ['email', 'email'],
        ];
    }

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('user', 'Surname'),
            'email' => Yii::t('app', 'Email'),
            'fullname' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
            'gender' => Yii::t('app', 'Gender'),
        ];
    }

    public function afterFind() {
        $this->fullname = $this->name . ' ' . $this->surname;

        $sponsored_teams = $this->hasMany(Team::className(), ['sponsor_id' => 'id'])->count();
        $wheels_as_observed = $this->hasMany(Wheel::className(), ['observed_id' => 'id'])->count();
        $wheels_as_observer = $this->hasMany(Wheel::className(), ['observer_id' => 'id'])->count();

        $this->deletable = $sponsored_teams == 0 && $wheels_as_observed == 0 && $wheels_as_observer == 0;

        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $this->afterFind();
    }

    public function beforeValidate() {
        if (!isset($this->coach_id)) {
            $this->coach_id = Yii::$app->user->id;
        }
        
        return parent::beforeValidate();
    }

    public static function browse() {
        return Person::find()->where(['coach_id' => Yii::$app->user->id]);
    }

    public function getCoach() {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getWheels() {
        return $this->hasMany(Wheel::className(), ['observed_id' => 'id'])
                        ->where(['type' => '0']);
    }

    static public function getGenders() {
        return [
            self::GENDER_MALE => Yii::t('app', 'Male'),
            self::GENDER_FEMALE => Yii::t('app', 'Female'),
            self::GENDER_OTHER => Yii::t('app', 'Other'),
        ];
    }

}
