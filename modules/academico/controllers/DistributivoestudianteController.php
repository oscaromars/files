<?php

namespace app\modules\academico\controllers;

use app\models\ExportFile;
use app\models\Persona;
use app\models\Utilities;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\DistributivoAcademicoEstudiante;
use app\modules\academico\models\DistributivoAcademicoHorario;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\Profesor;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use Exception;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

academico::registerTranslations();
admision::registerTranslations();

class DistributivoestudianteController extends \app\components\CController {

	public function actionNew($id) {
		$daca_id = $id;

		$distributivo_model = DistributivoAcademico::findOne($daca_id);
		$data = array();
		///sizeof($estuden);
		//print_r($distributivo_model->mpp->mpp_num_paralelo);die();
		$asi_id = $distributivo_model->asi_id;
		$num_paralelo = $distributivo_model->mpp->mpp_num_paralelo;
		$uaca_id = $distributivo_model->uaca_id;

		$paca_id = $distributivo_model->paca_id;

		Utilities::putMessageLogFile('$uaca_id ' . $uaca_id);
		Utilities::putMessageLogFile('$paca_id ' . $paca_id);
		if ($uaca_id == 1) {
			$estuden = $distributivo_model->buscarEstudiantesAsignados($asi_id, /*$num_paralelo,*/ $daca_id, $paca_id);
			$model = $estuden;
		} //if($uaca_id == 1)

		if ($uaca_id == 2) {
			$estuden = $distributivo_model->buscarEstudiantesPosgrados($daca_id);

			for ($i = 0; $i < sizeof($estuden); $i++) {
				$model = new DistributivoAcademicoEstudiante();
				$model->daca_id = $daca_id;

				$model->est_id = $estuden[$i]['est_id'];
				$model->daes_estado = 0;
				$data[] = $model;
			} //for

			//print_r($data);die;
		} //if($uaca_id == 2)

		//$paca_id = $distributivo_model['paca_id'];
		$paralelos = $distributivo_model->getParaleloxPeriodo($distributivo_model->asi_id, $paca_id);

		$dataProvider = new ArrayDataProvider([
			'allModels' => $model,
			'key' => 'est_id',
			'pagination' => [
				'pageSize' => 40,
			],
			'sort' => [
				'attributes' => ['daes_id', 'daca_id', 'est_id'],
			],
		]);

		return $this->render('new',
			['dataProvider' => $dataProvider,
				'distributivo_model' => $distributivo_model,
				'paralelos' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $paralelos), "id", "name"),
			]
		);
	} //function actionNew

	/*public function actionNew($id) {
		$daca_id = $id;

		$distributivo_model = DistributivoAcademico::findOne($daca_id);
		$data = array();
		///sizeof($estuden);
		//print_r($distributivo_model->mpp->mpp_num_paralelo);die();
		$asi_id = $distributivo_model->asi_id;
		$num_paralelo = $distributivo_model->mpp->mpp_num_paralelo;
		$uaca_id = $distributivo_model->uaca_id;

		if ($uaca_id == 1) {
			$estuden = $distributivo_model->buscarEstudiantesAsignados($asi_id, /$num_paralelo,/ $daca_id);

			for ($i = 0; $i < sizeof($estuden); $i++) {
				$model = new DistributivoAcademicoEstudiante();
				//$dae_id         = $distributivo_model->buscarEstudiantesAsignados($distributivo_model->asi_id,$estuden[$i]['est_id'] ,$num_paralelo);
				//$dae_id         = $distributivo_model->buscarEstudiantesAsignados($asi_id,$estuden[$i]['est_id'] ,$num_paralelo);

				$model->daca_id = $daca_id;
				$model->est_id = $estuden[$i]['est_id'];

				if ($estuden[$i]['daes_id'] != '') {
					$model->daes_id = $estuden[$i]['daes_id'];
					Utilities::putMessageLogFile('$daes_id ' . $estuden[$i]['daes_id']);
					//$model->daes_id = $daes_id['daes_id'];
					$model->daes_estado = 1;
				} else {
					$model->daes_estado = 0;
				}

				if (is_null($estuden[$i]['daca_id'])) {
					$data[] = $model;
				} else if ($estuden[$i]['daca_id'] == $daca_id) {
					$data[] = $model;
				}

			} //for
		} //if($uaca_id == 1)

		if ($uaca_id == 2) {
			$estuden = $distributivo_model->buscarEstudiantesPosgrados($daca_id);

			for ($i = 0; $i < sizeof($estuden); $i++) {
				$model = new DistributivoAcademicoEstudiante();
				$model->daca_id = $daca_id;

				$model->est_id = $estuden[$i]['est_id'];
				$model->daes_estado = 0;
				$data[] = $model;
			} //for

			//print_r($data);die;
		} //if($uaca_id == 2)

		$dataProvider = new ArrayDataProvider([
			'allModels' => $data,
			'key' => 'est_id',
			'pagination' => [
				'pageSize' => 10,
			],
			'sort' => [
				'attributes' => ['daes_id', 'daca_id', 'est_id'],
			],
		]);

		return $this->render('new',
			['dataProvider' => $dataProvider,
				'distributivo_model' => $distributivo_model]
		);
	}*/ //function actionNew

	public function actionNewposgrado($id) {
		$distributivo_model = DistributivoAcademico::findOne($id);
		$data = array();
		///sizeof($estuden);
		$estuden = $distributivo_model->buscarEstudiantesPosgrados($id);
		for ($i = 0; $i < sizeof($estuden); $i++) {
			$model = new DistributivoAcademicoEstudiante();
			$model->daca_id = $id;

			$model->est_id = $estuden[$i]['est_id'];
			$model->daes_estado = 0;
			$data[] = $model;
		}

		// print_r($data);
		$dataProvider = new ArrayDataProvider([
			'allModels' => $data,
			'key' => 'est_id',
			'pagination' => [
				'pageSize' => 10,
			],
			'sort' => [
				'attributes' => ['daes_id', 'daca_id', 'est_id'],
			],
		]);

		return $this->render('newposgrado',
			['dataProvider' => $dataProvider,
				'distributivo_model' => $distributivo_model,
			]
		);
	}

	public function actionCambioparalelo($id, $daes_id) {
		$distributivo_model = DistributivoAcademico::findOne($id);
		$paca_id = $distributivo_model['paca_id'];
		Utilities::putMessageLogFile('$paca_id ' . $paca_id);
		$estudiante_model = DistributivoAcademicoEstudiante::findOne($daes_id);
		$paralelos = $distributivo_model->getParaleloxPeriodo($distributivo_model->asi_id, $paca_id);
		return $this->render('cambioparalelo',
			['distributivo_model' => $distributivo_model,
				'estudiante_model' => $estudiante_model,
				'paralelos' => ArrayHelper::map(array_merge($paralelos), "id", "name")]
		);
	}

	public function actionSavechangeparalelo() {
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);

		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$daca_id = $data['daca_id'];
			$daes_id = $data['daes_id'];

			$estudiante_model = DistributivoAcademicoEstudiante::findOne($daes_id);
			//print_r($daes_id);die();
			$estudiante_model->daca_id = $daca_id;
			if ($estudiante_model->save()) {
				$message = array(
					"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
					"title" => Yii::t('jslang', 'Success'),
				);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);

			}
		}
	}

	public function actionIndex($id) {
		$per_id = @Yii::$app->session->get("PB_perid");
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$distributivoEst_model = new DistributivoAcademicoEstudiante();
		if (!isset($id) && $id <= 0) {
			return $this->redirect('distributivoacademico/index');
		}

		$data = Yii::$app->request->get();

		if ($data['PBgetFilter']) {
			$search = $data['search'];
			$id = $data['id'];
			$model = $distributivoEst_model->getListadoDistributivoEstudiante($id, $search);
			return $this->render('index-grid', [
				"model" => $model,
			]);
		}

		$distributivo_model = DistributivoAcademico::findOne($id);
		$distributivo_hora = DistributivoAcademicoHorario::findOne($distributivo_model->daho_id);
		$mod_modalidad = Modalidad::findOne($distributivo_hora->mod_id);
		$mod_unidad = UnidadAcademica::findOne($distributivo_hora->uaca_id);
		$mod_asignatura = Asignatura::findOne($distributivo_model->asi_id);
		$mod_periodo = new PeriodoAcademicoMetIngreso();
		$periodo = $mod_periodo->consultarPeriodoAcademico($distributivo_model->paca_id);
		$mod_profesor = Profesor::findOne($distributivo_model->pro_id);
		$mod_persona = Persona::findOne($mod_profesor->per_id);
		$arr_jornada = array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia");
		$model = $distributivoEst_model->getListadoDistributivoEstudiante($id);

		return $this->render('index', [
			'unidad' => $mod_unidad->uaca_nombre,
			'profesor' => $mod_persona->per_pri_nombre . " " . $mod_persona->per_pri_apellido,
			'modalidad' => $mod_modalidad->mod_nombre,
			'periodo' => $periodo[0]['name'],
			'materia' => $mod_asignatura->asi_nombre,
			'horario' => $distributivo_hora->daho_horario,
			'model' => $model,
			'jornada' => $arr_jornada[$distributivo_hora->daho_jornada],
			'daca_id' => $id,
		]);
	}

	public function actionEdit($id) {
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$distributivoEst_model = new DistributivoAcademicoEstudiante();
		if (!isset($id) && $id <= 0) {
			return $this->redirect('distributivoacademico/index');
		}
		$data = Yii::$app->request->get();
		if ($data['PBgetFilter']) {
			$search = $data['search'];
			$id = $data['id'];
			$model = $distributivoEst_model->getListadoDistributivoEstudiante($id, $search);
			return $this->render('edit-grid', [
				"model" => $model,
			]);
		}

		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if ($data['PBgetAutoComplete']) {
				$search = $data['search'];
				$unidad = $data['unidad'];
				$response = $distributivoEst_model->getEstudiantesXUnidadAcademica($unidad, $search);
				return json_encode($response);
			}
			if ($data['PBgetDataEstudiante']) {
				$est_id = $data['est_id'];
				$mod_est = Estudiante::findOne($est_id);
				$mod_per = Persona::findOne($mod_est->per_id);
				$arr_carrera = $mod_est->getInfoCarreraEstudiante($est_id, $emp_id);
				$data = [
					"nombres" => $mod_per->per_pri_nombre . " " . $mod_per->per_seg_nombre,
					"apellidos" => $mod_per->per_pri_apellido . " " . $mod_per->per_seg_apellido,
					"matricula" => $mod_est->est_matricula,
					"carrera" => $arr_carrera['Carrera'],
				];
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', [], $data);
			}
		}
		$distributivo_model = DistributivoAcademico::findOne($id);
		$distributivo_hora = DistributivoAcademicoHorario::findOne($distributivo_model->daho_id);
		$mod_modalidad = Modalidad::findOne($distributivo_hora->mod_id);
		$mod_unidad = UnidadAcademica::findOne($distributivo_hora->uaca_id);
		$mod_asignatura = Asignatura::findOne($distributivo_model->asi_id);
		$mod_periodo = new PeriodoAcademicoMetIngreso();
		$periodo = $mod_periodo->consultarPeriodoAcademico($distributivo_model->paca_id);
		$mod_profesor = Profesor::findOne($distributivo_model->pro_id);
		$mod_persona = Persona::findOne($mod_profesor->per_id);
		$arr_jornada = array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia");
		$model = $distributivoEst_model->getListadoDistributivoEstudiante($id);

		return $this->render('edit', [
			'unidad' => $mod_unidad->uaca_nombre,
			'profesor' => $mod_persona->per_pri_nombre . " " . $mod_persona->per_pri_apellido,
			'modalidad' => $mod_modalidad->mod_nombre,
			'periodo' => $periodo[0]['name'],
			'materia' => $mod_asignatura->asi_nombre,
			'horario' => $distributivo_hora->daho_horario,
			'model' => $model,
			'jornada' => $arr_jornada[$distributivo_hora->daho_jornada],
			'daca_id' => $id,
			'uaca_id' => $distributivo_hora->uaca_id,
		]);
	}

	public function actionSave() {
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
		$user = Yii::$app->session->get("PB_iduser");

		try {
			if (Yii::$app->request->isAjax) {
				$data = Yii::$app->request->post();
				$daca_id = $data['daca_id'];
				$est_id = $data['est_id'];
				$paralelo = $data['paralelo'];
				$lista_daes_id = $data['lista_daes_id']; 

				$verifica = 0;
				Utilities::putMessageLogFile('daca_id:'. $daca_id);
				Utilities::putMessageLogFile('est_id :'. $est_id);
				Utilities::putMessageLogFile('paralelo :'. $paralelo);
				Utilities::putMessageLogFile('lista_daes_id :'. $lista_daes_id);

				/*if ( $paralelo == 0 ){
					//return;
					Utilities::putMessageLogFile('INSERTA');
					for ($i = 0; $i < sizeof($est_id); $i++) {
						$distributivoEst_model = new DistributivoAcademicoEstudiante();
						$distributivoEst_model->daca_id = $daca_id;
						$distributivoEst_model->est_id = $est_id[$i];
						$distributivoEst_model->daes_fecha_registro = $fecha_transaccion;
						$distributivoEst_model->daes_estado = '1';
						$distributivoEst_model->daes_estado_logico = '1';
						if ($distributivoEst_model->save()) {
							$verifica++;
						}
					}
				}else{*/
					//Actualiza paralelo
					//Utilities::putMessageLogFile('1 ACTUALIZA');
					if (!empty($lista_daes_id)) {
	                    $cont = 0;
	                    if ($paralelo !='0'){
	                    	Utilities::putMessageLogFile('********** ACTUALIZA: Todos los paralelos ********** : '.$paralelo);
	                    	foreach ($lista_daes_id as $key => $value) {
		                        $daes_id = $value['daes_id'];

								if ($daes_id !=""){
									Utilities::putMessageLogFile('OP1 ACTUALIZA todos: estan asignados:'.$cont);
									Utilities::putMessageLogFile('OP1 Indice:'.$cont.' - daes_id:'. $daes_id);
									$estudiante_model = DistributivoAcademicoEstudiante::findOne($daes_id);
									$estudiante_model->daca_id = $paralelo;
									$estudiante_model->daes_usuario_modifica = $user;
									$estudiante_model->daes_fecha_modificacion = $fecha_transaccion;
									if ($estudiante_model->save()) {
										$verifica++;
									}
								}else{
									Utilities::putMessageLogFile('OP2 INSERTA: están pendientes por asignar:'.$cont);
									Utilities::putMessageLogFile('OP2 Están pendientes por asignar $value[daca_id]: '.$value['daca_id'] .' $value[est_id]: '. $value['est_id']);
									$distributivoEst_model = new DistributivoAcademicoEstudiante();
									$distributivoEst_model->daca_id = $paralelo;
									$distributivoEst_model->est_id = $value['est_id'];
									$distributivoEst_model->daes_fecha_registro = $fecha_transaccion;
									$distributivoEst_model->daes_usuario_ingreso = $user;
									$distributivoEst_model->daes_estado = '1';
									$distributivoEst_model->daes_estado_logico = '1';
									if ($distributivoEst_model->save()) {
										$verifica++;
									}
								}

								$cont++;
		                    }	
	                    }elseif ($paralelo =='0'){
	                    	Utilities::putMessageLogFile('********** ACTUALIZA cuando selecciona por item **********:'.$cont);
	                    	foreach ($lista_daes_id as $key => $value) {
		                        
		                        $daes_id = $value['daes_id'];

		                        if ($daes_id !=""){
		                        	Utilities::putMessageLogFile('OP3 ACTUALIZA todos: estan asignados:'.$cont);
			                        Utilities::putMessageLogFile('OP3 Indice:'.$cont.' - daes_id:'. $daes_id);
									$estudiante_model = DistributivoAcademicoEstudiante::findOne($daes_id);
									$estudiante_model->daca_id = $value['daca_id'];
									$estudiante_model->daes_usuario_modifica = $user;
									$estudiante_model->daes_fecha_modificacion = $fecha_transaccion;
									if ($estudiante_model->save()) {
										$verifica++;
									}
								}else{
									Utilities::putMessageLogFile('OP4 INSERTA: están pendientes por asignar:'.$cont);
									Utilities::putMessageLogFile('OP4 Están pendientes por asignar $value[daca_id]: '.$value['daca_id'] .' $value[est_id]: '. $value['est_id']);
									$distributivoEst_model = new DistributivoAcademicoEstudiante();
									$distributivoEst_model->daca_id = $value['daca_id'];
									$distributivoEst_model->est_id = $value['est_id'];
									$distributivoEst_model->daes_usuario_ingreso = $user;
									$distributivoEst_model->daes_fecha_registro = $fecha_transaccion;
									$distributivoEst_model->daes_estado = '1';
									$distributivoEst_model->daes_estado_logico = '1';
									if ($distributivoEst_model->save()) {
										$verifica++;
									}
								}
								$cont++;

		                    }	
	                    }                    
	                }
				//}

				/*  $dataExists = DistributivoAcademicoEstudiante::findOne(['daca_id' => $daca_id, 'est_id' => $est_id, 'daes_estado' => '1', 'daes_estado_logico' => '1']);
					                  if(isset($dataExists) && $dataExists != ""){
					                  $message = array(
					                  "wtmessage" => academico::t('distributivoacademico', 'Register already exists in System.'),
					                  "title" => Yii::t('jslang', 'Error'),
					                  );
					                  return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
				*/

				if ($verifica >= 1) {
					$message = array(
						"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
						"title" => Yii::t('jslang', 'Success'),
					);
					return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
				} else {
					throw new Exception('Error en Guardar registro.');
				}
			}
		} catch (Exception $e) {
			Utilities::putMessageLogFile('excepecion e: ' . $e);
			$message = array(
				"wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
				"title" => Yii::t('jslang', 'Error'),
			);
			return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
		}
	}

	public function actionDelete() {
		if (Yii::$app->request->isAjax) {
			$usu_id = @Yii::$app->session->get("PB_iduser");
			$data = Yii::$app->request->post();
			$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
			try {
				$id = $data["id"];
				$model = DistributivoAcademicoEstudiante::findOne($id);
				$model->daes_fecha_modificacion = $fecha_transaccion;
				$model->daes_estado = '0';
				$model->daes_estado_logico = '0';
				$message = array(
					"wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
					"title" => Yii::t('jslang', 'Success'),
				);
				if ($model->update() !== false) {
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
		$colPosition = array("C", "D", "E", "F", "G", "H");
		$arrHeader = array(
			academico::t("matriculacion", "Student"),
			Yii::t("formulario", "DNI 1"),
			Yii::t("formulario", "Email"),
			Yii::t("formulario", "Phone"),
			academico::t("matriculacion", "Registration Number"),
			academico::t("matriculacion", "Career"),
		);
		$distributivo_model = new DistributivoAcademicoEstudiante();
		$data = Yii::$app->request->get();
		$arrSearch["search"] = ($data['search'] != "") ? $data['search'] : NULL;
		$arrSearch["id"] = ($data['id'] > 0) ? $data['id'] : NULL;

		$arrData = $distributivo_model->getListadoDistributivoEstudiante($arrSearch["id"], $arrSearch["search"], true);
		foreach ($arrData as $key => $value) {
			unset($arrData[$key]["Id"]);
		}
		$nameReport = academico::t("distributivoacademico", "Student Lists by Subject");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionExportpdf() {
		$per_id = @Yii::$app->session->get("PB_perid");
		$report = new ExportFile();
		$this->view->title = academico::t("distributivoacademico", "Student Lists by Subject"); // Titulo del reporte
		$arrHeader = array(
			academico::t("matriculacion", "Student"),
			Yii::t("formulario", "DNI 1"),
			Yii::t("formulario", "Email"),
			Yii::t("formulario", "Phone"),
			academico::t("matriculacion", "Registration Number"),
			academico::t("matriculacion", "Career"),
		);
		$distributivo_model = new DistributivoAcademicoEstudiante();
		$data = Yii::$app->request->get();
		$arrSearch["search"] = ($data['search'] != "") ? $data['search'] : NULL;
		$arrSearch["id"] = ($data['id'] > 0) ? $data['id'] : NULL;

		$arrData = $distributivo_model->getListadoDistributivoEstudiante($arrSearch["id"], $arrSearch["search"], true);
		$report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
		$report->createReportPdf(
			$this->render('exportpdf', [
				'arr_head' => $arrHeader,
				'arr_body' => $arrData,
			])
		);
		$report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
	}

	public function actionHorarioestudiante() {
		$per_id = @Yii::$app->session->get("PB_perid");
		$mod_distributivoest = new DistributivoAcademicoEstudiante();
		$mod_estudiante = new Estudiante();
		// Obtener est_id a partir del per_id
		$arr_estudiante = $mod_estudiante->getEstudiantexperid($per_id);
		$arr_horario = $mod_distributivoest->consultarHorarioEstudiante($arr_estudiante["est_id"]);
		return $this->render('horarioestudiante', [
			'arr_horario' => $arr_horario,
		]);
	}

}
