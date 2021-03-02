<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "obmo_acci".
 *
 * @property int $oacc_id
 * @property int $omod_id
 * @property int $acc_id
 * @property string $oacc_tipo_boton
 * @property string $oacc_cont_accion
 * @property string $oacc_function
 * @property string $oacc_estado
 * @property string $oacc_fecha_creacion
 * @property string $oacc_fecha_modificacion
 * @property string $oacc_estado_logico
 *
 * @property GrupObmoGrupRolObmoAcci[] $grupObmoGrupRolObmoAccis
 * @property ObjetoModulo $omod
 * @property Accion $acc
 */
class ObmoAcci extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obmo_acci';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['omod_id', 'acc_id', 'oacc_estado', 'oacc_estado_logico'], 'required'],
            [['omod_id', 'acc_id'], 'integer'],
            [['oacc_fecha_creacion', 'oacc_fecha_modificacion'], 'safe'],
            [['oacc_tipo_boton', 'oacc_estado', 'oacc_estado_logico'], 'string', 'max' => 1],
            [['oacc_cont_accion', 'oacc_function'], 'string', 'max' => 250],
            [['omod_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjetoModulo::className(), 'targetAttribute' => ['omod_id' => 'omod_id']],
            [['acc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Accion::className(), 'targetAttribute' => ['acc_id' => 'acc_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'oacc_id' => 'Oacc ID',
            'omod_id' => 'Omod ID',
            'acc_id' => 'Acc ID',
            'oacc_tipo_boton' => 'Oacc Tipo Boton',
            'oacc_cont_accion' => 'Oacc Cont Accion',
            'oacc_function' => 'Oacc Function',
            'oacc_estado' => 'Oacc Estado',
            'oacc_fecha_creacion' => 'Oacc Fecha Creacion',
            'oacc_fecha_modificacion' => 'Oacc Fecha Modificacion',
            'oacc_estado_logico' => 'Oacc Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupObmoGrupRolObmoAccis()
    {
        return $this->hasMany(GrupObmoGrupRolObmoAcci::className(), ['oacc_id' => 'oacc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOmod()
    {
        return $this->hasOne(ObjetoModulo::className(), ['omod_id' => 'omod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcc()
    {
        return $this->hasOne(Accion::className(), ['acc_id' => 'acc_id']);
    }
}
