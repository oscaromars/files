<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "tipo_documento".
 *
 * @property int $tdoc_id
 * @property string $tdoc_nombre
 * @property int $tdoc_usuario_ingreso
 * @property int $tdoc_usuario_modifica
 * @property string $tdoc_estado
 * @property string $tdoc_fecha_creacion
 * @property string $tdoc_fecha_modificacion
 * @property string $tdoc_estado_logico
 */
class TipoDocumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_documento';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tdoc_nombre', 'tdoc_usuario_ingreso'], 'required'],
            [['tdoc_usuario_ingreso', 'tdoc_usuario_modifica'], 'integer'],
            [['tdoc_fecha_creacion', 'tdoc_fecha_modificacion'], 'safe'],
            [['tdoc_nombre'], 'string', 'max' => 50],
            [['tdoc_estado', 'tdoc_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tdoc_id' => 'Tdoc ID',
            'tdoc_nombre' => 'Tdoc Nombre',
            'tdoc_usuario_ingreso' => 'Tdoc Usuario Ingreso',
            'tdoc_usuario_modifica' => 'Tdoc Usuario Modifica',
            'tdoc_estado' => 'Tdoc Estado',
            'tdoc_fecha_creacion' => 'Tdoc Fecha Creacion',
            'tdoc_fecha_modificacion' => 'Tdoc Fecha Modificacion',
            'tdoc_estado_logico' => 'Tdoc Estado Logico',
        ];
    }
}
