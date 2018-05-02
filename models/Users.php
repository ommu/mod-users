<?php
/**
 * Users
 * version: 0.0.1
 *
 * This is the model class for table "ommu_users".
 *
 * The followings are the available columns in table "ommu_users":
 * @property integer $user_id
 * @property integer $enabled
 * @property integer $verified
 * @property integer $level_id
 * @property integer $language_id
 * @property string $email
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $displayname
 * @property string $password
 * @property string $photos
 * @property string $salt
 * @property integer $deactivate
 * @property integer $search
 * @property integer $invisible
 * @property integer $privacy
 * @property integer $comments
 * @property string $creation_date
 * @property string $creation_ip
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $lastlogin_date
 * @property string $lastlogin_ip
 * @property string $lastlogin_from
 * @property string $update_date
 * @property string $update_ip
 *
 * The followings are the available model relations:
 * @property UserForgot[] $forgots
 * @property UserHistoryEmail[] $emails
 * @property UserHistoryLogin[] $logins
 * @property UserHistoryPassword[] $passwords
 * @property UserHistoryUsername[] $usernames
 * @property UserOption $option
 * @property UserVerify[] $verifies
 * @property UserLevel $level
 * @property CoreLanguages $language

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 05:31 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\user\models;

use Yii;
use yii\helpers\Url;
use app\modules\user\models\Users;
use app\models\CoreLanguages;

class Users extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

    // Variable Search
    public $level_search;
    public $language_search;
    public $user_search;
    public $modified_search;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'ommu_users';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('ecc4');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['enabled', 'verified', 'level_id', 'language_id', 'deactivate', 'search', 'invisible',
                'privacy', 'comments', 'modified_id'], 'integer'],
            [['email', 'username', 'first_name', 'last_name', 'displayname', 'password', 'photos',
                'salt', 'creation_ip', 'modified_id', 'lastlogin_ip', 'lastlogin_from', 'update_ip'],
                'required'],
            [['displayname', 'photos'], 'string'],
            [['creation_date', 'modified_date', 'lastlogin_date', 'update_date'], 'safe'],
            [['email', 'username', 'first_name', 'last_name', 'password', 'salt', 'lastlogin_from'],
                'string', 'max' => 32],
            [['creation_ip', 'lastlogin_ip', 'update_ip'], 'string', 'max' => 20],
            // [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserLevel::className(),
            //     'targetAttribute' => ['level_id' => 'level_id']],
            // [['language_id'], 'exist', 'skipOnError' => true,
            //     'targetClass' => CoreLanguages::className(),
            //     'targetAttribute' => ['language_id' => 'language_id']
            // ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForgots()
    {
        return $this->hasMany(UserForgot::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(UserHistoryEmail::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogins()
    {
        return $this->hasMany(UserHistoryLogin::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasswords()
    {
        return $this->hasMany(UserHistoryPassword::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsernames()
    {
        return $this->hasMany(UserHistoryUsername::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(UserOption::className(), ['option_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerifies()
    {
        return $this->hasMany(UserVerify::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(UserLevel::className(), ['level_id' => 'level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(CoreLanguages::className(), ['language_id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModified()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'user_id'         => Yii::t('app', 'User'),
            'enabled'         => Yii::t('app', 'Enabled'),
            'verified'        => Yii::t('app', 'Verified'),
            'level_id'        => Yii::t('app', 'Level'),
            'language_id'     => Yii::t('app', 'Language'),
            'email'           => Yii::t('app', 'Email'),
            'username'        => Yii::t('app', 'Username'),
            'first_name'      => Yii::t('app', 'First Name'),
            'last_name'       => Yii::t('app', 'Last Name'),
            'displayname'     => Yii::t('app', 'Displayname'),
            'password'        => Yii::t('app', 'Password'),
            'photos'          => Yii::t('app', 'Photos'),
            'salt'            => Yii::t('app', 'Salt'),
            'deactivate'      => Yii::t('app', 'Deactivate'),
            'search'          => Yii::t('app', 'Search'),
            'invisible'       => Yii::t('app', 'Invisible'),
            'privacy'         => Yii::t('app', 'Privacy'),
            'comments'        => Yii::t('app', 'Comments'),
            'creation_date'   => Yii::t('app', 'Creation Date'),
            'creation_ip'     => Yii::t('app', 'Creation Ip'),
            'modified_date'   => Yii::t('app', 'Modified Date'),
            'modified_id'     => Yii::t('app', 'Modified'),
            'lastlogin_date'  => Yii::t('app', 'Lastlogin Date'),
            'lastlogin_ip'    => Yii::t('app', 'Lastlogin Ip'),
            'lastlogin_from'  => Yii::t('app', 'Lastlogin From'),
            'update_date'     => Yii::t('app', 'Update Date'),
            'update_ip'       => Yii::t('app', 'Update Ip'),
            'level_search'    => Yii::t('app', 'Level'),
            'language_search' => Yii::t('app', 'Language'),
            'user_search'     => Yii::t('app', 'User'),
            'modified_search' => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Set default columns to display
     */
    public function init()
    {
        parent::init();

        $this->templateColumns['_no'] = [
            'header' => Yii::t('app', 'No'),
            'class'  => 'yii\grid\SerialColumn',
            'contentOptions' => ['class'=>'center'],
        ];
        if(!Yii::$app->request->get('level')) {
            $this->templateColumns['level_search'] = [
                'attribute' => 'level_search',
                'value' => function($model, $key, $index, $column) {
                    return $model->level->name;
                },
            ];
        }
        if(!Yii::$app->request->get('language')) {
            $this->templateColumns['language_search'] = [
                'attribute' => 'language_search',
                'value' => function($model, $key, $index, $column) {
                    return $model->language->name;
                },
            ];
        }
        $this->templateColumns['email'] = 'email';
        $this->templateColumns['username'] = 'username';
        $this->templateColumns['first_name'] = 'first_name';
        $this->templateColumns['last_name'] = 'last_name';
        $this->templateColumns['displayname'] = 'displayname';
        $this->templateColumns['password'] = 'password';
        $this->templateColumns['photos'] = 'photos';
        $this->templateColumns['salt'] = 'salt';
        $this->templateColumns['creation_date'] = [
            'attribute' => 'creation_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'creation_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/) : '-';
            },
            'format'    => 'html',
        ];
        $this->templateColumns['creation_ip'] = 'creation_ip';
        $this->templateColumns['modified_date'] = [
            'attribute' => 'modified_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'modified_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/) : '-';
            },
            'format'    => 'html',
        ];
        if(!Yii::$app->request->get('modified')) {
            $this->templateColumns['modified_search'] = [
                'attribute' => 'modified_search',
                'value' => function($model, $key, $index, $column) {
                    return isset($model->modified) ? $model->modified->displayname : '-';
                },
            ];
        }
        $this->templateColumns['lastlogin_date'] = [
            'attribute' => 'lastlogin_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'lastlogin_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->lastlogin_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->lastlogin_date, 'date'/*datetime*/) : '-';
            },
            'format'    => 'html',
        ];
        $this->templateColumns['lastlogin_ip'] = 'lastlogin_ip';
        $this->templateColumns['lastlogin_from'] = 'lastlogin_from';
        $this->templateColumns['update_date'] = [
            'attribute' => 'update_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'update_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->update_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->update_date, 'date'/*datetime*/) : '-';
            },
            'format'    => 'html',
        ];
        $this->templateColumns['update_ip'] = 'update_ip';
        $this->templateColumns['enabled'] = [
            'attribute' => 'enabled',
            'value' => function($model, $key, $index, $column) {
                return $model->enabled;
            },
            'contentOptions' => ['class'=>'center'],
        ];
        $this->templateColumns['verified'] = [
            'attribute' => 'verified',
            'value' => function($model, $key, $index, $column) {
                return $model->verified;
            },
            'contentOptions' => ['class'=>'center'],
        ];
        $this->templateColumns['deactivate'] = [
            'attribute' => 'deactivate',
            'value' => function($model, $key, $index, $column) {
                return $model->deactivate;
            },
            'contentOptions' => ['class'=>'center'],
        ];
        $this->templateColumns['search'] = [
            'attribute' => 'search',
            'value' => function($model, $key, $index, $column) {
                return $model->search;
            },
            'contentOptions' => ['class'=>'center'],
        ];
        $this->templateColumns['invisible'] = [
            'attribute' => 'invisible',
            'value' => function($model, $key, $index, $column) {
                return $model->invisible;
            },
            'contentOptions' => ['class'=>'center'],
        ];
        $this->templateColumns['privacy'] = [
            'attribute' => 'privacy',
            'value' => function($model, $key, $index, $column) {
                return $model->privacy;
            },
            'contentOptions' => ['class'=>'center'],
        ];
        $this->templateColumns['comments'] = [
            'attribute' => 'comments',
            'value' => function($model, $key, $index, $column) {
                return $model->comments;
            },
            'contentOptions' => ['class'=>'center'],
        ];
    }

    /**
     * before validate attributes
     */
    public function beforeValidate()
    {
        if(parent::beforeValidate()) {
            if(!$this->isNewRecord)
                $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
        }
        return true;
    }

    /**
     * before save attributes
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            // Create action
        }
        return true;
    }

    /**
     * after validate attributes
     */
    public function afterValidate()
    {
        parent::afterValidate();
        // Create action

        return true;
    }

    /**
     * After save attributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Create action
    }

    /**
     * Before delete attributes
     */
    public function beforeDelete()
    {
        if(parent::beforeDelete()) {
            // Create action
        }
        return true;
    }

    /**
     * After delete attributes
     */
    public function afterDelete()
    {
        parent::afterDelete();
        // Create action
    }

    public function getBiodata()
    {
        return $this->hasOne(\app\modules\cv\models\CvBiodata::className(), ['user_id' => 'user_id']);
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['bio_id'] = function($model) {
            return isset($model->biodata) ? $model->biodata->bio_id : '0';
        };
        return $fields;
    }
}
