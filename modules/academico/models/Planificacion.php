<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "planificacion".
 *
 * @property integer $pla_id
 * @property integer $mod_id
 * @property string $pla_fecha_inicio
 * @property string $pla_fecha_fin
 * @property string $pla_periodo_academico
 * @property string $pla_path
 * @property string $pla_estado
 * @property string $pla_fecha_creacion
 * @property string $pla_fecha_modificacion
 * @property string $pla_estado_logico
 *
 */
class Planificacion extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'planificacion';
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
            [['pla_estado_logico', 'pla_path'], 'required'],
            [['pla_fecha_creacion', 'pla_fecha_modificacion', 'pla_usuario_modifica'], 'safe'],
            [['pla_fecha_inicio', 'pla_fecha_fin', 'pla_periodo_academico', 'pla_estado'], 'string'],
            [['pla_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pla_id' => 'Planificacion ID',
            'mod_id' => 'Modalidad ID',
            'pla_fecha_inicio' => 'Planificacion Fecha Inicio',
            'pla_fecha_fin' => 'Planificacion Fecha Fin',
            'pla_periodo_academico' => 'Planificacion Periodo Academico',
            'pla_estado' => 'Planificacion Estado',
            'pla_fecha_creacion' => 'Planificacion Fecha Creacion',
            'pla_usuario_modificacion' => 'Planificacion Usuario Modificacion',
            'pla_fecha_modificacion' => 'Planificacion Fecha Modificacion',
            'pla_estado_logico' => 'Planificacion Estado Logico'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public static function getAllPlanificacionesGrid($search = NULL, $dataProvider = false) {
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%" . $search . "%";
        $str_search = "";
        if (isset($search)) {
            $str_search = "(pl.pla_periodo_academico like :search OR ";
            $str_search .= "mod.mod_nombre like :search) AND ";
        }
        $sql = "SELECT
      pl.pla_id as id,
      pl.pla_periodo_academico as Periodo Academico
      mod.mod_nombre as Nombre Modalidad
      FROM
      planificacion as pl
      inner join modalidad as mod on mod.mod_id = pl.mod_id
      WHERE
      $str_search
      pl.pla_estado_logico=1 and
      mod.mod_estado_logico=1 and
      ORDER BY pl.pla_id;";
        $comando = Yii::$app->db_academico->createCommand($sql);
        if ($dataProvider) {
            $dataProvider = new ArrayDataProvider([
                'key' => 'pla_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Pla_per_aca', 'Modalidad'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }*/

    public static function getAllPlanificacionesGrid($planificacion, $pla_periodo_academico, $mod_id) {
        $filter = "";
        $search = "%" . $planificacion . "%";
        if (!is_null($planificacion) || $planificacion != "") {
            $filter = 'AND pla.pla_periodo_academico like :search';
        }
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    pla.pla_id as id,
                    pla.pla_periodo_academico as PeriodoAcademico,
                    moda.mod_id as ModaId,
                    moda.mod_nombre as Modalidad
                FROM " . $con_academico->dbname . ".planificacionx as pla,
                " . $con_academico->dbname . ".modalidad as moda
                 WHERE moda.mod_id = pla.mod_id        
                AND pla.pla_periodo_academico =:pla_periodo_academico
                AND pla.mod_id =:mod_id
                $filter
                AND pla.pla_estado =:estado
                AND pla.pla_estado_logico =:estado
            ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":pla_periodo_academico", $pla_periodo_academico, \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":search", $search, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    
    
   
    
    public static function getPeriodosAcademico() {
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT @row_number:=@row_number+1 as pla_id, pla_periodo_academico " .
                "FROM " . Yii::$app->db_academico->dbname . ".planificacion, (SELECT @row_number:=0) AS t " .
                "WHERE pla_estado_logico=1 AND pla_estado=1 " .
                "GROUP BY pla_periodo_academico";

        $comando = $con_academico->createCommand($sql);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    public static function getCurrentPeriodoAcademico() {
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT 
                    pla_id, pla_periodo_academico, pla_fecha_inicio, pla_fecha_fin, mod_id
                FROM " . $con_academico->dbname . ".planificacion 
                WHERE pla_estado_logico=1 AND pla_estado=1 
                GROUP BY pla_id, pla_periodo_academico, pla_fecha_inicio, pla_fecha_fin 
                ORDER BY pla_id DESC";
        $comando = $con_academico->createCommand($sql);
        $resultData = $comando->queryAll();
        $newData = [];
        $arrIds = [];
        foreach ($resultData as $key => $value) {
            if (count($newData) == 0) {
                $newData[] = $resultData[$key];
                $arrIds[] = $value['mod_id'];
            } else {
                if (!array_search($value['mod_id'], $arrIds, true)) {
                    $arrIds[] = $value['mod_id'];
                    $newData[] = $resultData[$key];
                }
            }
        }
        return $newData;
    }

    /**
     * Function getPeriodosmodalidad
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los períodos académicos de planificacion con modalidad).
     */
    public function getPeriodosmodalidad() {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT plan.pla_id id, concat(plan.pla_periodo_academico,'-',moda.mod_nombre) name
                  FROM " . $con->dbname . ".planificacion plan
                  INNER JOIN " . $con->dbname . ".modalidad moda ON moda.mod_id = plan.mod_id
                  WHERE
                    plan.pla_estado = :estado AND
                    plan.pla_estado_logico = :estado AND
                    moda.mod_estado = :estado AND
                    moda.mod_estado_logico = :estado;                
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function getPeriodos_x_modalidad
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los períodos académicos de planificación por modalidad).
     */
    public function getPeriodos_x_modalidad($mod_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT pla_id id, pla_periodo_academico name
                FROM " . $con->dbname . ".planificacion p
                WHERE mod_id = :mod_id and
                     pla_estado = :estado and pla_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
       public static function getVerifypla($pla_periodo_academico, $mod_id) {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                pla_id as issaved
                FROM " . $con_academico->dbname . ".planificacionx 
                WHERE pla_periodo_academico =:pla_periodo_academico
                AND mod_id =:mod_id
                AND pla_estado =:estado
                AND pla_estado_logico =:estado
            ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":pla_periodo_academico", $pla_periodo_academico, \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();

        return $resultData;
    }
    
    public function getPaca_id($per_id){
        $con = \Yii::$app->db_academico;

        $sql = "SELECT
                pla_academico_periodo as acad_per
                from " . $con->dbname . ".planificacion
                where per_id = :per_id and pes_estado_logico = 1 limit 0,1;";
        
        if($per_id == NULL){
            $resultData = [];
        }else{
            $comando = $con->createCommand($sql);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();
        }
        return $resultData;
    }
    
    
    public static function getPeriodosAcademicoPorModalidad($mod_id) {
        $con_academico = \Yii::$app->db_academico;

        $sql = "SELECT pla.pla_id, pla.pla_periodo_academico 
                FROM " . $con->dbname . ".planificacion as pla
                WHERE pla.mod_id = :mod_id;";


        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();

        return $resultData;
    }

}
