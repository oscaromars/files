<?php

namespace app\modules\investigacion\models;

use Yii;

/**
 * This is the model class for table "registro_integrante".
 *
 * @property int $rint_id
 * @property int $rpro_id
 * @property int $per_id
 * @property int $tins_id
 * @property string $rint_institucion
 * @property string $rint_rol
 * @property string $rint_nombre
 * @property string $rint_apellido
 * @property string $rint_cell
 * @property string $rint_correo
 * @property string $rint_archivo
 * @property int $rint_estado
 * @property string $rint_fecha_creacion
 * @property int $rint_usuario_ingreso
 * @property int $rint_usuario_modifica
 * @property string $rint_fecha_modificacion
 * @property int $rint_estado_logico
 *
 * @property RegistroAvances[] $registroAvances
 * @property RegistroProyecto $rpro
 * @property TipoInstitución $tins
 */
class RegistroIntegrante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_integrante';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_investigacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rpro_id', 'tins_id', 'rint_institucion', 'rint_rol', 'rint_nombre', 'rint_apellido', 'rint_cell', 'rint_correo', 'rint_archivo', 'rint_estado', 'rint_usuario_ingreso', 'rint_estado_logico'], 'required'],
            [['rpro_id', 'per_id', 'tins_id', 'rint_estado', 'rint_usuario_ingreso', 'rint_usuario_modifica', 'rint_estado_logico'], 'integer'],
            [['rint_archivo'], 'string'],
            [['rint_fecha_creacion', 'rint_fecha_modificacion'], 'safe'],
            [['rint_institucion', 'rint_rol'], 'string', 'max' => 350],
            [['rint_nombre', 'rint_apellido', 'rint_correo'], 'string', 'max' => 50],
            [['rint_cell'], 'string', 'max' => 10],
            [['rpro_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroProyecto::className(), 'targetAttribute' => ['rpro_id' => 'rpro_id']],
            [['tins_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoInstitución::className(), 'targetAttribute' => ['tins_id' => 'tins_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rint_id' => 'Rint ID',
            'rpro_id' => 'Rpro ID',
            'per_id' => 'Per ID',
            'tins_id' => 'Tins ID',
            'rint_institucion' => 'Rint Institucion',
            'rint_rol' => 'Rint Rol',
            'rint_nombre' => 'Rint Nombre',
            'rint_apellido' => 'Rint Apellido',
            'rint_cell' => 'Rint Cell',
            'rint_correo' => 'Rint Correo',
            'rint_archivo' => 'Rint Archivo',
            'rint_estado' => 'Rint Estado',
            'rint_fecha_creacion' => 'Rint Fecha Creacion',
            'rint_usuario_ingreso' => 'Rint Usuario Ingreso',
            'rint_usuario_modifica' => 'Rint Usuario Modifica',
            'rint_fecha_modificacion' => 'Rint Fecha Modificacion',
            'rint_estado_logico' => 'Rint Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroAvances()
    {
        return $this->hasMany(RegistroAvances::className(), ['rint_id' => 'rint_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRpro()
    {
        return $this->hasOne(RegistroProyecto::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTins()
    {
        return $this->hasOne(TipoInstitución::className(), ['tins_id' => 'tins_id']);
    }
}
