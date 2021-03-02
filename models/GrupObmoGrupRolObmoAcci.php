<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grup_obmo_grup_rol_obmo_acci".
 *
 * @property int $ggoa_id
 * @property int $gogr_id
 * @property int $oacc_id
 * @property string $ggoa_estado
 * @property string $ggoa_fecha_creacion
 * @property string $ggoa_fecha_modificacion
 * @property string $ggoa_estado_logico
 *
 * @property GrupObmoGrupRol $gogr
 * @property ObmoAcci $oacc
 */
class GrupObmoGrupRolObmoAcci extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grup_obmo_grup_rol_obmo_acci';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gogr_id', 'oacc_id', 'ggoa_estado', 'ggoa_estado_logico'], 'required'],
            [['gogr_id', 'oacc_id'], 'integer'],
            [['ggoa_fecha_creacion', 'ggoa_fecha_modificacion'], 'safe'],
            [['ggoa_estado', 'ggoa_estado_logico'], 'string', 'max' => 1],
            [['gogr_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrupObmoGrupRol::className(), 'targetAttribute' => ['gogr_id' => 'gogr_id']],
            [['oacc_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObmoAcci::className(), 'targetAttribute' => ['oacc_id' => 'oacc_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ggoa_id' => 'Ggoa ID',
            'gogr_id' => 'Gogr ID',
            'oacc_id' => 'Oacc ID',
            'ggoa_estado' => 'Ggoa Estado',
            'ggoa_fecha_creacion' => 'Ggoa Fecha Creacion',
            'ggoa_fecha_modificacion' => 'Ggoa Fecha Modificacion',
            'ggoa_estado_logico' => 'Ggoa Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGogr()
    {
        return $this->hasOne(GrupObmoGrupRol::className(), ['gogr_id' => 'gogr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOacc()
    {
        return $this->hasOne(ObmoAcci::className(), ['oacc_id' => 'oacc_id']);
    }
}
