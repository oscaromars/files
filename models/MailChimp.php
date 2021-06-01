<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "usuario".
 *
 */
class MailChimp extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['per_id', 'usu_estado', 'usu_estado_logico'], 'required'],
                [['per_id'], 'integer'],
                [['usu_time_pass', 'usu_last_login', 'usu_fecha_creacion', 'usu_fecha_modificacion'], 'safe'],
                [['usu_sha', 'usu_session', 'usu_link_activo'], 'string'],
                [['usu_user'], 'string', 'max' => 45],
                [['usu_password'], 'string', 'max' => 255],
                [['usu_estado', 'usu_estado_logico'], 'string', 'max' => 1],
                [['per_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['per_id' => 'per_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'usu_id' => 'Usu ID',
            'per_id' => 'Per ID',
            'usu_user' => 'Usu User',
            'usu_password' => 'Usu Password',
            'usu_time_pass' => 'Usu Time Pass',
            'usu_sha' => 'Usu Sha',
            'usu_session' => 'Usu Session',
            'usu_last_login' => 'Usu Last Login',
            'usu_link_activo' => 'Usu Link Activo',
            'usu_estado' => 'Usu Estado',
            'usu_fecha_creacion' => 'Usu Fecha Creacion',
            'usu_fecha_modificacion' => 'Usu Fecha Modificacion',
            'usu_estado_logico' => 'Usu Estado Logico',
        ];
    }
}
