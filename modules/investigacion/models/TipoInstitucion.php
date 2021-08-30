<?php

namespace app\modules\investigacion\models;

use Yii;

/**
 * This is the model class for table "tipo_institucion".
 *
 * @property int $tins_id
 * @property string $tins_nombre
 * @property string $tins_descripcion
 * @property int $tins_estado
 * @property string $tins_fecha_creacion
 * @property int $tins_usuario_ingreso
 * @property int $tins_usuario_modifica
 * @property string $tins_fecha_modificacion
 * @property int $tins_estado_logico
 *
 * @property RegistroIntegrante[] $registroIntegrantes
 */
class TipoInstitucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_institucion';
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
            [['tins_nombre', 'tins_descripcion', 'tins_estado', 'tins_usuario_ingreso', 'tins_estado_logico'], 'required'],
            [['tins_estado', 'tins_usuario_ingreso', 'tins_usuario_modifica', 'tins_estado_logico'], 'integer'],
            [['tins_fecha_creacion', 'tins_fecha_modificacion'], 'safe'],
            [['tins_nombre', 'tins_descripcion'], 'string', 'max' => 350],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tins_id' => 'Tins ID',
            'tins_nombre' => 'Tins Nombre',
            'tins_descripcion' => 'Tins Descripcion',
            'tins_estado' => 'Tins Estado',
            'tins_fecha_creacion' => 'Tins Fecha Creacion',
            'tins_usuario_ingreso' => 'Tins Usuario Ingreso',
            'tins_usuario_modifica' => 'Tins Usuario Modifica',
            'tins_fecha_modificacion' => 'Tins Fecha Modificacion',
            'tins_estado_logico' => 'Tins Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroIntegrantes()
    {
        return $this->hasMany(RegistroIntegrante::className(), ['tins_id' => 'tins_id']);
    }
}
