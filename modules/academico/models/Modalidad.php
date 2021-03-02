<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use DateTime;
use Yii;

/**
 * This is the model class for table "modalidad".
 *
 * @property integer $mod_id
 * @property string $mod_nombre
 * @property string $mod_descripcion
 * @property integer $mod_nivel_grado
 * @property integer $mod_nivel_posgrado
 * @property string $mod_estado
 * @property string $mod_fecha_creacion
 * @property string $mod_fecha_modificacion
 * @property string $mod_estado_logico
 *
 * @property CarreraMalla[] $carreraMallas
 * @property EvaluacionDesempeno[] $evaluacionDesempenos
 */
class Modalidad extends \app\modules\academico\components\CActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'modalidad';
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
            [['mod_nombre', 'mod_descripcion', 'mod_estado', 'mod_estado_logico'], 'required'],
            [['mod_nivel_grado', 'mod_nivel_posgrado'], 'integer'],
            [['mod_fecha_creacion', 'mod_fecha_modificacion'], 'safe'],
            [['mod_nombre'], 'string', 'max' => 200],
            [['mod_descripcion'], 'string', 'max' => 500],
            [['mod_estado', 'mod_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mod_id' => 'Mod ID',
            'mod_nombre' => 'Mod Nombre',
            'mod_descripcion' => 'Mod Descripcion',
            'mod_nivel_grado' => 'Mod Nivel Grado',
            'mod_nivel_posgrado' => 'Mod Nivel Posgrado',
            'mod_estado' => 'Mod Estado',
            'mod_fecha_creacion' => 'Mod Fecha Creacion',
            'mod_fecha_modificacion' => 'Mod Fecha Modificacion',
            'mod_estado_logico' => 'Mod Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarreraMallas() {
        return $this->hasMany(CarreraMalla::className(), ['mod_id' => 'mod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluacionDesempenos() {
        return $this->hasMany(EvaluacionDesempeno::className(), ['mod_id' => 'mod_id']);
    }

    /**
     * Function obtener Modalidad segun nivel interes estudio
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarModalidad($uaca_id, $emp_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
            $sql = "SELECT distinct moda.mod_id as id,
                           moda.mod_nombre as name
                    FROM " . $con->dbname . ".modalidad_estudio_unidad meu "
                    . "inner join " . $con->dbname . ".modalidad moda ON moda.mod_id = meu.mod_id
                    WHERE uaca_id = :uaca_id 
                    and emp_id =:emp_id
                    and meu.meun_estado_logico = :estado
                    and meu.meun_estado = :estado
                    and moda.mod_estado_logico = :estado
                    and moda.mod_estado = :estado
                    ORDER BY 1 asc";        
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /** Se debe cambiar esta funcion que regrese el codigo de area ***ojo***
     * Function consultarIdsCarrera
     * @author  Byron Villacreses <developer@uteg.edu.ec>
     * @property integer car_id      
     * @return  
     */
    public static function consultarIdsModalidad($TextAlias) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT mod_id Ids 
                    FROM " . $con->dbname . ".modalidad  
                WHERE mod_estado_logico=1 AND mod_nombre=:mod_nombre ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":mod_nombre", $TextAlias, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto 
        return $rawData;
    }

    /**
     * Function consulta el nombre de modalidad
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarNombremoda($modalidad) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    moda.mod_nombre as nombre_modalidad
                FROM 
                    " . $con->dbname . ".modalidad as moda            
                WHERE   
                    moda.mod_id=:modalidad AND
                    moda.mod_estado_logico=:estado AND 
                    moda.mod_estado=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function getCodeCCostoxModalidad($mod_id){
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    mcco_code AS Cod
                FROM 
                    " . $con->dbname . ".modalidad_centro_costo            
                WHERE   
                    mod_id=:modalidad AND
                    mcco_estado_logico=:estado AND 
                    mcco_estado=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":modalidad", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

}
