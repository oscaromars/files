<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "periodo_academico_mensualizado".
 *
 * @property int $pame_id
 * @property int $uaca_id
 * @property string $pame_mes
 * @property int $paca_id
 * @property string $pame_descripcion
 * @property int $pame_usuario_ingreso
 * @property int $pame_usuario_modifica
 * @property string $pame_estado
 * @property string $pame_fecha_creacion
 * @property string $pame_fecha_modificacion
 * @property string $pame_estado_logico
 *
 * @property PeriodoAcademico $paca
 * @property UnidadAcademica $uaca
 */
class PeriodoAcademicoMensualizado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo_academico_mensualizado';
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
            [['uaca_id', 'pame_mes', 'paca_id', 'pame_usuario_ingreso', 'pame_estado', 'pame_estado_logico'], 'required'],
            [['uaca_id', 'paca_id', 'pame_usuario_ingreso', 'pame_usuario_modifica'], 'integer'],
            [['pame_fecha_creacion', 'pame_fecha_modificacion'], 'safe'],
            [['pame_mes'], 'string', 'max' => 300],
            [['pame_descripcion'], 'string', 'max' => 500],
            [['pame_estado', 'pame_estado_logico'], 'string', 'max' => 1],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pame_id' => 'Pame ID',
            'uaca_id' => 'Uaca ID',
            'pame_mes' => 'Pame Mes',
            'paca_id' => 'Paca ID',
            'pame_descripcion' => 'Pame Descripcion',
            'pame_usuario_ingreso' => 'Pame Usuario Ingreso',
            'pame_usuario_modifica' => 'Pame Usuario Modifica',
            'pame_estado' => 'Pame Estado',
            'pame_fecha_creacion' => 'Pame Fecha Creacion',
            'pame_fecha_modificacion' => 'Pame Fecha Modificacion',
            'pame_estado_logico' => 'Pame Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaca()
    {
        return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca()
    {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }
}
