<?php

namespace app\modules\academico\controllers;

use app\models\ExportFile;
use app\models\Persona;
use app\models\Utilities;
use app\modules\academico\models\DistributivoAcademicoHorario;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\MallaAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\Planificacion;
use app\modules\academico\models\PlanificacionEstudiante;
use app\modules\academico\models\RegistroConfiguracion;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\Module as academico;
use app\modules\admision\models\Oportunidad;
use Yii;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

academico::registerTranslations();

class PlanificacionController extends \app\components\CController {

	private function Bloques($id = 0) {
		if ($id == 0) {
			return [
				'0' => Yii::t('formulario', 'Seleccionar'),
				'1' => Yii::t('formulario', 'Bloque 1'),
				'2' => Yii::t('formulario', 'Bloque 2'),
			];
		}
		if ($id == 1) {
			return [
				'1' => Yii::t('formulario', 'Bloque 1'),
				'2' => Yii::t('formulario', 'Bloque 2'),
			];
		}
		if ($id == 2) {
			return [
				'2' => Yii::t('formulario', 'Bloque 2'),
				'1' => Yii::t('formulario', 'Bloque 1'),
			];
		}
		return [
			'0' => Yii::t('formulario', 'Seleccionar'),
			'1' => Yii::t('formulario', 'Bloque 1'),
			'2' => Yii::t('formulario', 'Bloque 2'),
		];
	}

	private function Horas() {
		return [
			'0' => Yii::t('formulario', 'Seleccionar'),
			'1' => Yii::t('formulario', 'Hora 1'),
			'2' => Yii::t('formulario', 'Hora 2'),
			'3' => Yii::t('formulario', 'Hora 3'),
			'4' => Yii::t('formulario', 'Hora 4'),
			'5' => Yii::t('formulario', 'Hora 5'),
			'6' => Yii::t('formulario', 'Hora 6'),
		];
	}

	private function Paralelo() {
		return [
			'0' => Yii::t('formulario', 'Seleccionar'),
		];
	}
	private function Modalidades() {
		return [
			'0' => Yii::t('formulario', 'Seleccionar'),
			'1' => Yii::t('formulario', 'Online'),
			'2' => Yii::t('formulario', 'Presencial'),
			'3' => Yii::t('formulario', 'Semi Presencial'),
			'4' => Yii::t('formulario', 'Distancia'),
		];
	}
	private function Horario() {
		return [
			'0' => Yii::t('formulario', 'Seleccionar'),
		];
	}

