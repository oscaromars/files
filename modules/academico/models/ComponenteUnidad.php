<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "componente_unidad".
 *
 * @property int $cuni_id
 * @property int $com_id
 * @property int $uaca_id
 * @property double $cuni_calificacion
 * @property string $cuni_estado
 * @property string $cuni_fecha_creacion
 * @property string $cuni_fecha_modificacion
 * @property string $cuni_estado_logico
 *
 * @property UnidadAcademica $uaca
 * @property Componente $com
 * @property ComponenteUnidadActividad[] $componenteUnidadActividads
 * @property DetalleCalificacion[] $detalleCalificacions
 */
class ComponenteUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'componente_unidad';
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
            [['com_id', 'uaca_id', 'cuni_calificacion', 'cuni_estado', 'cuni_estado_logico'], 'required'],
            [['com_id', 'uaca_id'], 'integer'],
            [['cuni_calificacion'], 'number'],
            [['cuni_fecha_creacion', 'cuni_fecha_modificacion'], 'safe'],
            [['cuni_estado', 'cuni_estado_logico'], 'string', 'max' => 1],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['com_id'], 'exist', 'skipOnError' => true, 'targetClass' => Componente::className(), 'targetAttribute' => ['com_id' => 'com_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cuni_id' => 'Cuni ID',
            'com_id' => 'Com ID',
            'uaca_id' => 'Uaca ID',
            'cuni_calificacion' => 'Cuni Calificacion',
            'cuni_estado' => 'Cuni Estado',
            'cuni_fecha_creacion' => 'Cuni Fecha Creacion',
            'cuni_fecha_modificacion' => 'Cuni Fecha Modificacion',
            'cuni_estado_logico' => 'Cuni Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca()
    {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCom()
    {
        return $this->hasOne(Componente::className(), ['com_id' => 'com_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponenteUnidadActividads()
    {
        return $this->hasMany(ComponenteUnidadActividad::className(), ['cuni_id' => 'cuni_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleCalificacions()
    {
        return $this->hasMany(DetalleCalificacion::className(), ['cuni_id' => 'cuni_id']);
    }
}
