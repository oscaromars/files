<?php

namespace app\modules\academico\controllers;

use app\models\ExportFile;
use app\models\Utilities;
use app\modules\academico\models\AreaConocimientoCampoAmplio;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\DistributivoAcademicoEstudiante;
use app\modules\academico\models\DistributivoAcademicoHorario;
use app\modules\academico\models\DistributivoCabecera;
use app\modules\academico\models\MateriaParaleloPeriodo;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\ParaleloPromocionPrograma;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\PeriodoAcademicoMensualizado;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\Planificacion;
use app\modules\academico\models\Profesor;
use app\modules\academico\models\TipoDistributivo;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;

academico::registerTranslations();
admision::registerTranslations();

class DistributivoacademicoController extends \app\components\CController {

	public function paralelo() {
		return [
			'0' => Yii::t("formulario", "Seleccionar"),
			'1' => Yii::t("formulario", "Paralelo 1"),
			'2' => Yii::t("formulario", "Paralelo 2"),
			'3' => Yii::t("formulario", "Paralelo 3"),
			'4' => Yii::t("formulario", "Paralelo 4"),
		];
	}

	private function estados() {
		return [
			'0' => Yii::t("formulario", "Seleccionar"),
			'1' => Yii::t("formulario", "Revisado"),
			'2' => Yii::t("formulario", "Aprobado"),
			'3' => Yii::t("formulario", "No aprobado"),
		];
	}

