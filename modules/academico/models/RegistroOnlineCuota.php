<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use app\modules\academico\models\RegistroOnline;

/**
 * This is the model class for table "registro_online_cuota".
 *
 * @property integer $roc_id
 * @property integer $ron_id
 * @property string $roc_num_cuota
 * @property string $roc_vencimiento
 * @property string $roc_porcentaje
 * @property float $roc_costo
 * @property string $roc_estado
 * @property string $roc_fecha_creacion
 * @property string $roc_usuario_modifica
 * @property string $roc_fecha_modifcacion
 * @property string $roc_estado_logico
 * 
 */

class RegistroOnlineCuota extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'registro_online_cuota';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ron_id','roc_estado','roc_estado_logico'],'required'],
            [['roc_fecha_creacion','roc_fecha_modificacion'], 'safe'],
            [['roc_estado_logico','roc_estado'], 'string', 'max' => 1],
            [['roc_porcentaje'], 'string', 'max' => 10],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
            /* [['pes_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionEstudiante::className(), 'targetAttribute' => ['pes_id' => 'pes_id']], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'roc_id' => 'Roc ID',
            'ron_id' => 'Ron ID',
            'roc_num_cuota' => 'Roc Num Cuota',
            'roc_vencimiento' => 'Roc Vencimiento',
            'roc_porcentaje' => 'Roc Porcentaje',
            'roc_costo' => 'Roc Costo',
            'roc_estado' => 'Roc Estado',
            'roc_fecha_creacion' => 'Roc Fecha Creacion',
            'roc_usuario_modifica' => 'Roc Usuario Modifica',
            'roc_fecha_modifcacion' => 'Roc Fecha Modificacion',
            'roc_estado_logico' => 'Roc Estado Logico',
        ];
    }

    public function getDataCuotasRegistroOnline($ron_id, $dataProvider = false)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
            SELECT 
                c.roc_id as Id, 
                c.roc_num_cuota as Cuota, 
                c.roc_vencimiento as Vencimiento, 
                c.roc_porcentaje as Porcentaje, 
                c.roc_costo as Price
            FROM " . $con_academico->dbname . ".registro_online_cuota as c
            WHERE c.ron_id =:ron_id
            AND c.roc_estado =:estado
            AND c.roc_estado_logico =:estado
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        if(!$dataProvider) return $resultData;

        return new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Cuota"],
            ],
        ]);
    }
}