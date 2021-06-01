<?php

namespace app\modules\gfinanciero\models;

use Yii;

/**
 * This is the model class for table "configuracion_rol".
 *
 * @property int $crol_id
 * @property float|null $crol_salario_minimo
 * @property float|null $crol_porcentaje_aporte_patronal
 * @property string|null $crol_aporte_mensual_quincena
 * @property float|null $crol_porcentaje_iess
 * @property string|null $crol_iess_mensual_quincena
 * @property int|null $crol_horas_trabajo
 * @property string|null $crol_paga_benenficios
 * @property float|null $crol_transporte
 * @property string|null $crol_transp_mensual_quincena
 * @property float|null $crol_alimentacion
 * @property string|null $crol_alimen_mensul_quincena
 * @property int|null $crol_usuario_ingreso
 * @property int|null $crol_usuario_modifica
 * @property string|null $crol_estado
 * @property string|null $crol_fecha_creacion
 * @property string|null $crol_fecha_modificacion
 * @property string|null $crol_estado_logico
 */
class ConfiguracionRol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_rol';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gfinanciero');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['crol_salario_minimo', 'crol_porcentaje_aporte_patronal', 'crol_porcentaje_iess', 'crol_transporte', 'crol_alimentacion'], 'number'],
            [['crol_horas_trabajo', 'crol_usuario_ingreso', 'crol_usuario_modifica'], 'integer'],
            [['crol_fecha_creacion', 'crol_fecha_modificacion'], 'safe'],
            [['crol_aporte_mensual_quincena', 'crol_iess_mensual_quincena', 'crol_paga_benenficios', 'crol_transp_mensual_quincena', 'crol_alimen_mensul_quincena', 'crol_estado', 'crol_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'crol_id' => 'Crol ID',
            'crol_salario_minimo' => 'Crol Salario Minimo',
            'crol_porcentaje_aporte_patronal' => 'Crol Porcentaje Aporte Patronal',
            'crol_aporte_mensual_quincena' => 'Crol Aporte Mensual Quincena',
            'crol_porcentaje_iess' => 'Crol Porcentaje Iess',
            'crol_iess_mensual_quincena' => 'Crol Iess Mensual Quincena',
            'crol_horas_trabajo' => 'Crol Horas Trabajo',
            'crol_paga_benenficios' => 'Crol Paga Benenficios',
            'crol_transporte' => 'Crol Transporte',
            'crol_transp_mensual_quincena' => 'Crol Transp Mensual Quincena',
            'crol_alimentacion' => 'Crol Alimentacion',
            'crol_alimen_mensul_quincena' => 'Crol Alimen Mensul Quincena',
            'crol_usuario_ingreso' => 'Crol Usuario Ingreso',
            'crol_usuario_modifica' => 'Crol Usuario Modifica',
            'crol_estado' => 'Crol Estado',
            'crol_fecha_creacion' => 'Crol Fecha Creacion',
            'crol_fecha_modificacion' => 'Crol Fecha Modificacion',
            'crol_estado_logico' => 'Crol Estado Logico',
        ];
    }
}