	public function actionIndex() {
		$per_id = @Yii::$app->session->get("PB_perid");
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$model = NULL;
		$model_posgrado = NULL;
		$distributivo_model = new DistributivoAcademico();
		$mod_modalidad = new Modalidad();
		$mod_unidad = new UnidadAcademica();
		$mod_periodo = new PeriodoAcademicoMetIngreso();
		$mod_asignatura = new Asignatura();

		$data = Yii::$app->request->get();

		if ($data['PBgetFilter']) {
			$arrSearch['search'] = $data['search'];
			$arrSearch['unidad'] = $data['unidad'];
			$arrSearch['modalidad'] = $data['modalidad'];
			$arrSearch['periodo'] = $data['periodo'];
			$arrSearch['materia'] = $data['materia'];
			$arrSearch['jornada'] = $data['jornada'];
			/*$unidad = (isset($data['unidad']) && $data['unidad'] > 0) ? $data['unidad'] : NULL;
				            $modalidad = (isset($data['modalidad']) && $data['modalidad'] > 0) ? $data['modalidad'] : NULL;
				            $periodo = (isset($data['periodo']) && $data['periodo'] > 0) ? $data['periodo'] : NULL;
				            $materia = (isset($data['materia']) && $data['materia'] > 0) ? $data['materia'] : NULL;
				            $jornada = (isset($data['jornada']) && $data['jornada'] > 0) ? $data['jornada'] : NULL;

			*/
			// enviar el array de la busqueda
			$model = $distributivo_model->getListadoDistributivoGrado($arrSearch);

			//JLC: 19 ABRIL 2022
			/*return $this->renderPartial('index-grid', [
				"model" => $model,
			]);*/
			//JLC: 19 ABRIL 2022
		} else {
			$model = $distributivo_model->getListadoDistributivoGrado();
			//$model_posgrado = $distributivo_model->getListadoDistributivoPosgrado();
		}
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if (isset($data["getmodalidad"])) {
				$modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
				$message = array("modalidad" => $modalidad);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getjornada"])) {
				$jornada = $distributivo_model->getJornadasByUnidadAcad($data["uaca_id"], $data["mod_id"]);
				$message = array("jornada" => $jornada);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["gethorario"])) {
				$horario = $distributivo_model->getHorariosByUnidadAcad($data["uaca_id"], $data["mod_id"], $data['jornada_id']);
				$message = array("horario" => $horario);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}
		$arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
		$arr_asignatura = $mod_asignatura->getAsignaturaUnidad($arr_unidad[0]['id']);
		$arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
		$arr_jornada = $distributivo_model->getJornadasByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"]);
		$arr_periodo = $mod_periodo->consultarPeriodoAcademico();
		$periodo = $mod_periodo->consultarPeriodoActivos();
		return $this->render('index', [
			'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
			'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
			'mod_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_periodo), "id", "name"),
			'mod_materias' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_asignatura), "id", "name"),
			'model' => $model,
			//'model_posgrado' => $model_posgrado,
			'mod_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_jornada), "id", "name"),
			'periodo' => ArrayHelper::map(array_merge($periodo), "id", "nombre"),

		]);
	}

	public function actionNew() {
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$mod_modalidad = new Modalidad();
		$mod_unidad = new UnidadAcademica();
		$mod_periodo = new Planificacion();
		$mod_asignatura = new Asignatura();
		$mod_profesor = new Profesor();
		$distributivo_model = new DistributivoAcademico();
		$mod_periodoActual = new PeriodoAcademicoMetIngreso();
		$model_periodomensualizado = new PeriodoAcademicoMensualizado();
		$model_area_conocimiento = new AreaConocimientoCampoAmplio();
		$mod_tipo_distributivo = new TipoDistributivo();
		$arr_periodoActual = $mod_periodoActual->consultarPeriodoActivos();
		$mod_horario = new DistributivoAcademicoHorario();
		$paralelo = new MateriaParaleloPeriodo();
		$mod_paraleloprograma = new ParaleloPromocionPrograma();
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			//\app\models\Utilities::putMessageLogFile('uaca_id'. $data["uaca_id"]);
			if (isset($data["getmodalidad"])) {
				$modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
				$periodomensualizado = $model_periodomensualizado->consultarMesDistributivoPos($data['uaca_id'], $data['paca_id']);
				$areaconocimiento = $model_area_conocimiento->consultarAreaConocimiento();
				$message = array("modalidad" => $modalidad, "periodomensualizado" => $periodomensualizado);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getperiodo"])) {
				$mod_periodoActual = new PeriodoAcademico();
				$periodo = $mod_periodoActual->consultarPeriodoAcademico();
				$message = array("periodo" => $periodo);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getjornada"])) {
				$jornada = $distributivo_model->getJornadasByUnidadAcad($data["uaca_id"], $data["mod_id"]);
				$message = array("jornada" => $jornada);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			// AQUI CAMBIAR YA FILTRAR POR EL PARALELO SU HORARIO EN materia_paralelo_periodo (daho_id)
			// El PARALELO YA TRAE EL MPP_ID, ENVIAR ESE Y FILTAR EL CURSO Q CORRESPONDE
			// PARA GRADO
			if (isset($data["gethorario"])) {
				//$horario = $distributivo_model->getHorariosByUnidadAcad($data["uaca_id"], $data["mod_id"], $data['jornada_id']);
				// SI UNIDAD ACADEMICA ES 1 GRADO
				if ($data["uaca_id"] == 1) {
					$horario = $distributivo_model->getHorariosmppid($data["mpp_id"]);
				}
				//SI UNIDAD ACADEMICA ES 2 POSGRADO
				elseif ($data["uaca_id"] == 2) {
					//\app\models\Utilities::putMessageLogFile('uaca_id'. $data["uaca_id"]);
					//\app\models\Utilities::putMessageLogFile('meun_id'. $data["meun_id"]);
					$horario = $mod_horario->consultaHorariosxuacaymeun($data["uaca_id"], $data["maca_id"]);
				} else {
					\app\models\Utilities::putMessageLogFile('unidad academico no es grado ni posgrado');
				}

				$message = array("horario" => $horario);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			// fin if gethorario
			if (isset($data["getasignatura"])) {
				$asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif_slim($data["periodo_id"], $data["mod_id"]);
				$message = array("asignatura" => $asignatura);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getasignaturapos"])) {
				$asignatura = $mod_asignatura->getAsignaturaPosgrado($data["maca_id"]);
				$message = array("asignaturapos" => $asignatura);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}

			if (isset($data["getestudio"])) {
				$estudio = $distributivo_model->getModalidadEstudio($data["uaca_id"], $data["mod_id"]);
				$message = array("carrera" => $estudio);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}

			if (isset($data["getparalelo"])) {
				\app\models\Utilities::putMessageLogFile('materias ' . $data["asig_id"][0]);
				$paralelos = $paralelo->getParalelosAsignatura($data["paca_id"], $data["mod_id"], $data["asig_id"][0]);
				\app\models\Utilities::putMessageLogFile('paralelos ' . $paralelos);
				$message = array("paralelo" => $paralelos);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}

			if (isset($data["getparaleloposgrado"])) {
				\app\models\Utilities::putMessageLogFile('mod_id ' . $data["mod_id"]);
				\app\models\Utilities::putMessageLogFile('uaca_id ' . $data["uaca_id"]);
				//$paralelos = $paralelo->getParalelosAsignatura($data["paca_id"],$data["mod_id"],$data["asig_id"]);
				//$paralelos =$mod_horario->consultarParaleloHorario($data["hora_id"]);
				$paralelos = $mod_paraleloprograma->getParalelosprograma($data["maca_id"], $data["mod_id"]);
				$message = array("paralelo" => $paralelos);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}

		$arr_profesor = $mod_profesor->getProfesores();
		$arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
		$arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], $emp_id);
		$arr_periodo = $mod_periodoActual->consultarPeriodoAcademico();
		$arr_periodomensualizado = $model_periodomensualizado->consultarMesDistributivoPos($arr_unidad[1]["id"], $arr_periodo);
		$arr_areaconocimiento = $model_area_conocimiento->consultarAreaConocimiento();
		$arr_jornada = $distributivo_model->getJornadasByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"]);
		$arr_asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif(0, 0);
		$arr_horario = $distributivo_model->getHorariosByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"], $arr_jornada[0]["id"]);
		$model = $distributivo_model->getDistribAcadXprofesorXperiodo(0, 0);
		$arr_tipo_distributivo = $mod_tipo_distributivo->consultarTipoDistributivo(null);
		$arr_programa = $distributivo_model->getModalidadEstudio(2, 1);
		$arr_paralelos = $paralelo->getParalelosAsignatura(0, 0, 0);
		return $this->render('new', [
			'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Select")]], $arr_profesor), "Id", "Nombres"),
			'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
			'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
			'arr_periodomensualizado' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodomensualizado), "id", "name"),
			'arr_areaconocimiento' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_areaconocimiento), "id", "name"),
			'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodo), "id", "name"),
			'arr_materias' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
			'arr_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_jornada), "id", "name"),
			'arr_horario' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_horario), "id", "name"),
			'arr_tipo_asignacion' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_tipo_distributivo), "id", "name"),
			//'arr_paralelo' => $this->paralelo(),
			'arr_paralelo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_paralelos), "id", "name"),
			'model' => $model,
			'arr_periodoActual' => $arr_periodoActual,
			'arr_programa' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_programa), "id", "name"),
		]);
	}

	public function actionTest() {

		$searchModel = new \app\modules\academico\models\DistributivoAcademicoSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('test', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionSave() {
		$usu_id = @Yii::$app->session->get("PB_iduser");
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		$distributivo_model = new DistributivoAcademico();
		$distributivo_cab = new DistributivoCabecera();

		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$con = \Yii::$app->db_academico;
			$transaction = $con->beginTransaction();
			try {
				\app\models\Utilities::putMessageLogFile('existe validacion ');
				$pro_id = $data['profesor'];
				$paca_id = $data['periodo'];
				$dts = (isset($data["grid_docencia"]) && $data["grid_docencia"] != "") ? $data["grid_docencia"] : NULL;
				$datos = json_decode($dts);
				//Validar que no exista en distributivo_cabecera porque para crear no debe existir.
				$cons = $distributivo_cab->existeDistCabecera($paca_id, $pro_id);
				if ($cons == 0) {
					\app\models\Utilities::putMessageLogFile('existe validacion 1' . $dts);
					if (isset($datos)) {
						$valida = 1;
						for ($i = 0; $i < sizeof($datos); $i++) {
							// Valida que no exista el mismo tipo de distributivo en otro profesor.
							if (($datos[$i]->tasi_id == 1) || ($datos[$i]->tasi_id == 7)) {
								$dataExisOtro = $distributivo_model->existsDistribAcadOtroProf($datos[$i]->uni_id, $datos[$i]->tasi_id, $datos[$i]->asi_id, $paca_id, $datos[$i]->hor_id, $datos[$i]->par_id);

								if (!empty($dataExisOtro)) {
									//   \app\models\Utilities::putMessageLogFile('existe validacion 2');
									$valida = 0;
									$transaction->rollback();
									$message = array(
										"wtmessage" => academico::t('distributivoacademico', 'Ya se encuentra asignada la materia ' . $dataExisOtro["asignatura"] . ' en el docente ' . $dataExisOtro["profesor"] . " en el mismo horario y paralelo."),
										"title" => Yii::t('jslang', 'Error'),
									);
									return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
								}
							}
						}
					}

					if ($valida == 1) {
						$estado = '1';
						\app\models\Utilities::putMessageLogFile('existe validacion 2' . $paca_id);
						// $cab = $distributivo_cab->insertarDistributivoCab($paca_id, $pro_id);
						$distributivo_cab->paca_id = $paca_id;
						$distributivo_cab->pro_id = $pro_id;
						$distributivo_cab->dcab_estado_revision = '0';
						$distributivo_cab->dcab_fecha_registro = date("Y-m-d H:i:s");
						$distributivo_cab->dcab_usuario_ingreso = @Yii::$app->session->get("PB_iduser");
						$distributivo_cab->dcab_estado = $estado;
						$distributivo_cab->dcab_estado_logico = '1';

						if ($distributivo_cab->save()) {
							for ($i = 0; $i < sizeof($datos); $i++) {
								/* $model = new DistributivoAcademico();
									                                  // Grabar en distributivo acad??mico
									                                  //  \app\models\Utilities::putMessageLogFile('tasi_id' . ' ' . $datos[$i]->tasi_id);
									                                  $model->paca_id = $paca_id;
									                                  $model->pro_id = $pro_id;
									                                  $model->dcab_id = $distributivo_cab->primaryKey;
									                                  $model->tdis_id = $datos[$i]->tdas_id;

									                                  // \app\models\Utilities::putMessageLogFile('pedro:' . $datos[$i]->tdis_id);

									                                  if ($datos[$i]->uni_id) {
									                                  $model->uaca_id = $datos[$i]->uni_id;
									                                  \app\models\Utilities::putMessageLogFile('$datos[$i]->uni_id:' . $datos[$i]->uni_id);
									                                  }
									                                  if ($datos[$i]->asi_id) {
									                                  \app\models\Utilities::putMessageLogFile('$datos[$i]->asi_id:' . $datos[$i]->asi_id);
									                                  $model->asi_id = $datos[$i]->asi_id;
									                                  }
									                                  if ($datos[$i]->jor_id) {
									                                  $model->daca_jornada = $datos[$i]->jor_id;
									                                  \app\models\Utilities::putMessageLogFile('$datos[$i]->jor_id:' . $datos[$i]->jor_id);
									                                  }
									                                  if ($datos[$i]->mod_id) {
									                                  $model->mod_id = $datos[$i]->mod_id;
									                                  \app\models\Utilities::putMessageLogFile('$datos[$i]->mod_id:' . $datos[$i]->mod_id);
									                                  }
									                                  if ($datos[$i]->daho_id) {
									                                  $model->daho_id = $datos[$i]->daho_id;
									                                  \app\models\Utilities::putMessageLogFile('$datos[$i]->daho_id:' . $datos[$i]->daho_id);
									                                  }
									                                  if ($datos[$i]->par_id) {
									                                  \app\models\Utilities::putMessageLogFile('$datos[$i]->par_id:' . $datos[$i]->par_id);
									                                  $model->dhpa_id = $datos[$i]->par_id;

									                                  }
									                                  if ($datos[$i]->num_estudiantes) {
									                                  $model->daca_num_estudiantes_online = $datos[$i]->num_estudiantes;
									                                  }
									                                  if ($datos[$i]->hor_id) {
									                                  \app\models\Utilities::putMessageLogFile('$datos[$i]->dhpa_id:' . $datos[$i]->hor_id);
									                                  $model->daca_horario = $datos[$i]->hor_id;

									                                  }

									                                  $model->daca_fecha_registro = date("Y-m-d H:i:s");
									                                  $model->daca_usuario_ingreso = @Yii::$app->session->get("PB_iduser");
									                                  $model->daca_estado = $estado;
									                                  $model->daca_estado_logico = $estado;

								*/
								// \app\models\Utilities::putMessageLogFile('model::' . $model->getErrors());
								//  \app\models\Utilities::putMessageLogFile('datos '.$datos);
								$res = $distributivo_model->insertarDistributivoAcademico($i, $datos, $pro_id, $paca_id, $distributivo_cab->primaryKey);
								if ($res) {
									$exito = '1';
								} else {
									$exito = '0';
								}
							}
						}
					}
				} else {
					$transaction->rollback();
					$message = array(
						"wtmessage" => academico::t('distributivoacademico', 'Ya existe distributivo para este profesor en el per??odo actual'),
						"title" => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
				}
				if ($exito == '1') {
					$transaction->commit();
					$message = array(
						"wtmessage" => Yii::t("notificaciones", "La infomaci??n ha sido grabada. "),
						"title" => Yii::t('jslang', 'Success'),
					);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
				} else {
					$transaction->rollback();
					$message = array(
						"wtmessage" => Yii::t('notificaciones', 'Su informaci??n no ha sido grabada. Por favor intente nuevamente o contacte al ??rea de DesarrolloXXX.'),
						"title" => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
				}
			} catch (Exception $e) {
				\app\models\Utilities::putMessageLogFile('error: ' . $e);
				$transaction->rollback();
				$message = array(
					"wtmessage" => Yii::t('notificaciones', 'Su informaci??n no ha sido grabada. Por favor intente nuevamente o contacte al ??rea de Desarrollo.' . $m),
					"title" => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

	public function actionActualizar() {
		\app\models\Utilities::putMessageLogFile('entra: ');
		$usu_id = @Yii::$app->session->get("PB_iduser");
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		$distributivo_model = new DistributivoAcademico();
		$distributivo_cab = new DistributivoCabecera();

		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$con = \Yii::$app->db_academico;
			$transaction = $con->beginTransaction();

			try {
				$pro_id = $data['profesor'];
				$paca_id = $data['periodo'];
				$pcab_id = $data['id'];
				$dts = (isset($data["grid_docencia"]) && $data["grid_docencia"] != "") ? $data["grid_docencia"] : NULL;
				$datos = json_decode($dts);

				//Validar que no exista en distributivo_cabecera porque para crear no debe existir.
				//$cons = $distributivo_cab->existeDistCabecera($paca_id,$pro_id);
				//   $cons = $distributivo_cab->EliminaexisteDistCabecera($pcab_id, $pro_id);

				$ok = '1';

				if ($ok == 1) {
					if (isset($datos)) {
						$valida = 1;
						for ($i = 0; $i < sizeof($datos); $i++) {
							// Valida que no exista el mismo tipo de distributivo en otro profesor.
							if (!$datos[$i]->daca_id) {
								if ($datos[$i]->tasi_id == 1) {

									$dataExisOtro = $distributivo_model->existsDistribAcadOtroProf($datos[$i]->uni_id, $datos[$i]->tasi_id, $datos[$i]->asi_id, $paca_id, $datos[$i]->hor_id, $datos[$i]->par_id);

									if (!empty($dataExisOtro)) {
										$valida = 0;
										$transaction->rollback();
										$message = array(
											"wtmessage" => academico::t('distributivoacademico', 'Ya se encuentra asignada la materia ' . $dataExisOtro["asignatura"] . ' en el docente ' . $dataExisOtro["profesor"] . " en el mismo horario y paralelo."),
											"title" => Yii::t('jslang', 'Error'),
										);
										return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
									}
								}
							}
						}
					}
					//ha cumplido todas las validaciones entonces graba.
					$ok_datos = 0;
					if ($valida == 1) {
						// $cab = $distributivo_cab->insertarDistributivoCab($paca_id, $pro_id);
						//  if ($cab > 0) {
						for ($i = 0; $i < sizeof($datos); $i++) {
							// Grabar en distributivo acad??mico
							if (!$datos[$i]->daca_id) {
								\app\models\Utilities::putMessageLogFile("paca_id:" . $paca_id);
								$res = $distributivo_model->insertarDistributivoAcademico($i, $datos, $pro_id, $paca_id, $pcab_id);
								$discab = DistributivoCabecera::findOne(['dcab_id' => $pcab_id, 'dcab_estado' => 1, 'dcab_estado_logico' => 1]);
								if ($discab['dcab_estado_revision'] != 0) {
									$dis_cab = $distributivo_model->actualizarDistributivocabecera($pcab_id);
									//$bitacora_revision = $distributivo_cab->insertarBitacoraDCAB($discab['dcab_id'], $discab['dcab_estado_revision']);
								}
								$ok_datos = 1;
								if ($res > 0) {
									$exito = '1';
								} else {
									$exito = '0';
								}
							}
						}
						//}
					}
				} else {
					$transaction->rollback();
					$message = array(
						"wtmessage" => academico::t('distributivoacademico', 'Ya existe distributivo para este profesor en el per??odo actual'),
						"title" => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
				}
				if ($ok_datos == 1) {
					if ($exito == '1') {
						$transaction->commit();
						$message = array(
							"wtmessage" => Yii::t("notificaciones", "La infomaci??n ha sido grabada. "),
							"title" => Yii::t('jslang', 'Success'),
						);
						return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
					} else {
						$transaction->rollback();
						$message = array(
							"wtmessage" => Yii::t('notificaciones', 'Su informaci??n no ha sido grabada. Por favor intente nuevamente o contacte al ??rea de DesarrolloXXX.'),
							"title" => Yii::t('jslang', 'Error'),
						);
						return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
					}
				} else {
					$message = array(
						"wtmessage" => academico::t('distributivoacademico', 'No existe data para ingresar.'),
						"title" => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);

				}
			} catch (Exception $e) {
				\app\models\Utilities::putMessageLogFile('error: ' . $e);
				$transaction->rollback();
				$message = array(
					"wtmessage" => Yii::t('notificaciones', 'Su informaci??n no ha sido grabada. Por favor intente nuevamente o contacte al ??rea de Desarrollo.' . $m),
					"title" => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

	public function actionView($id) {
		Utilities::putMessageLogFile('view:$id ' . $id);
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$distributivo_model = new DistributivoAcademico();
		$distributivo_cab = new DistributivoCabecera();
		$mod_periodoActual = new PeriodoAcademico();
		$mod_profesor = new Profesor();
		$mod_unidad = new UnidadAcademica();
		$mod_modalidad = new Modalidad();
		$mod_periodo = new Planificacion();
		$mod_asignatura = new Asignatura();
		$mod_tipo_distributivo = new TipoDistributivo();

		$resCab = $distributivo_cab->obtenerDatosCabecera($id);

		$arr_distributivo = $distributivo_model->getListarDistribProf($id);

		$arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual();
		$arr_profesor = $mod_profesor->getProfesores();
		$arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
		$arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], $emp_id);
		$arr_periodo = $mod_periodo->getPeriodos_x_modalidad($arr_modalidad[0]["id"]);
		$arr_jornada = $distributivo_model->getJornadasByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"]);
		$arr_asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif(0, 0);
		$arr_horario = $distributivo_model->getHorariosByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"], $arr_jornada[0]["id"]);
		$arr_tipo_distributivo = $mod_tipo_distributivo->consultarTipoDistributivo();
		$arr_programa = $distributivo_model->getModalidadEstudio(2, 1);

		return $this->render('view', [
			'arr_cabecera' => $resCab,
			'arr_detalle' => $arr_distributivo,
			'arr_estados' => $this->estados(),
			'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Select")]], $arr_profesor), "Id", "Nombres"),
			'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
			'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
			'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodo), "id", "name"),
			'arr_materias' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
			'arr_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_jornada), "id", "name"),
			'arr_horario' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_horario), "id", "name"),
			'arr_tipo_asignacion' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_tipo_distributivo), "id", "name"),
			'arr_paralelo' => $this->paralelo(),
			'arr_periodoActual' => $arr_periodoActual,
			'arr_programa' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_programa), "id", "name"),
		]);
	}

	public function actionEditcab($id) {
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$mod_modalidad = new Modalidad();
		$mod_unidad = new UnidadAcademica();
		$mod_periodo = new Planificacion();
		$mod_asignatura = new Asignatura();
		$mod_profesor = new Profesor();
		$distributivo_cab = new DistributivoCabecera();
		$distributivo_model = new DistributivoAcademico();
		$mod_periodoActual = new PeriodoAcademicoMetIngreso();
		$model_periodomensualizado = new PeriodoAcademicoMensualizado();
		$model_area_conocimiento = new AreaConocimientoCampoAmplio();
		$mod_periodoActual = new PeriodoAcademico();
		$mod_tipo_distributivo = new TipoDistributivo();
		$arr_periodoActual = $mod_periodoActual->getPeriodoAcademico();

		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if (isset($data["getmodalidad"])) {
				$modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
				$periodomensualizado = $model_periodomensualizado->consultarMesDistributivoPos($data['uaca_id'], $data['paca_id']);
				$areaconocimiento = $model_area_conocimiento->consultarAreaConocimiento();
				$message = array("modalidad" => $modalidad, "periodomensualizado" => $periodomensualizado);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getperiodo"])) {
				$periodo = $mod_periodo->getPeriodos_x_modalidad($data["mod_id"]);
				$message = array("periodo" => $periodo);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getjornada"])) {
				$jornada = $distributivo_model->getJornadasByUnidadAcad($data["uaca_id"], $data["mod_id"]);
				$message = array("jornada" => $jornada);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["gethorario"])) {
				$horario = $distributivo_model->getHorariosByUnidadAcad($data["uaca_id"], $data["mod_id"], $data['jornada_id']);
				$message = array("horario" => $horario);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getasignatura"])) {
				$asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif($data["periodo_id"], $data["mod_id"]);
				$message = array("asignatura" => $asignatura);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getasignaturapos"])) {
				$asignatura = $mod_asignatura->getAsignaturaPosgrado($data["maca_id"]);
				$message = array("asignaturapos" => $asignatura);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}

			if (isset($data["getestudio"])) {
				$estudio = $distributivo_model->getModalidadEstudio($data["uaca_id"], $data["mod_id"]);
				$message = array("carrera" => $estudio);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}

		$resCab = $distributivo_cab->obtenerDatosCabecera($id);
		$arr_distributivo = $distributivo_model->getListarDistribProf($id);
		$arr_profesor = $mod_profesor->getProfesores();
		$arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
		$arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], $emp_id);
		$arr_periodo = $mod_periodoActual->getPeriodoAcademico();
		$arr_periodomensualizado = $model_periodomensualizado->consultarMesDistributivoPos($arr_unidad[1]["id"], $arr_periodo);
		$arr_areaconocimiento = $model_area_conocimiento->consultarAreaConocimiento();
		$arr_jornada = array(); //$distributivo_model->getJornadasByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"]);
		$arr_asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif(0, 0);
		$arr_horario = $distributivo_model->getHorariosByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"], $arr_jornada[0]["id"]);
		$model = $distributivo_model->getDistribAcadXprofesorXperiodo(0, 0);
		$arr_tipo_distributivo = $mod_tipo_distributivo->consultarTipoDistributivo($resCab["ddoc_id"]);
		$arr_programa = $distributivo_model->getModalidadEstudio(2, 1);

		return $this->render('editcab', [
			'arr_cabecera' => $resCab,
			'arr_detalle' => $arr_distributivo,
			'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Select")]], $arr_profesor), "Id", "Nombres"),
			'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
			'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
			'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodo), "id", "name"),
			'arr_periodomensualizado' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodomensualizado), "id", "name"),
			'arr_areaconocimiento' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_areaconocimiento), "id", "name"),
			'arr_materias' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
			'arr_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_jornada), "id", "name"),
			'arr_horario' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_horario), "id", "name"),
			'arr_tipo_asignacion' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_tipo_distributivo), "id", "name"),
			'arr_paralelo' => $this->paralelo(),
			'model' => $model,
			'arr_periodoActual' => $arr_periodoActual,
			'arr_programa' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_programa), "id", "name"),
		]);
	}

	public function actionEdit($id) {
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$mod_modalidad = new Modalidad();
		$mod_unidad = new UnidadAcademica();
		$mod_periodo = new PeriodoAcademicoMetIngreso();
		$distributivo_model = DistributivoAcademico::findOne($id);
		$arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
		$arr_modalidad = $mod_modalidad->consultarModalidad($distributivo_model->uaca_id, $emp_id);
		$arr_periodo = $mod_periodo->consultarPeriodoAcademico();
		$mod_asignatura = Asignatura::findAll(['asi_estado' => 1, 'asi_estado_logico' => 1]);
		$arr_jornada = array(); // $distributivo_model->getJornadasByUnidadAcad($distributivo_model->uaca_id, $distributivo_model->mod_id);
		$mod_profesor = new Profesor();
		$arr_profesor = $mod_profesor->getProfesores();
		$arr_horario = array(); //$distributivo_model->getHorariosByUnidadAcad($distributivo_model->uaca_id, $distributivo_model->mod_id, $arr_jornada[0]["id"]);
		$mod_horario = DistributivoAcademicoHorario::findOne($distributivo_model->daho_id);
		$horario_values = ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_horario), "id", "name");
		return $this->render('edit', [
			'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Grid")]], $arr_profesor), "Id", "Nombres"),
			'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
			'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
			'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_periodo), "id", "name"),
			'arr_materias' => ArrayHelper::map(array_merge([["asi_id" => "0", "asi_nombre" => Yii::t("formulario", "Grid")]], $mod_asignatura), "asi_id", "asi_nombre"),
			'arr_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_jornada), "id", "name"),
			'arr_horario' => $horario_values,
			'pro_id' => $distributivo_model->pro_id,
			'uaca_id' => $distributivo_model->uaca_id,
			'mod_id' => $distributivo_model->mod_id,
			'paca_id' => $distributivo_model->paca_id,
			'asi_id' => $distributivo_model->asi_id,
			'horario' => array_search($mod_horario->daho_horario, $horario_values),
			'jornada' => $mod_horario->daho_jornada,
			'daca_id' => $id,
		]);
	}

	public function actionUpdate() {
		$usu_id = @Yii::$app->session->get("PB_iduser");
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			try {
				$daca_id = $data['id'];
				$pro_id = $data['profesor'];
				$uaca_id = $data['unidad'];
				$mod_id = $data['modalidad'];
				$paca_id = $data['periodo'];
				$jornada_id = $data['jornada'];
				$horario = $data['horario'];
				$materia = $data['materia'];
				$distributivo_model = DistributivoAcademico::findOne($daca_id);
				$dataExists = $distributivo_model->existsDistribucionAcademico($pro_id, $materia, $uaca_id, $mod_id, $paca_id, $jornada_id, $horario);
				if (isset($dataExists) && $dataExists != "" && count($dataExists) > 0) {
					if ($dataExists['id'] == $daca_id) {
						$message = array(
							"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
							"title" => Yii::t('jslang', 'Success'),
						);
						return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
					}
					$message = array(
						"wtmessage" => academico::t('distributivoacademico', 'Register already exists in System.'),
						"title" => Yii::t('jslang', 'Error'),
					);
					return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
				}
				$dataHorario = $distributivo_model->getDistribucionAcademicoHorario($uaca_id, $mod_id, $jornada_id, $horario);

				$distributivo_model->pro_id = $pro_id;
				$distributivo_model->mod_id = $mod_id;
				$distributivo_model->asi_id = $materia;
				$distributivo_model->paca_id = $paca_id;
				$distributivo_model->daho_id = $dataHorario['daho_id'];
				$distributivo_model->daca_fecha_modificacion = $fecha_transaccion;
				$distributivo_model->daca_usuario_modifica = $usu_id;
				if ($distributivo_model->update() !== false) {
					$message = array(
						"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
						"title" => Yii::t('jslang', 'Success'),
					);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
				} else {
					throw new Exception('Error en Guardar registro.');
				}
			} catch (Exception $e) {
				$message = array(
					"wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
					"title" => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

	public function actionDelete() {
		if (Yii::$app->request->isAjax) {
			$usu_id = @Yii::$app->session->get("PB_iduser");
			$data = Yii::$app->request->post();

			$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
			try {
				\app\models\Utilities::putMessageLogFile('entro delete: ');
				$valida_daca = DistributivoAcademico::findOne(['daca_id' => $data['id'], "daca_estado" => 1, "daca_estado_logico" => 1]);
				if ($valida_daca['daca_carga_academica'] == 1 and $valida_daca['tdis_id'] != 1) {
					\app\models\Utilities::putMessageLogFile('entro 1: ');
					$model = DistributivoAcademico::findOne($data['id']);
					$model->daca_fecha_modificacion = $fecha_transaccion;
					$model->daca_usuario_modifica = $usu_id;
					$model->daca_estado = '0';
					$model->daca_estado_logico = '0';
					// validaci??n, cuando se elimina un item del daca se modifica el estado del dcab_id
					$distributivo_model = new DistributivoAcademico();
					$discab = DistributivoCabecera::findOne(['dcab_id' => $data['daca_id'], 'dcab_estado' => 1, 'dcab_estado_logico' => 1]);
					if ($discab['dcab_estado_revision'] != 0) {
						$dis_cab = $distributivo_model->actualizarDistributivocabecera($data['daca_id']);
						//$bitacora_revision = $distributivo_cab->insertarBitacoraDCAB($discab['dcab_id'], $discab['dcab_estado_revision']);
					}

					$message = array(
						"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
						"title" => Yii::t('jslang', 'Success'),
					);

				} else {
					\app\models\Utilities::putMessageLogFile('entro 2: ');
					if ($valida_daca['daca_carga_academica'] == 1 && $valida_daca['uaca_id'] != 1) {
						\app\models\Utilities::putMessageLogFile('entro 3: ');
						$model = DistributivoAcademico::findOne($data['id']);
						$model->daca_fecha_modificacion = $fecha_transaccion;
						$model->daca_usuario_modifica = $usu_id;
						$model->daca_estado = '0';
						$model->daca_estado_logico = '0';
						// validaci??n, cuando se elimina un item del daca se modifica el estado del dcab_id
						$distributivo_model = new DistributivoAcademico();
						$discab = DistributivoCabecera::findOne(['dcab_id' => $data['daca_id'], 'dcab_estado' => 1, 'dcab_estado_logico' => 1]);
						if ($discab['dcab_estado_revision'] != 0) {
							$dis_cab = $distributivo_model->actualizarDistributivocabecera($data['daca_id']);
							//$bitacora_revision = $distributivo_cab->insertarBitacoraDCAB($discab['dcab_id'], $discab['dcab_estado_revision']);
						}

						$message = array(
							"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
							"title" => Yii::t('jslang', 'Success'),

						);
					} else {
						\app\models\Utilities::putMessageLogFile('entro 4: ');
						$daca_asi_relacion = DistributivoAcademico::findAll(['dcab_id' => $data['daca_id'], 'tdis_id' => $valida_daca['tdis_id'], 'daca_asi_relacion' => $valida_daca['daca_asi_relacion'], 'daca_estado' => 1, 'daca_estado_logico' => 1]);
						\app\models\Utilities::putMessageLogFile('daca_asi_relacion: ' . count($daca_asi_relacion));
						if (count($daca_asi_relacion) > 1 and $valida_daca['daca_carga_academica'] == 0) {
							\app\models\Utilities::putMessageLogFile('entro 5: ');
							$model = DistributivoAcademico::findOne($data['id']);
							$model->daca_fecha_modificacion = $fecha_transaccion;
							$model->daca_usuario_modifica = $usu_id;
							$model->daca_estado = '0';
							$model->daca_estado_logico = '0';
							// validaci??n, cuando se elimina un item del daca se modifica el estado del dcab_id
							$distributivo_model = new DistributivoAcademico();
							$discab = DistributivoCabecera::findOne(['dcab_id' => $data['daca_id'], 'dcab_estado' => 1, 'dcab_estado_logico' => 1]);
							if ($discab['dcab_estado_revision'] != 0) {
								$dis_cab = $distributivo_model->actualizarDistributivocabecera($data['daca_id']);
								//$bitacora_revision = $distributivo_cab->insertarBitacoraDCAB($discab['dcab_id'], $discab['dcab_estado_revision']);
							}

							$message = array(
								"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
								"title" => Yii::t('jslang', 'Success'),

							);
						} else if (count($daca_asi_relacion) == 1 and $valida_daca['daca_carga_academica'] == 1) {
							\app\models\Utilities::putMessageLogFile('entro 6: ');
							$model = DistributivoAcademico::findOne($data['id']);
							$model->daca_fecha_modificacion = $fecha_transaccion;
							$model->daca_usuario_modifica = $usu_id;
							$model->daca_estado = '0';
							$model->daca_estado_logico = '0';
							// validaci??n, cuando se elimina un item del daca se modifica el estado del dcab_id
							$distributivo_model = new DistributivoAcademico();
							$discab = DistributivoCabecera::findOne(['dcab_id' => $data['daca_id'], 'dcab_estado' => 1, 'dcab_estado_logico' => 1]);
							if ($discab['dcab_estado_revision'] != 0) {
								$dis_cab = $distributivo_model->actualizarDistributivocabecera($data['daca_id']);
								//$bitacora_revision = $distributivo_cab->insertarBitacoraDCAB($discab['dcab_id'], $discab['dcab_estado_revision']);
							}

							$message = array(
								"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
								"title" => Yii::t('jslang', 'Success'),

							);
						} else {
							\app\models\Utilities::putMessageLogFile('entro 7: ');
							$asi_nombre = Asignatura::findOne(['asi_id' => $valida_daca['asi_id'], 'asi_estado' => 1, 'asi_estado_logico' => 1]);
							$message = array(
								"wtmessage" => Yii::t('notificaciones', 'Imposible eliminar ' . $asi_nombre['asi_nombre'] . ' porque existe materias bajo dependencia'),
								"title" => Yii::t('jslang', 'Error'),
							);
							return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
						}
					}
				}

				if ($model->update() !== false) {
					//return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
				} else {
					throw new Exception('Error SubModulo no ha sido eliminado.');
				}
			} catch (Exception $ex) {
				$message = array(
					"wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
					"title" => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
			}
		}
	}

	public function actionExportexcel() {
		$per_id = @Yii::$app->session->get("PB_perid");
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K");
		$arrHeader = array(
			academico::t("Academico", "Teacher"),
			Yii::t("formulario", "DNI 1"),
			Yii::t("formulario", "Academic unit"),
			Yii::t("formulario", "Mode"),
			Yii::t("formulario", "Period"),
			Yii::t("formulario", "Subject"),
			academico::t("Academico", "Working day"),
			academico::t("Academico", "Paralelo"),
			academico::t("Academico", "Total Estudiantes"),
		);
		$distributivo_model = new DistributivoAcademico();
		$data = Yii::$app->request->get();
		$arrSearch = array();
		if (count($data) > 0) {
			$arrSearch['search'] = $data['search'];
			$arrSearch['unidad'] = $data['unidad'];
			$arrSearch['modalidad'] = $data['modalidad'];
			$arrSearch['periodo'] = $data['periodo'];
			$arrSearch['materia'] = $data['materia'];
			$arrSearch['jornada'] = $data['jornada'];
		}
		$arrData = array();
		if ($arrSearch["unidad"] == 1) {
			$arrData = $distributivo_model->getListadoDistributivoGrado($arrSearch, true);
		} else {
			$arrData = $distributivo_model->getListadoDistributivoPosgrado($arrSearch["search"], $arrSearch["modalidad"], $arrSearch["asignatura"], $arrSearch["jornada"], $arrSearch["unidad"], $arrSearch["periodo"], true);
		}
		foreach ($arrData as $key => $value) {
			unset($arrData[$key]["Id"]);
		}
		$nameReport = academico::t("distributivoacademico", "Profesor Lists by Subject");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionExportpdf() {
		$per_id = @Yii::$app->session->get("PB_perid");
		$report = new ExportFile();
		$this->view->title = academico::t("distributivoacademico", "Profesor Lists by Subject"); // Titulo del reporte
		$arrHeader = array(
			academico::t("Academico", "Teacher"),
			Yii::t("formulario", "DNI 1"),
			Yii::t("formulario", "Academic unit"),
			Yii::t("formulario", "Mode"),
			Yii::t("formulario", "Period"),
			Yii::t("formulario", "Subject"),
			academico::t("Academico", "Working day"),
			academico::t("Academico", "Paralelo"),
			academico::t("Academico", "Total Estudiantes"),
		);
		$distributivo_model = new DistributivoAcademico();
		$data = Yii::$app->request->get();
		$arrSearch = array();
		if (count($data) > 0) {
			$arrSearch['search'] = $data['search'];
			$arrSearch['unidad'] = $data['unidad'];
			$arrSearch['modalidad'] = $data['modalidad'];
			$arrSearch['periodo'] = $data['periodo'];
			$arrSearch['materia'] = $data['materia'];
			$arrSearch['jornada'] = $data['jornada'];
		}
		//$arrData = $distributivo_model->getListadoDistributivo($arrSearch["search"], $arrSearch["modalidad"], $arrSearch["asignatura"], $arrSearch["jornada"], $arrSearch["unidad"], $arrSearch["periodo"], true);
		if ($arrSearch["unidad"] == '1') {
			$arrData = $distributivo_model->getListadoDistributivoGrado($arrSearch, true);
		} else {
			$arrData = $distributivo_model->getListadoDistributivoPosgrado($arrSearch["search"], $arrSearch["modalidad"], $arrSearch["asignatura"], $arrSearch["jornada"], $arrSearch["unidad"], $arrSearch["periodo"], true);
		}
		$report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
		$report->createReportPdf(
			$this->render('exportpdf', [
				'arr_head' => $arrHeader,
				'arr_body' => $arrData,
			])
		);
		$report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
	}

	public function actionSaveasignarestudiante() {
		$per_id = Yii::$app->session->get('PB_iduser');
		$estudiante = new DistributivoAcademicoEstudiante();

		$data = Yii::$app->request->post();
		try {
			$paca_id = $data['paca_id'];
			$daca_id = $estudiante->consultarDaesEstudiante($paca_id);
			\app\models\Utilities::putMessageLogFile('Consulta daca_id: ' . $daca_id['daca_id']);
			if ($daca_id['daca_id'] > 0) {
				$insertID = $estudiante->insertarDaesEstudiante($paca_id);
				$exito = 1;
			} else {
				$message = array(
					"wtmessage" => Yii::t('notificaciones', 'Error al grabar. No existe un distributivo academico con esa informaci??n'),
					"title" => Yii::t('jslang', 'Error'),
				);
				return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), false, $message);
			}
			if ($exito) {
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
				"wtmessage" => Yii::t("notificaciones", "Error" . $ex),
				"title" => Yii::t('jslang', 'Error'),
			);
			return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
		}
	}

}
