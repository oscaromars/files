<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grup_obmo_grup_rol".
 *
 * @property integer $gogr_id
 * @property integer $grol_id
 * @property integer $gmod_id
 * @property string $gogr_estado
 * @property string $gogr_fecha_creacion
 * @property string $gogr_fecha_modificacion
 * @property string $gogr_estado_logico
 *
 * @property GrupRol $grol
 * @property GrupObmo $gmod
 * @property GrupObmoGrupRolObmoAcci[] $grupObmoGrupRolObmoAccis
 */
class GrupObmoGrupRol extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grup_obmo_grup_rol';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grol_id', 'gmod_id', 'gogr_estado', 'gogr_estado_logico'], 'required'],
            [['grol_id', 'gmod_id'], 'integer'],
            [['gogr_fecha_creacion', 'gogr_fecha_modificacion'], 'safe'],
            [['gogr_estado', 'gogr_estado_logico'], 'string', 'max' => 1],
            [['grol_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrupRol::className(), 'targetAttribute' => ['grol_id' => 'grol_id']],
            [['gmod_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrupObmo::className(), 'targetAttribute' => ['gmod_id' => 'gmod_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gogr_id' => 'Gogr ID',
            'grol_id' => 'Grol ID',
            'gmod_id' => 'Gmod ID',
            'gogr_estado' => 'Gogr Estado',
            'gogr_fecha_creacion' => 'Gogr Fecha Creacion',
            'gogr_fecha_modificacion' => 'Gogr Fecha Modificacion',
            'gogr_estado_logico' => 'Gogr Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrol()
    {
        return $this->hasOne(GrupRol::className(), ['grol_id' => 'grol_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGmod()
    {
        return $this->hasOne(GrupObmo::className(), ['gmod_id' => 'gmod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupObmoGrupRolObmoAccis()
    {
        return $this->hasMany(GrupObmoGrupRolObmoAcci::className(), ['gogr_id' => 'gogr_id']);
    }
}
