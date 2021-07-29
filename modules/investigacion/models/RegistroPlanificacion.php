<?php

namespace app\modules\investigacion\models;

use Yii;

/**
 * This is the model class for table "registro_planificacion".
 *
 * @property int $rpla_id
 * @property int $rpro_id
 * @property string $rpla_fecha_inicio
 * @property string $rpla_fecha_fin
 * @property int $rpla_cantidad_meses
 * @property int $rpla_estado
 * @property string $rpla_fecha_creacion
 * @property int $rpla_usuario_ingreso
 * @property int $rpla_usuario_modifica
 * @property string $rpla_fecha_modificacion
 * @property int $rpla_estado_logico
 *
 * @property RegistroProyecto $rpro
 */
class RegistroPlanificacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_planificacion';
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
            [['rpro_id', 'rpla_estado', 'rpla_usuario_ingreso', 'rpla_estado_logico'], 'required'],
            [['rpro_id', 'rpla_cantidad_meses', 'rpla_estado', 'rpla_usuario_ingreso', 'rpla_usuario_modifica', 'rpla_estado_logico'], 'integer'],
            [['rpla_fecha_inicio', 'rpla_fecha_fin', 'rpla_fecha_creacion', 'rpla_fecha_modificacion'], 'safe'],
            [['rpro_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroProyecto::className(), 'targetAttribute' => ['rpro_id' => 'rpro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpla_id' => 'Rpla ID',
            'rpro_id' => 'Rpro ID',
            'rpla_fecha_inicio' => 'Rpla Fecha Inicio',
            'rpla_fecha_fin' => 'Rpla Fecha Fin',
            'rpla_cantidad_meses' => 'Rpla Cantidad Meses',
            'rpla_estado' => 'Rpla Estado',
            'rpla_fecha_creacion' => 'Rpla Fecha Creacion',
            'rpla_usuario_ingreso' => 'Rpla Usuario Ingreso',
            'rpla_usuario_modifica' => 'Rpla Usuario Modifica',
            'rpla_fecha_modificacion' => 'Rpla Fecha Modificacion',
            'rpla_estado_logico' => 'Rpla Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRpro()
    {
        return $this->hasOne(RegistroProyecto::className(), ['rpro_id' => 'rpro_id']);
    }
}