	public function actionIndex() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->get();
			$pla_periodo_academico = $data['pla_periodo_academico'];
			$mod_id = $data['mod_id'];
			$dataPlanificaciones = Planificacion::getAllPlanificacionesGrid($search, $pla_periodo_academico, $mod_id);
			$dataProvider = new ArrayDataProvider([
				'key' => 'pla_id',
				'allModels' => $dataPlanificaciones,
				'pagination' => [
					'pageSize' => Yii::$app->params['pageSize'],
				],
				'sort' => [
					'attributes' => ['PeriodoAcademico'],
				],
			]);
			return $this->renderPartial('index-grid', [
				'model' => $dataProvider,
			]);
			/*   }
             */
		}
		/*
			          $arr_pla = Planificacion::find()
			          ->select( ['ROW_NUMBER() OVER (ORDER BY pla_periodo_academico) pla_id', 'pla_periodo_academico'] )
			          ->where( 'pla_estado_logico = 1 and pla_estado = 1' )
			          ->groupBy( ['pla_periodo_academico'] )
			          ->all();
		*/
		$arr_pla = Planificacion::getPeriodosAcademico();
		/* print_r( $arr_pla );
         */
		//if ( count( $arr_pla ) > 0 ) {
		$arr_modalidad = Modalidad::findAll(['mod_estado' => 1, 'mod_estado_logico' => 1]);
		$pla_periodo_academico = $arr_pla[0]->pla_periodo_academico;
		$mod_id = $arr_modalidad[0]->mod_id;
		$dataPlanificaciones = Planificacion::getAllPlanificacionesGrid(null, $pla_periodo_academico, $mod_id);
		$dataProvider = new ArrayDataProvider([
			'key' => 'pla_id',
			'allModels' => $dataPlanificaciones,
			'pagination' => [
				'pageSize' => Yii::$app->params['pageSize'],
			],
			'sort' => [
				'attributes' => ['Modalidad'],
			],
		]);
		return $this->render('index', [
			'arr_pla' => (empty(ArrayHelper::map($arr_pla, 'pla_id', 'pla_periodo_academico'))) ? array(Yii::t('planificacion', '-- Select periodo --')) : (ArrayHelper::map($arr_pla, 'pla_id', 'pla_periodo_academico')),
			// 'arr_modalidad' => ( empty(ArrayHelper::map($arr_modalidad, 'mod_id', 'mod_nombre')) ) ? array(Yii::t('planificacion', '-- Select Modality --')) : ( ArrayHelper::map($arr_modalidad, 'mod_id', 'mod_nombre') ),
			'arr_modalidad' => ArrayHelper::map(array_merge([["mod_id" => "0", "mod_nombre" => Yii::t("formulario", "Select")]], $arr_modalidad), "mod_id", "mod_nombre"),
			'model' => $dataProvider,
			'pla_periodo_academico' => 0,
			'mod_id' => 0,
		]);
		//}
	}

	public function actionGenerator($periodo, $modalidad) {

		$con = \Yii::$app->db_academico;
		$sql = "
                            SELECT
                            CONCAT(saca_nombre,' ',saca_anio) as semestre
                            FROM db_academico.semestre_academico where saca_id = :periodo
                    ";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
		$saca_nombre = $comando->queryOne();

		$con = \Yii::$app->db_academico;

		$sql = "
            select distinct e.est_id, e.per_id, e.est_matricula, e.est_fecha_creacion, e.est_categoria, meu.uaca_id, meu.mod_id, meu.eaca_id, DATEDIFF(NOW(),e.est_fecha_creacion) as olderi, --
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
   inner join db_academico.materias_periodo_modalidad mpmo on mpmo.eaca_id = meu.eaca_id and mpmo.mod_id = meu.mod_id and mpmo.saca_id = :periodo
   inner join db_asgard.persona per on per.per_id = e.per_id
   left join db_academico.malla_academico_estudiante malle on per.per_id = malle.per_id
     where  malle.maca_id = maca.maca_id  AND
         meu.mod_id = :modalidad and meu.uaca_id = 1 and mpmo.saca_id = :periodo and mpmo.mpmo_activo = 'A'
          and mpmo.mpmo_procesado is Null
    AND  mpmo.mpmo_estado = 1 AND mpmo.mpmo_estado_logico = 1
    AND  e.est_estado = 1 AND e.est_estado_logico = 1
    AND  c.ecpr_estado = 1 AND c.ecpr_estado_logico = 1
    AND  meu.meun_estado = 1 AND meu.meun_estado_logico = 1
    AND  mumo.mumo_estado = 1 AND mumo.mumo_estado_logico = 1
    AND  maca.maca_estado = 1 AND maca.maca_estado_logico = 1
    AND  u.uaca_estado = 1 AND u.uaca_estado_logico = 1
     AND  ea.eaca_estado = 1
    AND  per.per_estado = 1 AND per.per_estado_logico = 1
    AND  malle.maes_estado = 1 AND malle.maes_estado_logico = 1
     AND
((e.per_id in (select b.per_id from db_academico.planificacion_estudiante b where
b.pla_id= ( select max(dap.pla_id) from db_academico.planificacion dap
 where dap.mod_id = :modalidad ))) or
((e.per_id in (
select distinct a.per_id from db_asgard.persona as a
inner join db_academico.estudiante bas on a.per_id = bas.per_id
where DATEDIFF(NOW(),bas.est_fecha_creacion) <=1800 or
DATEDIFF(NOW(),a.per_fecha_creacion) <=1800 )))
 )
order by maca.maca_id DESC , ea.eaca_codigo, e.est_fecha_creacion ASC;
                ";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_STR);
		$comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
		$resultData = $comando->queryAll();

		$malla = new MallaAcademica();
		$allstudents = count($resultData);
		\app\models\Utilities::putMessageLogFile("all: " . count($resultData));

		   if (count($resultData) > 0) { 
        $preprocess = $malla->traerActivas($periodo,$modalidad); 
        if (count($preprocess) > 0) {  
             for ($ix = 0; $ix < count($resultData); $ix++) {  
$postprocess = $malla->marcarAsignaturas($preprocess[$ix]["mpmo_id"],$periodo); 

     }    }     }

		if (count($resultData) > 0) {

			for ($i = 0; $i < count($resultData); $i++) {
				$centralprocess = $malla->consultarAsignaturas($resultData[$i], $periodo, $saca_nombre["semestre"], $modalidad);if ($centralprocess == 1) {$ok = 1;}
		
				\app\models\Utilities::putMessageLogFile("Received " . $centralprocess);
			}

		} else {

			\Yii::$app->getSession()->setFlash('msg', 'No existen mas estudiantes para la modalidad actual');
			return $this->redirect(['index']);

		}

		if ($ok != 1) {
			\Yii::$app->getSession()->setFlash('msg', 'No existen datos suficientes para generar la planificacion seleccionada');
			// \Yii::$app->getSession()->setFlash('msg') ;
			return $this->redirect(['index']);

		}
		//   return $resultData;
		//  return $this->render('temporal', [
		//         'resultData' => $centralprocess,
		//          ]);
		\Yii::$app->getSession()->setFlash('msgok', 'Se ha generado con exito la planificacion');
		return $this->redirect(['index']);
	}

		public function actionRegenerator($periodo, $modalidad) {

		$con = \Yii::$app->db_academico;
		$sql = "
                            SELECT
                            CONCAT(saca_nombre,' ',saca_anio) as semestre
                            FROM db_academico.semestre_academico where saca_id = :periodo
                    ";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
		$saca_nombre = $comando->queryOne();


          $sql = "
		SELECT pla_id
		FROM db_academico.planificacion 
		WHERE saca_id = :periodo and mod_id= :modalidad";
          $comando = $con->createCommand($sql);
		$comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
		$comando->bindParam(":modalidad", $periodo, \PDO::PARAM_INT);
		$get_pla_id = $comando->queryOne();
          $oldplan = $get_pla_id["pla_id"];

          $sql = "
		SELECT pla_id
		FROM db_academico.planificacion 
		WHERE saca_id = :periodo and mod_id= :modalidad 
          AND pla_id <  :oldplan ";
          $comando = $con->createCommand($sql);
		$comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
		$comando->bindParam(":modalidad", $periodo, \PDO::PARAM_INT);
			$comando->bindParam(":oldplan", $oldplan, \PDO::PARAM_INT);
		$get_pla_id = $comando->queryOne();
          $oldplan = $get_pla_id["pla_id"];

		$con = \Yii::$app->db_academico;

		$sql = "
            select distinct e.est_id, e.per_id, e.est_matricula, e.est_fecha_creacion, e.est_categoria, meu.uaca_id, meu.mod_id, meu.eaca_id, DATEDIFF(NOW(),e.est_fecha_creacion) as olderi, --
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
   inner join db_academico.materias_periodo_modalidad mpmo on mpmo.eaca_id = meu.eaca_id and mpmo.mod_id = meu.mod_id and mpmo.saca_id = :periodo
   inner join db_asgard.persona per on per.per_id = e.per_id
   left join db_academico.malla_academico_estudiante malle on per.per_id = malle.per_id
     where  malle.maca_id = maca.maca_id  AND
         meu.mod_id = :modalidad and meu.uaca_id = 1 and mpmo.saca_id = :periodo and mpmo.mpmo_activo = 'A'
          and mpmo.mpmo_procesado is Null
    AND  mpmo.mpmo_estado = 1 AND mpmo.mpmo_estado_logico = 1
    AND  e.est_estado = 1 AND e.est_estado_logico = 1
    AND  c.ecpr_estado = 1 AND c.ecpr_estado_logico = 1
    AND  meu.meun_estado = 1 AND meu.meun_estado_logico = 1
    AND  mumo.mumo_estado = 1 AND mumo.mumo_estado_logico = 1
    AND  maca.maca_estado = 1 AND maca.maca_estado_logico = 1
    AND  u.uaca_estado = 1 AND u.uaca_estado_logico = 1
     AND  ea.eaca_estado = 1
    AND  per.per_estado = 1 AND per.per_estado_logico = 1
    AND  malle.maes_estado = 1 AND malle.maes_estado_logico = 1
     AND
((e.per_id in (select b.per_id from db_academico.planificacion_estudiante b where
b.pla_id= :oldplan )) OR
((e.per_id in (
select distinct a.per_id from db_asgard.persona as a
inner join db_academico.estudiante bas on a.per_id = bas.per_id
where DATEDIFF(NOW(),bas.est_fecha_creacion) <=1800 or
DATEDIFF(NOW(),a.per_fecha_creacion) <=1800 )))
)
order by maca.maca_id DESC , ea.eaca_codigo, e.est_fecha_creacion ASC;
                ";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_STR);
		$comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
		$comando->bindParam(":oldplan", $oldplan, \PDO::PARAM_INT);
		$resultData = $comando->queryAll();

		$malla = new MallaAcademica();
		$allstudents = count($resultData);
		\app\models\Utilities::putMessageLogFile("all: " . count($resultData));

		   if (count($resultData) > 0) { 
        $preprocess = $malla->traerActivas($periodo,$modalidad); 
        if (count($preprocess) > 0) {  
             for ($ix = 0; $ix < count($resultData); $ix++) {  
$postprocess = $malla->marcarAsignaturas($preprocess[$ix]["mpmo_id"],$periodo); 

     }    }     }

		if (count($resultData) > 0) {

			for ($i = 0; $i < count($resultData); $i++) {
				$centralprocess = $malla->consultarAsignaturas($resultData[$i], $periodo, $saca_nombre["semestre"], $modalidad);if ($centralprocess == 1) {$ok = 1;}
		
				\app\models\Utilities::putMessageLogFile("Received " . $centralprocess);
			}

		} else {

			\Yii::$app->getSession()->setFlash('msg', 'No existen mas estudiantes para la modalidad actual');
			return $this->redirect(['index']);

		}

		if ($ok != 1) {
			\Yii::$app->getSession()->setFlash('msg', 'No existen datos suficientes para generar la planificacion seleccionada');
			// \Yii::$app->getSession()->setFlash('msg') ;
			return $this->redirect(['index']);

		}
		//   return $resultData;
		//  return $this->render('temporal', [
		//         'resultData' => $centralprocess,
		//          ]);
		\Yii::$app->getSession()->setFlash('msgok', 'Se ha generado con exito la planificacion');
		return $this->redirect(['index']);
	}

	public function actionAddpes($periodo, $modalidad) {

		$con = \Yii::$app->db_academico;
		$sql = "
                            SELECT
                            CONCAT(saca_nombre,' ',saca_anio) as semestre
                            FROM db_academico.semestre_academico where saca_id = :periodo
                    ";
		$comando = $con->createCommand($sql);
		$comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
		$saca_nombre = $comando->queryOne();

		$con = \Yii::$app->db_academico;

		$sql = "
                 select distinct e.est_id, e.per_id, e.est_matricula, e.est_fecha_creacion, e.est_categoria, meu.uaca_id, meu.mod_id, meu.eaca_id, DATEDIFF(NOW(),e.est_fecha_creacion) as olderi, --
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
   inner join db_academico.materias_periodo_modalidad mpmo on mpmo.eaca_id = meu.eaca_id and mpmo.mod_id = meu.mod_id and mpmo.saca_id = :periodo
   inner join db_asgard.persona per on per.per_id = e.per_id
    left join db_academico.malla_academico_estudiante malle on per.per_id = malle.per_id
     where   malle.maca_id = maca.maca_id  AND
     meu.mod_id = :modalidad and meu.uaca_id = 1 and mpmo.mpmo_activo = 'A'
    AND  mpmo.mpmo_procesado is Null
    AND  mpmo.mpmo_estado = 1 AND mpmo.mpmo_estado_logico = 1
    AND  e.est_estado = 1 AND e.est_estado_logico = 1
    AND  c.ecpr_estado = 1 AND c.ecpr_estado_logico = 1
    AND  meu.meun_estado = 1 AND meu.meun_estado_logico = 1
    AND  mumo.mumo_estado = 1 AND mumo.mumo_estado_logico = 1
    AND  maca.maca_estado = 1 AND maca.maca_estado_logico = 1
    AND  u.uaca_estado = 1 AND u.uaca_estado_logico = 1
     AND  ea.eaca_estado = 1
    AND  per.per_estado = 1 AND per.per_estado_logico = 1
      AND  malle.maes_estado = 1 AND malle.maes_estado_logico = 1
     AND
((e.per_id in (
select distinct a.per_id from db_asgard.persona as a
inner join db_academico.estudiante bas on a.per_id = bas.per_id
where DATEDIFF(NOW(),bas.est_fecha_creacion) <=1800 or
DATEDIFF(NOW(),a.per_fecha_creacion) <=1800 ))) -- 1108
 AND
((e.per_id not in (select b.per_id from db_academico.planificacion_estudiante b where
b.pla_id= ( select max(dap.pla_id) from db_academico.planificacion dap
 where dap.mod_id = :modalidad ))))
order by maca.maca_id DESC , ea.eaca_codigo, e.est_fecha_creacion ASC;
                ";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_STR);
		$comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
		$resultData = $comando->queryAll();

		$malla = new MallaAcademica();
		$allstudents = count($resultData);
		\app\models\Utilities::putMessageLogFile("all: " . count($resultData));

			 if (count($resultData) > 0) { 
        $preprocess = $malla->traerActivas($periodo,$modalidad); 
        if (count($preprocess) > 0) {  
             for ($ix = 0; $ix < count($resultData); $ix++) {  
$postprocess = $malla->marcarAsignaturas($preprocess[$ix]["mpmo_id"],$periodo); 

     }    }     }

		if (count($resultData) > 0) {
			\app\models\Utilities::putMessageLogFile("Received std " . count($resultData));

			for ($i = 0; $i < count($resultData); $i++) {
				\app\models\Utilities::putMessageLogFile("Received std " . $resultData[$i]['estudiante']);
				$allpending = $allpending . $resultData[$i]['estudiante'] . ', ';
				$centralprocess = $malla->consultarAsignaturas($resultData[$i], $periodo, $saca_nombre["semestre"], $modalidad);if ($centralprocess == 1) {$ok = 1;}
				\app\models\Utilities::putMessageLogFile("Received " . $centralprocess);
			}

		} else {

			\Yii::$app->getSession()->setFlash('msgne', 'No existen alumnos nuevos para la modalidad elegida');

			return $this->redirect(['index']);

		}

		if ($ok != 1) {
			\Yii::$app->getSession()->setFlash('msg', 'No existen datos suficientes para generar la planificacion seleccionada');
			
			return $this->redirect(['index']);

		}
		
		\Yii::$app->getSession()->setFlash('msgok', 'Se ha generado con exito la planificacion');
		return $this->redirect(['index']);
	}

	      public function actionCargarmaterias($periodo) { 



         for ($c = 1; $c <= 4; $c++) {  
 $modalidad = $c;    
 $con = \Yii::$app->db_academico;
 
$sql = "
        select distinct e.est_id, e.per_id, e.est_matricula, e.est_fecha_creacion, e.est_categoria, meu.uaca_id, meu.mod_id, meu.eaca_id, DATEDIFF(NOW(),e.est_fecha_creacion) as olderi, -- 
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
    left join db_academico.materias_periodo_modalidad mpmo on mpmo.eaca_id = meu.eaca_id and mpmo.mod_id = meu.mod_id and mpmo.saca_id = :periodo 
   inner join db_asgard.persona per on per.per_id = e.per_id
   left join db_academico.malla_academico_estudiante malle on per.per_id = malle.per_id
     where  malle.maca_id = maca.maca_id  AND
         meu.mod_id = :modalidad and meu.uaca_id = 1 
         and mpmo.mpmo_id is Null
    AND  e.est_estado = 1 AND e.est_estado_logico = 1
    AND  c.ecpr_estado = 1 AND c.ecpr_estado_logico = 1
    AND  meu.meun_estado = 1 AND meu.meun_estado_logico = 1
    AND  mumo.mumo_estado = 1 AND mumo.mumo_estado_logico = 1
    AND  maca.maca_estado = 1 AND maca.maca_estado_logico = 1
    AND  u.uaca_estado = 1 AND u.uaca_estado_logico = 1
     AND  ea.eaca_estado = 1 
    AND  per.per_estado = 1 AND per.per_estado_logico = 1
    AND  malle.maes_estado = 1 AND malle.maes_estado_logico = 1
     AND
((e.per_id in (select b.per_id from db_academico.planificacion_estudiante b where
b.pla_id= ( select max(dap.pla_id) from db_academico.planificacion dap 
 where dap.mod_id = :modalidad ))) or 
((e.per_id in (
select distinct a.per_id from db_asgard.persona as a 
inner join db_academico.estudiante bas on a.per_id = bas.per_id
where DATEDIFF(NOW(),bas.est_fecha_creacion) <=1800 or
DATEDIFF(NOW(),a.per_fecha_creacion) <=1800 )))
 )  
order by maca.maca_id DESC , ea.eaca_codigo, e.est_fecha_creacion ASC;
                ";


 $comando = $con->createCommand($sql);
          $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_STR);
          $comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
               $resultData = $comando->queryAll();
               
               
                    
                               $malla = new MallaAcademica();
               $allstudents= count($resultData);
                      
                 if (count($resultData) > 0) {
           
            for ($i = 0; $i < count($resultData); $i++) {                       
$centralprocess = $malla->cargarAsignaturas($resultData[$i],$modalidad,$periodo);  

            }
          }        else {          

  \Yii::$app->getSession()->setFlash('msgok', 'Existen materias cargadas, No existen datos adicionales a ser procesados!');
         return $this->redirect(['index']);

          }
       }

   \Yii::$app->getSession()->setFlash('msgok', 'Se ha cargado las asignaturas exitosamente!');
         return $this->redirect(['index']);
 }

	public function actionCerrarplanaut($pla_id) {

		$mod_periodo = new PlanificacionEstudiante();
		$arrData = $mod_periodo->cerrarPlanificacionAut($pla_id);
		return $this->redirect(['index']);

	}

	public function actionTransferir($pla_id) {
		$mod_periodo = new PlanificacionEstudiante();
		$programacion_siga = array();
		$allsubjects = $mod_periodo->generateDatatotrasfer($pla_id);
		if (count($allsubjects) > 0) {
			for ($i = 0; $i < count($allsubjects); $i++) {
				$url = "https://acade.uteg.edu.ec/planificaciondesa/pass.php"; //--
				$content = json_encode($allsubjects[$i]); //--
				$curl = curl_init($url); //--
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //--
				curl_setopt($curl, CURLOPT_HTTPHEADER, //--
					array("Content-type: application/json"));
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $content); //--
				$json_response = curl_exec($curl); //--
				$status = curl_getinfo($curl, CURLINFO_HTTP_CODE); //--
				if ($status != 200) {
					//die(" $content url $url status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
					die(" status $status content $content ");
				}
				$response = json_decode($json_response, true); //--
				curl_close($curl); //--

			}
		}

		return $this->redirect(['index']);

	}

	public function actionDescargarples() {

		ini_set('memory_limit', '512M');
		$content_type = Utilities::mimeContentType('xls');
		$nombarch = 'Report-' . date('YmdHis') . '.xls';
		header("Content-Type: $content_type");
		header('Content-Disposition: attachment;filename=' . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array('B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC');
		$arrHeader = array(
			Yii::t('formulario', 'DNI 1'),
			Yii::t('formulario', 'Student'),
			Yii::t('crm', 'Carrera'),
			Yii::t('formulario', 'Period'),
			Yii::t('formulario', 'B1 Asignatura 1'),
			Yii::t('formulario', 'Horario Paralelo 1'),
			Yii::t('formulario', 'B1 Asignatura 2'),
			Yii::t('formulario', 'Horario Paralelo 2'),
			Yii::t('formulario', 'B1 Asignatura 3'),
			Yii::t('formulario', 'Horario Paralelo 3'),
			Yii::t('formulario', 'B1 Asignatura 4'),
			Yii::t('formulario', 'Horario Paralelo 4'),
			Yii::t('formulario', 'B1 Asignatura 5'),
			Yii::t('formulario', 'Horario Paralelo 5'),
			Yii::t('formulario', 'B1 Asignatura 6'),
			Yii::t('formulario', 'Horario Paralelo 6'),
			Yii::t('formulario', 'B2 Asignatura 1'),
			Yii::t('formulario', 'Horario Paralelo 1'),
			Yii::t('formulario', 'B2 Asignatura 2'),
			Yii::t('formulario', 'Horario Paralelo 2'),
			Yii::t('formulario', 'B2 Asignatura 3'),
			Yii::t('formulario', 'Horario Paralelo 3'),
			Yii::t('formulario', 'B2 Asignatura 4'),
			Yii::t('formulario', 'Horario Paralelo 4'),
			Yii::t('formulario', 'B2 Asignatura 5'),
			Yii::t('formulario', 'Horario Paralelo 5'),
			Yii::t('formulario', 'B2 Asignatura 6'),
			Yii::t('formulario', 'Horario Paralelo 6'),
		);
		$mod_periodo = new PlanificacionEstudiante();
		$data = Yii::$app->request->get();
		$pla_id = $data['pla_id'];
		\app\models\Utilities::putMessageLogFile('dater' . $pla_id);
		// $arrSearch['estudiante'] = $data['estudiante'];
		//$arrSearch['unidad'] = $data['unidad'];
		//$arrSearch['modalidad'] = $data['modalidad'];
		//$arrSearch['carrera'] = $data['carrera'];
		//$arrSearch['periodo'] = $data['periodo'];
		$arrData = array();
		//if (empty($arrSearch)) {
		$arrData = $mod_periodo->consultarEstudianteplanificapesold($pla_id, true);
		//$arrData = $mod_periodo->consultarEstudianteplanificapes($pla_id);
		//} else {
		//   $arrData = $mod_periodo->consultarEstudianteplanifica($arrSearch, true);
		//}
		$nameReport = academico::t('Academico', 'Lista de Planificaci??n por Estudiante');
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;

	}

	public function actionUpload() {
		$usu_id = Yii::$app->session->get('PB_iduser');
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if ($data['upload_file']) {
				if (empty($_FILES)) {
					return json_encode(['error' => Yii::t('notificaciones', 'Error to process File ' . basename($files['name']) . '. Try again.')]);
				}
				//Recibe Par??metros
				$files = $_FILES[key($_FILES)];
				$arrIm = explode('.', basename($files['name']));
				$namefile = substr_replace($data['name_file'], $data['mod_id'], 14, 0);
				$typeFile = strtolower($arrIm[count($arrIm) - 1]);
				if ($typeFile == 'xlsx' || $typeFile == 'csv' || $typeFile == 'xls') {
					$dirFileEnd = Yii::$app->params['documentFolder'] . 'planificacion/' . $namefile . '.' . $typeFile;
					$status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
					if ($status) {
						return true;
					} else {
						return json_encode(['error' => Yii::t('notificaciones', 'Error to process File ' . basename($files['name']) . '. Try again.')]);
					}
				} else {
					return json_encode(['error' => Yii::t('notificaciones', 'Error to process File ' . basename($files['name']) . '. Try again.')]);
				}
			}

			if ($data['procesar_file']) {
				ini_set('memory_limit', '256M');
				$per_id = Yii::$app->session->get('PB_perid');
				$model_planificacionEstudiante = new PlanificacionEstudiante();
				try {
					$namefile = substr_replace($data['archivo'], $data['modalidad'], 14, 0);
					//consultar periodo y modalidad sino existe guardar
					$mod_planifica = new PlanificacionEstudiante();
					$resulpla_id = $mod_planifica->consultarDatoscabplanifica($data['modalidad'], $data['periodoAcademico']);
					if (empty($resulpla_id['pla_id'])) {
						$path = 'planificacion/' . $namefile;
						$modelo_planificacion = new Planificacion();
						$modelo_planificacion->mod_id = $data['modalidad'];
						$modelo_planificacion->per_id = $per_id;
						$modelo_planificacion->pla_fecha_inicio = $data['fechaInicio'];
						$modelo_planificacion->pla_fecha_fin = $data['fechaFin'];
						$modelo_planificacion->pla_periodo_academico = $data['periodoAcademico'];
						$modelo_planificacion->pla_path = $path;
						$modelo_planificacion->pla_estado = '1';
						$modelo_planificacion->pla_estado_logico = '1';
						if ($modelo_planificacion->save() && $data['archivo'] != '.') {
							$pla_id = $modelo_planificacion->getPrimaryKey();
							\app\models\Utilities::putMessageLogFile('entraaaa0: ');
							$carga_archivo = $model_planificacionEstudiante->processFile($namefile, $pla_id);
							if ($carga_archivo['status']) {
								$message = array(
									'wtmessage' => Yii::t('notificaciones', 'Archivo procesado correctamente. ' . $carga_archivo['message']),
									'title' => Yii::t('jslang', 'Success'),
								);
								return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), false, $message);
							} else {
								/* $modelo_planificacion_saved = Planificacion::findOne( $pla_id );
									                                  $modelo_planificacion_saved->delete();
								*/
								$message = array(
									'wtmessage' => Yii::t('notificaciones', 'Error al procesar el archivo1. ' . $carga_archivo['message']),
									'title' => Yii::t('jslang', 'Error'),
								);

								return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), true, $message);
							}
						} else {
							$message = array(
								'wtmessage' => Yii::t('notificaciones', 'Se cre?? la planificaci??n correctamente. ' . $carga_archivo['message']),
								'title' => Yii::t('jslang', 'Success'),
							);
							return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), false, $message);
						}
					} else {
						// si ya existe la modalidad y la planificacion creada no permitir volver a guardar
						$message = array(
							'wtmessage' => Yii::t('notificaciones', 'No se puede crear la planificacion ya existe ese per??odo y modalidad. ' . $carga_archivo['message']),
							'title' => Yii::t('jslang', 'Error'),
						);

						return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), true, $message);
					}
				} catch (Exception $ex) {
					/* $modelo_planificacion_saved = Planificacion::findOne( $pla_id );
						                      $modelo_planificacion_saved->delete();
					*/
					$message = array(
						'wtmessage' => Yii::t('notificaciones', 'Error al procesar el archivo.'),
						'title' => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), true, $message);
				}
			}
			;
		} else {
			$arr_modalidad = Modalidad::findAll(['mod_estado' => 1, 'mod_estado_logico' => 1]);
			return $this->render('cargar_planificacion', [
				'arr_modalidad' => (empty(ArrayHelper::map($arr_modalidad, 'mod_id', 'mod_nombre'))) ? array(Yii::t('planificacion', '-- Select planificacion --')) : (ArrayHelper::map($arr_modalidad, 'mod_id', 'mod_nombre')),
			]);
		}
	}

	public function actionDescargarplanificacion() {
		$report = new ExportFile();

		$data = Yii::$app->request->get();

		$pla_id = $data['pla_id'];
		$planificacion = Planificacion::findOne(['pla_id' => $pla_id]);
		$file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . $planificacion->pla_path;
		if (file_exists($file)) {
			Yii::$app->response->sendFile($file);
		} else {
			/** en caso de que no */
		}
		return;
	}

	public function actionSave() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			try {
				$fecha_inicio = $data['pla_fecha_inicio'];
				$fecha_fin = $data['pla_fecha_fin'];
				$periodo_academico = $data['pla_periodo_academico'];
				$estado = $data['estado'];
				$mod_id = $data['mod_id'];

				$planificacion_model = new Planificacion();
				$planificacion_model->pla_fecha_inicio = $fecha_inicio;
				$planificacion_model->pla_fecha_fin = $fecha_fin;
				$planificacion_model->pla_periodo_academico = $periodo_academico;
				$planificacion_model->mod_id = $mod_id;
				$planificacion_model->pla_estado = $estado;
				$planificacion_model->pla_estado_logico = '1';
				$message = array(
					'wtmessage' => Yii::t('notificaciones', 'Your information was successfully saved.'),
					'title' => Yii::t('jslang', 'Success'),
				);
				if ($planificacion_model->save()) {
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
				} else {
					throw new Exception('Error SubModulo no creado.');
				}
			} catch (Exception $ex) {
				$message = array(
					'wtmessage' => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
					'title' => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

	public function actionRegisterprocess() {
		$modelReg = new RegistroConfiguracion();
		$data = Yii::$app->request->get();
		if ($data['PBgetFilter']) {
			$pla_periodo_academico = $data['periodo'];
			$mod_id = $data['mod_id'];
			$model = $modelReg->getRegistroConfList($pla_periodo_academico, $mod_id);
			return $this->renderPartial('register-index-grid', [
				'model' => $model,
			]);
		}

		$arr_pla = Planificacion::getPeriodosAcademico();
		$arr_modalidad = Modalidad::findAll(['mod_estado' => 1, 'mod_estado_logico' => 1]);
		$model = $modelReg->getRegistroConfList(null, 0);
		return $this->render('register-index', [
			'arr_pla' => ArrayHelper::map(array_merge([['pla_id' => '0', 'pla_periodo_academico' => Yii::t('formulario', 'Grid')]], $arr_pla), 'pla_id', 'pla_periodo_academico'),
			'arr_modalidad' => ArrayHelper::map(array_merge([['mod_id' => '0', 'mod_nombre' => Yii::t('formulario', 'Grid')]], $arr_modalidad), 'mod_id', 'mod_nombre'),
			'model' => $model,
		]);
	}

	public function actionNewreg() {
		$modplanificacion = new Planificacion();
		//$arr_pla = ArrayHelper::map( Planificacion::getPeriodosAcademico(), 'pla_id', 'pla_periodo_academico' );
		$_SESSION['JSLANG']['The initial date of registry cannot be greater than end date.'] = academico::t('matriculacion', 'The initial date of registry cannot be greater than end date.');
		//$arr_pla = ArrayHelper::map( Planificacion::findAll( ['pla_estado' => 1, 'pla_estado_logico' => 1] ), 'pla_id', 'pla_periodo_academico' );
		$arr_pla = $modplanificacion->getPeriodosmodalidad();
		return $this->render('newreg', [
			//'arr_pla' => $arr_pla,
			//'arr_pla' => ArrayHelper::map( array_merge( [['id' => '0', 'name' => 'Seleccionar']], $arr_pla ), 'id', 'name' ),
			'arr_pla' => ArrayHelper::map($arr_pla, 'id', 'name'),
		]);
	}

	public function actionViewreg() {
		$data = Yii::$app->request->get();
		if (isset($data['id'])) {
			$id = $data['id'];
			$model = RegistroConfiguracion::findOne(['rco_id' => $id, 'rco_estado' => 1, 'rco_estado_logico' => 1]);
			$arr_pla = Planificacion::findOne(['pla_id' => $model['pla_id'], 'pla_estado' => 1, 'pla_estado_logico' => 1]);
			$mod_id = Modalidad::findOne(['mod_id' => $arr_pla['mod_id'], 'mod_estado' => 1, 'mod_estado_logico' => 1]);
			return $this->render('viewreg', [
				'model' => $model,
				'arr_pla' => $arr_pla,
				'pla_id' => $model->pla_id,
				'mod_id' => $mod_id,
				'rco_id' => $model->rco_id,
				'bloque' => ($model->rco_num_bloques == 1) ? 0 : 1,
			]);
		}
		return $this->redirect('registerprocess');
	}

	public function actionEditreg() {
		$data = Yii::$app->request->get();
		if (isset($data['id'])) {
			$id = $data['id'];
			$model = RegistroConfiguracion::findOne(['rco_id' => $id, 'rco_estado' => 1, 'rco_estado_logico' => 1]);
			$arr_pla = Planificacion::findOne(['pla_id' => $model['pla_id'], 'pla_estado' => 1, 'pla_estado_logico' => 1]);
			$mod_id = Modalidad::findOne(['mod_id' => $arr_pla['mod_id'], 'mod_estado' => 1, 'mod_estado_logico' => 1]);
			return $this->render('editreg', [
				'model' => $model,
				'arr_pla' => $arr_pla,
				'pla_id' => $model->pla_id,
				'mod_id' => $mod_id,
				'rco_id' => $model->rco_id,
				'bloque' => ($model->rco_num_bloques == 1) ? 0 : 1,
			]);
		}
		return $this->redirect('registerprocess');
	}

	public function actionUpdatereg() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			try {
				$id = $data['id'];
				$pla_id = $data['pla_id'];
				$finicio = $data['finicio'];
				$ffin = $data['ffin'];
				$finicio1 = $data['finicioa'];
				$ffin1 = $data['ffina'];
				//   $finicio2 = $data['finicio2'];
				//   $ffin2 = $data['ffin2'];
				$finicio3 = $data['finicio3'];
				$ffin3 = $data['ffin3'];
				$finicio4 = $data['finicio4'];
				$ffin4 = $data['ffin4'];
				$finicio5 = $data['finicio5'];
				$ffin5 = $data['ffin5'];

				$modelconf = new RegistroConfiguracion();
				$model = $modelconf->updatePlanAnual($id, $finicio, $ffin, $finicio1, $ffin1, /*$finicio2, $ffin2, */ $finicio3, $ffin3, $finicio4, $ffin4, $finicio5, $ffin5);
				if ($model) {
					$message = array(
						"wtmessage" => Yii::t("notificaciones", "La infomaci??n ha sido grabada. "),
						"title" => Yii::t('jslang', 'Success'),
					);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
				} else {
					$message = array(
						"wtmessage" => Yii::t('notificaciones', 'Su informaci??n no ha sido grabada. Por favor intente nuevamente o contacte al ??rea de Desarrollo.'),
						"title" => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), false, $message);
				}
			} catch (Exception $ex) {
				$message = array(
					'wtmessage' => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
					'title' => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

	public function actionSavereg() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			try {
				$pla_id = $data['pla_id'];
				\app\models\Utilities::putMessageLogFile('pla..: ' . $pla_id);
				$finicio = $data['finicio'];
				//\app\models\Utilities::putMessageLogFile('entro..: ');
				\app\models\Utilities::putMessageLogFile('finicio..: ' . $finicio);
				$ffin = $data['ffin'];
				\app\models\Utilities::putMessageLogFile('ffin..: ' . $ffin);
				$finicio1 = $data['finicio1'];
				\app\models\Utilities::putMessageLogFile('finicio1.: ' . $finicio1);
				$ffin1 = $data['ffin1'];
				\app\models\Utilities::putMessageLogFile('ffin..: ' . $ffin1);

				//   $finicio2 = $data['finicio2'];
				//   $ffin2 = $data['ffin2'];

				$finicio3 = $data['finicio3'];
				\app\models\Utilities::putMessageLogFile('finicio3.: ' . $finicio3);
				$ffin3 = $data['ffin3'];
				\app\models\Utilities::putMessageLogFile('fin3.: ' . $ffin3);
				$finicio4 = $data['finicio4'];
				\app\models\Utilities::putMessageLogFile('finicio4.: ' . $finicio4);
				$ffin4 = $data['ffin4'];
				\app\models\Utilities::putMessageLogFile('fin4.: ' . $ffin4);
				$finicio5 = $data['finicio5'];
				\app\models\Utilities::putMessageLogFile('finicio5.: ' . $finicio5);
				$ffin5 = $data['ffin5'];
				\app\models\Utilities::putMessageLogFile('fin5.: ' . $ffin5);

				// $bloque = $data['bloque'];
				$modelconf = new RegistroConfiguracion();
				$inserta_planificacionxperiodo = $modelconf->insertarPlanAnual(
					$pla_id,
					strval($finicio),
					strval($ffin),
					strval($finicio1),
					strval($ffin1), /*$finicio2, $ffin2, */
					strval($finicio3),
					strval($ffin3),
					strval($finicio4),
					strval($ffin4),
					strval($finicio5),
					strval($ffin5)
				);

				if ($inserta_planificacionxperiodo) {
					$message = array(
						"wtmessage" => Yii::t("notificaciones", "La infomaci??n ha sido grabada. "),
						"title" => Yii::t('jslang', 'Success'),
					);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
				} else {
					$message = array(
						"wtmessage" => Yii::t('notificaciones', 'Su informaci??n no ha sido grabada. Por favor intente nuevamente o contacte al ??rea de Desarrollo.'),
						"title" => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), false, $message);
				}

			} catch (Exception $ex) {
				$message = array(
					'wtmessage' => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
					'title' => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

	public function actionDeletereg() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			try {
				$id = $data['id'];
				$model = RegistroConfiguracion::findOne($id);
				$model->rco_estado = '0';
				$model->rco_estado_logico = '0';
				$model->rco_usuario_modifica = Yii::$app->session->get('PB_iduser');
				$model->rco_fecha_modificacion = date(Yii::$app->params['dateTimeByDefault']);
				$message = array(
					'wtmessage' => Yii::t('notificaciones', 'Your information was successfully saved.'),
					'title' => Yii::t('jslang', 'Success'),
				);
				if ($model->update() !== false) {
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
				} else {
					throw new Exception('Error registro no ha sido eliminado.');
				}
			} catch (Exception $ex) {
				$message = array(
					'wtmessage' => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
					'title' => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

	public function actionPlanificacionestudiante() {
		$emp_id = @Yii::$app->session->get('PB_idempresa');
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modestudio = new ModuloEstudio();
		$modalidad_model = new Modalidad();
		$modcanal = new Oportunidad();
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], $emp_id);
		$academic_study_data = $modcanal->consultarCarreraModalidad(null, null);
		$model_plan = $mod_periodo->consultarEstudianteplanifica();
		$data = Yii::$app->request->get();
		if ($data['PBgetFilter']) {
			$arrSearch['estudiante'] = $data['estudiante'];
			//$arrSearch['unidad'] = $data['unidad'];
			$arrSearch['modalidad'] = $data['modalidad'];
			$arrSearch['carrera'] = $data['carrera'];
			$arrSearch['periodo'] = $data['periodo'];
			$model_plan = $mod_periodo->consultarEstudianteplanifica($arrSearch);
			return $this->render('planificacionestudiante-grid', [
				'model' => $model_plan,
			]);
		}
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if (isset($data['getmodalidad'])) {
				if (($data['nint_id'] == 1) or ($data['nint_id'] == 2)) {
					$modalidad = $modalidad_model->consultarModalidad($data['nint_id'], $data['empresa_id']);
				} else {
					$modalidad = $modestudio->consultarModalidadModestudio();
				}
				$message = array('modalidad' => $modalidad);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getcarrera'])) {
				if (($data['unidada'] == 1) or ($data['unidada'] == 2)) {
					$carrera = $modcanal->consultarCarreraModalidad($data['unidada'], $data['moda_id']);
				} else {
					$carrera = $modestudio->consultarCursoModalidad($data['unidada'], $data['moda_id']);
					// tomar id de impresa
				}
				$message = array('carrera' => $carrera);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}
		return $this->render('planificacionestudiante', [
			'arr_unidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $unidad_acad_data), 'id', 'name'),
			'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $modalidad_data), 'id', 'name'),
			'arr_carrera' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $academic_study_data), 'id', 'name'),
			'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $periodo), 'id', 'name'),
			'model' => $model_plan,
		]);
	}

	public function actionExpexcelplanifica() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType('xls');
		$nombarch = 'Report-' . date('YmdHis') . '.xls';
		header("Content-Type: $content_type");
		header('Content-Disposition: attachment;filename=' . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array('C', 'D', 'E', 'F', 'G', 'H');
		$arrHeader = array(
			Yii::t('formulario', 'DNI 1'),
			Yii::t('formulario', 'Student'),
			Yii::t('crm', 'Carrera'),
			Yii::t('formulario', 'Period'),
		);
		$mod_periodo = new PlanificacionEstudiante();
		$data = Yii::$app->request->get();
		$arrSearch['estudiante'] = $data['estudiante'];
		//$arrSearch['unidad'] = $data['unidad'];
		$arrSearch['modalidad'] = $data['modalidad'];
		$arrSearch['carrera'] = $data['carrera'];
		$arrSearch['periodo'] = $data['periodo'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $mod_periodo->consultarEstudianteplanifica(array(), true);
		} else {
			$arrData = $mod_periodo->consultarEstudianteplanifica($arrSearch, true);
		}
		$nameReport = academico::t('Academico', 'Lista de Planificaci??n por Estudiante');
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionExppdfplanifica() {
		$report = new ExportFile();
		$this->view->title = academico::t('Academico', 'Lista de Planificaci??n por Estudiante');
		// Titulo del reporte
		$arrHeader = array(
			Yii::t('formulario', 'DNI 1'),
			Yii::t('formulario', 'Student'),
			Yii::t('crm', 'Carrera'),
			Yii::t('formulario', 'Period'),
			Yii::t('formulario', 'Codigo de Materia'),
			Yii::t('formulario', 'Materia del Periodo'),
		);
		$mod_periodo = new PlanificacionEstudiante();
		$data = Yii::$app->request->get();
		$arrSearch['estudiante'] = $data['estudiante'];
		$arrSearch['unidad'] = $data['unidad'];
		$arrSearch['modalidad'] = $data['modalidad'];
		$arrSearch['carrera'] = $data['carrera'];
		$arrSearch['periodo'] = $data['periodo'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $mod_periodo->consultarEstudianteplanificaPdf(array(), true);
		} else {
			$arrData = $mod_periodo->consultarEstudianteplanificaPdf($arrSearch, true);
		}
		$report->orientation = 'L';
		// tipo de orientacion L => Horizontal, P => Vertical
		$report->createReportPdf(
			$this->render('exportpdf', [
				'arr_head' => $arrHeader,
				'arr_body' => $arrData,
			])
		);
		$report->mpdf->Output('Reporte_' . date('Ymdhis') . '.pdf', ExportFile::OUTPUT_TO_DOWNLOAD);
		return;
	}

	public function actionView() {
		$pla_id = $_GET['pla_id'];
		$per_id = $_GET['per_id'];
		$emp_id = 1;
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modalidad_model = new Modalidad();
		$modcanal = new Oportunidad();
		$mod_jornada = new DistributivoAcademicoHorario();
		$mod_cabecera = $mod_periodo->consultarCabeceraplanifica($pla_id, $per_id);
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], $emp_id);
		$academic_study_data = $modcanal->consultarCarreraModalidad($unidad_acad_data[0]['id'], $mod_cabecera['mod_id']);
		$mod_detalle = $mod_periodo->consultarDetalleplanifica($pla_id, $per_id, false);
		$mod_malla = $mod_periodo->consultarDetalleplanifica($pla_id, $per_id, true);
		$mod_carrera = $mod_periodo->consultardataplan($pla_id, $per_id, $mod_malla[0]['cod_asignatura']);
		$jornada = $mod_jornada->consultarJornadahorario();
		switch ($mod_carrera['pes_jornada']) {
		case "M":
			$jornadacab = '1';
			break;
		case "N":
			$jornadacab = '2';
			break;
		case "S":
			$jornadacab = '3';
			break;
		case "D":
			$jornadacab = '4';
			break;
		}
		return $this->render('view', [
			'arr_cabecera' => $mod_cabecera,
			'model_detalle' => $mod_detalle,
			'arr_unidad' => ArrayHelper::map($unidad_acad_data, 'id', 'name'),
			'arr_modalidad' => ArrayHelper::map($modalidad_data, 'id', 'name'),
			'arr_carrera' => ArrayHelper::map($academic_study_data, 'id', 'name'),
			'arr_periodo' => ArrayHelper::map($periodo, 'id', 'name'),
			'arr_idcarrera' => $mod_carrera,
			'arr_jornada' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $jornada), 'id', 'name'),
			'valorjornada' => $jornadacab,
		]);
	}

	public function actionDeleteplanest() {
		$mod_planestudiante = new PlanificacionEstudiante();
		$usu_autenticado = @Yii::$app->session->get('PB_iduser');
		$estado = 0;
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$pla_id = $data['pla_id'];
			$per_id = $data['per_id'];
			$fecha = date(Yii::$app->params['dateTimeByDefault']);
			$con = \Yii::$app->db_academico;
			$transaction = $con->beginTransaction();
			try {
				$resp_estado = $mod_planestudiante->eliminarPlanificacionest($pla_id, $per_id, $usu_autenticado, $estado, $fecha);
				if ($resp_estado) {
					$exito = '1';
				}
				if ($exito) {
					//Realizar accion
					$transaction->commit();
					$message = array(
						'wtmessage' => Yii::t('notificaciones', 'Se ha eliminado la planificaci??n del estudiante.'),
						'title' => Yii::t('jslang', 'Success'),
					);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Sucess'), false, $message);
				} else {
					$transaction->rollback();
					$message = array(
						'wtmessage' => Yii::t('notificaciones', 'Error al eliminar planificaci??n del estudiante. '),
						'title' => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), false, $message);
				}
			} catch (Exception $ex) {
				$transaction->rollback();
				$message = array(
					'wtmessage' => Yii::t('notificaciones', 'Error al realizar la acci??n. '),
					'title' => Yii::t('jslang', 'Success'),
				);
				return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), false, $message);
			}
		}
	}

	public function actionEdit() {
		$pla_id = $_GET['pla_id'];
		$per_id = $_GET['per_id'];
		$emp_id = 1;
		$mod_periodo = new PlanificacionEstudiante();
		$mod_jornada = new DistributivoAcademicoHorario();
		$periodo = $mod_periodo->consultarPeriodoplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modalidad_model = new Modalidad();
		$modcanal = new Oportunidad();
		$mode_malla = new MallaAcademica();
		$mod_cabecera = $mod_periodo->consultarCabeceraplanifica($pla_id, $per_id);
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], $emp_id);
		$academic_study_data = $modcanal->consultarCarreraModalidad($unidad_acad_data[0]['id'], $mod_cabecera['mod_id']);
		$mod_detalle = $mod_periodo->consultarDetalleplanifica($pla_id, $per_id, false);
		$mod_malla = $mod_periodo->consultarDetalleplanifica($pla_id, $per_id, true);
		$mod_carrera = $mod_periodo->consultardataplan($pla_id, $per_id, $mod_malla[0]['cod_asignatura']);
		$jornada = $mod_jornada->consultarJornadahorario();
		$malla = $mode_malla->consultarmallasxcarrera($unidad_acad_data[0]['id'], $mod_cabecera['mod_id'], $mod_carrera['eaca_id']);
		$materia = $mode_malla->consultarasignaturaxmalla($malla[0]['id']);
		switch ($mod_carrera['pes_jornada']) {
		case "M":
			$jornadacab = '1';
			break;
		case "N":
			$jornadacab = '2';
			break;
		case "S":
			$jornadacab = '3';
			break;
		case "D":
			$jornadacab = '4';
			break;
		}
		return $this->render('edit', [
			'arr_cabecera' => $mod_cabecera,
			'model_detalle' => $mod_detalle,
			'arr_unidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $unidad_acad_data), 'id', 'name'),
			'arr_modalidad' => ArrayHelper::map($modalidad_data, 'id', 'name'),
			'arr_carrera' => ArrayHelper::map($academic_study_data, 'id', 'name'),
			'arr_periodo' => ArrayHelper::map($periodo, 'id', 'name'),
			'arr_bloque' => $this->Bloques(),
			'arr_hora' => $this->Horas(),
			'arr_modalidadh' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidad_data), 'id', 'name'),
			'arr_jornada' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $jornada), 'id', 'name'),
			'arr_idcarrera' => $mod_carrera,
			'valorjornada' => $jornadacab,
			'arr_materia' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $materia), 'id', 'name'),
		]);
	}

	public function actionDeletematest() {
		$mod_planestudiante = new PlanificacionEstudiante();
		$usu_autenticado = @Yii::$app->session->get('PB_iduser');
		$estado = 1;
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$pla_id = $data['pla_id'];
			$per_id = $data['per_id'];
			$bloque = $data['bloque'];
			$hora = $data['hora'];
			$fecha = date(Yii::$app->params['dateTimeByDefault']);
			$con = \Yii::$app->db_academico;
			$transaction = $con->beginTransaction();
			try {
				$resp_estado = $mod_planestudiante->eliminarPlanmatest($pla_id, $per_id, $bloque, $hora, $usu_autenticado, $estado, $fecha);
				if ($resp_estado) {
					$exito = '1';
				}
				if ($exito) {
					//Realizar accion
					$transaction->commit();
					$message = array(
						'wtmessage' => Yii::t('notificaciones', 'Se ha eliminado la materia del estudiante.'),
						'title' => Yii::t('jslang', 'Success'),
					);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Sucess'), false, $message);
				} else {
					$transaction->rollback();
					$message = array(
						'wtmessage' => Yii::t('notificaciones', 'Error al eliminar materia del estudiante. '),
						'title' => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), false, $message);
				}
			} catch (Exception $ex) {
				$transaction->rollback();
				$message = array(
					'wtmessage' => Yii::t('notificaciones', 'Error al realizar la acci??n. '),
					'title' => Yii::t('jslang', 'Success'),
				);
				return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), false, $message);
			}
		}
	}

	public function actionNew() {
		//$pla_id = $_GET['pla_id'];
		//$per_id = $_GET['per_id'];
		$emp_id = 1;
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modalidad_model = new Modalidad();
		$modcarrera = new EstudioAcademico();
		$mod_jornada = new DistributivoAcademicoHorario();
		$mod_malla = new MallaAcademica();
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$academic_study_data = $modcarrera->consultarCarreraxunidad($unidad_acad_data[0]['id']);
		$modalidad_data = $modcarrera->consultarmodalidadxcarrera($academic_study_data[0]['id']);
		$jornada = $mod_jornada->consultarJornadahorario();
		$malla = $mod_malla->consultarmallasxcarrera($unidad_acad_data[0]['id'], $modalidad_data[0]['id'], $academic_study_data[0]['id']);
		$materia = $mod_malla->consultarasignaturaxmalla($malla[0]['id']);
		$modalidades = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], 1);
		$busquedalumno = $mod_periodo->busquedaEstudianteplanificacion();
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if (isset($data['getmodalidad'])) {
				$modalidad = $modcarrera->consultarmodalidadxcarrera($data['eaca_id']);
				$message = array('modalidad' => $modalidad);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getmalla'])) {
				$mallaca = $mod_malla->consultarmallasxcarrera($data['uaca_id'], $data['moda_id'], $data['eaca_id']);
				$message = array('mallaca' => $mallaca);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getmateria'])) {
				$asignatura = $mod_malla->consultarasignaturaxmalla($data['maca_id']);
				$message = array('asignatura' => $asignatura);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}
		return $this->render('new', [
			'arr_unidad' => ArrayHelper::map($unidad_acad_data, 'id', 'name'),
			'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidad_data), 'id', 'name'),
			'arr_carrera' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $academic_study_data), 'id', 'name'),
			'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $periodo), 'id', 'name'),
			'arr_jornada' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $jornada), 'id', 'name'),
			'arr_bloque' => $this->Bloques(),
			'arr_hora' => $this->Horas(),
			'arr_modalidadh' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidades), 'id', 'name'),
			'arr_malla' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $malla), 'id', 'name'),
			'arr_materia' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $materia), 'id', 'name'),
			'arr_alumno' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $busquedalumno), 'id', 'name'),

		]);
	}

	public function actionSaveplanificacion() {
		$usu_autenticado = @Yii::$app->session->get('PB_iduser');
		//$this->stdout("whatever");
		if (Yii::$app->request->isAjax) {
			$mod_planifica = new PlanificacionEstudiante();
			$mod_persona = new Persona();
			$data = Yii::$app->request->post();
			$jornada = null; //substr($data['jornadaest'], 0, 1);
			$carrera = $data['carreraest'];
			$modalidad = $data['modalidadest'];
			//$malla = $data['mallaest'];
			$malla = explode(" - ", $data['mallaest']);
			$malla_guarda = "'" . $malla[0] . "'";
			$periodo = $data['periodoest'];
			$per_id = $data['nombreest'];
			$perid = $data['per_id']; 
			$data_persona = $mod_persona->consultaPersonaId($per_id);
			$dni = $data_persona['per_cedula'];
			$nombre = $data_persona['per_pri_nombre'] . ' ' . $data_persona['per_pri_apellido'];
			// Consultar si la modalidad y periodo existen, devuelve pla_id
			$resulpla_id = $mod_planifica->consultarDatoscabplanifica($modalidad, $periodo);
			// Consultar pla_id y per_id existe
			$exitealumno = $mod_planifica->consultarAlumnoplan($resulpla_id['pla_id'], $per_id);
			$accion = isset($data['ACCION']) ? $data['ACCION'] : '';
			if ($data['crea_planificacion_centro_idioma'] == 1){
				if ($exitealumno['planexiste'] > 1){
					$accion = 'Update';
				}
			}
			if ($accion == 'Create') {
				//existe guardar modalidad y periodo
				if ($resulpla_id['pla_id']) {
					// si existe pla_id y per_id no guardar enviar mensaje a que debe modificar
					if ($exitealumno['planexiste'] == '0') {
						/***************/
						//Nuevo Registro
						$arrplan = json_decode($data['DATAS'], true);
						for ($i = 0; $i < sizeof($arrplan); $i++) {
							// recorrer y crear un arrrglo solo con los campos a ingresar de horario y bloque
							// crear string del insert
							$bloque = substr($arrplan[$i]['bloque'], -1);
							$horario = substr($arrplan[$i]['hora'], -1);
							switch ($arrplan[$i]['modalidad']) {
							case "Online":
								$modalidades = '1';
								break;
							case "Presencial":
								$modalidades = '2';
								break;
							case "Semipresencial":
								$modalidades = '3';
								break;
							case "Distancia":
								$modalidades = '4';
								break;
							}
							$insertar .= 'pes_mat_b' . $bloque . '_h' . $horario . '_cod, pes_mod_b' . $bloque . '_h' . $horario . ', pes_jor_b' . $bloque . '_h' . $horario . ',';
							// crear el string de los valores
							$materia = explode(" - ", $arrplan[$i]['asignatura']);
							$mat_cod = $materia[0];
							//$mat_nombre = $materia[1];
							$valores .= "'" . $mat_cod . "', '" . $modalidades . "', '" . $arrplan[$i]['jornada'] . "',";
						}

                              $mod_mallastudent = new MallaAcademica();
						$resultmaca_id = $mod_mallastudent->consultarMallaEstudiante($perid);
						$resul = $mod_planifica->insertarDataPlanificacionestudiante($resulpla_id['pla_id'], $perid, $jornada, $carrera, $dni, $nombre, $malla_guarda, $insertar, $valores,$resultmaca_id['codmalla']);
					}elseif ($exitealumno['planexiste'] == '1' && $data['crea_planificacion_centro_idioma'] == 1 ) { //15 febrero 2022
						//Nuevo Registro
						$arrplan = json_decode($data['DATAS'], true);
						for ($i = 0; $i < sizeof($arrplan); $i++) {
							// recorrer y crear un arrrglo solo con los campos a ingresar de horario y bloque
							// crear string del insert
							$bloque = substr($arrplan[$i]['bloque'], -1);
							$horario = substr($arrplan[$i]['hora'], -1);
							switch ($arrplan[$i]['modalidad']) {
							case "Online":
								$modalidades = '1';
								break;
							case "Presencial":
								$modalidades = '2';
								break;
							case "Semipresencial":
								$modalidades = '3';
								break;
							case "Distancia":
								$modalidades = '4';
								break;
							}
							
							if ($arrplan[$i]['jornada'] == 'Matutino'){
								$jornada = 'M';
							}elseif ($arrplan[$i]['jornada'] == 'Nocturno'){
								$jornada = 'N';
							}elseif ($arrplan[$i]['jornada'] == 'Semipresencial'){
								$jornada = 'S';
							}elseif ($arrplan[$i]['jornada'] == 'Distancia'){
								$jornada = 'D';
							}
							$pes_cod_carrera = substr($arrplan[$i]['asignatura'], 0, 8);
							$insertar .= 'pes_mat_b' . $bloque . '_h' . $horario . '_cod, pes_mod_b' . $bloque . '_h' . $horario . ', pes_jor_b' . $bloque . '_h' . $horario . ', pes_mat_b' . $bloque . '_h' . $horario . '_mpp, pes_cod_carrera,';

							// crear el string de los valores
							$materia = explode(" - ", $arrplan[$i]['asignatura']);
							$mat_cod = $materia[0];
							//$mat_nombre = $materia[1];
							$valores .= "'" . $mat_cod . "', '" . $modalidades . "', '" . $arrplan[$i]['jornada'] . "', '". $arrplan[$i]['mpp_id'] . "', '". $pes_cod_carrera . "', ";
						}

						$mod_mallastudent = new MallaAcademica();
						$resultmaca_id = $mod_mallastudent->consultarMallaEstudiante($perid);
						$resul = $mod_planifica->insertarDataPlanificacionestudiante($resulpla_id['pla_id'], $perid, $jornada, $carrera, $dni, $nombre, $malla_guarda, $insertar, $valores,$resultmaca_id['codmalla']);

					}else {
						// no existe mensaje que no permitar guardar
						$noentra = 'NOS';
					}
				} else {
					// no existe mensaje que no permitar guardar
					$noentra = 'NO';
				}
			} else if ($accion == 'Update') {
				//\app\models\Utilities::putMessageLogFile('entro..: ');
				$plan_id = $data['pla_id'];
				$pers_id = $data['per_id'];
				//Modificar Planificacion
				/***************/
				//Nuevo Registro
				$arrplanedit = json_decode($data['DATAS'], true);
				for ($i = 0; $i < sizeof($arrplanedit); $i++) {
					// recorrer y crear un arreglo solo con los campos a ingresar de horario y bloque
					// crear string del modificar
					$bloque = substr($arrplanedit[$i]['bloque'], -1);
					$horario = substr($arrplanedit[$i]['hora'], -1);
					switch ($arrplanedit[$i]['modalidad']) {
					case "Online":
						$modalidades = '1';
						break;
					case "Presencial":
						$modalidades = '2';
						break;
					case "Semipresencial":
						$modalidades = '3';
						break;
					case "Distancia":
						$modalidades = '4';
						break;
					}
					// crear el string de los valores
					$materia = explode(" - ", $arrplanedit[$i]['asignatura']);
					$mat_cod = $materia[0];
					$codmateria = "pes_mat_b" . $bloque . "_h" . $horario . "_cod = '" . $mat_cod . "', ";
					$modmateria = "pes_mod_b" . $bloque . "_h" . $horario . "= '" . $modalidades . "', ";
					$jormateria = "pes_jor_b" . $bloque . "_h" . $horario . "= '" . $arrplanedit[$i]['jornada'] . "', ";
					$mppmateria = "pes_mat_b" . $bloque . "_h" . $horario . "_mpp = '" . $arrplanedit[$i]['mpp_id'] . "', ";
					$modificar .= $codmateria . ' ' . $modmateria . ' ' . $jormateria . ' ' . $mppmateria;
				}
				\app\models\Utilities::putMessageLogFile('modificar..: ' . $modificar);
				$resul = $mod_planifica->modificarDataPlanificacionestudiante($plan_id, $pers_id, $usu_autenticado, $modificar);
			}

			if ($resul['status']) {
				$message = ['info' => Yii::t('exception', '<strong>Well done!</strong> your information was successfully saved.')];
				echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message, $resul);
			} elseif ($noentra == 'NO') {
				$message = ['info' => Yii::t('exception', 'No se puede guardar per??odo acad??mico y modalidad no existe, crearla en cargar planificaci??n.')];
				echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
			} elseif ($noentra == 'NOS') {
				$message = ['info' => Yii::t('exception', 'No se puede guardar estudiante ya tiene datos para ese periodo, por favor ir modificar de ser el caso.')];
				echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
			} else {
				$message = ['info' => Yii::t('exception', 'The above error occurred while the Web server was processing your request.')];
				echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
			}

			return;
		}
	}
	public function actionDownloadplantilla() {
		//$file_path ='/uploads/plantilla_planificacion/plantilla_carga_planificacionestudiante.xlsx';
		$file = 'plantilla_carga_planificacionestudiante.xlsx';
		$route = str_replace("../", "", $file);
		$url_file = Yii::$app->basePath . "/uploads/plantilla_planificacion/" . $route;
		$arrfile = explode(".", $url_file);
		$typeImage = $arrfile[count($arrfile) - 1];
		if (file_exists($url_file)) {
			if (strtolower($typeImage) == "xlsx") {
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Cache-Control: private', false);
				header("Content-type: application/xlsx");
				header('Content-Disposition: attachment; filename="plantillaplanificacionest_' . time() . '.xlsx";');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($url_file));
				readfile($url_file);
			}
		}
	}

	//DBE
	public function actionAcademicoestudiante() {
		\app\models\Utilities::putMessageLogFile('-----------------Looking for applied filter--------------------------');
		$emp_id = @Yii::$app->session->get('PB_idempresa');
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoAcadplanifica();
		$arr_pla = Planificacion::getPeriodosAcademicoMod();
		$uni_aca_model = new UnidadAcademica();
		$modestudio = new ModuloEstudio();
		$modalidad_model = new Modalidad();
		$modcanal = new Oportunidad();
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], $emp_id);
		$academic_study_data = $modcanal->consultarCarreraModalidad(null, null);
		$data = Yii::$app->request->get();
		\app\models\Utilities::putMessageLogFile('-------------------' . $data['PBgetfilters'] . '---------------------');
		/*if ($data['PBgetfilters']) {
			            print_r('PBgetfilters');die();
			            \app\models\Utilities::putMessageLogFile('Filtros iniciales: '.$data['PBgetFilter']);
			            $arrSearch['modalidad'] = $data['modalidad']?$data['modalidad']:0;
			            $arrSearch['periodo'] = $data['periodo']?$data['periodo']:0;
			            $model_plan = $mod_periodo->consultarEstudiantePeriodo($arrSearch);
			            //$model_plan = [];
			            //$model_total_plan = $model_plan
			            return $this->render('academicoestudiante', [
			                'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $modalidad_data), 'id', 'name'),
			                'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $arr_pla), 'id', 'name'),
			                'model' => $model_plan,
			            ]);
		*/
		if (Yii::$app->request->isAjax || $_GET['PBgetFilter'] == 1) {

			$data = Yii::$app->request->post();
			$periodo = $_GET['periodo'];
			$modalidad = $_GET['modalidad'];
			$bloque = $_GET['bloque'];
			$filtro = $_GET['PBgetFilter'];
			//print_r('true '.$periodo.'-'.$modalidad.'-'.$filtro);
			\app\models\Utilities::putMessageLogFile('Bot??n Buscar: ' . $data);
			try {
				if (($modalidad != 0 && $periodo != 0)) {
					$arrSearch['modalidad'] = $modalidad ? $modalidad : 0;
					$arrSearch['periodo'] = $periodo ? $periodo : 0;
					$arrSearch['bloque'] = $bloque ? $bloque : 0;
					$model_plan = $mod_periodo->consultarEstudiantePeriodo($arrSearch);

					//print_r($modalidad_data[$modalidad]);die();

					\app\models\Utilities::putMessageLogFile('FIltros...: ');
					\app\models\Utilities::putMessageLogFile('modalidad y periodod ' . $arrSearch['modalidad'] . '-' . $arrSearch['periodo']);
					\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('modalidad y periodod ' . $arrSearch['modalidad'] . '-' . $arrSearch['periodo']);
					return $this->render('academicoestudiante', [
						'arr_modalidad' => ArrayHelper::map(array_merge([['id' => ($modalidad), 'name' => ($modalidad)]], $modalidad_data), 'id', 'name'),
						'arr_periodo' => ArrayHelper::map(array_merge([['id' => ($periodo ? $periodo : "0"), 'name' => ($periodo ? $arr_pla[$periodo] : 'Todas')]], $arr_pla), 'id', 'name'),
						'arr_bloque' => $this->Bloques($bloque), //ArrayHelper::map(array_merge([['id' => ($bloque?$bloque:"0"), 'name' => ($bloque?$this->Bloques()[$periodo]:'Todas')]], $this->Bloques()), 'id', 'name'),
						'model' => $model_plan,
						'id_bloque' => $arrSearch['bloque'],
						'id_periodo' => $arrSearch['periodo'],
						'id_mod' => $arrSearch['modalidad'],
					]);

				} else {
					//print_r('Error'); die();
					$arrSearch['modalidad'] = $modalidad ? $modalidad : 0;
					$arrSearch['periodo'] = $periodo ? $periodo : 0;
					$arrSearch['bloque'] = $bloque ? $bloque : 0;
					$model_plan = $mod_periodo->consultarEstudiantePeriodo($arrSearch);
					\app\models\Utilities::putMessageLogFile('todos...: ');
					\app\models\Utilities::putMessageLogFile('modalidad y periodod ' . $arrSearch['modalidad'] . '-' . $arrSearch['periodo']);
					\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('modalidad y periodod ' . $arrSearch['modalidad'] . '-' . $arrSearch['periodo']);

					return $this->render('academicoestudiante', [
						'arr_modalidad' => ArrayHelper::map(array_merge([['id' => ($modalidad), 'name' => ($modalidad)]], $modalidad_data), 'id', 'name'),
						'arr_periodo' => ArrayHelper::map(array_merge([['id' => ($periodo ? $periodo : "0"), 'name' => ($periodo ? $arr_pla[$periodo] : 'Todas')]], $arr_pla), 'id', 'name'),
						'arr_bloque' => $this->Bloques($bloque), //ArrayHelper::map(array_merge([['id' => ($bloque?$bloque:"0"), 'name' => ($bloque?$this->Bloques()[$periodo]:'Todas')]], $this->Bloques()), 'id', 'name'),
						'model' => $model_plan,
					]);
				}
			} catch (Exception $ex) {
				\app\models\Utilities::putMessageLogFile('catch...: ');
				$message = array(
					"wtmessage" => Yii::t("notificaciones", "Error al buscar." . $ex->getMessage()),
					"title" => Yii::t('jslang', 'Error'),
				);
				\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('consultarModalidad: ' . $message);
				//return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
			}
		}
		/*$arrSearch['periodo'] = "x";
			        $arrSearch['modalidad'] = "x";
		*/
		$model_plan = [];
		$model_plan = new ArrayDataProvider([
			'key' => 'id',
			'allModels' => $model_plan,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => [],
			],
		]);

		\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('consultarModalidad: Inicio');

		return $this->render('academicoestudiante', [

			'arr_modalidad' => ArrayHelper::map(array_merge($modalidad_data), 'id', 'name'),
			'arr_bloque' => $this->Bloques(),
			'arr_periodo' => ArrayHelper::map(array_merge([['id' => "0", 'name' => 'Todas']], $arr_pla), 'id', 'name'),
			'model' => $model_plan,
		]);
	}

	public function actionResumenplanificacion() {
		\app\models\Utilities::putMessageLogFile('-----------------Looking for applied filter--------------------------');
		$emp_id = @Yii::$app->session->get('PB_idempresa');
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoAcadplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modestudio = new ModuloEstudio();
		$modalidad_model = new Modalidad();
		$modcanal = new Oportunidad();
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], $emp_id);
		$academic_study_data = $modcanal->consultarCarreraModalidad(null, null);
		$model_plan = $mod_periodo->consultarEstudiantePeriodo();
		$data = Yii::$app->request->get();
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			try {
				if (($data['modalidad'] != 0 && $data['periodo'] != 0)) {
					$arrSearch['modalidad'] = $data['modalidad'] ? $data['modalidad'] : 0;
					$arrSearch['periodo'] = $data['periodo'] ? $data['periodo'] : 0;
					$model_plan = $mod_periodo->consultarEstudiantePeriodo($arrSearch);
					\app\models\Utilities::putMessageLogFile('FIltros...: ');
					\app\models\Utilities::putMessageLogFile('modalidad y periodod ' . $arrSearch['modalidad'] . '-' . $arrSearch['periodo']);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message) .
					$this->render('resumenplanificacion', [

						'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $modalidad_data), 'id', 'name'),

						'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $periodo), 'id', 'name'),
						'model' => $model_plan,
						//Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), true, $message),
					]);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), true, $message);
				} else {
					$arrSearch['modalidad'] = $data['modalidad'] ? $data['modalidad'] : 0;
					$arrSearch['periodo'] = $data['periodo'] ? $data['periodo'] : 0;
					\app\models\Utilities::putMessageLogFile('todos...: ');
					\app\models\Utilities::putMessageLogFile('modalidad y periodod ' . $arrSearch['modalidad'] . '-' . $arrSearch['periodo']);
					$model_plan = $mod_periodo->consultarEstudiantePeriodo($arrSearch);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message) .
					$this->render('resumenplanificacion', [

						'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $modalidad_data), 'id', 'name'),

						'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $periodo), 'id', 'name'),
						'model' => $model_plan,
						//Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), true, $message),
					]);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), true, $message);
				}
			} catch (Exception $ex) {
				\app\models\Utilities::putMessageLogFile('catch...: ');
				$message = array(
					"wtmessage" => Yii::t("notificaciones", "Error al buscar." . $ex->getMessage()),
					"title" => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
			}
		}
		return $this->render('resumenplanificacion', [

			'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $modalidad_data), 'id', 'name'),

			'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $periodo), 'id', 'name'),
			'model' => $model_plan,
			//Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), true, $message),
		]);
		return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
	}

	public function actionProcesoplanificacion() {
		$emp_id = @Yii::$app->session->get('PB_idempresa');
		//$mod_periodo = new ProcesoPlanificacion();
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoAcadplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modestudio = new ModuloEstudio();
		$modalidad_model = new Modalidad();
		$modcanal = new Oportunidad();
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], $emp_id);
		$academic_study_data = $modcanal->consultarCarreraModalidad(null, null);
		$model_plan = $mod_periodo->consultarProcesoPlanificacion($arrSearch);
		$data = Yii::$app->request->get();
		if ($data['PBgetFilter']) {
			$arrSearch['estudiante'] = $data['estudiante'];
			////$arrSearch['unidad'] = $data['unidad'];
			$arrSearch['modalidad'] = $data['modalidad'];
			$arrSearch['carrera'] = $data['carrera'];
			$arrSearch['periodo'] = $data['periodo'];
			$arrSearch['Materia'] = $data['materia'];
			$arrSearch['Cantidad'] = $data['cantidad'];
			$model_plan = $mod_periodo->consultarProcesoPlanificacion($arrSearch);
			/*return $this->render('procesoplanificacion-grid', [
				                        'model' => $model_plan,
			*/
		}
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if (isset($data['getmodalidad'])) {
				if (($data['nint_id'] == 1) or ($data['nint_id'] == 2)) {
					$modalidad = $modalidad_model->consultarModalidad($data['nint_id'], $data['empresa_id']);
				} else {
					$modalidad = $modestudio->consultarModalidadModestudio();
				}
				$message = array('modalidad' => $modalidad);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getcarrera'])) {
				if (($data['unidada'] == 1) or ($data['unidada'] == 2)) {
					$carrera = $modcanal->consultarCarreraModalidad($data['unidada'], $data['moda_id']);
				} else {
					$carrera = $modestudio->consultarCursoModalidad($data['unidada'], $data['moda_id']);
					// tomar id de impresa
				}
				$message = array('carrera' => $carrera);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}
		return $this->render('procesoplanificacion', [

			'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $modalidad_data), 'id', 'name'),
			'arr_carrera' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $academic_study_data), 'id', 'name'),
			'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Todas']], $periodo), 'id', 'name'),
			'model' => $model_plan,
		]);
	}
	public function actionExppdfplanificacion() {
		$report = new ExportFile();
		$this->view->title = academico::t('Academico', 'Resumen de Planificaci??n');
		// Titulo del reporte
		$arrHeader = array(
			Yii::t('formulario', 'ID'),
			Yii::t('formulario', 'Modalidad'),
			Yii::t('formulario', 'Periodo'),
			Yii::t('formulario', 'Bloque'),
			Yii::t('formulario', 'Materia del Periodo'),
			Yii::t('formulario', 'Cantidad'),
		);
		$mod_periodo = new PlanificacionEstudiante();
		$data = Yii::$app->request->get();
		$arrSearch['unidad'] = $data['unidad'];
		$arrSearch['modalidad'] = $data['modalidad'];
		$arrSearch['periodo'] = $data['periodo'];
		$arrSearch['bloque'] = $data['bloque'];
		$arrSearch['view'] = 2;
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $mod_periodo->consultarEstudiantePeriodo(array(), true);
			#$arrData = $mod_periodo->consultarEstudianteplanificaPdf(array(), true);
		} else {
			$arrData = $mod_periodo->consultarEstudiantePeriodo($arrSearch, true);
			#$arrData = $mod_periodo->consultarEstudianteplanificaPdf($arrSearch, true);
		}
		$report->orientation = 'L';
		// tipo de orientacion L => Horizontal, P => Vertical
		$report->createReportPdf(
			$this->render('exportpdf', [
				'arr_head' => $arrHeader,
				'arr_body' => $arrData,
			])
		);
		$report->mpdf->Output('Reporte_' . date('Ymdhis') . '.pdf', ExportFile::OUTPUT_TO_DOWNLOAD);
		return;
	}

	public function actionExpexcelplanificacion() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType('xls');
		$nombarch = 'Report-' . date('YmdHis') . '.xls';
		header("Content-Type: $content_type");
		header('Content-Disposition: attachment;filename=' . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array('C', 'D', 'E', 'F', 'G', 'H');
		$arrHeader = array(
			Yii::t('formulario', 'ID'),
			Yii::t('formulario', 'Modalidad'),
			Yii::t('formulario', 'Periodo'),
			Yii::t('formulario', 'Bloque'),
			Yii::t('formulario', 'Materia del Periodo'),
			Yii::t('formulario', 'Cantidad'),
		);
		$mod_periodo = new PlanificacionEstudiante();
		$data = Yii::$app->request->get();
		$arrSearch['modalidad'] = $data['modalidad'];
		$arrSearch['carrera'] = $data['carrera'];
		$arrSearch['periodo'] = $data['periodo'];
		$arrSearch['bloque'] = $data['bloque'];
		$arrSearch['view'] = 1;
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $mod_periodo->consultarEstudiantePeriodo(array(), true);
		} else {
			$arrData = $mod_periodo->consultarEstudiantePeriodo($arrSearch, true);
		}
		$nameReport = academico::t('Academico', 'Resumen de Planificaci??n');
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionProcesoplanificacionaut() {
		//$pla_id = $_GET['pla_id'];
		$pla_id = @Yii::$app->session->get('pla_id');
		//$per_id = $_GET['per_id'];
		$per_id = @Yii::$app->session->get('per_id');
		$estudiante = $_GET['estudiante'];
		$emp_id = @Yii::$app->session->get('PB_idempresa');
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoAcadplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modestudio = new ModuloEstudio();
		$modalidad_model = new Modalidad();
		$modcanal = new Oportunidad();
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$modalidad_data = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], $emp_id);
		$academic_study_data = $modcanal->consultarCarreraModalidad(null, null);

		$busquedalumno = $mod_periodo->busquedaEstudianteplanificacion();

		$mod_jornada = new DistributivoAcademicoHorario();
		$jornada = $mod_jornada->consultarJornadahorario();
		$mod_cabecera = $mod_periodo->consultarCabeceraplanificaaut($pla_id, $per_id);
		//$mod_malla = $mod_periodo->consultarDetalleplanifica($pla_id, $per_id, true);
		//$mod_carrera = $mod_periodo->consultardataplan($pla_id, $per_id, $mod_malla[0]['cod_asignatura']);
		$data = Yii::$app->request->get();
		if ($data['PBgetFilter']) {
			$arrSearch['estudiante'] = $per_id;
			$arrSearch['unidad'] = $data['unidad'];
			$arrSearch['modalidad'] = $data['modalidad'];
			$arrSearch['carrera'] = $data['carrera'];
			$arrSearch['periodo'] = $data['periodo'];
			//$arrSearch['Materia'] = $data['materia'];
			//$arrSearch['Cantidad'] = $data['cantidad'];
			$model_plan = $mod_periodo->consultarProcesoPlanificacionAut($arrSearch);

			return $this->renderPartial('procesoplanificacion-grid', [
				"model" => $model_plan,
			]);

		} else {
			$model_plan = $mod_periodo->consultarProcesoPlanificacionAut();
		}
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if (isset($data['getmodalidad'])) {
				if (($data['nint_id'] == 1) or ($data['nint_id'] == 2)) {
					$modalidad = $modalidad_model->consultarModalidad($data['nint_id'], $data['empresa_id']);
				} else {
					$modalidad = $modestudio->consultarModalidadModestudio();
				}
				$message = array('modalidad' => $modalidad);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getcarrera'])) {
				if (($data['unidada'] == 1) or ($data['unidada'] == 2)) {
					$carrera = $modcanal->consultarCarreraModalidad($data['unidada'], $data['moda_id']);
				} else {
					$carrera = $modestudio->consultarCursoModalidad($data['unidada'], $data['moda_id']);
					// tomar id de impresa
				}
				$message = array('carrera' => $carrera);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}

		return $this->render('procesoplanificacionaut', [
			'arr_cabecera' => $mod_cabecera,
			'model_detalle' => $model_plan,
			'arr_unidad' => ArrayHelper::map($unidad_acad_data, 'id', 'name'),
			'arr_modalidad' => ArrayHelper::map($modalidad_data, 'id', 'name'),
			'arr_carrera' => ArrayHelper::map($academic_study_data, 'id', 'name'),
			'arr_periodo' => ArrayHelper::map($periodo, 'id', 'name'),
			'arr_alumno' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $busquedalumno), 'id', 'name'),
			//'arr_idcarrera' => $mod_carrera,
			//'arr_jornada' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $jornada), 'id', 'name'),
			//'valorjornada' => $jornadacab,
		]);
	}

	public function actionTemporalgetpla() {

		$con = \Yii::$app->db_academico;
		$sql = " SELECT a.pes_id, a.per_id, a.pes_cod_carrera, a.pes_carrera
,a.pes_mat_b1_h1_cod,a.pes_mat_b1_h2_cod,a.pes_mat_b1_h3_cod
,a.pes_mat_b1_h4_cod,a.pes_mat_b1_h5_cod,a.pes_mat_b1_h6_cod
,a.pes_mat_b2_h1_cod,a.pes_mat_b2_h2_cod,a.pes_mat_b2_h3_cod
,a.pes_mat_b2_h4_cod,a.pes_mat_b2_h5_cod,a.pes_mat_b2_h6_cod
,a.pes_mat_b1_h1_nombre,a.pes_mat_b1_h2_nombre,a.pes_mat_b1_h3_nombre
,a.pes_mat_b1_h4_nombre,a.pes_mat_b1_h5_nombre,a.pes_mat_b1_h6_nombre
,a.pes_mat_b2_h1_nombre,a.pes_mat_b2_h2_nombre,a.pes_mat_b2_h3_nombre
,a.pes_mat_b2_h4_nombre,a.pes_mat_b2_h5_nombre,a.pes_mat_b2_h6_nombre
, b.maca_id,b.maca_nombre, a.pes_dni
 FROM " . $con->dbname . ".planificacion_estudiantexx as a
inner join " . $con->dbname . ".malla_academica as b on a.pes_cod_carrera = b.maca_codigo
 WHERE a.pes_id < 101
                ";

		$comando = $con->createCommand($sql);
		$getterPla = $comando->queryAll();

		for ($i = 0; $i < count($getterPla); $i++) {

			$maca_id = $getterPla[$i]["maca_id"];
			$pes_id = $getterPla[$i]["pes_id"];
			$eaca_id = $getterPla[$i]["pes_cod_carrera"];
			$cedula = $getterPla[$i]["pes_dni"];

			$sta = $getterPla[$i]["pes_mat_b1_h1_cod"];
			$stb = $getterPla[$i]["pes_mat_b1_h2_cod"];
			$stc = $getterPla[$i]["pes_mat_b1_h3_cod"];
			$std = $getterPla[$i]["pes_mat_b1_h4_cod"];
			$ste = $getterPla[$i]["pes_mat_b1_h5_cod"];
			$stf = $getterPla[$i]["pes_mat_b1_h6_cod"];
			$stg = $getterPla[$i]["pes_mat_b2_h1_cod"];
			$sth = $getterPla[$i]["pes_mat_b2_h2_cod"];
			$sti = $getterPla[$i]["pes_mat_b2_h3_cod"];
			$stj = $getterPla[$i]["pes_mat_b2_h4_cod"];
			$stk = $getterPla[$i]["pes_mat_b2_h5_cod"];
			$stl = $getterPla[$i]["pes_mat_b2_h6_cod"];

			$distri = new Planificacionxx();

			$otherprocess = $distri->getMalla($cedula);
			if ($otherprocess && $otherprocess["maca_codigo"] != Null && $otherprocess["maca_codigo"] != '') {
				$replacemalla = $distri->updateMalla($pes_id, $otherprocess["maca_codigo"]);
				$maca_id = $otherprocess["maca_id"];
				$maca_codigo = $otherprocess["maca_codigo"];

				/*
					                  return $this->render('temporal', [
					                     'cedula' => $cedula,
					                     'maca_codigo' => $maca_codigo,
					                     'maca_id' => $maca_id,
					                     'pes_id' => $pes_id,
					                              ]);

					               /*
					                $centralprocess = $distri->getCode($sta,$maca_id);

					                  return $this->render('temporal', [
					                     'cod_source' => $sta,
					                     'malla_target' => $maca_id,
					                     'pes_id' => $pes_id,
					                     'new_id' => $centralprocess["made_codigo_asignatura"],
					                     'position' => "pes_mat_b1_h1_cod",
					                    ]);
				*/

				$centralprocess = $distri->getCode($sta, $maca_id);
				if ($centralprocess && $sta != Null && $sta != '') {
					$replacer = $distri->updateCode("pes_mat_b1_h1_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {

					/*
						                   if ($sta !=Null && $sta !='' && $sta !='NO EN MALLA') {
						                     return $this->render('temporal', [
						                     'cod_source' => $sta,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                       'position' => "pes_mat_b1_h1_cod",
						                    ]);
					*/
				}

				$centralprocess = $distri->getCode($stb, $maca_id);
				if ($centralprocess && $stb != Null && $stb != '') {
					$replacer = $distri->updateCode("pes_mat_b1_h2_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*  if ($stb !=Null && $stb !='' && $stb !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $stb,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                       'position' => "pes_mat_b1_h2_cod",
					*/
				}

				$centralprocess = $distri->getCode($stc, $maca_id);
				if ($centralprocess && $stc != Null && $stc != '') {
					$replacer = $distri->updateCode("pes_mat_b1_h3_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*  if ($stc !=Null && $stc !='' && $stc !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $stc,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b1_h3_cod",
					*/
				}

				$centralprocess = $distri->getCode($std, $maca_id);
				if ($centralprocess && $std != Null && $std != '') {
					$replacer = $distri->updateCode("pes_mat_b1_h4_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*   if ($std !=Null && $std !='' && $std !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $std,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b1_h4_cod",
					*/
				}

				$centralprocess = $distri->getCode($ste, $maca_id);
				if ($centralprocess && $ste != Null && $ste != '') {
					$replacer = $distri->updateCode("pes_mat_b1_h5_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*  if ($ste !=Null && $ste !='' && $ste !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $ste,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b1_h5_cod",
					*/
				}
				$centralprocess = $distri->getCode($stf, $maca_id);
				if ($centralprocess && $stf != Null && $stf != '') {
					$replacer = $distri->updateCode("pes_mat_b1_h6_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*   if ($stf !=Null && $stf !='' && $stf !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $stf,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => " ",
					*/
				}
				$centralprocess = $distri->getCode($stg, $maca_id);
				if ($centralprocess && $stg != Null && $stg != '') {
					$replacer = $distri->updateCode("pes_mat_b2_h1_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*   if ($stg !=Null && $stg !='' && $stg !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $stg,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b2_h1_cod",
					*/
				}
				$centralprocess = $distri->getCode($sth, $maca_id);
				if ($centralprocess && $sth != Null && $sth != '') {
					$replacer = $distri->updateCode("pes_mat_b2_h2_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*    if ($sth !=Null && $sth !='' && $sth !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $sth,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b2_h2_cod",
					*/
				}
				$centralprocess = $distri->getCode($sti, $maca_id);
				if ($centralprocess && $sti != Null && $sti != '') {
					$replacer = $distri->updateCode("pes_mat_b2_h3_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*    if ($sti !=Null && $sti !='' && $sti !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $sti,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b2_h3_cod",
					*/
				}
				$centralprocess = $distri->getCode($stj, $maca_id);
				if ($centralprocess && $stj != Null && $stj != '') {
					$replacer = $distri->updateCode("pes_mat_b2_h4_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*    if ($stj !=Null && $stj !='' && $stj !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $stj,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b2_h4_cod",
					*/
				}
				$centralprocess = $distri->getCode($stk, $maca_id);
				if ($centralprocess && $stk != Null && $stk != '') {
					$replacer = $distri->updateCode("pes_mat_b2_h5_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*    if ($stk !=Null && $stk !='' && $stk !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $stk,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b2_h5_cod",
					*/
				}
				$centralprocess = $distri->getCode($stl, $maca_id);
				if ($centralprocess && $stl != Null && $stl != '') {
					$replacer = $distri->updateCode("pes_mat_b2_h6_cod", $pes_id, $centralprocess["made_codigo_asignatura"]);
				} else {
					/*   if ($stl !=Null && $stl !=''&& $stl !='NO EN MALLA'){
						                     return $this->render('temporal', [
						                     'cod_source' => $stl,
						                     'malla_target' => $maca_id,
						                     'pes_id' => $pes_id,
						                      'new_id' => $centralprocess["made_codigo_asignatura"],
						                          'position' => "pes_mat_b2_h6_cod",
					*/
				}
			}
		}

		$centralprocess = $distri->getCode($sta, $maca_id);

		return $this->render('temporal', [
			'cod_source' => $sta,
			'malla_target' => $maca_id,
			'pes_id' => $pes_id,
			'new_id' => $centralprocess["made_codigo_asignatura"],
			'position' => "pes_mat_b1_h1_cod (END)",
		]);
	}

	public function actionAcademicoestudianteview() {
		$pla_id = $_GET['pla_id'];
		$per_id = $_GET['per_id'];
		$saca_id = $_GET['periodo'];
		$bloque = $_GET['bloque'];
		$asi_id = $_GET['id'];
		$modalidad = $_GET['modalidad'];
		$periodo = $_GET['periodo'];
		$materia = $_GET['materia'];
		$mpp_id = $_GET['mpp_id'];
		$arrSearch = [];
		$arrSearch['bloque'] = $bloque;
		$arrSearch['periodo'] = $periodo;
		$arrSearch['asi_id'] = $asi_id;
		$arrSearch['modalidad'] = $modalidad;
		$arrSearch['mpp_id'] = $mpp_id;
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modalidad_model = new Modalidad();
		$modcanal = new Oportunidad();
		$mod_jornada = new DistributivoAcademicoHorario();
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();

		$jornada = $mod_jornada->consultarJornadahorario();
		$model = $mod_periodo->consultarEstxMatPlan($arrSearch);
		return $this->render('academicoestudianteview', [
			'arr_unidad' => ArrayHelper::map($unidad_acad_data, 'id', 'name'),
			'arr_modalidad' => $this->Modalidades(), //ArrayHelper::map($modalidad_data, 'id', 'name'),
			'arr_periodo' => ArrayHelper::map($periodo, 'id', 'name'),
			'arr_idcarrera' => $mod_carrera,
			'materia' => $materia,
			'periodo' => $saca_id,
			'id_modalidad' => $modalidad,
			'model_detalle' => $model,
		]);
	}

	public function actionNewplanificacion() {
		//$pla_id = $_GET['pla_id'];
		//$per_id = $_GET['per_id'];
		//$emp_id = 1;
		$mod_periodo = new PlanificacionEstudiante();
		$periodo = $mod_periodo->consultarPeriodoAcadplanifica();
		$uni_aca_model = new UnidadAcademica();
		$modalidad_model = new Modalidad();
		$modcarrera = new EstudioAcademico();
		$mod_jornada = new DistributivoAcademicoHorario();
		$mod_malla = new MallaAcademica();
		$unidad_acad_data = $uni_aca_model->consultarUnidadAcademicas();
		$academic_study_data = $modcarrera->consultarCarreraxunidad($unidad_acad_data[0]['id']);
		$modalidad_data = $modcarrera->consultarmodalidadxcarrera($academic_study_data[0]['id']);
		$jornada = $mod_jornada->consultarJornadahorario();

		$model_carrera = new EstudioAcademico();

		$malla = $mod_malla->consultarmallasxcarrera($unidad_acad_data[0]['id'], $modalidad_data[0]['id'], $academic_study_data[0]['id']);

		$modalidades = $modalidad_model->consultarModalidad($unidad_acad_data[0]['id'], 1);

		$periodo_activo = $mod_periodo->consultaPeriodoAcadVigente();

		//$mode_malla = $mod_periodo->consultaMallaEstudiante($per_id);

		$arr_pla = Planificacion::getPeriodosAcademicoMod();
		$pla_periodo_academico = $arr_pla[0]->name;

		$session = Yii::$app->session;
		$data = Yii::$app->request->get();
		$per_id = $data['estudiante'];
		$periodoAcad = $data['periodo'];
		$saca_id = $periodoAcad;
		$pla_id = $mod_periodo->consultaPlanificacionEstVigente($per_id, $saca_id);
		$plan = $pla_id[0]["id"];
		$session->set("plan_id", $plan);
		$session->set("per_ids", $per_id);
		Yii::$app->session->set('pla_id', $plan);
		$existe = $mod_periodo->confirmarPlanificacionExistente($data['estudiante'], $data['periodo']);
		//print_r($data['per_id'].'-'.$data['periodo']);die();

		if ($data['estudiante'] != null) {
			$arrSearch['estudiante'] = $per_id;
			$arrSearch['planificacion'] = $pla_id[0]["id"];
			//$plan = $mod_periodo->getPlanificacionxPeriodo($periodoAcad,$per_id);
			$arrSearch['periodoAca'] = $data["periodo"];
			$arrSearch['modalidad'] = $data['modalidad'];
			$arrSearch['saca_id'] = $data["periodo"];
			$model_plan = $mod_periodo->consultarDetalleplanificaaut($arrSearch, false);
			$carrera_activa = $mod_periodo->consultaracarreraxmallaaut($per_id);
			//$pla_id = $mod_periodo->consultaPlanificacionEstVigente($per_id);
			$opt_malla_academica = $data["malla_academica"];//01 febrero 2022.
			$mode_malla = $mod_periodo->consultaMallaEstudiante($per_id);
			if ($opt_malla_academica==2){
				//Consulta asignaturas de malla academico, que no son centro de idiomas.
				$materia = $mod_malla->consultarasignaturaxmallaaut($per_id, null); //$mode_malla[0]['id']);
			}else{
				$materia = $mod_malla->selectAsignaturaPorMallaAutCentroIdioma($per_id, $data['modalidad'], null); 
			}
			$busquedalumno = $mod_periodo->busquedaEstudianteplanificacionaut($per_id);
			$arr_initial = $per_id;
			$id_carrera = $carrera_activa['0']['id'];
			$id_pla = $pla_id[0]['id'];
			$id_modalidad = $modalidad_data[0]['id'];
			$perSelect = $data["periodo"] ? $data["periodo"] : 0;
			\app\models\Utilities::putMessageLogFile('---------------------------------------------------------------');
			\app\models\Utilities::putMessageLogFile('$perSelect: ' . $perSelect);
			\app\models\Utilities::putMessageLogFile('---------------------------------------------------------------');
			$resp_carrera = $model_carrera->selectCarreraEst($per_id); //01 febrero 2022.
			return $this->render('newplanificacion', [
				'arr_unidad' => ArrayHelper::map($unidad_acad_data, 'id', 'name'),
				//'arr_modalidad' => $id_modalidad,//ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidad_data), 'id', 'name'),
				'arr_malla' => ArrayHelper::map($mode_malla, 'id', 'name'), //($periodo?$arr_pla[$periodo]:'Todas')
				'arr_periodo' => ArrayHelper::map(array_merge([['id' => ($perSelect), 'name' => ($perSelect ? $periodo[$perSelect] : 'Seleccionar')]], $periodo), 'id', 'name'),
				'arr_jornada' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $jornada), 'id', 'name'),
				'arr_bloque' => $this->Bloques(),
				'arr_hora' => $this->Horas(),
				'arr_modalidadh' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidades), 'id', 'name'),
				'arr_materia' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $materia), 'id', 'name'),
				'arr_alumno' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $busquedalumno), 'id', 'name'),
				'model_detalle' => $model_plan, //$mod_detalle,//$model_plan,
				//'periodo_activo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']],$periodo_activo), 'id', 'name'),
				'periodo_activo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_pla), 'id', 'name'),
				'arr_initial' => $arr_initial,
				'model' => $model_plan,
				'carrera_activa' => $id_carrera, //ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']],$carrera_activa), 'id', 'name'),
				'pla_id' => $id_pla, //ArrayHelper::map($pla_id, 'id', 'name'),
				'per_id' => $per_id,
				'existe' => $existe,
				'perSelect' => $perSelect ? $perSelect : 0,
				'arr_paralelo' => $this->Paralelo(),
				'arr_horario' => $this->Horario(),
				'arr_carrera' => $resp_carrera,//01 febrero 2022.
				'opt_malla_academica' => $opt_malla_academica,//01 febrero 2022.

				$this->renderPartial('procesoplanificacion-grid', [
					'model' => $model_plan,
					'carrera_activa' => $id_carrera, //ArrayHelper::map($carrera_activa, 'id', 'name'),
					'pla_id' => $id_pla, //ArrayHelper::map($pla_id, 'id', 'name'),
					'per_id' => $per_id,
					//'arr_modalidad' => $id_modalidad,//ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidad_data), 'id', 'name'),
					'existe' => $existe,
				]),

			]);
			/*return $this->renderPartial('procesoplanificacion-grid', [
				                "model" => $model_plan,
			*/
		} else {
			//$model_plan = $mod_periodo->consultarDetalleplanificaaut();
			$mode_malla = $mod_periodo->consultaMallaEstudiante($data['estudiante']);
			$model_plan = $mod_periodo->consultarDetalleplanificaaut($arrSearch, false);
			$materia = $mod_malla->consultarasignaturaxmallaaut($mode_malla[0]['id'],null);
			$busquedalumno = $mod_periodo->busquedaEstudianteplanificacion();
			$carrera_activa = $mod_periodo->consultaracarreraxmallaaut($per_id);
			$id_carrera = $carrera_activa['id'];
			$id_pla = $pla_id['id'];
		}
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if (isset($data['getmodalidad'])) {
				$modalidad = $modcarrera->consultarmodalidadxcarrera($data['eaca_id']);
				$message = array('modalidad' => $modalidad);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getmalla'])) {
				$mallaca = $mod_periodo->consultaMallaEstudiante($data['estudiante']);
				$message = array('mallaca' => $mallaca);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getmateria'])) {
				$asignatura = $mod_malla->consultarasignaturaxmalla($data['maca_id']);
				$message = array('asignatura' => $asignatura);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getcarrera'])) {
				//\app\models\Utilities::putMessageLogFile('AAAA post per_id: ' .$per_id);
				//\app\models\Utilities::putMessageLogFile('AAAA post $data[est_id]: ' .$data['est_id']);
				$carrera = $model_carrera->selectCarreraEst($data['est_id']);
				$message = array('carrera' => $carrera);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}
		$perSelect = $data["periodo"] ? $data["periodo"] : 0;
		\app\models\Utilities::putMessageLogFile('---------------------------------------------------------------');
		\app\models\Utilities::putMessageLogFile('$perSelect: ' . $perSelect);
		\app\models\Utilities::putMessageLogFile('---------------------------------------------------------------');
		return $this->render('newplanificacion', [
			'arr_unidad' => ArrayHelper::map($unidad_acad_data, 'id', 'name'),
			'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidad_data), 'id', 'name'),
			'arr_malla' => ArrayHelper::map($mode_malla, 'id', 'name'),
			'arr_periodo' => ArrayHelper::map(array_merge([['id' => ($perSelect), 'name' => ($perSelect ? $periodo[$perSelect] : 'Seleccionar')]], $periodo), 'id', 'name'),
			'arr_jornada' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $jornada), 'id', 'name'),
			'arr_bloque' => $this->Bloques(),
			'arr_hora' => $this->Horas(),
			'arr_modalidadh' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidades), 'id', 'name'),
			//'arr_malla' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $malla), 'id', 'name'),
			'arr_materia' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $materia), 'id', 'name'),
			'arr_alumno' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $busquedalumno), 'id', 'name'),
			'model_detalle' => $model_plan, //$mod_detalle,//$model_plan,
			//'periodo_activo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']],$periodo_activo), 'id', 'name'),
			'periodo_activo' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_pla), 'id', 'name'),
			'arr_initial' => $data['estudiante'],
			'carrera_activa' => $id_carrera, //ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']],$carrera_activa), 'id', 'name'),
			'pla_id' => $id_pla, //ArrayHelper::map($pla_id, 'id', 'name'),
			'per_id' => $per_id,
			'existe' => $existe,
			'perSelect' => $perSelect ? $perSelect : 0,
			'arr_paralelo' => $this->Paralelo(),
			'arr_horario' => $this->Horario(),

		]);
	}

	public function actionListarparalelos() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$asi_id = $data['asi_id'];
			$saca_id = $data['saca_id'];
			$mod_id = $data['mod_id'];
			$mod_malla = new MallaAcademica();
			$paralelos = $mod_malla->consultaParalelosxMateria($asi_id, $saca_id, $mod_id);
			return json_encode($paralelos);
		}
	}
	public function actionHorarioparalelos() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$mpp_id = $data['mpp_id'];
			$mod_malla = new MallaAcademica();
			$horario = $mod_malla->consultaHorarioxParalelo($mpp_id);
			return json_encode($horario);
		}
	}

			public function actionCargamaterias() {

		$squema = new Planificacion();    
                $referenced = $squema->getStudents();       

    if (count($referenced) >= 1 ) {
   for ($t = 0; $t < count($referenced); $t++) {


$scheme = $squema->getScheme($referenced[$t]['maca_codigo']);


        switch ($referenced[$t]['mod_id']) {
            case '1':
                $pla_id = 44;$jornada = 'N';
                break;
            case '2':
                $pla_id = 45;$jornada = 'N';
                break;
        }


$referencerone = $squema->getreference(
    $jornada,
    $pla_id,
    $referenced[$t]['maca_codigo']);

  if (count($referencerone) > 0){


$hasgenerated = $squema->doPusher($referencerone,$referenced[$t]['per_id'],$referenced[$t]['maca_nombre'],$referenced[$t]['per_cedula'],$referenced[$t]['estudiante']);

  } else {



  
  }

}}

	
	}
}
