<?php

namespace app\modules\investigacion\models;

use Yii;

/**
 * This is the model class for table "registro_proyecto".
 *
 * @property int $rpro_id
 * @property int $per_id
 * @property int $proy_id
 * @property int $linv_id
 * @property int $rfin_id
 * @property int $rpin_id
 * @property string $rpro_titulo
 * @property string $rpro_resumen
 * @property int $rpro_estado_formulario
 * @property int $rpro_estado
 * @property string $rpro_fecha_creacion
 * @property int $rpro_usuario_ingreso
 * @property int $rpro_usuario_modifica
 * @property string $rpro_fecha_modificacion
 * @property int $rpro_estado_logico
 *
 * @property RegistroAdicionales[] $registroAdicionales
 * @property RegistroAvances[] $registroAvances
 * @property RegistroIntegrante[] $registroIntegrantes
 * @property RegistroPlanificacion[] $registroPlanificacions
 * @property Proyectos $proy
 * @property LineaInvestigacion $linv
 * @property RegistroFinanciamiento $rfin
 * @property RegistroProgramaInvestigación $rpin
 */
class RegistroProyecto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_proyecto';
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
            [['per_id', 'proy_id', 'linv_id', 'rpro_titulo', 'rpro_resumen', 'rpro_estado', 'rpro_usuario_ingreso', 'rpro_estado_logico'], 'required'],
            [['per_id', 'proy_id', 'linv_id', 'rfin_id', 'rpin_id', 'rpro_estado_formulario', 'rpro_estado', 'rpro_usuario_ingreso', 'rpro_usuario_modifica', 'rpro_estado_logico'], 'integer'],
            [['rpro_fecha_creacion', 'rpro_fecha_modificacion'], 'safe'],
            [['rpro_titulo'], 'string', 'max' => 350],
            [['rpro_resumen'], 'string', 'max' => 3000],
            [['proy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proyectos::className(), 'targetAttribute' => ['proy_id' => 'proy_id']],
            [['linv_id'], 'exist', 'skipOnError' => true, 'targetClass' => LineaInvestigacion::className(), 'targetAttribute' => ['linv_id' => 'linv_id']],
            [['rfin_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroFinanciamiento::className(), 'targetAttribute' => ['rfin_id' => 'rfin_id']],
            [['rpin_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroProgramaInvestigación::className(), 'targetAttribute' => ['rpin_id' => 'rpin_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpro_id' => 'Rpro ID',
            'per_id' => 'Per ID',
            'proy_id' => 'Proy ID',
            'linv_id' => 'Linv ID',
            'rfin_id' => 'Rfin ID',
            'rpin_id' => 'Rpin ID',
            'rpro_titulo' => 'Rpro Titulo',
            'rpro_resumen' => 'Rpro Resumen',
            'rpro_estado_formulario' => 'Rpro Estado Formulario',
            'rpro_estado' => 'Rpro Estado',
            'rpro_fecha_creacion' => 'Rpro Fecha Creacion',
            'rpro_usuario_ingreso' => 'Rpro Usuario Ingreso',
            'rpro_usuario_modifica' => 'Rpro Usuario Modifica',
            'rpro_fecha_modificacion' => 'Rpro Fecha Modificacion',
            'rpro_estado_logico' => 'Rpro Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroAdicionales()
    {
        return $this->hasMany(RegistroAdicionales::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroAvances()
    {
        return $this->hasMany(RegistroAvances::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroIntegrantes()
    {
        return $this->hasMany(RegistroIntegrante::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroPlanificacions()
    {
        return $this->hasMany(RegistroPlanificacion::className(), ['rpro_id' => 'rpro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProy()
    {
        return $this->hasOne(Proyectos::className(), ['proy_id' => 'proy_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinv()
    {
        return $this->hasOne(LineaInvestigacion::className(), ['linv_id' => 'linv_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRfin()
    {
        return $this->hasOne(RegistroFinanciamiento::className(), ['rfin_id' => 'rfin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRpin()
    {
        return $this->hasOne(RegistroProgramaInvestigación::className(), ['rpin_id' => 'rpin_id']);
    }
}
