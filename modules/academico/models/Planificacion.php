<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use Yii;

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
			'pla_estado_logico' => 'Planificacion Estado Logico',
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
	*/

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

		$sql = "select distinct
                a.saca_id as pla_id,
                concat(b.saca_nombre , ' ' , b.saca_anio ) as pla_periodo_academico
                from  " . $con_academico->dbname . ".periodo_academico a
                inner join " . $con_academico->dbname . ".semestre_academico b on a.saca_id = b.saca_id
               -- left join db_academico.planificacion pl on pl.saca_id = b.saca_id -- R
                where a.paca_activo = 'A'
              --  AND   -- R
              --  pl.pla_estado != 0  -- R
                AND
                ( select count(*) from db_academico.periodo_academico bb
                 WHERE
                bb.saca_id = a.saca_id
                 group by bb.saca_id) > 1
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

		$sql = "SELECT plan.pla_id id, concat(plan.pla_periodo_academico,' - ',moda.mod_nombre) name
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

	public function getPaca_id($per_id) {
		$con = \Yii::$app->db_academico;

		$sql = "SELECT
                pla_academico_periodo as acad_per
                from " . $con->dbname . ".planificacion
                where per_id = :per_id and pes_estado_logico = 1 limit 0,1;";

		if ($per_id == NULL) {
			$resultData = [];
		} else {
			$comando = $con->createCommand($sql);
			$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
			$resultData = $comando->queryAll();
		}
		return $resultData;
	}

	public static function getPeriodosAcademicoPorModalidad($mod_id) {
		$con = \Yii::$app->db_academico;

		if (isset($mod_id) && $mod_id != "" && $mod_id != 0) {
			//$condition .= "WHERE pla.mod_id = :mod_id;";
			$condition .= " pla.mod_id = :mod_id AND ";
		}

		$sql = "SELECT pla.pla_id as id, pla.pla_periodo_academico as name
                FROM " . $con->dbname . ".planificacion as pla
                WHERE $condition
                pla.pla_estado = 1 and pla.pla_estado_logico = 1;";
		/*     WHERE pla.mod_id = :mod_id;";*/

		$comando = $con->createCommand($sql);
		if (isset($mod_id) && $mod_id != "" && $mod_id != 0) {
			$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		}

		//$comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
		$resultData = $comando->queryAll();
		\app\models\Utilities::putMessageLogFile('getPeriodosAcademicoPorModalidad: ' . $comando->getRawSql());

		return $resultData;
	}

	public function getCode($sta, $maca_id) {
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
		$sql = "
          SELECT made_codigo_asignatura from
           " . $con->dbname . ".malla_academica_detalle where maca_id=:maca_id and asi_id = :asi_id
         ";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
		$comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
		$gettercode = $comando->queryOne();

		return $gettercode;
	}

	public function updateCode($columna, $pes_id, $coder) {
		$con = \Yii::$app->db_academico;
		$sql = " UPDATE " . $con->dbname . ".planificacion_estudiantexx

 SET " . $columna . " = :coder WHERE pes_id = :pes_id

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
		$sql = "
          SELECT maca_id, maca_codigo from
           " . $con->dbname . ".malla_academica where maca_id=:maca_id";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
		//  $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
		$gettercode = $comando->queryOne();

		return $gettercode;
	}

	public function updateMalla($pes_id, $codigo) {
		$con = \Yii::$app->db_academico;
		$columna = "pes_cod_carrera";
		$sql = " UPDATE " . $con->dbname . ".planificacion_estudiantexx

 SET " . $columna . " = :codigo WHERE pes_id = :pes_id

 ";

		$comando = $con->createCommand($sql);
		//  $comando->bindParam("columna", $columna, \PDO::PARAM_INT);
		$comando->bindParam(":codigo", $codigo, \PDO::PARAM_STR);
		$comando->bindParam(":pes_id", $pes_id, \PDO::PARAM_INT);
		$result = $comando->execute();

		return $result;
	}

	public static function getPeriodosAcademicoMod() {
		//$con_academico = \Yii::$app->db_academico;
		$con = \Yii::$app->db_academico;
		$sql = "SELECT distinct
                a.saca_id as id,
                concat(b.saca_nombre , ' ' , b.saca_anio ) as name
                from  " . $con_academico->dbname . ".periodo_academico a
                inner join " . $con_academico->dbname . ".semestre_academico b on a.saca_id = b.saca_id
                where a.paca_activo = 'A'
            ORDER BY a.saca_id desc;";
         //order by a.paca_id asc;"; //comentado 31 enero 2022

		//$comando = $con_academico->createCommand($sql);
		$comando = $con->createCommand($sql);
		$resultData = $comando->queryAll();

		return $resultData;
	}

 /**
 * Function Obtiene periodos academico activos, parea asignar materias a paralelos.
 * @author  Julio Lopez <analistadesarrollo03@uteg.edu.ec>
 * @param   
 * @return  $resultData (Retornar los datos).
 */
	public static function getPeriodosAcademicoActivos() {
		$con_academico = \Yii::$app->db_academico;
		$sql = " SELECT paca.paca_id as id,
				        CONCAT (baca.baca_nombre , '-' , saca.saca_nombre , '-' ,  saca.saca_anio) as name
				FROM db_academico.periodo_academico as paca
				INNER JOIN db_academico.semestre_academico as saca on paca.saca_id = saca.saca_id
				INNER JOIN db_academico.bloque_academico as baca on paca.baca_id = baca.baca_id
				WHERE paca.paca_activo = 'A' AND
					paca.paca_estado = 1 AND paca.paca_estado_logico =1 AND
				    saca.saca_estado = 1 AND saca.saca_estado_logico =1 AND
				    baca.baca_estado = 1 AND baca.baca_estado_logico = 1;";

		$comando = $con_academico->createCommand($sql);
		$resultData = $comando->queryAll();

		return $resultData;
	}

		public function actionListarmaterias() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$opt_si = $data['opt_si'];
			$opt_no = $data['opt_no'];
			$per_id = $data['per_id'];
			$mod_id = $data['mod_id'];

			if ($opt_si !="" && $opt_si==1){
				$opt_malla_academica=1;
			}elseif ($opt_no !="" && $opt_no==2){
				$opt_malla_academica=2;
			}
			
			$mod_malla = new MallaAcademica();
			if ($opt_malla_academica==2){
				//Consulta asignaturas de malla academico, que no son centro de idiomas.
				$materia = $mod_malla->consultarasignaturaxmallaaut($per_id, 1); //$mode_malla[0]['id']);
			}else{
				$materia = $mod_malla->selectAsignaturaPorMallaAutCentroIdioma($per_id, $mod_id, 1); 
			}
			return json_encode($materia);
		}
	}

	/**
	 * Function getStudents
	 * @author  Oscar Sánchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Retornar estudiantes nuevos sin planificacion).
	 */
	public function getStudents($evaluator='') {    
		$con = \Yii::$app->db_academico;  
$queryStudents = "
SELECT
pes.pla_id,
e.est_id, e.per_id, e.est_matricula, e.est_fecha_creacion, e.est_categoria, meu.uaca_id, meu.mod_id, meu.eaca_id, DATEDIFF(NOW(),e.est_fecha_creacion) as olderi, --
u.uaca_id, u.uaca_nombre, ea.teac_id, ea.eaca_nombre, ea.eaca_codigo,
per.per_cedula,  maca.maca_id , maca.maca_codigo, maca.maca_nombre,
concat(per.per_pri_nombre, ' ', ifnull(per.per_seg_nombre,''), ' ', per.per_pri_apellido, ' ', ifnull(per.per_seg_apellido,'')) estudiante
 from db_academico.estudiante e
 inner join db_academico.estudiante_carrera_programa c on c.est_id = e.est_id
  inner join db_academico.modalidad_estudio_unidad meu on meu.meun_id = c.meun_id
  inner join db_academico.malla_unidad_modalidad mumo on mumo.meun_id = meu.meun_id
   inner join db_academico.malla_academica maca on maca.maca_id = mumo.maca_id
   inner join db_academico.unidad_academica u on u.uaca_id = meu.uaca_id
   inner join db_academico.estudio_academico ea on ea.eaca_id = meu.eaca_id
    inner join db_asgard.persona per on per.per_id = e.per_id
  left join db_academico.planificacion_estudiante pes on pes.per_id = e.per_id and pes.pla_id in (39,40,41,42)
   WHERE TRUE AND maca.maca_id >46 
    AND  e.est_estado = 1 AND e.est_estado_logico = 1
    AND  c.ecpr_estado = 1 AND c.ecpr_estado_logico = 1
    AND  meu.meun_estado = 1 AND meu.meun_estado_logico = 1
    AND  mumo.mumo_estado = 1 AND mumo.mumo_estado_logico = 1
    AND  maca.maca_estado = 1 AND maca.maca_estado_logico = 1
    AND  u.uaca_estado = 1 AND u.uaca_estado_logico = 1
     AND  ea.eaca_estado = 1
    AND  per.per_estado = 1 AND per.per_estado_logico = 1
    AND meu.uaca_id = 1
   AND pes.pla_id is $evaluator Null 
    AND DATEDIFF(NOW(),e.est_fecha_creacion) <=150
    AND DATEDIFF(NOW(),per.per_fecha_creacion) <=150
    order by maca.maca_id DESC;
";
    //var_dump($queryStudents);
 		$comando = $con->createCommand($queryStudents);
		$resultData = $comando->queryAll();
    return $resultData ;
   }

	/**
	 * Function getScheme
	 * @author  Oscar Sánchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Retornar la estructura de materias para alumnos nuevos).
	 */
    public function getScheme($codesubject) {    
          		$con = \Yii::$app->db_academico;  
$queryScheme = "
SELECT made_codigo_asignatura, made_hora, made_credito
FROM  db_academico.malla_academica_detalle made  
INNER JOIN db_academico.malla_academica maca on maca.maca_id = made.maca_id
WHERE maca.maca_codigo = '".$codesubject."'
AND made.made_semestre = 1
AND maca.maca_estado = 1 AND maca.maca_estado_logico = 1
AND made.made_estado = 1 AND made.made_estado_logico = 1
";
//var_dump($queryScheme);

		$comando = $con->createCommand($queryScheme);
		$resultData = $comando->queryAll();
    return $resultData ;
   }

	/**
	 * Function getreference
	 * @author  Oscar Sánchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Retornar la referencia de planificacion).
	 */
    public function getreference($pes_jornada,$pla_id,$maca_codigo, $bxs1=Null, $bxs2=Null, $bxs3=Null, $bxs4=Null, $bxs5=Null, $bxs6=Null,$bxs7=Null) {    
		$con = \Yii::$app->db_academico;  
if ($bxs7 == Null){ $bxs7 == $bxs1; }
$queryScheme = "
select 
                pes.pes_id,pes.pla_id,pes.pes_jornada,pes.pes_cod_carrera,pes.pes_cod_malla,
                pes.pes_mat_b1_h1_cod as b1h1c,pes.pes_mat_b1_h1_mpp as b1h1p, pes.pes_mod_b1_h1 as b1h1m,pes.pes_jor_b1_h1 as b1h1j,
                pes.pes_mat_b1_h2_cod as b1h2c,pes.pes_mat_b1_h2_mpp as b1h2p, pes.pes_mod_b1_h2 as b1h2m,pes.pes_jor_b1_h2 as b1h2j,
                pes.pes_mat_b1_h3_cod as b1h3c,pes.pes_mat_b1_h3_mpp as b1h3p, pes.pes_mod_b1_h3 as b1h3m,pes.pes_jor_b1_h3 as b1h3j,
                pes.pes_mat_b1_h4_cod as b1h4c,pes.pes_mat_b1_h4_mpp as b1h4p, pes.pes_mod_b1_h4 as b1h4m,pes.pes_jor_b1_h4 as b1h4j,
                pes.pes_mat_b1_h5_cod as b1h5c,pes.pes_mat_b1_h5_mpp as b1h5p, pes.pes_mod_b1_h5 as b1h5m,pes.pes_jor_b1_h5 as b1h5j,
                pes.pes_mat_b1_h6_cod as b1h6c,pes.pes_mat_b1_h6_mpp as b1h6p, pes.pes_mod_b1_h6 as b1h6m,pes.pes_jor_b1_h6 as b1h6j,
                pes.pes_mat_b2_h1_cod as b2h1c,pes.pes_mat_b2_h1_mpp as b2h1p, pes.pes_mod_b2_h1 as b2h1m,pes.pes_jor_b2_h1 as b2h1j,
                pes.pes_mat_b2_h2_cod as b2h2c,pes.pes_mat_b2_h2_mpp as b2h2p, pes.pes_mod_b2_h2 as b2h2m,pes.pes_jor_b2_h2 as b2h2j,
                pes.pes_mat_b2_h3_cod as b2h3c,pes.pes_mat_b2_h3_mpp as b2h3p, pes.pes_mod_b2_h3 as b2h3m,pes.pes_jor_b2_h3 as b2h3j,
                pes.pes_mat_b2_h4_cod as b2h4c,pes.pes_mat_b2_h4_mpp as b2h4p, pes.pes_mod_b2_h4 as b2h4m,pes.pes_jor_b2_h4 as b2h4j,
                pes.pes_mat_b2_h5_cod as b2h5c,pes.pes_mat_b2_h5_mpp as b2h5p, pes.pes_mod_b2_h5 as b2h5m,pes.pes_jor_b2_h5 as b2h5j,
                pes.pes_mat_b2_h6_cod as b2h6c,pes.pes_mat_b2_h6_mpp as b2h6p, pes.pes_mod_b2_h6 as b2h6m,pes.pes_jor_b2_h6 as b2h6j
from db_academico.planificacion_estudiante pes
WHERE TRUE
AND pes.pla_id = $pla_id
-- AND pes.pes_jornada = '".$pes_jornada."'
AND pes.pes_cod_carrera = '".$maca_codigo."'
-- AND  pes.pes_mat_b1_h1_cod in ('".$bxs1."','".$bxs2."','".$bxs3."','".$bxs4."','".$bxs5."','".$bxs6."','".$bxs7."')
-- AND  pes.pes_mat_b2_h1_cod in ('".$bxs1."','".$bxs2."','".$bxs3."','".$bxs4."','".$bxs5."','".$bxs6."','".$bxs7."')
-- AND pes.pes_mat_b1_h1_mpp > 999 
AND pes_semestre = '77'
order by pes_id DESC limit 1
";
//var_dump($queryScheme);
 		$comando = $con->createCommand($queryScheme);
		$resultData = $comando->queryAll();
    return $resultData ;
   }

	/**
	 * Function doPusher
	 * @author  Oscar Sánchez <analistadesarrollo05@uteg.edu.ec>
	 * @param
	 * @return  $resultData (Retornar la insercion de planificacion).
	 */
