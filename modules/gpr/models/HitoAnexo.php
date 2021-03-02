<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "hito_anexo".
 *
 * @property int $hane_id
 * @property int $hseg_id
 * @property string $hane_nombre
 * @property string $hane_descripcion
 * @property string $hane_ruta
 * @property int $hane_usuario_ingreso
 * @property int|null $hane_usuario_modifica
 * @property string $hane_estado
 * @property string $hane_fecha_creacion
 * @property string|null $hane_fecha_modificacion
 * @property string $hane_estado_logico
 *
 * @property HitoSeguimiento $hseg
 */
class HitoAnexo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hito_anexo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gpr');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hseg_id', 'hane_nombre', 'hane_descripcion', 'hane_ruta', 'hane_usuario_ingreso', 'hane_estado', 'hane_estado_logico'], 'required'],
            [['hseg_id', 'hane_usuario_ingreso', 'hane_usuario_modifica'], 'integer'],
            [['hane_fecha_creacion', 'hane_fecha_modificacion'], 'safe'],
            [['hane_nombre'], 'string', 'max' => 300],
            [['hane_descripcion', 'hane_ruta'], 'string', 'max' => 500],
            [['hane_estado', 'hane_estado_logico'], 'string', 'max' => 1],
            [['hseg_id'], 'exist', 'skipOnError' => true, 'targetClass' => HitoSeguimiento::className(), 'targetAttribute' => ['hseg_id' => 'hseg_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hane_id' => 'Hane ID',
            'hseg_id' => 'Hseg ID',
            'hane_nombre' => 'Hane Nombre',
            'hane_descripcion' => 'Hane Descripcion',
            'hane_ruta' => 'Hane Ruta',
            'hane_usuario_ingreso' => 'Hane Usuario Ingreso',
            'hane_usuario_modifica' => 'Hane Usuario Modifica',
            'hane_estado' => 'Hane Estado',
            'hane_fecha_creacion' => 'Hane Fecha Creacion',
            'hane_fecha_modificacion' => 'Hane Fecha Modificacion',
            'hane_estado_logico' => 'Hane Estado Logico',
        ];
    }

    /**
     * Gets query for [[Hseg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHseg()
    {
        return $this->hasOne(HitoSeguimiento::className(), ['hseg_id' => 'hseg_id']);
    }
}
