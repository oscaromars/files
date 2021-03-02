<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use app\models\Persona;
use app\models\Usuario;
/**
 * This is the model class for table "persona" and "usuario".
 *
 * @property integer $per_id
 * @property integer $usu_id
 * 
 * @property string $per_pri_nombre
 * @property string $per_seg_nombre
 * @property string $per_pri_apellido
 * @property string $per_seg_apellido
 * @property string $per_cedula
 * @property string $per_ruc
 * @property string $per_pasaporte
 * @property integer $etn_id
 * @property integer $eciv_id
 * @property string $per_genero
 * @property string $per_nacionalidad 
 * @property integer $pai_id_nacimiento
 * @property integer $pro_id_nacimiento
 * @property integer $can_id_nacimiento
 * @property string $per_nac_ecuatoriano
 * @property string $per_fecha_nacimiento
 * @property string $per_celular
 * @property string $per_correo
 * @property integer $tsan_id
 * @property string $usu_user
 * @property string $usu_password
 *
 */

class Perfil extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'usuario';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_asgard');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['per_id', 'pro_estado','pro_estado_logico'], 'required'],            
            [['pro_fecha_creacion','pro_fecha_modificacion'], 'safe'],            
            [['pro_estado_logico','pro_estado'], 'string', 'max' => 1],
            [['per_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['per_id' => 'per_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pro_id' => 'Pro ID',
            'per_id' => 'Per ID',
            'pro_usuario_ingreso' => 'Pro Usuario Ingreso',
            'pro_usuario_modifica' => 'Pro Usuario Modifica',
            'pro_estado' => 'Pro Estado',
            'pro_fecha_creacion' => 'Pro Fecha Creacion',
            'pro_fecha_modificacion' => 'Pro Fecha Modificacion',
            'pro_estado_logico' => 'Pro Estado Logico',
        ];
    }
}
