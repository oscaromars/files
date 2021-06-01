<?php

namespace app\modules\admision\models;

use yii\data\ArrayDataProvider;
use DateTime;
use Yii;

/**
 * This is the model class for table "metodo_ingreso".
 *
 * @property integer $ming_id
 * @property string $ming_nombre
 * @property string $ming_descripcion
 * @property string $ming_estado
 * @property string $ming_fecha_creacion
 * @property string $ming_fecha_modificacion
 * @property string $ming_estado_logico
 *
 * @property NivelintMetodo[] $nivelintMetodos
 * @property SolicitudInscripcion[] $solicitudInscripcions
 */
class MetodoIngreso extends \app\modules\admision\components\CActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        //return 'metodo_ingreso';
        return \Yii::$app->db_captacion->dbname.'.metodo_ingreso';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ming_nombre', 'ming_descripcion', 'ming_estado', 'ming_estado_logico'], 'required'],
                [['ming_fecha_creacion', 'ming_fecha_modificacion'], 'safe'],
                [['ming_nombre'], 'string', 'max' => 300],
                [['ming_descripcion'], 'string', 'max' => 500],
                [['ming_estado', 'ming_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ming_id' => 'Ming ID',
            'ming_nombre' => 'Ming Nombre',
            'ming_descripcion' => 'Ming Descripcion',
            'ming_estado' => 'Ming Estado',
            'ming_fecha_creacion' => 'Ming Fecha Creacion',
            'ming_fecha_modificacion' => 'Ming Fecha Modificacion',
            'ming_estado_logico' => 'Ming Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNivelintMetodos() {
        return $this->hasMany(NivelintMetodo::className(), ['ming_id' => 'ming_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudInscripcions() {
        return $this->hasMany(SolicitudInscripcion::className(), ['ming_id' => 'ming_id']);
    }

    
    /**
     * Function consultarMetodoIngNivelInt
     * @author  
     * @property       
     * @return  
     */
    public function consultarMetodoIngNivelInt($uaca_id) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    ming.ming_id as id,
                    ming.ming_nombre as name
                FROM 
                    " . $con->dbname . ".nivelint_metodo as nmet                
                INNER JOIN " . $con1->dbname . ".unidad_academica as uaca on nmet.uaca_id = uaca.uaca_id
                INNER JOIN " . $con->dbname . ".metodo_ingreso as ming on nmet.ming_id = ming.ming_id                    
                WHERE 
                    nmet.nmet_estado_logico=:estado AND 
                    uaca.uaca_estado_logico=:estado AND 
                    ming.ming_estado_logico=:estado AND 
                    nmet.nmet_estado=:estado AND 
                    uaca.uaca_estado=:estado AND 
                    ming.ming_estado=:estado AND
                    nmet.uaca_id=:id_nint";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":id_nint", $uaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    public function obtenerNombreNivel($nivel_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT                     
                    nint.nint_nombre  
               FROM " . $con->dbname . ".nivel_interes nint                    
               WHERE nint.nint_id = :nivel_id AND 
                    nint.nint_estado = :estado AND
                    nint.nint_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":nivel_id", $nivel_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    public function obtenerNombremetodo($metodo_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT                     
                    ming.ming_nombre 
               FROM " . $con->dbname . ".metodo_ingreso ming                    
               WHERE ming.ming_id = :metodo_id AND 
                    ming.ming_estado = :estado AND
                    ming.ming_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":metodo_id", $metodo_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    public function obtenerNombreconocio($mediopub) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $sql = "SELECT                     
                    mpub.mpub_nombre 
               FROM " . $con->dbname . ".medio_publicitario mpub                    
               WHERE mpub.mpub_id = :mediopub AND 
                    mpub.mpub_estado = :estado AND
                    mpub.mpub_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":mediopub", $mediopub, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarMetodoUnidadAca_2
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property       
     * @return  
     */
    public function consultarMetodoUnidadAca_2($uaca_id) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                    ming.ming_id as id,
                    ming.ming_nombre as name
                FROM 
                    " . $con->dbname . ".nivelint_metodo as nmet                
                INNER JOIN " . $con1->dbname . ".unidad_academica as uaca on nmet.uaca_id = uaca.uaca_id
                INNER JOIN " . $con->dbname . ".metodo_ingreso as ming on nmet.ming_id = ming.ming_id                    
                WHERE ming.ming_id <> 3 AND
                    nmet.nmet_estado_logico=:estado AND 
                    uaca.uaca_estado_logico=:estado AND 
                    ming.ming_estado_logico=:estado AND 
                    nmet.nmet_estado=:estado AND 
                    uaca.uaca_estado=:estado AND 
                    ming.ming_estado=:estado AND
                    nmet.uaca_id=:id_nint";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":id_nint", $uaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
