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
                FROM " . $con_academico->dbname . ".planificacion as pla,
                " . $con_academico->dbname . ".modalidad as moda
                 WHERE moda.mod_id = pla.mod_id        
                AND pla.saca_id =:pla_periodo_academico
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
      $sql = "select 
                a.paca_id as pla_id,
                concat(b.saca_nombre , ' ' , b.saca_anio, '(', c.baca_nombre, ')') as pla_periodo_academico
                from  ". $con->dbname . ".periodo_academico a 
                inner join ". $con->dbname . ".semestre_academico b on a.saca_id = b.saca_id
                inner join db_academico.bloque_academico c on a.baca_id = c.baca_id
                where a.paca_activo = 'A'
                order by a.paca_id asc;";


      $sql = "select distinct
                a.saca_id as pla_id,
                concat(b.saca_nombre , ' ' , b.saca_anio ) as pla_periodo_academico
                from  ". $con->dbname . ".periodo_academico a 
                inner join ". $con->dbname . ".semestre_academico b on a.saca_id = b.saca_id
                where a.paca_activo = 'A'
                order by a.paca_id asc;";  

        $comando = $con_academico->createCommand($sql);
        $resultData = $comando->queryAll();

        return $resultData;
    }



    public static function getPeriodosAcademicofull() {
        $con_academico = \Yii::$app->db_academico;
              $sql = " SELECT a.paca_id as pla_id,
                 CONCAT (c.baca_nombre , ' ',c.baca_descripcion,' ',c.baca_anio , '(',a.paca_activo, ')' ) as pla_periodo_academico 
                 FROM db_academico.periodo_academico as a 
INNER JOIN db_academico.semestre_academico as b on a.saca_id = b.saca_id
INNER JOIN db_academico.bloque_academico as c on a.baca_id = c.baca_id
WHERE paca_activo = 'A'
        ";


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
                FROM " . $con_academico->dbname . ".planificacion 
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
        $con = \Yii::$app->db_academico;

        if(isset($mod_id) && $mod_id != "" && $mod_id != 0){
            //$condition .= "WHERE pla.mod_id = :mod_id;";
            $condition .= " pla.mod_id = :mod_id AND ";
        }

        $sql = "SELECT pla.pla_id as id, pla.pla_periodo_academico as name
                FROM " . $con->dbname . ".planificacion as pla
                WHERE $condition
                pla.pla_estado = 1 and pla.pla_estado_logico = 1;";
    /*     WHERE pla.mod_id = :mod_id;";*/

        $comando = $con->createCommand($sql);
        if(isset($mod_id) && $mod_id != "" && $mod_id != 0)  
            $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        //$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        \app\models\Utilities::putMessageLogFile('getPeriodosAcademicoPorModalidad: '.$comando->getRawSql());

        return $resultData;
    }



 public function getCode($sta,$maca_id) {
     $con = \Yii::$app->db_academico;
    $sql = "
          SELECT asi_id 
          from " . $con->dbname . ".malla_academica_detalle where made_codigo_asignatura = :sta
         ";

          $comando = $con->createCommand($sql);
          $comando->bindParam(":sta", $sta, \PDO::PARAM_STR);
          $getterasi = $comando->queryOne();

          $asi_id = $getterasi["asi_id"];


     $con = \Yii::$app->db_academico;
    $sql ="
          SELECT made_codigo_asignatura from
           " . $con->dbname . ".malla_academica_detalle where maca_id=:maca_id and asi_id = :asi_id
         ";
         

          $comando = $con->createCommand($sql);
          $comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
          $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
          $gettercode = $comando->queryOne();

        return $gettercode;
    }



    public function updateCode($columna,$pes_id,$coder){
  $con = \Yii::$app->db_academico;
$sql = " UPDATE " . $con->dbname . ".planificacion_estudiantexx

 SET " .$columna. " = :coder WHERE pes_id = :pes_id

 ";

          $comando = $con->createCommand($sql);
        //  $comando->bindParam("columna", $columna, \PDO::PARAM_INT);
          $comando->bindParam(":coder", $coder, \PDO::PARAM_STR);
           $comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
          $result = $comando->execute();

          return $result;
    }


     public function getMalla($per_id) {
     $con = \Yii::$app->db_academico;
    $sql = "
          SELECT maca_id 
          from " . $con->dbname . ".historico_siga_prueba where cedula = :per_id
         ";

          $comando = $con->createCommand($sql);
          $comando->bindParam(":per_id", $per_id, \PDO::PARAM_STR);
          $getterasi = $comando->queryAll();

  
          $maca_id = $getterasi[0]["maca_id"];




     $con = \Yii::$app->db_academico;
    $sql ="
          SELECT maca_id, maca_codigo from
           " . $con->dbname . ".malla_academica where maca_id=:maca_id";
         

          $comando = $con->createCommand($sql);
          $comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
        //  $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
          $gettercode = $comando->queryOne();

        return $gettercode;
    }


     public function updateMalla($pes_id,$codigo){
  $con = \Yii::$app->db_academico;
  $columna = "pes_cod_carrera";
$sql = " UPDATE " . $con->dbname . ".planificacion_estudiantexx

 SET " .$columna. " = :codigo WHERE pes_id = :pes_id

 ";

          $comando = $con->createCommand($sql);
        //  $comando->bindParam("columna", $columna, \PDO::PARAM_INT);
          $comando->bindParam(":codigo", $codigo, \PDO::PARAM_STR);
           $comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
          $result = $comando->execute();

          return $result;
    }
 
    public static function getPeriodosAcademicoMod() {
        $con_academico = \Yii::$app->db_academico;
      $sql = "SELECT distinct
                a.saca_id as id,
                concat(b.saca_nombre , ' ' , b.saca_anio ) as name
                from  ". $con->dbname . ".periodo_academico a 
                inner join ". $con->dbname . ".semestre_academico b on a.saca_id = b.saca_id
                where a.paca_activo = 'A'
                order by a.paca_id asc;";  

        $comando = $con_academico->createCommand($sql);
        $resultData = $comando->queryAll();

        return $resultData;
    }


}
