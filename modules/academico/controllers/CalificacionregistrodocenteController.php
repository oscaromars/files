<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use app\modules\academico\models\EstudioAcademico;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\ModalidadEstudioUnidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Paralelo;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\UsuarioEducativa;
use app\modules\academico\models\EstudianteCarreraPrograma;
use app\modules\academico\models\Profesor;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\MallaAcademicoEstudiante;
use app\modules\academico\models\CabeceraCalificacion;
use app\modules\academico\models\CabeceraAsistencia;
use app\models\Persona;
use app\models\Usuario;
use yii\base\Security;
use yii\base\Exception;
use app\models\EmpresaPersona;
use app\models\UsuaGrolEper;
use app\modules\academico\models\NumeroMatricula;
use app\models\Grupo;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;

use app\modules\academico\models\Asignatura;
use app\modules\academico\models\AsignaturasPorPeriodo;
use app\modules\academico\models\PlanificacionEstudiante;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\ComponenteUnidad;
use yii\data\ArrayDataProvider;

academico::registerTranslations();
admision::registerTranslations();
financiero::registerTranslations();
////
class CalificacionregistrodocenteController extends \app\components\CController {

	private function categorias() {
		return [
			'0' => Yii::t("formulario", "Seleccionar"),
			'1' => Yii::t("formulario", "A"),
			'2' => Yii::t("formulario", "B"),
			'3' => Yii::t("formulario", "C"),
			'4' => Yii::t("formulario", "D"),
			'5' => Yii::t("formulario", "E"),
			'6' => Yii::t("formulario", "F"),
			'7' => Yii::t("formulario", "G"),
			'8' => Yii::t("formulario", "H"),
		];
	}

	private function estados() {
		return [
			'-1' => Yii::t("formulario", "All"),
			'null' => Yii::t("formulario", "Not student"),
			'0' => Yii::t("formulario", "Inactive"),
			'1' => Yii::t("formulario", "Active"),
		];
	}

	private function parciales() {
		return [
			1 => "1° " . academico::t("Academico", "Partial"),
			2 => "2° " . academico::t("Academico", "Partial"),
		];
	}

	public function actionIndex() {
		$grupo_model = new Grupo();
		$mod_estudiante = new Estudiante();
		$mod_programa = new EstudioAcademico();
		$mod_modalidad = new Modalidad();
		$mod_unidad = new UnidadAcademica();
		$mod_profesor = new Profesor();
		$cabeceraCalificacion = new CabeceraCalificacion();
		$mod_periodos = new PeriodoAcademico();
		$per_id = Yii::$app->session->get("PB_perid");
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		//$user_usermane = 'carlos.carrera@mbtu.us';//Yii::$app->session->get("PB_username");
		$user_usermane = Yii::$app->session->get("PB_username");
		$mod_periodo = new PlanificacionEstudiante();
		$busquedalumno = $cabeceraCalificacion->busquedaEstudiantes();
		$Asignatura_distri = new Asignatura();
		Utilities::putMessageLogFile('58 $user_usermane: ' . $user_usermane);

		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			/*if (isset($data["getasignaturas"])) {
				                $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"],$data['pro_id'],$data["uaca_id"],$data["mod_id"]);
				                $message = array("asignatura" => $asignatura);
				                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			*/
			\app\models\Utilities::putMessageLogFile('Id de profesor: ' . $data['pro_id']);
			if (isset($data["getasignaturas_prof_periodo"])) {
				$asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"], $data['pro_id'], $data["uaca_id"], $data["mod_id"]);
				$profesorup = $mod_profesor->getProfesoresEnAsignaturasByall($data["paca_id"], $data["uaca_id"], $data["mod_id"]);
				$paralelo_clcf = [];
				$message = array("asignatura" => $asignatura, "profesorup" => $profesorup);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			//Peridood academico
			if (isset($data["getasignaturas_prof"])) {
				$asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"], $data['pro_id'], $data["uaca_id"], $data["mod_id"]);
				$paralelo_clcf = [];
				$message = array("asignatura" => $asignatura, "paralelo_clcf" => $paralelo_clcf);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}

			if (isset($data["getasignaturas_bus"])) {
				$asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"], $data['pro_id'], $data["uaca_id"], $data["mod_id"]);
				$profesorup = $mod_profesor->getProfesoresEnAsignaturasByall($data["paca_id"], $data["uaca_id"], $data["mod_id"]);
				$paralelo_clcf = [];
				$message = array("asignatura" => $asignatura, "profesorup" => $profesorup);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}

			if (isset($data["getasignaturas_uaca"])) {
				$modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
				$asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"], $data['pro_id'], $data["uaca_id"], $data["mod_id"]);
				$profesorup = $mod_profesor->getProfesoresEnAsignaturasByall($data["paca_id"], $data["uaca_id"], $data["mod_id"]);
				$paralelo_clcf = [];
				$message = array("modalidad" => $modalidad, "asignatura" => $asignatura, "profesorup" => $profesorup);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}

		$data = Yii::$app->request->get();

		if ($data['PBgetFilter']) {
			//$search = $data['search'];
			Utilities::putMessageLogFile("124  PBgetFilter");
			$unidad = (isset($data['unidad']) && $data['unidad'] > 0) ? $data['unidad'] : NULL;
			$modalidad = (isset($data['modalidad']) && $data['modalidad'] > 0) ? $data['modalidad'] : NULL;
			$periodo = (isset($data['periodo']) && $data['periodo'] > 0) ? $data['periodo'] : NULL;
			$materia = (isset($data['materia']) && $data['materia'] > 0) ? $data['materia'] : NULL;
			$profesor = (isset($data['profesor']) && $data['profesor'] > 0) ? $data['profesor'] : NULL;
			$estudiante = (isset($data['estudiante']) && $data['estudiante'] > 0) ? $data['estudiante'] : NULL;
			//$model = $distributivo_model->getListadoDistributivo($search, NULL, $periodo);
			if ($unidad <= 0) {
				$unidad = "";
			}

			$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllSearch($unidad, $periodo, $materia, $profesor, $modalidad, $estudiante);
			return $this->render('index-grid', [
				"model" => $arr_estudiante,
				'arr_alumno' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $busquedalumno), 'id', 'name'),
			]);
		}

