<?php

namespace app\modules\admision\models;

use yii\data\ArrayDataProvider;
use DateTime;
use Yii;

/**
 * This is the model class for table "res_sol_inscripcion".
 *
 * @property integer $rsin_id
 * @property string $rsin_nombre
 * @property string $rsin_descripcion
 * @property string $rsin_estado
 * @property string $rsin_fecha_creacion
 * @property string $rsin_fecha_modificacion
 * @property string $rsin_estado_logico
 *
 * @property SolicitudInscripcion[] $solicitudInscripcions
 */
class ResSolInscripcion extends \app\modules\admision\components\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        //return 'res_sol_inscripcion';
        return \Yii::$app->db_captacion->dbname . '.res_sol_inscripcion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rsin_nombre', 'rsin_descripcion', 'rsin_estado', 'rsin_estado_logico'], 'required'],
            [['rsin_fecha_creacion', 'rsin_fecha_modificacion'], 'safe'],
            [['rsin_nombre'], 'string', 'max' => 300],
            [['rsin_descripcion'], 'string', 'max' => 500],
            [['rsin_estado', 'rsin_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rsin_id' => 'Rsin ID',
            'rsin_nombre' => 'Rsin Nombre',
            'rsin_descripcion' => 'Rsin Descripcion',
            'rsin_estado' => 'Rsin Estado',
            'rsin_fecha_creacion' => 'Rsin Fecha Creacion',
            'rsin_fecha_modificacion' => 'Rsin Fecha Modificacion',
            'rsin_estado_logico' => 'Rsin Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudInscripcions()
    {
        return $this->hasMany(SolicitudInscripcion::className(), ['rsin_id' => 'rsin_id']);
    }
}
