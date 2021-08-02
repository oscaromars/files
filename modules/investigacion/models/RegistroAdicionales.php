<?php

namespace app\modules\investigacion\models;

use Yii;

/**
 * This is the model class for table "registro_adicionales".
 *
 * @property int $radi_id
 * @property int $rpro_id
 * @property string $radi_antecedente
 * @property string $radi_justificacion
 * @property string $radi_objetivo_general
 * @property string $radi_objetivo_especifico
 * @property string $radi_formulacion
 * @property string $radi_identificacion_variables
 * @property string $radi_metodologia
 * @property string $radi_producto_esperados
 * @property string $radi_marco
 * @property string $radi_referentes_campo_artis
 * @property string $radi_aporte
 * @property string $radi_proceso_creación
 * @property string $radi_resultados
 * @property string $radi_impactos
 * @property string $radi_referencias_bibliogra
 * @property int $radi_estado
 * @property string $radi_fecha_creacion
 * @property int $radi_usuario_ingreso
 * @property int $radi_usuario_modifica
 * @property string $radi_fecha_modificacion
 * @property int $radi_estado_logico
 *
 * @property RegistroProyecto $rpro
 */
class RegistroAdicionales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_adicionales';
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
            [['rpro_id', 'radi_referencias_bibliogra', 'radi_estado', 'radi_usuario_ingreso', 'radi_estado_logico'], 'required'],
            [['rpro_id', 'radi_estado', 'radi_usuario_ingreso', 'radi_usuario_modifica', 'radi_estado_logico'], 'integer'],
            [['radi_antecedente', 'radi_justificacion', 'radi_objetivo_general', 'radi_objetivo_especifico', 'radi_formulacion', 'radi_identificacion_variables', 'radi_metodologia', 'radi_producto_esperados', 'radi_marco', 'radi_referentes_campo_artis', 'radi_aporte', 'radi_proceso_creación', 'radi_resultados', 'radi_impactos'], 'string'],
            [['radi_fecha_creacion', 'radi_fecha_modificacion'], 'safe'],
            [['radi_referencias_bibliogra'], 'string', 'max' => 4000],
            [['rpro_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroProyecto::className(), 'targetAttribute' => ['rpro_id' => 'rpro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'radi_id' => 'Radi ID',
            'rpro_id' => 'Rpro ID',
            'radi_antecedente' => 'Radi Antecedente',
            'radi_justificacion' => 'Radi Justificacion',
            'radi_objetivo_general' => 'Radi Objetivo General',
            'radi_objetivo_especifico' => 'Radi Objetivo Especifico',
            'radi_formulacion' => 'Radi Formulacion',
            'radi_identificacion_variables' => 'Radi Identificacion Variables',
            'radi_metodologia' => 'Radi Metodologia',
            'radi_producto_esperados' => 'Radi Producto Esperados',
            'radi_marco' => 'Radi Marco',
            'radi_referentes_campo_artis' => 'Radi Referentes Campo Artis',
            'radi_aporte' => 'Radi Aporte',
            'radi_proceso_creación' => 'Radi Proceso Creación',
            'radi_resultados' => 'Radi Resultados',
            'radi_impactos' => 'Radi Impactos',
            'radi_referencias_bibliogra' => 'Radi Referencias Bibliogra',
            'radi_estado' => 'Radi Estado',
            'radi_fecha_creacion' => 'Radi Fecha Creacion',
            'radi_usuario_ingreso' => 'Radi Usuario Ingreso',
            'radi_usuario_modifica' => 'Radi Usuario Modifica',
            'radi_fecha_modificacion' => 'Radi Fecha Modificacion',
            'radi_estado_logico' => 'Radi Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRpro()
    {
        return $this->hasOne(RegistroProyecto::className(), ['rpro_id' => 'rpro_id']);
    }
}
