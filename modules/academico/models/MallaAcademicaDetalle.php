<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "malla_academica_detalle".
 *
 * @property int $made_id
 * @property int $maca_id
 * @property int $asi_id
 * @property int $made_semestre
 * @property int $uest_id
 * @property int $nest_id
 * @property int $fmac_id
 * @property string $made_codigo_asignatura
 * @property int $made_asi_requisito
 * @property int $made_horas_docencia
 * @property int $made_horas_otros
 * @property int $made_hora
 * @property int $made_credito
 * @property int $made_usuario_ingreso
 * @property int $made_usuario_modifica
 * @property string $made_estado
 * @property string $made_fecha_creacion
 * @property string $made_fecha_modificacion
 * @property string $made_estado_logico
 *
 * @property MallaAcademica $maca
 * @property Asignatura $asi
 * @property UnidadEstudio $uest
 * @property NivelEstudio $nest
 * @property FormacionMallaAcademica $fmac
 */
class MallaAcademicaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'malla_academica_detalle';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['maca_id', 'asi_id', 'made_semestre', 'uest_id', 'nest_id', 'fmac_id', 'made_codigo_asignatura', 'made_usuario_ingreso', 'made_estado', 'made_estado_logico'], 'required'],
            [['maca_id', 'asi_id', 'made_semestre', 'uest_id', 'nest_id', 'fmac_id', 'made_asi_requisito', 'made_horas_docencia', 'made_horas_otros', 'made_hora', 'made_credito', 'made_usuario_ingreso', 'made_usuario_modifica'], 'integer'],
            [['made_fecha_creacion', 'made_fecha_modificacion'], 'safe'],
            [['made_codigo_asignatura'], 'string', 'max' => 300],
            [['made_estado', 'made_estado_logico'], 'string', 'max' => 1],
            [['maca_id'], 'exist', 'skipOnError' => true, 'targetClass' => MallaAcademica::className(), 'targetAttribute' => ['maca_id' => 'maca_id']],
            [['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
            [['uest_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadEstudio::className(), 'targetAttribute' => ['uest_id' => 'uest_id']],
            [['nest_id'], 'exist', 'skipOnError' => true, 'targetClass' => NivelEstudio::className(), 'targetAttribute' => ['nest_id' => 'nest_id']],
            [['fmac_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormacionMallaAcademica::className(), 'targetAttribute' => ['fmac_id' => 'fmac_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'made_id' => 'Made ID',
            'maca_id' => 'Maca ID',
            'asi_id' => 'Asi ID',
            'made_semestre' => 'Made Semestre',
            'uest_id' => 'Uest ID',
            'nest_id' => 'Nest ID',
            'fmac_id' => 'Fmac ID',
            'made_codigo_asignatura' => 'Made Codigo Asignatura',
            'made_asi_requisito' => 'Made Asi Requisito',
            'made_horas_docencia' => 'Made Horas Docencia',
            'made_horas_otros' => 'Made Horas Otros',
            'made_hora' => 'Made Hora',
            'made_credito' => 'Made Credito',
            'made_usuario_ingreso' => 'Made Usuario Ingreso',
            'made_usuario_modifica' => 'Made Usuario Modifica',
            'made_estado' => 'Made Estado',
            'made_fecha_creacion' => 'Made Fecha Creacion',
            'made_fecha_modificacion' => 'Made Fecha Modificacion',
            'made_estado_logico' => 'Made Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaca()
    {
        return $this->hasOne(MallaAcademica::className(), ['maca_id' => 'maca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsi()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUest()
    {
        return $this->hasOne(UnidadEstudio::className(), ['uest_id' => 'uest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNest()
    {
        return $this->hasOne(NivelEstudio::className(), ['nest_id' => 'nest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFmac()
    {
        return $this->hasOne(FormacionMallaAcademica::className(), ['fmac_id' => 'fmac_id']);
    }
}
