<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "medio_publicitario".
 *
 * @property integer $mpub_id
 * @property string $mpub_nombre
 * @property string $mpub_descripcion
 * @property string $mpub_estado
 * @property string $mpub_fecha_creacion
 * @property string $mpub_fecha_modificacion
 * @property string $mpub_estado_logico
 */
class MedioPublicitario extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        //return 'medio_publicitario';
        return \Yii::$app->db_captacion->dbname . '.medio_publicitario';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['mpub_nombre', 'mpub_descripcion', 'mpub_estado', 'mpub_estado_logico'], 'required'],
                [['mpub_fecha_creacion', 'mpub_fecha_modificacion'], 'safe'],
                [['mpub_nombre'], 'string', 'max' => 300],
                [['mpub_descripcion'], 'string', 'max' => 500],
                [['mpub_estado', 'mpub_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mpub_id' => 'Mpub ID',
            'mpub_nombre' => 'Mpub Nombre',
            'mpub_descripcion' => 'Mpub Descripcion',
            'mpub_estado' => 'Mpub Estado',
            'mpub_fecha_creacion' => 'Mpub Fecha Creacion',
            'mpub_fecha_modificacion' => 'Mpub Fecha Modificacion',
            'mpub_estado_logico' => 'Mpub Estado Logico',
        ];
    }

    public static function getMedioPublicitario() {
        $con = \Yii::$app->db_captacion;
        
        $estado = 1;

        $sql = "SELECT 
                    mpub.mpub_id as mpub_id,
                    mpub.mpub_nombre as mpub_nombre                  
                FROM 
                    " . $con->dbname . ".medio_publicitario as mpub
                WHERE 
                    mpub.mpub_estado=:estado AND
                    mpub.mpub_estado_logico=:estado
                    
                ORDER BY mpub.mpub_nombre ASC";


        $comando = $con->createCommand($sql);
        $estado = 1;
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryAll();

        return $resultData;
    }

}
