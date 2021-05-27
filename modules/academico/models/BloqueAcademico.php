<?php


namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "periodo_academico".
 *
 * @property int $baca_id
 * @property int $saca_id
 * @property int $baca_id
 * @property string $baca_activo
 * @property string $baca_fecha_inicio
 * @property string $baca_fecha_fin
 * @property int $baca_usuario_ingreso
 * @property int $baca_usuario_modifica
 * @property string $baca_estado
 * @property string $baca_fecha_creacion
 * @property string $baca_fecha_modificacion
 * @property string $baca_estado_logico
 *
 * @property DistributivoAcademico[] $distributivoAcademicos
 * @property DistributivoCabecera[] $distributivoCabeceras
 * @property EstudiantePeriodoPago[] $estudiantePeriodoPagos
 * @property SemestreAcademico $saca
 * @property BloqueAcademico $baca
 * @property PlanificacionAcademicaMalla[] $planificacionAcademicaMallas
 */
class BloqueAcademico extends \yii\db\ActiveRecord
{
     /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bloque_academico';
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
            [['baca_descripcion','baca_nombre', 'baca_estado', 'baca_estado_logico','baca_anio'], 'required'],
            [['baca_usuario_ingreso', 'baca_usuario_modifica','baca_anio'], 'integer'],
            [['baca_fecha_inicio', 'baca_fecha_fin', 'baca_fecha_creacion', 'baca_fecha_modificacion'], 'safe'],
            [[ 'baca_estado', 'baca_estado_logico'], 'string', 'max' => 1],
           ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'baca_id' => 'Paca ID',
            'baca_anio' => 'Bloque Año',
            'baca_descripcion' => 'Bloque Descripción',
            'baca_nombre' => 'Bloque Nombre',
            'baca_fecha_inicio' => '',
            'baca_fecha_fin' => '',
            'baca_usuario_ingreso' => '',
            'baca_usuario_modifica' => '',
            'baca_estado' => '',
            'baca_fecha_creacion' => '',
            'baca_fecha_modificacion' => '',
            'baca_estado_logico' => '',
        ];
    }

    
}