		$arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);

		$arr_periodos = $mod_periodos->consultarPeriodosActivos();

		$arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);

		$arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);

		if (in_array(['id' => '1'], $arr_grupos) ||
			in_array(['id' => '6'], $arr_grupos) ||
			in_array(['id' => '7'], $arr_grupos) ||
			in_array(['id' => '8'], $arr_grupos)
		) {
			//Es Cordinados
			$arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas();
			Utilities::putMessageLogFile("Paso por cordinador");
			// Utilities::putMessageLogFile(print_r($arr_profesor_all,true));
			$asignatura = $Asignatura_distri->getAsignaturasBy($arr_profesor_all[0]['pro_id'], $arr_ninteres[0]["id"], $arr_periodos[0]["id"]);
			$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteSearch($arr_ninteres[0]["id"], $arr_periodos[0]["id"], $asignatura[0]['id'], $arr_profesor_all[0]["pro_id"]);
		} else {
			Utilities::putMessageLogFile("Paso no Cordinador");
			//No es Cordinador
			$arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
			// Utilities::putMessageLogFile(print_r($arr_profesor_all,true));
			$asignatura = $Asignatura_distri->getAsignaturasBy($arr_profesor_all[0]['pro_id'], $arr_ninteres[0]["id"], $arr_periodos[0]["id"]);
			$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteSearch($arr_ninteres[0]["id"], $arr_periodos[0]["id"], $asignatura[0]['id'], $arr_profesor_all[0]['pro_id']);
		}
		//Obtiene el grupo id del suaurio

		return $this->render('index', [
			'model' => $arr_estudiante,
			'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $asignatura), "id", "name"),
			'arr_periodos' => ArrayHelper::map(array_merge([["id" => "0", "nombre" => Yii::t("formulario", "Todos")]], $arr_periodos), "id", "nombre"),
			'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_ninteres), "id", "name"),
			'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_modalidad), "id", "name"),
			// 'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_carrerra1), "id", "name"),
			'arr_estados' => $this->estados(),
			'arr_profesor_all' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_profesor_all), "id", "name"),
			'arr_alumno' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $busquedalumno), 'id', 'name'),
		]); //
	} //function actionIndex

	public function actionView() {
		$per_id = base64_decode($_GET['per_id']);
		$est_id = base64_decode($_GET['est_id']);
		$persona_model = new Persona();
		$mod_modalidad = new Modalidad();
		$mod_unidad = new UnidadAcademica();
		$modcanal = new Oportunidad();
		$mod_Estudiante = new Estudiante();

		$dataPersona = $persona_model->consultaDatosPersonaid($per_id);
		$dataEstudiante = $mod_Estudiante->getEstudiantexestid($est_id);
		$arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
		$arr_modalidad = $mod_modalidad->consultarModalidad($dataEstudiante['unidad'], 1);
		$arr_carrerra1 = $modcanal->consultarCarreraModalidad($dataEstudiante['unidad'], $dataEstudiante['modalidad']);
		return $this->render('view', [
			'arr_persona' => $dataPersona,
			'arr_estudiante' => $dataEstudiante,
			'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_ninteres), "id", "name"),
			'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
			'arr_carrera' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_carrerra1), "id", "name"),
			'arr_categorias' => $this->categorias(),
		]);
	} //function actionView

    public function actionRegistro() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $user_usermane = Yii::$app->session->get("PB_username");

        $mod_programa      = new EstudioAcademico();
        $mod_modalidad     = new Modalidad();
        $mod_unidad        = new UnidadAcademica();
        $Asignatura_distri = new Asignatura();        
        $mod_periodo = new PeriodoAcademico(); 
        $mod_profesor      = new Profesor();
        $mod_registro      = new DistributivoAcademico();
        $mod_calificacion  = new CabeceraCalificacion();
        $grupo_model       = new Grupo();
        
        $data = Yii::$app->request->get();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();    
            $periodo = $data['paca_id'];
            Utilities::putMessageLogFile('$periodo' . $periodo);
            if (isset($data["getparcial"])) {
                $parcial = $mod_periodo->getParcialUnidad($data["uaca_id"]);
                $message = array("parcial" => $parcial);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

            if (isset($data["getasignaturas_prof_periodo_reg"])) {
                $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"],$data['pro_id'],$data["uaca_id"],$data["mod_id"]);
                $profesorreg = $mod_profesor->getProfesoresEnAsignaturasByall($data["paca_id"], $data["uaca_id"], $data["mod_id"]);
                $paralelo_clcf = [];
                $message = array("asignatura" => $asignatura,"profesorreg" => $profesorreg);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

            if (isset($data["getasignaturas_uaca_reg"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
                $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"], $data['pro_id'], $data["uaca_id"], $data["mod_id"]);
                $profesorreg = $mod_profesor->getProfesoresEnAsignaturasByall($data["paca_id"], $data["uaca_id"], $data["mod_id"]);
                $message = array("modalidad" => $modalidad,"asignatura" => $asignatura, "profesorreg" => $profesorreg);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

            if (isset($data["getasignaturas_bus_reg"])) {
                $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"], $data['pro_id'], $data["uaca_id"], $data["mod_id"]);
                $profesorreg = $mod_profesor->getProfesoresEnAsignaturasByall($data["paca_id"], $data["uaca_id"], $data["mod_id"]);
                $message = array("asignatura" => $asignatura, "profesorreg" => $profesorreg);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

            if (isset($data["getmateria"])) {
                $materia = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"], $data['pro_id'], $data["uaca_id"], $data["mod_id"]);
                $message = array("materia" => $materia);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }

        $arr_periodos = $mod_periodo->consultarPeriodosActivos();
        // $arr_periodos = $mod_periodo->consultarPeriodosActivosmalla();


        $arr_ninteres      = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad     = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);     
        $arr_parcialunidad = $mod_periodo->getParcialUnidad($arr_ninteres[0]["id"]);
        $arr_grupos        = $grupo_model->getAllGruposByUser($user_usermane);
        
        //print_r($arr_grupos);die();

        if ( in_array(['id' => '1'], $arr_grupos) ||
             in_array(['id' => '6'], $arr_grupos) ||
             in_array(['id' => '7'], $arr_grupos) ||
             in_array(['id' => '8'], $arr_grupos)
        ){
            //Es Cordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas();
            $asignatura = $Asignatura_distri->getAsignaturasBy($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],$arr_periodos[0]["id"]);
            //print_r("Es Cordinador");
            //print_r($arr_profesor_all);die();
        }else{
            //No es coordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId2($per_id);
            $asignatura = $Asignatura_distri->getAsignaturasBy($arr_profesor_all[0]['pro_id'],
                                                               $arr_ninteres[0]["id"],
                                                               $arr_periodos[0]["id"]);
            //print_r($per_id);die();
            //print_r("NO Es Cordinador");die();
        }
        
           if ($data = Yii::$app->request->get()){ 

           $thisperiodo= $data["periodo"];
           $thisunidad= $data["unidad"];
           $thismodalidad= $data["modalidad"];
           $thisprofesor= $data["profesor"];
           $thisparcial= $data["parcial"];
        


           $arr_profesor_all = $mod_profesor->getProfesorEnAsignaturas(true,$thisprofesor);
           $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($thisperiodo,$thisprofesor, $thisunidad, $thismodalidad);
              $thismateria= $asignatura[0]['id'];
           } else {  
           
           $thisperiodo= 0;
             $thisunidad= 0;
           $thismodalidad= 0;
           $thisprofesor= 0;
            $thismateria= 0;
            $thisparcial= 0;

           }

        return $this->render('register', [
            //'arr_ninteres'      => ArrayHelper::map(array_merge([["id" => "", "name" => Yii::t("formulario", "All")]], $arr_ninteres), "id", "name"),
            'arr_periodos'  => ArrayHelper::map(array_merge([["id" => "0", "nombre" => Yii::t("formulario", "Todos")]], $arr_periodos), "id", "nombre"),
            'arr_ninteres'  => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_ninteres), "id", "name"),
            'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
            'arr_asignatura'=> ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
            'arr_parcial'   => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_parcialunidad), "id", "name"),
            'pro_id'        => $arr_profesor["Id"],
            'model'         => "",
            'arr_profesor_all'=> ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_profesor_all), "id", "name"),
            'arr_grupos'      => $arr_grupos[0]['id'],
             'thisperiodo'        => $thisperiodo,
            'thisunidad'        => $thisunidad,
            'thismodalidad'        => $thismodalidad,
            'thisprofesor'        => $thisprofesor,
            'thismateria'        => $thismateria,
            'thisparcial'        => $thisparcial,
            //'isreg'             => "",
            //'arr_profesor_all'  => ArrayHelper::map($arr_profesor_all, "pro_id", "nombres"),
            //'componente'        => $componenteuni,
            //'campos'            => $campos,
        ]);
    }//function actionRegistro

	public function actionTraermodelo() {
		/*
			        $per_id         = @Yii::$app->session->get("PB_perid");

			        $mod_calificacion  = new CabeceraCalificacion();
			        $data = Yii::$app->request->post();

			        $arrSearch["periodo"]   = $data['periodo'];
			        $arrSearch["unidad"]    = $data['uaca_id'];
			        $arrSearch["modalidad"] = $data['modalidad'];
			        $arrSearch["materia"]   = $data['materia'];
			        $arrSearch["parcial"]   = $data['parcial'];
			        $arrSearch["profesor"]  = $data['profesor'];

			        $model = $mod_calificacion->getRegistroCalificaciones($arrSearch);
			        return json_encode($model);
		*/
		$per_id = @Yii::$app->session->get("PB_perid");

		$mod_calificacion = new CabeceraCalificacion();
		$data = Yii::$app->request->post();

		$arrSearch["periodo"] = $data['periodo'];
		$arrSearch["unidad"] = $data['uaca_id'];
		$arrSearch["modalidad"] = $data['mod_id'];
		$arrSearch["materia"] = $data['materia'];
		$arrSearch["parcial"] = $data['parcial'];
		$arrSearch["profesor"] = $data['profesor'];
		// $arrSearch["paralelo"]  = $data['paralelo'];
		$arrSearch["grupo"] = $data['grupo'];
		$isreg = $mod_calificacion->getPeriodoCalificaciones($arrSearch["grupo"], $arrSearch["periodo"]);
		\app\models\Utilities::putMessageLogFile('$isreg: ' . print_r($isreg, true));
		$model = array();
		$componentes = array();
		$model['data'] = $mod_calificacion->getRegistroCalificaciones($arrSearch);

		$componentes_temp = $mod_calificacion->getComponenteUnidadarr($arrSearch["unidad"], $arrSearch["modalidad"], $arrSearch["parcial"]);
		foreach ($componentes_temp as $key => $value) {
			$componentes[$value['nombre']] = array('id' => $value['id'], 'nombre' => $value['nombre'], 'notamax' => $value['notamax']);
		}
		$model['isreg'] = $isreg;
		$model['componentes'] = $componentes;
		\app\models\Utilities::putMessageLogFile('$model: ' . print_r($model['isreg'], true));
		\app\models\Utilities::putMessageLogFile('$model: ' . print_r($model['componentes'], true));
		return json_encode($model);
	} //function actionTraerModelo

	public function actionActualizarnota() {
		$per_id = @Yii::$app->session->get("PB_perid");
		$mod_calificacion = new CabeceraCalificacion();

		$data = Yii::$app->request->post();

		$row_id = array_key_first($data['data']);

		$ccal_id = $data['data'][$row_id]['ccal_id'];

		$valor = array();

		$valor["DT_RowId"] = "row_" . $row_id;
		$valor["row_num"] = $row_id;

		if ($ccal_id != 0) {
			$valor["ccal_id"] = $data['data'][$row_id]['ccal_id'];
			$total = 0;

			foreach ($data['data'][$row_id] as $key => $value) {
				if ($key != 'nparcial' &&
					$key != 'paca_id' &&
					$key != 'est_id' &&
					$key != 'pro_id' &&
					$key != 'asi_id' &&
					$key != 'ecal_id' &&
					$key != 'ccal_id' &&
					$key != 'mod_id' &&
					$key != 'uaca_id') {
					if ($value != '') {
						$valida = 2;
						$insertID = $mod_calificacion->insertarRBNO($ccal_id, $key, $value, $valida);
						$mod_calificacion->actualizarDetalleCalificacionporcomponente($ccal_id, $key, $value);

						if (!(is_null($value)) && $value != '') {
							$valor[$key] = $value;
							$total = $total + $value;
							//\app\models\Utilities::putMessageLogFile($value);
						}

					}
				}
//if
			}
		} else {
			$paca_id = $data['data'][$row_id]['paca_id'];
			$est_id = $data['data'][$row_id]['est_id'];
			$pro_id = $data['data'][$row_id]['pro_id'];
			$asi_id = $data['data'][$row_id]['asi_id'];
			$mod_id = $data['data'][$row_id]['mod_id'];
			$ecal_id = $data['data'][$row_id]['ecal_id'];
			$uaca_id = $data['data'][$row_id]['uaca_id'];

			$total = 0;

			$ccal_id = $mod_calificacion->crearCabeceraCalificacionporcomponente($paca_id, $est_id, $pro_id, $asi_id, $ecal_id, $uaca_id);

			$valor["ccal_id"] = $ccal_id;

			foreach ($data['data'][$row_id] as $key => $value) {

				if ($key != 'nparcial' &&
					$key != 'paca_id' &&
					$key != 'est_id' &&
					$key != 'pro_id' &&
					$key != 'asi_id' &&
					$key != 'ecal_id' &&
					$key != 'ccal_id' &&
					$key != 'mod_id' &&
					$key != 'uaca_id') {
					$mod_calificacion->crearDetalleCalificacionporcomponente($ccal_id, $key, $value, $uaca_id, $mod_id, $ecal_id);
					if ($value != '') {
						$valida = 1;
						$insertID = $mod_calificacion->insertarRBNO($ccal_id, $key, $value, $valida);
					}

					if (!(is_null($value)) && $value != '') {
						$valor[$key] = $value;
						$total = $total + $value;

					}
				} //if
			} //foeach
		} //else

		//Solucion temporal, debe revisarse porq suma 1 de mas en la iteraccion
		//$total--;
		$total_promedio = $mod_calificacion->actualizarDetalleCalificacion2($ccal_id, $total);
		if ($total_promedio) {
			$ccalEst = CabeceraCalificacion::findOne(['ccal_id' => $ccal_id, 'ccal_estado' => 1, 'ccal_estado_logico' => 1]);
			$est_id = Estudiante::findOne(['est_id' => $ccalEst['est_id'], 'est_estado' => 1, 'est_estado_logico' => 1]);
			//Utilities::putMessageLogFile('est_id1 ' .$est_id['est_id']);
			//Utilities::putMessageLogFile('per_id ' .$est_id['per_id']);
			//Utilities::putMessageLogFile('paca_id ' .$ccalEst['paca_id']);
			//Utilities::putMessageLogFile('asi_id ' .$ccalEst['asi_id']);
			$maes_id = MallaAcademicoEstudiante::findOne(['per_id' => $est_id['per_id'], 'asi_id' => $ccalEst['asi_id'], 'maes_estado' => 1, 'maes_estado_logico' => 1]);
			//Utilities::putMessageLogFile('maes_id ' .$maes_id['maes_id']);
			$promedio = $mod_calificacion->updatepromedio($maes_id['maes_id'], $ccalEst['paca_id']);
		}
		header('Content-Type: application/json');

		$valor["total"] = $total;

		$respuesta["data"] = array();
		$respuesta['data'][] = $valor;
		return json_encode($respuesta, JSON_PRETTY_PRINT);
	} //function actionActualizarnota

	public function actionCargararchivo() {
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			// \app\models\Utilities::putMessageLogFile($data);
			if ($data["upload_file"]) {
				if (empty($_FILES)) {
					return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
				}
				//Recibe Parámetros
				$files = $_FILES[key($_FILES)];
				$arrIm = explode(".", basename($files['name']));
				$typeFile = strtolower($arrIm[count($arrIm) - 1]);
				if ($typeFile == 'xlsx' || $typeFile == 'xls') {

					$dirFileEnd = Yii::$app->params["documentFolder"] . "calificaciones/" . $data["name_file"] . "." . $typeFile;
					$temp_file = Utilities::createTemporalFile($files['tmp_name']);

					$status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);

					if ($status) {
						return true;
					} else {
						return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
					}

				}
			}
			if ($data["procesar_file"]) {
				try {
					ini_set('memory_limit', '256M');

					$archivo_nombre = $data["archivo"];

					$mod_asig = new Asignatura();
					$per_id = $data['per_id'];
					$ecal_id = $data['ecal_id'];
					$asi_id = $data['asi_id'];
					$paca_id = $data['paca_id'];

					// \app\models\Utilities::putMessageLogFile($asignatura);
					// \app\models\Utilities::putMessageLogFile($per_id);

					$carga_archivo = $this->procesarArchivoCalificaciones($archivo_nombre, $asi_id, $per_id, $mod_asig, $ecal_id, $paca_id);

					if ($carga_archivo['status']) {
						\app\models\Utilities::putMessageLogFile('no estudiante controller...: ' . $carga_archivo['noalumno']);

						if (!empty($carga_archivo['noalumno'])) {
							$noalumno = ' Se encontró las cédulas ' . $carga_archivo['noalumno'] . ' que no pertencen a estudiantes por ende no se cargaron. ';
						}

						$message = array(
							"wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data'] . " Observaciones: " . $carga_archivo['noalumno']),
							"title" => Yii::t('jslang', 'Success'),
						);
						return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
					} else {
						$message = array(
							"wtmessage" => Yii::t("notificaciones", "Error al procesar el archivo. " . $carga_archivo['message']),
							"title" => Yii::t('jslang', 'Error'),
						);
						return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
					}
					return;
				} catch (Exception $ex) {
					$message = array(
						'wtmessage' => Yii::t('notificaciones', 'Error al procesar el archivo.'),
						'title' => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), true, $message);
				}
			}

			// Filtros de asignatura por profesor y período
			if (isset($data["getasignaturas"])) {
				// Utilities::putMessageLogFile($data);
				$per_id = $data['per_id'];
				// Utilities::putMessageLogFile($per_id);
				$pro_id = (new Profesor())->getProfesoresxid($per_id)['Id'];
				// Utilities::putMessageLogFile($pro_id);
				$materias = (new Asignatura())->getAsignaturasBy($pro_id, NULL, $data['paca_id']);
				// Utilities::putMessageLogFile($materias);
				$message = array("asignaturas" => $materias);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		} else {
			$mod_periodo = new PeriodoAcademico();
			$asig_mod = new Asignatura();
			$mod_profesor = new Profesor();

			$periodos = $mod_periodo->consultarPeriodosActivos();
			$periodo_actual = $mod_periodo->getPeriodoAcademicoActual();
			$profesores = $mod_profesor->getProfesoresEnAsignaturas();

			// Determinar si el usuario logueado es sólo profesor o tiene más privilegios
			$per_id = Yii::$app->session->get("PB_perid");
			$usu_id = Yii::$app->session->get("PB_iduser");
			$admin = $this->isAdmin($usu_id);

			// $admin = 1;

			if ($admin) {
// Es administrador
				$pro_id = $mod_profesor->getProfesoresxid($per_id)['Id'];
				if (isset($pro_id)) {
					// Y profesor
					$materias = $asig_mod->getAsignaturasBy($pro_id, NULL, $periodo_actual[0]['id']);
				} else {
					$materias = $asig_mod->getAsignaturasBy($profesores[0]['pro_id'], NULL, $periodo_actual[0]['id']);
				}
			} else {
				// No es administrador
				$pro_id = $mod_profesor->getProfesoresxid($per_id)['Id'];
				if (!isset($pro_id)) { // Ni profesor
					$materias = $asig_mod->getAsignaturasBy($profesores[0]['pro_id'], NULL, $periodo_actual[0]['id']); // En realidad no se debería permitir entrar en la pantalla, pero por si acaso
				} else {
					$materias = $asig_mod->getAsignaturasBy($pro_id, NULL, $periodo_actual[0]['id']);
				}
			}

			// Utilities::putMessageLogFile($periodo_actual['id']);

			// \app\models\Utilities::putMessageLogFile("materias: " . print_r($materias, true));

			return $this->render('cargarcalificaciones', [
				'periodos' => ArrayHelper::map(array_merge($periodos), "paca_id", "paca_nombre"),
				'periodo_actual' => $periodo_actual[0],
				'materias' => ArrayHelper::map(array_merge($materias), "asi_id", "asi_descripcion"),
				'parciales' => $this->parciales(),
				'profesores' => ArrayHelper::map(array_merge($profesores), "per_id", "nombres"),
				'admin' => $admin,
			]);
		}
	}

	public function actionGuardararchivo() {
		//$per_id = @Yii::$app->session->get("PB_perid");
		//$usu_id = Yii::$app->session->get('PB_iduser');
		//$mod_cartera = new CargaCartera();
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if ($data["upload_file"]) {
				if (empty($_FILES)) {
					return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
				}
				//Recibe Parámetros
				$files = $_FILES[key($_FILES)];
				$arrIm = explode(".", basename($files['name']));
				$typeFile = strtolower($arrIm[count($arrIm) - 1]);
				if ($typeFile == 'xlsx' || $typeFile == 'csv' || $typeFile == 'xls') {
					$dirFileEnd = Yii::$app->params["documentFolder"] . "cartera/" . $data["name_file"] . "." . $typeFile;
					$status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
					if ($status) {
						return true;
					} else {
						return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
					}
				}
			}
			if ($data["procesar_file"]) {
				try {
					ini_set('memory_limit', '256M');
					// \app\models\Utilities::putMessageLogFile('Files ...: ' . $data["archivo"]);
					$carga_archivo = $mod_cartera->CargarArchivocartera($data["archivo"]);
					if ($carga_archivo['status']) {
						\app\models\Utilities::putMessageLogFile('no estudiante controller...: ' . $arroout['noalumno']);
						if (!empty($carga_archivo['noalumno'])) {
							$noalumno = ' Se encontró las cédulas ' . $carga_archivo['noalumno'] . ' que no pertencen a estudiantes por ende no se cargaron. ';
						}
						$message = array(
							"wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data'] . $noalumno),
							"title" => Yii::t('jslang', 'Success'),
						);
						return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
					} else {
						$message = array(
							"wtmessage" => Yii::t("notificaciones", "Error al procesar el archivo. " . $carga_archivo['message']),
							"title" => Yii::t('jslang', 'Error'),
						);
						return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
					}
					return;
				} catch (Exception $ex) {
					$message = array(
						'wtmessage' => Yii::t('notificaciones', 'Error al procesar el archivo.'),
						'title' => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), true, $message);
				}
			}
		} else {
			return $this->render('cargarcalificaciones', []);
		}
	}

	/**
	 * Guarda las calificaciones que provienen del archivo de excel
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	public function procesarArchivoCalificaciones($fname, $asi_id, $per_id, $mod_asig, $ecal_id, $paca_id) {
		$file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "calificaciones/" . $fname;
		$fila = 0;
		$chk_ext = explode(".", $file);
		$con = Yii::$app->db_facturacion;
		$transaccion = $con->getTransaction();

		if ($transaccion !== null) {$transaccion = null;} else { $transaccion = $con->beginTransaction();}

		if (strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx") {
			try
			{
				$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
				$dataArr = array();
				$validacion = false;
				$row_global = 0;

				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle = $worksheet->getTitle();
					$highestRow = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
					$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

					for ($row = 1; $row <= $highestRow; ++$row) {
						$row_global = $row_global + 1;
						for ($col = 1; $col <= $highestColumnIndex; ++$col) {
							$cell = $worksheet->getCellByColumnAndRow($col, $row);
							$dataArr[$row_global][$col] = $cell->getCalculatedValue();
						}
					}
				}

				$noalumno = "";

				$fila = 0;
				$tipo = -1; // O = Grado; 1 = Posgrado

				$pro_id = (new Profesor())->getProfesoresxid($per_id)['Id'];

				$ecun_id_posgrado = 4; // Posgrado

				foreach ($dataArr as $val) {
					$fila++;

					if (!is_null($val[5]) || $val[5]) {
						if ($tipo == 0 || $val[5] == "PRIMER PARCIAL") // GRADO
						{
							$tipo = 0;
							if ($fila == 1 || $fila == 2 || $fila == 3 || $fila == 4 || $fila == 5) {
								// No leer estas filas
								continue;
							} else {
								//Aquí se hace el cálculo

								/*
									                                ** $val[5] -> 'Act. Asincro 10P' - 1° PARCIAL
									                                ** $val[6] -> 'Act. Sincro 10P' - 1° PARCIAL
									                                ** $val[7] -> 'Autónomas 20P' - 1° PARCIAL
									                                ** $val[8] -> 'Evaluación Par. 20P' - 1° PARCIAL
									                                ** $val[9] -> 'Examen 40P' - 1° PARCIAL
									                                ** $val[10] -> 'Calificación' - 1° PARCIAL  No se usa porque el sistema lo calcula por si acaso esté mal calculado
									                                **
									                                ** $val[12] -> 'Act. Asincro 10P' - 2° PARCIAL
									                                ** $val[13] -> 'Act. Sincro 10P' - 2° PARCIAL
									                                ** $val[14] -> 'Autónomas 20P' - 2° PARCIAL
									                                ** $val[15] -> 'Evaluación Par. 20P' - 2° PARCIAL
									                                ** $val[16] -> 'Examen 40P' - 2° PARCIAL
									                                ** $val[17] -> 'Calificación' - 2° PARCIAL  No se usa porque el sistema lo calcula por si acaso esté mal calculado
								*/

								$usuario = trim($val[2], " "); // Si es est de Grado Online
								$cedula = trim($val[2], " "); // Si es est de grado no online
								$nombre = $val[4];

								$estudianteOnline = UsuarioEducativa::find()->where(['uedu_usuario' => $usuario, 'uedu_estado' => 1, 'uedu_estado_logico' => 1])->asArray()->one();

								$persona = Persona::find()->where(['per_cedula' => $cedula, 'per_estado' => 1, 'per_estado_logico' => 1])->asArray()->one();

								// Si no retorna nada, puede que sea un estudiante de grado no online
								if (!isset($estudianteOnline) && !isset($persona)) {
									// Si el estudiante no existe en ninguna de las dos, continuar al siguiente, y colocarlo en la lista
									$noalumno .= $nombre . " (no es un estudiante registrado), ";
									continue;
								}
								// Si es de Online
								elseif (isset($estudianteOnline)) {
									$estudiante = $estudianteOnline;
								}
								// Si no es de online
								elseif (isset($persona)) {
									$estudiante = Estudiante::find()->where(['per_id' => $persona['per_id']])->asArray()->one();
								}

								$est_id = $estudiante['est_id'];

								$meun_id = EstudianteCarreraPrograma::find()->where(['est_id' => $est_id])->asArray()->one()['meun_id'];
								$meun = ModalidadEstudioUnidad::find()->where(['meun_id' => $meun_id])->asArray()->one();
								$uaca_id = $meun['uaca_id'];

								// Si el estudiante no es parte de Grado
								if ($uaca_id != 1) {
									$noalumno .= $nombre . " (no pertenece a la Unidad Académica de Grado), ";
									continue;
								}

								// Sacar la asignatura correcta
								$asignatura = $mod_asig->consultarEstudiantePertenece($est_id, $asi_id, $uaca_id);
								if (empty($asignatura)) {
									$noalumno .= $nombre . " (no pertenece a esta asignatura), ";
									continue;
								}

								// Modalidad ID
								$mod_id = $meun['mod_id'];

								// Validar que el período académico sea el correcto
								$daca = DistributivoAcademico::find()->where(['asi_id' => $asi_id, 'pro_id' => $pro_id, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
								$paca_id_daca = $daca['paca_id'];
								if ($paca_id != $paca_id_daca) {
									$noalumno .= $nombre . " (no pertenece al período académico seleccionado), ";
									continue;
								}

								// Grado Online
								if ($mod_id == 1) {
									$seguir = $this->calificarGradoOnline($mod_id, $uaca_id, $ecal_id, $val, $nombre, $noalumno, $paca_id, $est_id, $pro_id, $asi_id);
									if (!$seguir) {continue;}
								}
								// Grado Presencial
								elseif ($mod_id == 2) {
									$seguir = $this->calificarGradoPresencial($mod_id, $uaca_id, $ecal_id, $val, $nombre, $noalumno, $paca_id, $est_id, $pro_id, $asi_id);
									if (!$seguir) {continue;}
								}
								// Grado Semi-Presencial
								elseif ($mod_id == 3) {
									$seguir = $this->calificarGradoSemiPresencial($mod_id, $uaca_id, $ecal_id, $val, $nombre, $noalumno, $paca_id, $est_id, $pro_id, $asi_id);
									if (!$seguir) {continue;}
								}
								// Grado Distancia
								else {
									// mod_id = 4
									$seguir = $this->calificarGradoDistancia($mod_id, $uaca_id, $ecal_id, $val, $nombre, $noalumno, $paca_id, $est_id, $pro_id, $asi_id);
									if (!$seguir) {continue;}
								}
							}
						} else if ($tipo == 1 || $val[5] != "PRIMER PARCIAL") // POSGRADO
						{
							$tipo = 1;
							if ($fila == 1) {
								// No leer la primera fila ni la 2da
								continue;
							} else {
								/*
									                                ** $val[5] -> 'Act. Asincro 10P
									                                ** $val[6] -> 'Autónomas 20P'
									                                ** $val[7] -> 'Evaluación Par. 20P'
									                                ** $val[8] -> 'Trabajo Final'
									                                ** $val[9] -> 'Calificación' No se usa porque el sistema lo calcula por si acaso esté mal calculado
								*/

								$cedula = $val[2];
								$nombre = $val[4];

								$persona = Persona::find()->where(['per_cedula' => $cedula, 'per_estado' => 1, 'per_estado_logico' => 1])->asArray()->one();
								// Si el estudiante no existe, continuar al siguiente, y colocarlo en la lista
								if (!isset($persona)) {
									$noalumno .= $nombre . " (no es un estudiante registrado), ";
									continue;
								}

								$estudiante = Estudiante::find()->where(['per_id' => $persona['per_id']])->asArray()->one();
								// Si el estudiante no existe, continuar al siguiente, y colocarlo en la lista
								if (!isset($estudiante)) {
									$noalumno .= $nombre . " (no es un estudiante registrado), ";
									continue;
								}

								$est_id = $estudiante['est_id'];

								$meun_id = EstudianteCarreraPrograma::find()->where(['est_id' => $est_id])->asArray()->one()['meun_id'];
								$meun = ModalidadEstudioUnidad::find()->where(['meun_id' => $meun_id])->asArray()->one();
								$uaca_id = $meun['uaca_id'];

								// Si el estudiante no es parte de Posgrado
								if ($uaca_id != 2) {
									$noalumno .= $nombre . " (no pertenece a la Unidad Académica de Posgrado), ";
									continue;
								}

								// Sacar la asignatura correcta
								$asignatura = $mod_asig->consultarEstudiantePertenece($est_id, $asi_id, $uaca_id);
								if (empty($asignatura)) {
									$noalumno .= $nombre . " (no pertenece a esta asignatura), ";
									continue;
								}

								// Modalidad ID
								$mod_id = $meun['mod_id'];

								// Validar que el período académico sea el correcto
								$daca = DistributivoAcademico::find()->where(['asi_id' => $asi_id, 'pro_id' => $pro_id, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
								$paca_id_daca = $daca['paca_id'];
								if ($paca_id != $paca_id_daca) {
									$noalumno .= $nombre . " (no pertenece al período académico seleccionado), ";
									continue;
								}

								// Posgrado Online
								if ($mod_id == 1) {
									$seguir = $this->calificarPosgradoOnline($mod_id, $uaca_id, $ecal_id, $val, $nombre, $noalumno, $paca_id, $est_id, $pro_id, $asi_id, $ecun_id_posgrado);
									if (!$seguir) {continue;}
								}
								// Posgrado Presencial
								else {
									// mod_id = 2
									$seguir = $this->calificarPosgradoPresencial($mod_id, $uaca_id, $ecal_id, $val, $nombre, $noalumno, $paca_id, $est_id, $pro_id, $asi_id, $ecun_id_posgrado);
									if (!$seguir) {continue;}
								}
							}
						}
					}
				}

				$arroout['status'] = TRUE;
				$arroout['noalumno'] = $noalumno;

				return $arroout;
			} catch (Exception $ex) {
				if ($trans !== null) {$trans->rollback();}
				$arroout["status"] = FALSE;
				$arroout["error"] = null;
				$arroout["message"] = null;
				$arroout["data"] = null;
				return $arroout;
			}
		}
	}

	/**
	 * Modularización de la función de calificaciones. Para estudiantes de Grado modalidad Online
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	private function calificarGradoOnline($mod_id, $uaca_id, $ecal_id, $val, $nombre, &$noalumno, $paca_id, $est_id, $pro_id, $asi_id) {
		// Tomar las calificaciones dependiendo del parcial elegido
		if ($ecal_id == 1) {
			// 1er Parcial
			// Componentes Unidades
			$cuni_asincrono = ComponenteUnidad::find()->where(['com_id' => 1, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
			$cuni_sincrono = ComponenteUnidad::find()->where(['com_id' => 2, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
			$cuni_cuestionarios = ComponenteUnidad::find()->where(['com_id' => 3, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
			$cuni_autonoma = ComponenteUnidad::find()->where(['com_id' => 4, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
			$cuni_evaluacion = ComponenteUnidad::find()->where(['com_id' => 5, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();

			$cal_asin = $val[5];
			// \app\models\Utilities::putMessageLogFile("cal_asin: " . $cal_asin);
			// \app\models\Utilities::putMessageLogFile("cuni_asincrono['cuni_calificacion']: " . $cuni_asincrono['cuni_calificacion']);
			if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Asincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_sinc = $val[6];
			if ($cal_sinc > $cuni_sincrono['cuni_calificacion'] || $cal_sinc < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Sincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_cuest = $val[7];
			if ($cal_cuest > $cuni_cuestionarios['cuni_calificacion'] || $cal_cuest < 0) {
				$noalumno .= $nombre . " (la nota 'Cuestionarios 4P' está mal colocada), ";
				return 0;
			}

			$cal_aut = $val[8];
			if ($cal_aut > $cuni_autonoma['cuni_calificacion'] || $cal_aut < 0) {
				$noalumno .= $nombre . " (la nota 'Autónomas 6P' está mal colocada), ";
				return 0;
			}

			$cal_eval = $val[9];
			if ($cal_eval > $cuni_evaluacion['cuni_calificacion'] || $cal_eval < 0) {
				$noalumno .= $nombre . " (la nota 'Evaluación Par. 6P' está mal colocada), ";
				return 0;
			}

			$cal_calif = $cal_asin + $cal_sinc + $cal_cuest + $cal_aut + $cal_eval;
		} elseif ($ecal_id == 2) {
			// 2do Parcial
			// Componentes Unidades
			$cuni_asincrono = ComponenteUnidad::find()->where(['com_id' => 1, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id, 'ecal_id' => $ecal_id])->asArray()->one();
			$cuni_sincrono = ComponenteUnidad::find()->where(['com_id' => 2, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id, 'ecal_id' => $ecal_id])->asArray()->one();
			$cuni_cuestionarios = ComponenteUnidad::find()->where(['com_id' => 3, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id, 'ecal_id' => $ecal_id])->asArray()->one();
			$cuni_autonoma = ComponenteUnidad::find()->where(['com_id' => 4, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id, 'ecal_id' => $ecal_id])->asArray()->one();
			$cuni_evaluacion = ComponenteUnidad::find()->where(['com_id' => 5, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id, 'ecal_id' => $ecal_id])->asArray()->one();

			$cal_asin = $val[12];
			// \app\models\Utilities::putMessageLogFile("cal_asin: " . $cal_asin);

			// \app\models\Utilities::putMessageLogFile("uaca_id: " . $uaca_id);
			// \app\models\Utilities::putMessageLogFile("mod_id: " . $mod_id);
			// \app\models\Utilities::putMessageLogFile("ecal_id: " . $ecal_id);
			// \app\models\Utilities::putMessageLogFile("cuni_asincrono: " . $cuni_asincrono);
			// \app\models\Utilities::putMessageLogFile("cuni_asincrono['cuni_calificacion']: " . $cuni_asincrono['cuni_calificacion']);
			if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Asincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_sinc = $val[13];
			if ($cal_sinc > $cuni_sincrono['cuni_calificacion'] || $cal_sinc < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Sincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_cuest = $val[14];
			if ($cal_cuest > $cuni_cuestionarios['cuni_calificacion'] || $cal_cuest < 0) {
				$noalumno .= $nombre . " (la nota 'Cuestionarios 4P' está mal colocada), ";
				return 0;
			}

			$cal_aut = $val[15];
			if ($cal_aut > $cuni_autonoma['cuni_calificacion'] || $cal_aut < 0) {
				$noalumno .= $nombre . " (la nota 'Autónomas 6P' está mal colocada), ";
				return 0;
			}

			$cal_eval = $val[16];
			if ($cal_eval > $cuni_evaluacion['cuni_calificacion'] || $cal_eval < 0) {
				\app\models\Utilities::putMessageLogFile("noalumno: " . $noalumno);
				\app\models\Utilities::putMessageLogFile("nombre: " . $nombre);
				$noalumno .= $nombre . " (la nota 'Evaluación Par. 6P' está mal colocada), ";
				\app\models\Utilities::putMessageLogFile("noalumno: " . $noalumno);
				return 0;
			}

			$cal_calif = $cal_asin + $cal_sinc + $cal_cuest + $cal_aut + $cal_eval;
		}

		// \app\models\Utilities::putMessageLogFile("cal_calif: " . $cal_calif);

		// $cal_prom = $val[19]; // No usada

		$mod_cab_cal = new CabeceraCalificacion();

		// ecun_id es igual que ecal_id para parciales en Unidad Académica de Grado
		$ecun_id = $ecal_id;

		// Si el estudiaante ya tiene calificación, actualizar, sino, insertar
		$has_cabecera_calificacion = CabeceraCalificacion::find()->where(['est_id' => $est_id, 'asi_id' => $asi_id, 'paca_id' => $paca_id, 'ecun_id' => $ecun_id, 'pro_id' => $pro_id])->asArray()->all();

		if (empty($has_cabecera_calificacion)) {
			// INSERT
			$ccal_id = $mod_cab_cal->insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id, $cal_calif);

			$idDetAsin = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$idDetSinc = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_sincrono['cuni_id'], $cal_sinc);
			$idDetAut = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_cuestionarios['cuni_id'], $cal_cuest);
			$idDetEval = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_autonoma['cuni_id'], $cal_aut);
			$idDetExam = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_evaluacion['cuni_id'], $cal_eval);
		} else {
			// UPDATE
			$cabecera = $has_cabecera_calificacion[0];
			$ccal_id = $cabecera['ccal_id'];

			$mod_cab_cal->actualizarCabeceraCalificacion($ccal_id, $cal_calif);

			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_sincrono['cuni_id'], $cal_sinc);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_cuestionarios['cuni_id'], $cal_cuest);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_autonoma['cuni_id'], $cal_aut);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_evaluacion['cuni_id'], $cal_eval);
		}

		return 1;
	}

	/**
	 * Modularización de la función de calificaciones. Para estudiantes de Grado modalidad Presencial
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	private function calificarGradoPresencial($mod_id, $uaca_id, $ecal_id, $val, $nombre, &$noalumno, $paca_id, $est_id, $pro_id, $asi_id) {
		// Componentes Unidades
		$cuni_asincrono = ComponenteUnidad::find()->where(['com_id' => 8, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_sincrono = ComponenteUnidad::find()->where(['com_id' => 9, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_cuestionarios = ComponenteUnidad::find()->where(['com_id' => 6, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();

		// Tomar las calificaciones dependiendo del parcial elegido
		if ($ecal_id == 1) {
			// 1er Parcial
			$cal_asin = $val[5];
			if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Asincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_sinc = $val[6];
			if ($cal_sinc > $cuni_sincrono['cuni_calificacion'] || $cal_sinc < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Sincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_cuest = $val[7];
			if ($cal_cuest > $cuni_cuestionarios['cuni_calificacion'] || $cal_cuest < 0) {
				$noalumno .= $nombre . " (la nota 'Cuestionarios 4P' está mal colocada), ";
				return 0;
			}

			$cal_calif = $cal_asin + $cal_sinc + $cal_cuest;
		} elseif ($ecal_id == 2) {
			// 2do Parcial
			$cal_asin = $val[12];
			if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Asincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_sinc = $val[13];
			if ($cal_sinc > $cuni_sincrono['cuni_calificacion'] || $cal_sinc < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Sincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_cuest = $val[14];
			if ($cal_cuest > $cuni_cuestionarios['cuni_calificacion'] || $cal_cuest < 0) {
				$noalumno .= $nombre . " (la nota 'Cuestionarios 4P' está mal colocada), ";
				return 0;
			}

			$cal_calif = $cal_asin + $cal_sinc + $cal_cuest;
		}

		// $cal_prom = $val[19]; // No usada

		$mod_cab_cal = new CabeceraCalificacion();

		// ecun_id es igual que ecal_id para parciales en Unidad Académica de Grado
		$ecun_id = $ecal_id;

		// Si el estudiaante ya tiene calificación, actualizar, sino, insertar
		$has_cabecera_calificacion = CabeceraCalificacion::find()->where(['est_id' => $est_id, 'asi_id' => $asi_id, 'paca_id' => $paca_id, 'ecun_id' => $ecun_id, 'pro_id' => $pro_id])->asArray()->all();

		if (empty($has_cabecera_calificacion)) {
			// INSERT
			$ccal_id = $mod_cab_cal->insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id, $cal_calif);

			$idDetAsin = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$idDetSinc = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_sincrono['cuni_id'], $cal_sinc);
			$idDetAut = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_cuestionarios['cuni_id'], $cal_cuest);
		} else {
			// UPDATE
			$cabecera = $has_cabecera_calificacion[0];
			$ccal_id = $cabecera['ccal_id'];

			$mod_cab_cal->actualizarCabeceraCalificacion($ccal_id, $cal_calif);

			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_sincrono['cuni_id'], $cal_sinc);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_cuestionarios['cuni_id'], $cal_cuest);
		}

		return 1;
	}

	/**
	 * Modularización de la función de calificaciones. Para estudiantes de Grado modalidad Semi-Presencial
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	private function calificarGradoSemiPresencial($mod_id, $uaca_id, $ecal_id, $val, $nombre, &$noalumno, $paca_id, $est_id, $pro_id, $asi_id) {
		// Componentes Unidades
		$cuni_asincrono = ComponenteUnidad::find()->where(['com_id' => 8, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_sincrono = ComponenteUnidad::find()->where(['com_id' => 9, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_cuestionarios = ComponenteUnidad::find()->where(['com_id' => 6, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();

		// Tomar las calificaciones dependiendo del parcial elegido
		if ($ecal_id == 1) {
			// 1er Parcial
			$cal_asin = $val[5];
			if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Asincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_sinc = $val[6];
			if ($cal_sinc > $cuni_sincrono['cuni_calificacion'] || $cal_sinc < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Sincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_cuest = $val[7];
			if ($cal_cuest > $cuni_cuestionarios['cuni_calificacion'] || $cal_cuest < 0) {
				$noalumno .= $nombre . " (la nota 'Cuestionarios 4P' está mal colocada), ";
				return 0;
			}

			$cal_calif = $cal_asin + $cal_sinc + $cal_cuest;
		} elseif ($ecal_id == 2) {
			// 2do Parcial
			$cal_asin = $val[12];
			if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Asincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_sinc = $val[13];
			if ($cal_sinc > $cuni_sincrono['cuni_calificacion'] || $cal_sinc < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Sincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_cuest = $val[14];
			if ($cal_cuest > $cuni_cuestionarios['cuni_calificacion'] || $cal_cuest < 0) {
				$noalumno .= $nombre . " (la nota 'Cuestionarios 4P' está mal colocada), ";
				return 0;
			}

			$cal_calif = $cal_asin + $cal_sinc + $cal_cuest;
		}

		// $cal_prom = $val[19]; // No usada

		$mod_cab_cal = new CabeceraCalificacion();

		// ecun_id es igual que ecal_id para parciales en Unidad Académica de Grado
		$ecun_id = $ecal_id;

		// Si el estudiaante ya tiene calificación, actualizar, sino, insertar
		$has_cabecera_calificacion = CabeceraCalificacion::find()->where(['est_id' => $est_id, 'asi_id' => $asi_id, 'paca_id' => $paca_id, 'ecun_id' => $ecun_id, 'pro_id' => $pro_id])->asArray()->all();

		if (empty($has_cabecera_calificacion)) {
			// INSERT
			$ccal_id = $mod_cab_cal->insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id, $cal_calif);

			$idDetAsin = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$idDetSinc = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_sincrono['cuni_id'], $cal_sinc);
			$idDetAut = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_cuestionarios['cuni_id'], $cal_cuest);
		} else {
			// UPDATE
			$cabecera = $has_cabecera_calificacion[0];
			$ccal_id = $cabecera['ccal_id'];

			$mod_cab_cal->actualizarCabeceraCalificacion($ccal_id, $cal_calif);

			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_sincrono['cuni_id'], $cal_sinc);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_cuestionarios['cuni_id'], $cal_cuest);
		}

		return 1;
	}

	/**
	 * Modularización de la función de calificaciones. Para estudiantes de Grado modalidad Distancia
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	private function calificarGradoDistancia($mod_id, $uaca_id, $ecal_id, $val, $nombre, &$noalumno, $paca_id, $est_id, $pro_id, $asi_id) {
		// Componentes Unidades
		$cuni_asincrono = ComponenteUnidad::find()->where(['com_id' => 1, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_sincrono = ComponenteUnidad::find()->where(['com_id' => 2, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_cuestionarios = ComponenteUnidad::find()->where(['com_id' => 5, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_autonoma = ComponenteUnidad::find()->where(['com_id' => 6, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();

		// Tomar las calificaciones dependiendo del parcial elegido
		if ($ecal_id == 1) {
			// 1er Parcial
			$cal_asin = $val[5];
			if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Asincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_sinc = $val[6];
			if ($cal_sinc > $cuni_sincrono['cuni_calificacion'] || $cal_sinc < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Sincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_cuest = $val[7];
			if ($cal_cuest > $cuni_cuestionarios['cuni_calificacion'] || $cal_cuest < 0) {
				$noalumno .= $nombre . " (la nota 'Cuestionarios 4P' está mal colocada), ";
				return 0;
			}

			$cal_aut = $val[8];
			if ($cal_aut > $cuni_autonoma['cuni_calificacion'] || $cal_aut < 0) {
				$noalumno .= $nombre . " (la nota 'Autónomas 6P' está mal colocada), ";
				return 0;
			}

			$cal_calif = $cal_asin + $cal_sinc + $cal_cuest + $cal_aut;
		} elseif ($ecal_id == 2) {
			// 2do Parcial
			$cal_asin = $val[12];
			if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Asincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_sinc = $val[13];
			if ($cal_sinc > $cuni_sincrono['cuni_calificacion'] || $cal_sinc < 0) {
				$noalumno .= $nombre . " (la nota 'Act. Sincro 2P' está mal colocada), ";
				return 0;
			}

			$cal_cuest = $val[14];
			if ($cal_cuest > $cuni_cuestionarios['cuni_calificacion'] || $cal_cuest < 0) {
				$noalumno .= $nombre . " (la nota 'Cuestionarios 4P' está mal colocada), ";
				return 0;
			}

			$cal_aut = $val[15];
			if ($cal_aut > $cuni_autonoma['cuni_calificacion'] || $cal_aut < 0) {
				$noalumno .= $nombre . " (la nota 'Autónomas 6P' está mal colocada), ";
				return 0;
			}

			$cal_calif = $cal_asin + $cal_sinc + $cal_cuest + $cal_aut;
		}

		// $cal_prom = $val[19]; // No usada

		$mod_cab_cal = new CabeceraCalificacion();

		// ecun_id es igual que ecal_id para parciales en Unidad Académica de Grado
		$ecun_id = $ecal_id;

		// Si el estudiaante ya tiene calificación, actualizar, sino, insertar
		$has_cabecera_calificacion = CabeceraCalificacion::find()->where(['est_id' => $est_id, 'asi_id' => $asi_id, 'paca_id' => $paca_id, 'ecun_id' => $ecun_id, 'pro_id' => $pro_id])->asArray()->all();

		if (empty($has_cabecera_calificacion)) {
			// INSERT
			$ccal_id = $mod_cab_cal->insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id, $cal_calif);

			$idDetAsin = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$idDetSinc = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_sincrono['cuni_id'], $cal_sinc);
			$idDetAut = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_cuestionarios['cuni_id'], $cal_cuest);
			$idDetEval = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_autonoma['cuni_id'], $cal_aut);
		} else {
			// UPDATE
			$cabecera = $has_cabecera_calificacion[0];
			$ccal_id = $cabecera['ccal_id'];

			$mod_cab_cal->actualizarCabeceraCalificacion($ccal_id, $cal_calif);

			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_sincrono['cuni_id'], $cal_sinc);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_cuestionarios['cuni_id'], $cal_cuest);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_autonoma['cuni_id'], $cal_aut);
		}

		return 1;
	}

	/**
	 * Modularización de la función de calificaciones. Para estudiantes de Posgrado modalidad Online
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	private function calificarPosgradoOnline($mod_id, $uaca_id, $ecal_id, $val, $nombre, &$noalumno, $paca_id, $est_id, $pro_id, $asi_id, $ecun_id_posgrado) {
		// Componentes Unidades
		$cuni_autonoma = ComponenteUnidad::find()->where(['com_id' => 4, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_eval = ComponenteUnidad::find()->where(['com_id' => 5, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_asincrono = ComponenteUnidad::find()->where(['com_id' => 1, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_examen = ComponenteUnidad::find()->where(['com_id' => 6, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();

		$cal_aut = $val[5];
		if ($cal_aut > $cuni_autonoma['cuni_calificacion'] || $cal_aut < 0) {
			$noalumno .= $nombre . " (la nota 'Autónomas Talleres (5 Puntos)' está mal colocada), ";
			return 0;
		}

		$cal_eval = $val[6];
		if ($cal_eval > $cuni_eval['cuni_calificacion'] || $cal_eval < 0) {
			$noalumno .= $nombre . " (la nota 'Evaluaciones 1 Punto' está mal colocada), ";
			return 0;
		}

		$cal_asin = $val[7];
		if ($cal_asin > $cuni_asincrono['cuni_calificacion'] || $cal_asin < 0) {
			$noalumno .= $nombre . " (la nota 'Asíncronas % Avance 1 Punto' está mal colocada), ";
			return 0;
		}

		$cal_exam = $val[8];
		if ($cal_exam > $cuni_examen['cuni_calificacion'] || $cal_exam < 0) {
			$noalumno .= $nombre . " (la nota 'Examen 3 Puntos' está mal colocada), ";
			return 0;
		}
		// $cal_calif = ($cal_asin * 0.1) + ($cal_cuest * 0.2) + ($cal_aut * 0.2) + ($cal_final * 0.5);
		$cal_calif = $cal_aut + $cal_eval + $cal_asin + $cal_exam;

		$mod_cab_cal = new CabeceraCalificacion();

		// Si el estudiaante ya tiene calificación, actualizar, sino, insertar
		$has_cabecera_calificacion = CabeceraCalificacion::find()->where(['est_id' => $est_id, 'asi_id' => $asi_id, 'paca_id' => $paca_id, 'pro_id' => $pro_id])->asArray()->all();

		if (empty($has_cabecera_calificacion)) {
			// INSERT
			$ccal_id = $mod_cab_cal->insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id_posgrado, $cal_calif);

			$idDetAsin = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_autonoma['cuni_id'], $cal_aut);
			$idDetAut = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_eval['cuni_id'], $cal_eval);
			$idDetEval = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$idDetFinal = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_examen['cuni_id'], $cal_exam);
		} else {
			// UPDATE
			$cabecera = $has_cabecera_calificacion[0];

			$ccal_id = $cabecera['ccal_id'];

			$mod_cab_cal->actualizarCabeceraCalificacion($ccal_id, $cal_calif);

			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_autonoma['cuni_id'], $cal_aut);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_eval['cuni_id'], $cal_eval);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_asincrono['cuni_id'], $cal_asin);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_examen['cuni_id'], $cal_exam);
		}

		return 1;
	}

	/**
	 * Modularización de la función de calificaciones. Para estudiantes de Posgrado modalidad Presencial
	 * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
	 * @param
	 * @return
	 */
	private function calificarPosgradoPresencial($mod_id, $uaca_id, $ecal_id, $val, $nombre, &$noalumno, $paca_id, $est_id, $pro_id, $asi_id, $ecun_id_posgrado) {
		// Componentes Unidades
		$cuni_talleres = ComponenteUnidad::find()->where(['com_id' => 7, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_deberes = ComponenteUnidad::find()->where(['com_id' => 8, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();
		$cuni_examen = ComponenteUnidad::find()->where(['com_id' => 6, 'uaca_id' => $uaca_id, 'mod_id' => $mod_id])->asArray()->one();

		$cal_talleres = $val[5];
		if ($cal_talleres > $cuni_talleres['cuni_calificacion'] || $cal_talleres < 0) {
			$noalumno .= $nombre . " (la nota 'Talleres (4 Puntos)' está mal colocada), ";
			return 0;
		}

		$cal_deberes = $val[6];
		if ($cal_deberes > $cuni_deberes['cuni_calificacion'] || $cal_deberes < 0) {
			$noalumno .= $nombre . " (la nota 'Deberes (3 Puntos)' está mal colocada), ";
			return 0;
		}

		$cal_exam = $val[7];
		if ($cal_exam > $cuni_examen['cuni_calificacion'] || $cal_exam < 0) {
			$noalumno .= $nombre . " (la nota 'Examen (3 Puntos)' está mal colocada), ";
			return 0;
		}

		$cal_calif = $cal_talleres + $cal_deberes + $cal_exam;

		$mod_cab_cal = new CabeceraCalificacion();

		// Si el estudiaante ya tiene calificación, actualizar, sino, insertar
		$has_cabecera_calificacion = CabeceraCalificacion::find()->where(['est_id' => $est_id, 'asi_id' => $asi_id, 'paca_id' => $paca_id, 'pro_id' => $pro_id])->asArray()->all();

		if (empty($has_cabecera_calificacion)) {
			// INSERT
			$ccal_id = $mod_cab_cal->insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id_posgrado, $cal_calif);

			$idDetAsin = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_talleres['cuni_id'], $cal_talleres);
			$idDetAut = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_deberes['cuni_id'], $cal_deberes);
			$idDetEval = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $cuni_examen['cuni_id'], $cal_exam);
		} else {
			// UPDATE
			$cabecera = $has_cabecera_calificacion[0];

			$ccal_id = $cabecera['ccal_id'];

			$mod_cab_cal->actualizarCabeceraCalificacion($ccal_id, $cal_calif);

			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_talleres['cuni_id'], $cal_talleres);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_deberes['cuni_id'], $cal_deberes);
			$mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $cuni_examen['cuni_id'], $cal_exam);
		}

		return 1;
	}

	public function actionDownloadplantillagradoonline() {
		$file = 'plantilla_grado_online.xlsx';
		$route = str_replace("../", "", $file);
		$url_file = Yii::$app->basePath . "/uploads/calificaciones/plantilla/" . $route;
		$arrfile = explode(".", $url_file);
		$typeImage = $arrfile[count($arrfile) - 1];
		if (file_exists($url_file)) {
			if (strtolower($typeImage) == "xlsx") {
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Cache-Control: private', false);
				header("Content-type: application/xlsx");
				header('Content-Disposition: attachment; filename="plantilla_calificacion_GRADO_ONLINE' . '.xlsx";');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($url_file));
				readfile($url_file);
				exit();
			}
		}
	}

	public function actionDownloadplantillaposgradoonline() {
		$file = 'plantilla_posgrado_online.xlsx';
		$route = str_replace("../", "", $file);
		$url_file = Yii::$app->basePath . "/uploads/calificaciones/plantilla/" . $route;
		$arrfile = explode(".", $url_file);
		$typeImage = $arrfile[count($arrfile) - 1];
		if (file_exists($url_file)) {
			if (strtolower($typeImage) == "xlsx") {
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Cache-Control: private', false);
				header("Content-type: application/xlsx");
				header('Content-Disposition: attachment; filename="plantilla_calificacion_POSGRADO_ONLINE' . '.xlsx";');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($url_file));
				readfile($url_file);
				exit();
			}
		}
	}

	public function actionDownloadplantillaposgradopresencial() {
		$file = 'plantilla_posgrado_presencial.xlsx';
		$route = str_replace("../", "", $file);
		$url_file = Yii::$app->basePath . "/uploads/calificaciones/plantilla/" . $route;
		$arrfile = explode(".", $url_file);
		$typeImage = $arrfile[count($arrfile) - 1];
		if (file_exists($url_file)) {
			if (strtolower($typeImage) == "xlsx") {
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Cache-Control: private', false);
				header("Content-type: application/xlsx");
				header('Content-Disposition: attachment; filename="plantilla_calificacion_POSGRADO_PRESENCIAL' . '.xlsx";');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($url_file));
				readfile($url_file);
				exit();
			}
		}
	}

	/**
	 * Determina si el usuario logueado tiejne privilegios más avanzados
	 * @author  Jorge Paladines analista.desarrollo@uteg.edu.ec
	 * @param
	 * @return
	 */
	private function isAdmin($usu_id) {
		$con = Yii::$app->db_academico;
		$sql1 = "SELECT * FROM db_asgard.usua_grol_eper where usu_id = $usu_id";
		$comando = $con->createCommand($sql1);
		$res = $comando->queryOne();

		$sql2 = "SELECT * FROM db_asgard.grup_rol where grol_id = " . $res['grol_id'];
		$comando = $con->createCommand($sql2);
		$res2 = $comando->queryOne();

		if ($res2['gru_id'] == 6) {
			return true;
		} else {
			return false;
		}
	}

	public function actionExportpdfclfc() {
		// accion para descargar pdf de materias registradas

		$report = new ExportFile(); //Instancio Objeto de ExportaFile

		$this->view->title = Academico::t("academico", "Acta de calificaciones"); // Titulo del reporte

		//$matriculacion_model = new Matriculacion();
		$cabeceraCalificacion = new CabeceraCalificacion();

		$data = Yii::$app->request->get();

		$periodo = $data['paca'];
		$unidad = $data['unidad'];
		$materia = $data['materia'];
		$profesor = $data['profesor'];
		$modalidad = $data['modalidad'];
		/* return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $ron_id); */

		//$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteSearch($unidad,$periodo,$materia,$profesor);
		$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllSearch($unidad, $periodo, $materia, $profesor, $modalidad, null, true);

		// \app\models\Utilities::putMessageLogFile('arr_estudiante: ' . print_r($arr_estudiante, true));

		$profesor_data = (new Profesor())->getProfesoresDist($profesor);

		// \app\models\Utilities::putMessageLogFile('profesor_data: ' . print_r($profesor_data, true));

		//$data_student = $matriculacion_model->getDataStudenbyRonId($ron_id);
		//$dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id);
		/*$dataProvider = new ArrayDataProvider([
	            'key' => 'Ids',
	            'allModels' => $arr_estudiante,
	            'pagination' => [
	                'pageSize' => Yii::$app->params["pageSize"],
	            ],
	            'sort' => [
	                'attributes' => ["Subject"],
	            ],
	        ]);
*/
		$report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
		$report->createReportPdf(
			$this->render('acta_body', [
				"model" => $arr_estudiante,
				"profesor_data" => $profesor_data,
				//"data_student" => $data_student,
			])
		);
		$report->mpdf->Output('Acta_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
		return;
	}

	public function actionActivacron() {
		$datacron = Yii::$app->request->get();
//var_dump($datacron["fecha"]);
		//var_dump($datacron["cronid"]);
		$data3 = ($datacron["moda"]);
		$data2 = ($datacron["fecha"]);
		$data1 = ($datacron["cronid"]);
		$cabeceraCalificacion = new CabeceraCalificacion();
		$cronactive = $cabeceraCalificacion->activateCron($data1, $data2);
		//var_dump($cronactive);

		return $this->redirect(['educativa', 'paca' => $cronactive['croe_paca_id'], 'unidad' => $cronactive['croe_uaca_id'], 'modalidad' => $data3, 'parcial' => $cronactive['croe_parcial']]);

	}

	public function actionEducativa() {
		$grupo_model = new Grupo();
		$arr_parcial = array(0 => '[ Elija Parcial ]', 1 => 'Parcial 1', 2 => 'Parcial 2', 3 => 'Notas Finales');
		$cabeceraCalificacion = new CabeceraCalificacion();
		$user_usermane = Yii::$app->session->get("PB_username");
//$user_usermane = 'carlos.carrera@mbtu.us';//Yii::$app->session->get("PB_username");
		$arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);

		/*
			 if ( in_array(['id' => '6'], $arr_grupos) ||
			             in_array(['id' => '7'], $arr_grupos) ||
			             in_array(['id' => '8'], $arr_grupos)
		*/

		$mod_periodos = new PeriodoAcademico();
		$mod_unidad = new UnidadAcademica();
		$mod_modalidad = new Modalidad();
		//$mod_crones  = new CronEducativa();
		$arr_periodos = $mod_periodos->consultarPeriodosActivosmalla();
		$arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
		$arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);

		$data = Yii::$app->request->get();

		if ($data = Yii::$app->request->get()) {
			$cabeceraCalificacion = new CabeceraCalificacion();
			$arr_crones = $cabeceraCalificacion->getallmods($data['paca'], $data['unidad'], $data['modalidad'], $data['parcial']);
			$modalidades = $data['modalidad'];
//var_dump($arr_crones);
			//return $this->redirect('index');

/*
["croe_id"]=> string(1) "1"
["croe_mod_id"]=> string(1) "1"
["croe_paca_id"]=> string(2) "15"
["croe_uaca_id"]=> string(1) "1"
["croe_fecha_ejecucion"]=> string(19) "2021-10-20 09:49:02"
["croe_exec"]=> string(1) "1" */

			$dataProvider = new ArrayDataProvider([
				'key' => 'croe_id',
				'allModels' => $arr_crones,
				'pagination' => [
					'pageSize' => Yii::$app->params["pageSize"],
				],
				'sort' => [
					'attributes' => [
					],
				],
			]);

			return $this->render('educativa', [
				'model' => $dataProvider,
				'modeldata' => $arr_crones,
				'arr_periodos' => ArrayHelper::map(array_merge($arr_periodos), "id", "nombre"),
				'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_ninteres), "id", "name"),
				'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_modalidad), "id", "name"),
				'modalidades' => $modalidades,
				'arr_parcial' => $arr_parcial,

			]);

		} Else { $arr_crones = Array();}

		return $this->render('educativa', [
			'model' => $arr_crones,
			'arr_periodos' => ArrayHelper::map(array_merge($arr_periodos), "id", "nombre"),
			'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_ninteres), "id", "name"),
			'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_modalidad), "id", "name"),
			'arr_parcial' => $arr_parcial,
		]);