public function doPusher($schedule,$per_id,$maca_nombre,$per_cedula,$estudiante) {

		$con = \Yii::$app->db_academico;  

 if (isset($schedule[0]['pes_id'])){

$ishere = "
select pes_id 
FROM db_academico.planificacion_estudiante
WHERE TRUE
AND per_id = :per_id
AND pes_id in (39,40,41,42)
";

		$comando = $con->createCommand($ishere);
				$comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
				$hereis = $comando->queryAll();

     if ($hereis[0]['pes_id'] == Null ){

$replier=
"
INSERT INTO db_academico.planificacion_estudiante
(
pla_id,
per_id,
pes_jornada,
pes_cod_carrera,
pes_carrera,
pes_dni,
pes_nombres,
pes_cod_malla,
pes_mat_b1_h1_cod,
pes_mod_b1_h1,
pes_jor_b1_h1,
pes_mat_b1_h2_cod,
pes_mod_b1_h2,
pes_jor_b1_h2,
pes_mat_b1_h3_cod,
pes_mod_b1_h3,
pes_jor_b1_h3,
pes_mat_b1_h4_cod,
pes_mod_b1_h4,
pes_jor_b1_h4,
pes_mat_b1_h5_cod,
pes_mod_b1_h5,
pes_jor_b1_h5,
pes_mat_b1_h6_cod,
pes_mod_b1_h6,
pes_jor_b1_h6,
pes_mat_b2_h1_cod,
pes_mod_b2_h1,
pes_jor_b2_h1,
pes_mat_b2_h2_cod,
pes_mod_b2_h2,
pes_jor_b2_h2,
pes_mat_b2_h3_cod,
pes_mod_b2_h3,
pes_jor_b2_h3,
pes_mat_b2_h4_cod,
pes_mod_b2_h4,
pes_jor_b2_h4,
pes_mat_b2_h5_cod,
pes_mod_b2_h5,
pes_jor_b2_h5,
pes_mat_b2_h6_cod,
pes_mod_b2_h6,
pes_jor_b2_h6,
pes_estado,
pes_estado_logico
)
VALUES
(
'".$schedule[0]['pla_id']."',
'".$per_id."',
'".$schedule[0]['pes_jornada']."',
'".$schedule[0]['pes_cod_carrera']."',
'".$maca_nombre."',
'".$per_cedula."',
'".$estudiante."',
'".$schedule[0]['pes_cod_malla']."',
'".$schedule[0]['b1h1c']."',
'".$schedule[0]['b1h1m']."',
'".$schedule[0]['b1h1j']."',
'".$schedule[0]['b1h2c']."',
'".$schedule[0]['b1h2m']."',
'".$schedule[0]['b1h2j']."',
'".$schedule[0]['b1h3c']."',
'".$schedule[0]['b1h3m']."',
'".$schedule[0]['b1h3j']."',
'".$schedule[0]['b1h4c']."',
'".$schedule[0]['b1h4m']."',
'".$schedule[0]['b1h4j']."',
'".$schedule[0]['b1h5c']."',
'".$schedule[0]['b1h5m']."',
'".$schedule[0]['b1h5j']."',
'".$schedule[0]['b1h6c']."',
'".$schedule[0]['b1h6m']."',
'".$schedule[0]['b1h6j']."',
'".$schedule[0]['b2h1c']."',
'".$schedule[0]['b2h1m']."',
'".$schedule[0]['b2h1j']."',
'".$schedule[0]['b2h2c']."',
'".$schedule[0]['b2h2m']."',
'".$schedule[0]['b2h2j']."',
'".$schedule[0]['b2h3c']."',
'".$schedule[0]['b2h3m']."',
'".$schedule[0]['b2h3j']."',
'".$schedule[0]['b2h4c']."',
'".$schedule[0]['b2h4m']."',
'".$schedule[0]['b2h4j']."',
'".$schedule[0]['b2h5c']."',
'".$schedule[0]['b2h5m']."',
'".$schedule[0]['b2h5j']."',
'".$schedule[0]['b2h6c']."',
'".$schedule[0]['b2h6m']."',
'".$schedule[0]['b2h6j']."',
'1',
'1'
)"
;

 //var_dump($replier);

 //$replierer = str_replace('999','0', $replier);


    $comando = $con->createCommand($replier);
                     $fullrefer = $comando->execute(); 
                 return $fullrefer;

    }}

 if (isset($schedule[0]['made_codigo_asignatura'])){

return true;

    }

 

    }




}
