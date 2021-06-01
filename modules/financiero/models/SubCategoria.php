<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "sub_categoria".
 *
 * @property int $scat_id
 * @property int $cat_id
 * @property string $scat_nombre
 * @property string $scat_descripcion
 * @property int $scat_usu_ingreso
 * @property int $scat_usu_modifica
 * @property string $scat_estado
 * @property string $scat_fecha_creacion
 * @property string $scat_fecha_modificacion
 * @property string $scat_estado_logico
 *
 * @property Item[] $items
 * @property Categoria $cat
 */
class SubCategoria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sub_categoria';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'scat_nombre', 'scat_descripcion', 'scat_usu_ingreso', 'scat_estado', 'scat_estado_logico'], 'required'],
            [['cat_id', 'scat_usu_ingreso', 'scat_usu_modifica'], 'integer'],
            [['scat_fecha_creacion', 'scat_fecha_modificacion'], 'safe'],
            [['scat_nombre'], 'string', 'max' => 200],
            [['scat_descripcion'], 'string', 'max' => 500],
            [['scat_estado', 'scat_estado_logico'], 'string', 'max' => 1],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scat_id' => 'Scat ID',
            'cat_id' => 'Cat ID',
            'scat_nombre' => 'Scat Nombre',
            'scat_descripcion' => 'Scat Descripcion',
            'scat_usu_ingreso' => 'Scat Usu Ingreso',
            'scat_usu_modifica' => 'Scat Usu Modifica',
            'scat_estado' => 'Scat Estado',
            'scat_fecha_creacion' => 'Scat Fecha Creacion',
            'scat_fecha_modificacion' => 'Scat Fecha Modificacion',
            'scat_estado_logico' => 'Scat Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['scat_id' => 'scat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Categoria::className(), ['cat_id' => 'cat_id']);
    }
}