/*
} else {

return $this->redirect('index');
} */
	}

  public function actionTransferiraulas(){

    $mod_periodos    = new PeriodoAcademico(); 
    $mod_unidad     = new UnidadAcademica();
    $mod_modalidad  = new Modalidad();
    $mod_calificacion  = new CabeceraCalificacion();
     $arr_parcial = array(0 => '[ Elija Parcial ]',1 => 'Parcial 1',2 => 'Parcial 2',3 => 'Supletorio/Mejoramiento');

    $arr_periodos = $mod_periodos->consultarPeriodosActivosmalla();
    $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
    $arr_unidades = array(0 => '[ Elija Unidad Académica ]',1 => 'Grado',2 => 'Posgrado');
    $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);    


        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
             if (isset($data["getteraulas"])) {
                $arr_aulas =  $mod_calificacion->consultarAulas($data['paca_id'], $data['uaca_id'], $data['mod_id']);
                $message = array("arr_aulas" => $arr_aulas);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

        }

       $data = Yii::$app->request->get();
       if ($data = Yii::$app->request->get()){ 
    
      $arr_aula = $mod_calificacion->consultarAulas($data['paca'], $data['unidad'], $data['modalidad'], $data['aula'], $data['parcial']);

       } else {

    $arr_aula = $mod_calificacion->consultarAulas();

       }
    
       $dataProvider = new ArrayDataProvider([
            'key' => 'cedu_id',
            'allModels' => $arr_aula,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                ],
            ],
        ]);

     return $this->render('educativa_aulas', [
                    'model' => $dataProvider,                    
                    'arr_periodos' => ArrayHelper::map(array_merge($arr_periodos), "id", "nombre"),
                    //'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_unidad), "id", "name"),
                     'arr_unidad' => $arr_unidades, 
                      'arr_parcial' => $arr_parcial, 
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_modalidad), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_modalidad), "id", "name"),
                    'arr_aula' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Todos")]], $arr_aula), "id", "name"),
                    'paca' => $data['paca'], 
                    'unidad' => $data['unidad'], 
                    'modalidad' => $data['modalidad'], 
                    'aula' => $data['aula'], 
                    'parcial' => $data['parcial'], 
        ]);



}

  public function actionTransferer($eduasid,$parcial){

 try {

     $mod_calificacion  = new CabeceraCalificacion();
     $mod_asistencia  = new CabeceraAsistencia();
     $arr_usuarios = $mod_calificacion->consultarUsuarios($eduasid,$parcial);
     $parciales=$parcial; if ($parcial > 2){$parcial=2;}

 if (count($arr_usuarios) > 0) {  

 	          $wsdl = 'https://campusvirtual.uteg.edu.ec/soap/?wsdl=true';
         
         $client = new \SoapClient($wsdl, [
         "soap_version" => SOAP_1_1,
         "login"    => "webservice", 
         "password" => "WxrrvTt8",
            "trace"    => 1,
         "exceptions" => 0,
         "cache_wsdl" => WSDL_CACHE_NONE,
         "stream_context" => stream_context_create(
         [
         'ssl' => [
         'verify_peer' => false,
         'verify_peer_name' => true,
         'allow_self_signed' => true,
         ]])]);

         $client->setCredentials("webservice", 
                          "WxrrvTt8",
                          "basic");
    
               for ($u = 0; $u < count($arr_usuarios); $u++) {  

  $daca_id = $arr_usuarios[$u]['daca_id'];
            $cedu_asi_id = $arr_usuarios[$u]['cedu_asi_id']; 
            $uaca_id = $arr_usuarios[$u]['uaca_id'];
            $paca_id = $arr_usuarios[$u]['paca_id'];
            $mod_id = $arr_usuarios[$u]['mod_id']; 
            $mpp_id = $arr_usuarios[$u]['mpp_id'];
            $pro_id = $arr_usuarios[$u]['pro_id'];
            $asi_id = $arr_usuarios[$u]['asi_id'];
            $est_id = $arr_usuarios[$u]['est_id'];
            $uedu_usuario = $arr_usuarios[$u]['uedu_usuario'];
            $per_id = $arr_usuarios[$u]['per_id'];
            $ced_id = $arr_usuarios[$u]['per_cedula'];
            $maes_id = $arr_usuarios[$u]['maes_id'];

          $method = 'obtener_avance_usuarios'; 
       
          $args = Array(
                 'id_grupo' =>$eduasid, 
                 'id_usuario' =>$uedu_usuario,
                );

   try {

            $advancer = $client->__call( $method, Array( $args ) );
              while (openssl_error_string()) {
            $advancer = $client->__call( $method, Array( $args ) );
            }

           $arrayadv = json_decode(json_encode($advancer), true);

            $sincro=$arrayadv['usuarios']['avance'];
            $asiste=$arrayadv['usuarios']['avance'];

              }   finally { $hasadvance = True; }

          $method = 'obtener_notas_calificaciones'; 
           
             try {
            $response = $client->__call( $method, Array( $args ) );
             while (openssl_error_string()) {
            $response = $client->__call( $method, Array( $args ) );	
            }

              }     finally { $hasresponse = True; }


     if (isset($response->categorias)) { 


     $valuated = $response->categorias;

       $arraycat = json_decode(json_encode($valuated), true);


            $arrayl2 = array_column($arraycat, 'id_categoria');
            $arraydata1 = array();
            $arraydata2 = array();
            $arraydata3 = array();
            $grades=0;

            if (isset($arraycat[0]['id_categoria'])) { 
for ($i = 0; $i < count($arrayl2); $i++) {


   
    if (isset($arraycat[$i]['calificaciones']['notas'][0]['id_nota'])) { 
         $arrayl4 = array_column($arraycat[$i]['calificaciones']['notas'], 'id_nota'); 
              for ($k = 0; $k < count($arrayl4); $k++) {  
                  $allcode = $mod_calificacion->getallcode($i,-1,$k);
                    eval ($allcode);
                    $grades++;
              }
    }  else {
                if (isset($arraycat[$i]['calificaciones']['notas'])) {
                $allcode = $mod_calificacion->getallcode($i,-1,-1);   
                eval ($allcode);
                $grades++;
                }           

            }

    if (isset($arraycat[$i]['calificaciones'][0]['notas'] )) { 
    $arrayl3 = array_column($arraycat[$i]['calificaciones'], 'id_calificacion'); // --DEBUG!!!! 
    for ($j = 0; $j < count($arrayl3); $j++) {


            if (isset($arraycat[$i]['calificaciones'][$j]['notas'][0]['id_nota'])) {
                 $arrayl4 = array_column($arraycat[$i]['calificaciones'][$j]['notas'], 'id_nota'); 
                     for ($k = 0; $k < count($arrayl4); $k++) {
                         $allcode = $mod_calificacion->getallcode($i,$j,$k);    
                         eval ($allcode);
                          $grades++;
                     }
             } else {

                        if (isset($arraycat[$i]['calificaciones'][$j]['notas'])) {
                        $allcode = $mod_calificacion->getallcode($i,$j,-1);    
                        eval ($allcode);
                $grades++;
                         }
                    } 
    }  

    }

} 

}

 if (isset($arraycat['id_categoria'])) { 
   
if (isset($arraycat['calificaciones']['notas'][0]['id_nota'])) {
$arrayl4 = array_column($arraycat['calificaciones']['notas'], 'id_nota'); 
for ($k = 0; $k < count($arrayl4); $k++) {


$allcode = $mod_calificacion->getallcode(-1,-1,$k);    
eval ($allcode);
$grades++;

}} else {

if (isset($arraycat['calificaciones']['notas'])) {

$allcode = $mod_calificacion->getallcode(-1,-1,-1);   
eval ($allcode);
$grades++;


}


}

 if (isset($arraycat['calificaciones'][0]['notas'])) { 
    $arrayl3 = array_column($arraycat['calificaciones'], 'id_calificacion');  

 for ($j = 0; $j < count($arrayl3); $j++) {
if (isset($arraycat['calificaciones'][$j]['notas'][0]['id_nota'])) {
$arrayl4 = array_column($arraycat['calificaciones'][$j]['notas'], 'id_nota'); 
for ($k = 0; $k < count($arrayl4); $k++) {
  
$allcode = $mod_calificacion->getallcode(-1,$j,$k);    
eval ($allcode);
$grades++;


}} else {

if (isset($arraycat['calificaciones'][$j]['notas'])) {
    
$allcode = $mod_calificacion->getallcode(-1,$j,-1); 
eval ($allcode);
$grades++;

}


}
    } }

 }}  //response categorias

if (isset($arraydata3[0])) {  

$componentes = $mod_calificacion->getescalas($uaca_id,$mod_id,$parciales);
$cabeceras = $mod_calificacion->getcabeceras($est_id,$asi_id,$paca_id,$parciales);
if ($cabeceras == Null){ 
$cabeceras = $mod_calificacion->putcabeceras($est_id,$asi_id,$paca_id,$parciales,$pro_id);
$cabeceras = $mod_calificacion->getcabeceras($est_id,$asi_id,$paca_id,$parciales);}

if ($mod_id==1 AND $uaca_id ==1){

if (isset($sincro)) {
$fsincro = (float)$sincro;
$fsincro = $fsincro/50;
$comp_cuni_id1 = 2 ;
$comp_cuni_id2 = 7;
}

if ( $fsincro > 0 ){
$dcalificacion = (float)$fsincro;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id1);
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id1 ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 

if ( $fsincro > 0 ){
$dcalificacion = (float)$fsincro;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id2);
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id2 ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 

$cabecerasasi = $mod_asistencia->getcasistencia($est_id,$asi_id,$paca_id,$parciales);
if ($cabecerasasi == Null){ 
$cabecerasasi = $mod_asistencia->putcasistencia($est_id,$asi_id,$paca_id,$parciales,$pro_id);
$cabecerasasi = $mod_asistencia->getcasistencia($est_id,$asi_id,$paca_id,$parciales);}

if ( $asiste > 0 ){
$dasistencia = $asiste;
$detallesasi = $mod_asistencia->getdasistencia($cabecerasasi[0]['casi_id'],$parciales);
if ($detallesasi == Null) {
$detalles = $mod_asistencia->putdasistencia($cabecerasasi[0]['casi_id'],$parciales,$dasistencia); 
}else {
if ($detallesasi[0]['dasi_usuario_creacion'] == '1' AND $detallesasi[0]['dasi_fecha_modificacion'] ==Null){
$detallesup = $mod_asistencia->updatedasitencia($detallesasi[0]['dasi_id'],$dasistencia); 
}
}
} 

$ucasi = $mod_asistencia->updatecasistencia($cabecerasasi[0]['casi_id']); 

for ($it = 0; $it < count($arraydata3); $it++) { 

$comp_evaluacion1 = 0.00;$comp_autonoma1 = 0.00;$comp_examen1 = 0.00;
$comp_foro1 = 0.00 ; $comp_sincrona1 = 0.00 ; 
 $comp_evaluacion2 = 0.00; $comp_autonoma2 = 0.00; $comp_examen2 = 0.00;
 $comp_foro2 = 0.00 ; $comp_sincrona2 = 0.00 ; 
$comp_examen3 = 0.00;$comp_supletorio3 = 0.00;$comp_mejoramiento3 = 0.00;

$data01= $mod_calificacion->getparamcategoria($arraydata1[$it]['nombre']); 
$data02= $mod_calificacion->getparamitem($arraydata2[$it]['nombre']); 
$data03= $mod_calificacion->getnota($arraydata3[$it]['nota']);

 if (isset($semanaexa1)) {} else {

 if(isset($data02['examen']) ) { 
$semanaexa1 = $data01['semana'];
if ($semanaexa1 <= 5 AND $parciales == 1){ 

         $comp_examen1 = (float)$data03; 
          $comp_cuni_id = 5;

           $dcalificacion = (float)$comp_examen1;
          $detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
      if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_calificacion'] < $dcalificacion ){
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
}
}   
}


if (isset($semanaexa2)) {} else {

 if(isset($data02['examen']) ) { 
$semanaexa2 = $data01['semana'];
if ($semanaexa2 >= 6 AND $parciales == 2){ 

         $comp_examen2 = (float)$data03; 
          $comp_cuni_id = 10;
          print_r("parcial 2 examen ES ");
           print_r($comp_examen2);

           $dcalificacion = (float)$comp_examen2;
          $detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
      if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_calificacion'] < $dcalificacion ){
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
}
}   
}

 if(isset($data01['parcial'])) {


if ($parciales == 1 AND $data01['parcial']==1) {
//print_r("======= Inicia proceso parcial 1 ===========");
//print_r(count($componentes));
for ($il = 0; $il < count($componentes); $il++) {
/*print_r("componente: ");
print_r($componentes[$il]['com_id']);
print_r("evaluacion: ");
print_r(isset($data02['evaluacion']));
    print_r("nota");
print_r($data03);*/

    if ($componentes[$il]['com_id']== 3 AND isset($data02['evaluacion'])) {    //COMP_EVALUACION ol

    $comp_evaluacion1 = (float)$comp_evaluacion1 + (float)$data03; 
    $comp_cuni_id = $componentes[$il]['cuni_id'];
       print_r("comp_evaluacion1 ES  ");
      print_r($comp_evaluacion1);

    }

     if ($componentes[$il]['com_id']== 4 AND isset($data02['taller'])) {    //COMP_AUTONOMA ol
        
     $comp_autonoma1 = (float)$comp_autonoma1+ (float)$data03;print_r("SUMADO:"); 
     $comp_cuni_id = $componentes[$il]['cuni_id'];
    print_r("comp_autonoma1 ES ");
      print_r($comp_autonoma1);

    }

        if ($componentes[$il]['com_id']== 1 AND isset($data02['foro'])) {    //COMP_FORO ol
        
     $comp_foro1 = (float)$comp_foro1+ (float)$data03; //print_r("SUMADO:"); 
     $comp_cuni_id = $componentes[$il]['cuni_id'];
    print_r("comp_foro1 ES ");
     print_r($comp_foro1);

    }

        if ($componentes[$il]['com_id']== 2 AND isset($data02['sincrona'])) {    //COMP_SINCRONA ol
        
     $comp_sincrona1 = (float)$comp_sincrona1+ (float)$data03; //print_r("SUMADO:"); 
     $comp_cuni_id = $componentes[$il]['cuni_id'];
    //print_r("comp_sincrona1 ES ");
    //  print_r($comp_sincrona1);

    }


}
if ( $comp_evaluacion1 > 0 ){
$dcalificacion = (float)$comp_evaluacion1;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 

if ( $comp_autonoma1 > 0 ){
$dcalificacion = (float)$comp_autonoma1;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id); 
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 


} //print_r("======= Fin proceso parcial 1 ===========");


if ($parciales == 2 AND $data01['parcial']==2) {
   

for ($il = 0; $il < count($componentes); $il++) {


    if ($componentes[$il]['com_id']== 3 AND isset($data02['evaluacion'] )) {    //COMP_EVALUACION ol

     $comp_evaluacion2 = (float)$comp_evaluacion2 + (float)$data03;  
      $comp_cuni_id = $componentes[$il]['cuni_id'];

    }

     if ($componentes[$il]['com_id']== 4 AND isset($data02['taller'] )) {    //COMP_AUTONOMA ol
        
         $comp_autonoma2 = (float)$comp_autonoma2 + (float)$data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];

    }

         if ($componentes[$il]['com_id']== 1 AND isset($data02['foro'] )) {    //COMP_FORO ol
        
         $comp_foro2 = (float)$comp_foro2 + (float)$data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];

    }

             if ($componentes[$il]['com_id']== 2 AND isset($data02['sincrona'] )) { //COMP_SINCRONA ol
        
         $comp_sincrona2 = (float)$comp_sincrona2 + (float)$data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];

    }


      if ($componentes[$il]['com_id']== 10 AND isset($data02['examen'] )) {    //COMP_EXAMEN ol
        
         if ($data03 > $comp_examen2){

         $comp_examen2 = (float)$data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];
        
        }

    }



}
if ( $comp_evaluacion2 > 0 ){
$dcalificacion = (float)$comp_evaluacion2;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion);  
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 


if ( $comp_autonoma2 > 0 ){
$dcalificacion = (float)$comp_autonoma2;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id); 
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 

} //print_r("======= Fin proceso parcial 2 ===========");
 }


$ucab = $mod_calificacion->updatecabeceras($cabeceras[0]['ccal_id']); 
if ($maes_id != null){ 
$upro = $mod_calificacion->updatepromedio($maes_id, $paca_id);
}



} //all degrees items
} //by moduaca


if ($mod_id==1 AND $uaca_id ==2){
for ($it = 0; $it < count($arraydata3); $it++) { 

$comp_evaluacion1 = 0.00;$comp_autonoma1 = 0.00;$comp_examen1 = 0.00;
$comp_foro1 = 0.00 ; $comp_sincrona1 = 0.00 ; 
 $comp_evaluacion2 = 0.00; $comp_autonoma2 = 0.00; $comp_examen2 = 0.00;
 $comp_foro2 = 0.00 ; $comp_sincrona2 = 0.00 ; 
$comp_examen3 = 0.00;$comp_supletorio3 = 0.00;$comp_mejoramiento3 = 0.00;

$data01= $mod_calificacion->getparamcategoria($arraydata1[$it]['nombre']); 
$data02= $mod_calificacion->getparamitem($arraydata2[$it]['nombre']); 
$data03= $mod_calificacion->getnota($arraydata3[$it]['nota']);

 if (isset($semanaexa1)) {} else {

 if(isset($data02['examen']) ) { 
$semanaexa1 = $data01['semana'];
if ($semanaexa1 <= 5 AND $parciales == 1){ 

         $comp_examen1 = (float)$data03; 
          $comp_cuni_id = 5;

           $dcalificacion = (float)$comp_examen1;
          $detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
      if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_calificacion'] < $dcalificacion ){
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
}
}   
}


if (isset($semanaexa2)) {} else {

 if(isset($data02['examen']) ) { 
$semanaexa2 = $data01['semana'];
if ($semanaexa2 >= 6 AND $parciales == 2){ 

         $comp_examen2 = (float)$data03; 
          $comp_cuni_id = 10;
          print_r("parcial 2 examen ES ");
           print_r($comp_examen2);

           $dcalificacion = (float)$comp_examen2;
          $detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
      if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_calificacion'] < $dcalificacion ){
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
}
}   
}

 if(isset($data01['parcial'])) {


if ($parciales == 1 AND $data01['parcial']==1) {
//print_r("======= Inicia proceso parcial 1 ===========");
//print_r(count($componentes));
for ($il = 0; $il < count($componentes); $il++) {
/*print_r("componente: ");
print_r($componentes[$il]['com_id']);
print_r("evaluacion: ");
print_r(isset($data02['evaluacion']));
    print_r("nota");
print_r($data03);*/

    if ($componentes[$il]['com_id']== 3 AND isset($data02['evaluacion'])) {    //COMP_EVALUACION ol

    $comp_evaluacion1 = (float)$comp_evaluacion1 + (float)$data03; 
    $comp_cuni_id = $componentes[$il]['cuni_id'];
       print_r("comp_evaluacion1 ES  ");
      print_r($comp_evaluacion1);

    }

     if ($componentes[$il]['com_id']== 4 AND isset($data02['taller'])) {    //COMP_AUTONOMA ol
        
     $comp_autonoma1 = (float)$comp_autonoma1+ (float)$data03;print_r("SUMADO:"); 
     $comp_cuni_id = $componentes[$il]['cuni_id'];
    print_r("comp_autonoma1 ES ");
      print_r($comp_autonoma1);

    }


}
if ( $comp_evaluacion1 > 0 ){
$dcalificacion = (float)$comp_evaluacion1;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 

if ( $comp_autonoma1 > 0 ){
$dcalificacion = (float)$comp_autonoma1;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id); 
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 


} //print_r("======= Fin proceso parcial 1 ===========");


if ($parciales == 2 AND $data01['parcial']==2) {
   

for ($il = 0; $il < count($componentes); $il++) {


    if ($componentes[$il]['com_id']== 8 AND isset($data02['evaluacion'] )) {    //COMP_EVALUACION ol

     $comp_evaluacion2 = (float)$comp_evaluacion2 + (float)$data03;  
      $comp_cuni_id = $componentes[$il]['cuni_id'];

    }

     if ($componentes[$il]['com_id']== 9 AND isset($data02['taller'] )) {    //COMP_AUTONOMA ol
        
         $comp_autonoma2 = (float)$comp_autonoma2 + (float)$data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];

    }

      if ($componentes[$il]['com_id']== 10 AND isset($data02['examen'] )) {    //COMP_EXAMEN ol
        
         if ($data03 > $comp_examen2){

         $comp_examen2 = (float)$data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];
        
        }

    }



}
if ( $comp_evaluacion2 > 0 ){
$dcalificacion = (float)$comp_evaluacion2;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion);  
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 


if ( $comp_autonoma2 > 0 ){
$dcalificacion = (float)$comp_autonoma2;
$detalles = $mod_calificacion->getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id); 
if ($detalles == Null) {
$detalles = $mod_calificacion->putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = $mod_calificacion->updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
$bt= $mod_calificacion->putbitacora($detalles[0]['dcal_id'],$dcalificacion);
}
}
} 

} //print_r("======= Fin proceso parcial 2 ===========");
 }


$ucab = $mod_calificacion->updatecabeceras($cabeceras[0]['ccal_id']); 
if ($maes_id != null){ 
$upro = $mod_calificacion->updatepromedio($maes_id, $paca_id);
}



} //all degrees items
} //by moduaca


} // weget grades
}}  // all students



    }  catch (PDOException $e) {
           putMessageLogFile('Error: ' . $e->getMessage());
           exit; }

           return $this->redirect(['registro', 
    'periodo' => $paca_id, 
    'unidad' => $uaca_id,
    'modalidad' => $mod_id,
    'profesor' => $pro_id,
    'parcial' => $parcial]);

 }

}
