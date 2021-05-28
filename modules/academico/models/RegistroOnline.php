<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "registro_online".
 *
 * @property int $ron_id
 * @property int $per_id
 * @property int $pes_id
 * @property int $ron_num_orden
 * @property string $ron_fecha_registro
 * @property string $ron_anio
 * @property string $ron_semestre
 * @property string $ron_modalidad
 * @property string $ron_carrera
 * @property string $ron_categoria_est
 * @property string $ron_valor_arancel
 * @property string $ron_valor_matricula
 * @property string $ron_valor_gastos_adm
 * @property string $ron_valor_aso_estudiante
 * @property string $ron_estado_registro
 * @property string $ron_estado
 * @property string $ron_fecha_creacion
 * @property int $ron_usuario_modifica
 * @property string $ron_fecha_modificacion
 * @property string $ron_estado_logico
 *
 * @property PlanificacionEstudiante $pes
 * @property RegistroOnlineCuota[] $registroOnlineCuotas
 * @property RegistroOnlineItem[] $registroOnlineItems
 */
class RegistroOnline extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_online';
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
            [['per_id', 'pes_id', 'ron_num_orden', 'ron_fecha_registro', 'ron_estado_registro', 'ron_estado', 'ron_estado_logico'], 'required'],
            [['per_id', 'pes_id', 'ron_num_orden', 'ron_usuario_modifica'], 'integer'],
            [['ron_fecha_registro', 'ron_fecha_creacion', 'ron_fecha_modificacion'], 'safe'],
            [['ron_valor_arancel', 'ron_valor_matricula', 'ron_valor_gastos_adm', 'ron_valor_aso_estudiante'], 'number'],
            [['ron_anio'], 'string', 'max' => 4],
            [['ron_semestre', 'ron_estado_registro', 'ron_estado', 'ron_estado_logico'], 'string', 'max' => 1],
            [['ron_modalidad'], 'string', 'max' => 80],
            [['ron_carrera'], 'string', 'max' => 500],
            [['ron_categoria_est'], 'string', 'max' => 2],
            [['pes_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionEstudiante::className(), 'targetAttribute' => ['pes_id' => 'pes_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ron_id' => 'Ron ID',
            'per_id' => 'Per ID',
            'pes_id' => 'Pes ID',
            'ron_num_orden' => 'Num Orden',
            'ron_fecha_registro' => 'Ron Fecha Registro',
            'ron_anio' => 'Ron Anio',
            'ron_semestre' => 'Ron Semestre',
            'ron_modalidad' => 'Ron Modalidad',
            'ron_carrera' => 'Ron Carrera',
            'ron_categoria_est' => 'Ron Categoria Est',
            'ron_valor_arancel' => 'Ron Valor Arancel',
            'ron_valor_matricula' => 'Ron Valor Matricula',
            'ron_valor_gastos_adm' => 'Ron Valor Gastos Adm',
            'ron_valor_aso_estudiante' => 'Ron Valor Aso Estudiante',
            'ron_estado_registro' => 'Ron Estado Registro',
            'ron_estado' => 'Ron Estado',
            'ron_fecha_creacion' => 'Ron Fecha Creacion',
            'ron_usuario_modifica' => 'Ron Usuario Modifica',
            'ron_fecha_modificacion' => 'Ron Fecha Modificacion',
            'ron_estado_logico' => 'Ron Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPes()
    {
        return $this->hasOne(PlanificacionEstudiante::className(), ['pes_id' => 'pes_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroOnlineCuotas()
    {
        return $this->hasMany(RegistroOnlineCuota::className(), ['ron_id' => 'ron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroOnlineItems()
    {
        return $this->hasMany(RegistroOnlineItem::className(), ['ron_id' => 'ron_id']);
    }
}
