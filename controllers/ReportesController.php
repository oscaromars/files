<?php

namespace app\controllers;
use app\components\CController;
use app\models\Empresa;
use app\models\ExportFile;
use app\models\Reporte;
use app\models\Utilities;
use app\modules\academico\models\BloqueAcademico;
use app\modules\academico\models\DistributivoAcademicoSearch;
use app\modules\academico\models\EstudianteCarreraProgramaSearch;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\MallaAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\PeriodoAcademicoMensualizado;
use app\modules\academico\models\PlanificacionEstudiante;
use app\modules\academico\models\PlanificacionSearch;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\Module as academico;
use app\modules\financiero\models\CargaCartera;
use app\modules\financiero\Module as financiero;
use Yii;
use yii\helpers\ArrayHelper;

academico::registerTranslations();
financiero::registerTranslations();

academico::registerTranslations();
financiero::registerTranslations();

class ReportesController extends CController {

	private function estados() {
		return [
			'0' => Yii::t("formulario", "All"),
			'C' => Yii::t("formulario", "Cancelado"),
			'N' => Yii::t("formulario", "Pendiente"),
		];
	}

	public function actionIndex() {
		$empresa_mod = new Empresa();
		$empresa = $empresa_mod->getAllEmpresa();
		return $this->render('index', [
			'arr_empresa' => ArrayHelper::map(array_merge([["id" => "0", "value" => "Todas"]], $empresa), "id", "value"),
		]);
	}
	public function actionExpexcelreport() {
		$objDat = new Reporte();
		//$data["estado"]= $_GET["estado"];
		$data["op"] = $_GET["op"];
		$data["f_ini"] = $_GET["f_ini"];
		$data["f_fin"] = $_GET["f_fin"];
		$data["search_dni"] = $_GET["searchdni"];
		$data["empresa_id"] = $_GET["empresa_id"];
		//$data["valor"]= $_GET["valor"];
		switch ($data["op"]) {
		case '1': //GRADO
			$arrData = $objDat->consultarActividadporOportunidad($data);
			$arrHeader = array("N?? Oport", "Fecha", "Empresa", "Nombres", "Apellidos", "Unidad Academica", "Estado", "Observacion");
			$nombarch = "ActividadesOportunidad-" . date("YmdHis") . ".xls";
			$nameReport = yii::t("formulario", "Gesti??n de Contactos");
			break;
		case '2': //POSGRADO
			$arrData = $objDat->consultarOportunidadProximaAten($data);
			$arrHeader = array(
				"N?? Oport", "Fecha Atenci??n", "Hora Atenci??n", "F.Pr??x.At", "Hora Pr??x.At", "Empresa", "C??dula",
				"Nombres Completos", "Canal Contacto",
				"Estado Oport.", "Observaci??n", "Unidad Academica", "Modalidad", "Carrera", "Agente",
			);
			$nombarch = "EstadoOportunidad-" . date("YmdHis") . ".xls";
			$nameReport = yii::t("formulario", "Gesti??n de Contactos");
			break;
		case '3': //Aspirantes
			$arrData = $objDat->consultarAspirantesPendientes($data);
			$arrHeader =
			array(
				"DNI",
				"Fecha Solicitud",
				"Num. Solicitud",
				"Nombres",
				"Apellidos",
				"Empresa",
				"Unidad Academica",
				"Carrera",
				"Modalidad",
				"Agente",
				"Estado Solicitud",
				"Estado Documentos",
				"Estado Pago",
			);
			$nombarch = "Aspirantes-" . date("YmdHis") . ".xls";
			$nameReport = yii::t("formulario", "Application Reports");
			break;
		}
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		// $nameReport = yii::t("formulario", "Application Reports");
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionInscriptos() {
		$arrAnio = array();
		$objDat = new Reporte();
		$anio = $objDat->getAllAnios();
		for ($i = 0; $i < count($anio); $i++) {
			$arrAnio[$i]['id'] = $i;
			$arrAnio[$i]['value'] = $anio[$i]['anio'];
		}
		//Utilities::putMessageLogFile($arrAnio);
		return $this->render('inscriptos', [
			'arr_anio' => ArrayHelper::map(array_merge($arrAnio), "id", "value"),
		]);
	}
	public function actionExpexcelinscriptos() {
		$objDat = new Reporte();
		$arrHeader = array();
		$TotEst = array();
		$EmpCol = array();
		$arrDataNew = array();
		$anio = $_GET["anio"];

		$arrData = $objDat->consultarInscriptos($anio);
		//$arrCabData=$objDat->getAllConvenioEmpresa();
		$nombarch = "Inscriptos-" . date("YmdHis") . ".xls";
		//Utilities::putMessageLogFile($arrCabData);
		$aux = "";
		//Obtener datos de cabercera
		$arrHeader[] = "Venta x Mes";
		$arrHeader[] = "Estudiantes";
		for ($i = 0; $i < count($arrData); $i++) {
			if ($arrData[$i]['cemp_nombre'] != $aux) {
				$columna = $arrData[$i]['cemp_nombre'];
				if (!$this->existeColumna($columna, $arrHeader)) {
					$arrHeader[] = $columna;
				}
				$aux = $columna;
			}
		}
		//Utilities::putMessageLogFile($arrHeader);
		//Crear Cuerpo de Datos
		$aux = "";
		for ($i = 0; $i < count($arrData); $i++) {
			if ($arrData[$i]['cemp_nombre'] != $aux) {
				$sumCol = 0;
				$conEmp = $arrData[$i]['cemp_nombre']; //FIjar la columna
				if (!$this->existeColumna($conEmp, $EmpCol)) {
					$EmpCol[] = $conEmp;
					for ($j = 0; $j < 12; $j++) {
//Recorrer el nuevo Array
						$arrDataNew[$j]['Mes'] = $this->retornaMes($j + 1);
						$arrDataNew[$j]['Total'] = 0;
						$cant = $this->numEstudiantes($conEmp, $j + 1, $arrData);
						$arrDataNew[$j][$conEmp] = $cant;
						$TotEst[$j] += $cant;
						$sumCol += $cant;
					}
					$arrDataNew[12]['Mes'] = 'TOTALES';
					$arrDataNew[12]['Total'] = 0;
					$arrDataNew[12][$conEmp] = $sumCol;

				}
				$aux = $conEmp;

			}

		}

		//Actualiza Totales
		$sumCol = 0;
		for ($j = 0; $j < 12; $j++) {
			$arrDataNew[$j]['Total'] = $TotEst[$j];
			$sumCol += $TotEst[$j];
		}
		$arrDataNew[12]['Total'] = $sumCol;

		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$nameReport = yii::t("formulario", "Application Reports");
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrDataNew, $colPosition);
		exit;

	}

	private function existeColumna($columna, $arrHeader) {
		for ($i = 0; $i < count($arrHeader); $i++) {
			//Utilities::putMessageLogFile($arrHeader[$i]);
			if ($arrHeader[$i] == $columna) {
				return true;
			}
		}
		return false;
	}
	private function numEstudiantes($Empresa, $Mes, $arrData) {
		$valor = 0;
		for ($i = 0; $i < count($arrData); $i++) {
			if ($arrData[$i]['MES'] == $Mes && $arrData[$i]['cemp_nombre'] == $Empresa) {
				return $arrData[$i]['CANT'];
			}
		}
		return $valor;
	}

	private function retornaMes($number) {
		$valor = "";
		switch ($number) {
		case 1:
			$valor = "Enero";
			break;
		case 2:
			$valor = "Febrero";
			break;
		case 3:
			$valor = "Marzo";
			break;
		case 4:
			$valor = "Abril";
			break;
		case 5:
			$valor = "Mayo";
			break;
		case 6:
			$valor = "Junio";
			break;
		case 7:
			$valor = "Julio";
			break;
		case 8:
			$valor = "Agosto";
			break;
		case 9:
			$valor = "Septiembre";
			break;
		case 10:
			$valor = "Octubre";
			break;
		case 11:
			$valor = "Noviembre";
			break;
		case 12:
			$valor = "Diciembre";
			break;

		}
		return $valor;
	}
	public function actionCartera() {
		return $this->render('cartera', [
			'arrEstados' => $this->estados(),
		]);
	}

	public function actionExpexcelreportcartera() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q");
		$arrHeader = array(
			Yii::t("formulario", "DNI 1"),
			Yii::t("formulario", "First Names"),
			academico::t("Academico", "Academic unit"),
			academico::t("Academico", "Modality"),
			academico::t("Academico", "Career"),
			financiero::t("Pagos", "# Voucher"),
			financiero::t("Pagos", "Amount Fees"),
			financiero::t("Pagos", "Date Bill"),
			financiero::t("Pagos", "Expiration date"),
			financiero::t("Pagos", "Quota value"),
			financiero::t("Pagos", "Value") . ' ' . financiero::t("Pagos", "Bill"),
			financiero::t("Pagos", "Pass"),
			Yii::t("formulario", "Payment Status"),
			financiero::t("Pagos", "Balance"),
		);
		$data = Yii::$app->request->get();
		$arrSearch = array();
		if (count($data) > 0) {
			$arrSearch["search"] = $data['search'];
			$arrSearch["f_inif"] = $data['f_inif'];
			$arrSearch["f_finf"] = $data['f_finf'];
			$arrSearch["f_iniv"] = $data['f_iniv'];
			$arrSearch["f_finv"] = $data['f_finv'];
			$arrSearch["estadopago"] = $data['estadopago'];
		}
		$arrData = array();
		$carga_model = new CargaCartera();
		if (count($arrSearch) > 0) {
			$arrData = $carga_model->consultarReportcartera($arrSearch, true);
		} else {
			$arrData = $carga_model->consultarReportcartera(array(), true);
		}
		$nameReport = academico::t("Pagos", "Cartera");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionReportdistributivo() {
		$searchModel = new DistributivoAcademicoSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->getListadoDistributivoBloqueDocente($params, false, 1);
		return $this->render('reportdistributivo', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionExpexceldistributivo() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
		$arrHeader = array(
			Yii::t("formulario", "Docente"),
			Yii::t("formulario", "N?? C??dula"),
			Yii::t("formulario", "T??tulo Tercer Nivel"),
			Yii::t("formulario", "T??tulo Cuarto Nivel"),
			Yii::t("formulario", "Correo Electronico"),
			Yii::t("formulario", "Tiempo de Dedicaci??n"),
			Yii::t("formulario", "Tipo Asignaci??n"),
			Yii::t("formulario", "Materia"),
			Yii::t("formulario", "Total Horas a Dictar"),
			Yii::t("formulario", "Promedio"),
		);
		$searchModel = new DistributivoAcademicoSearch();
		$data = Yii::$app->request->get();
		$arrSearch["periodo"] = $data['periodo'];
		$arrSearch["tipo_asignacion"] = $data['tipo_asignacion'];
		$arrSearch["modalidad"] = $data['modalidad'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getListadoDistributivoexcel(NULL, true);
		} else {
			$arrData = $searchModel->getListadoDistributivoexcel($arrSearch, true);
		}
		/*for ($i = 0; $i < count($arrData); $i++) {
			            unset($arrData[$i]['est_id']);
		*/
		$nameReport = academico::t("Academico", "Reporte Distributivo");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionReportemateriasnoasignadas() {
		$mod_periodoActual = new PeriodoAcademico();
		$arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual();
		$searchModel = new DistributivoAcademicoSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->getListadoMateriaNoAsignada($params, false, 1, $arr_periodoActual["baca_nombre"], $arr_periodoActual["id"]);
		return $this->render('reportemateriasnoasignadas', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionExpexcelmateriasnoasignadas() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
		$arrHeader = array(
			Yii::t("formulario", "C??digo"),
			Yii::t("formulario", "Materia"),
			Yii::t("formulario", "Paralelo"),
		);
		$searchModel = new DistributivoAcademicoSearch();
		$data = Yii::$app->request->get();
		\app\models\Utilities::putMessageLogFile('modalidad ' . $data['modalidad']);
		$arrSearch["modalidad"] = $data['modalidad'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getListadoMateriaNoAsignadaexcel(NULL, true, $data['modalidad']);
		} else {
			$arrData = $searchModel->getListadoMateriaNoAsignadaexcel($arrSearch, true, $data['modalidad']);
		}
		/*for ($i = 0; $i < count($arrData); $i++) {
			            unset($arrData[$i]['est_id']);
		*/
		$nameReport = academico::t("Academico", "Reporte Materias No Asignadas");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionReportdistributivodocente() {
		$searchModel = new DistributivoAcademicoSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$params = Yii::$app->request->queryParams;
		$_return = $searchModel->getListadoDistributivoMateriaProfresor($params, false, 1);

		$gridColumns = $_return[0];
		$dataProvider = $_return[1];
		return $this->render('reportdistributivodocente', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'gridColumns' => $gridColumns,
		]);

	}
	public function actionExportpdflistadodocente() {
		$searchModel = new DistributivoAcademicoSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$params = Yii::$app->request->queryParams;
		$res = $searchModel->getListadoDistributivoBloqueDocente($params, true, 2);
		$rep = new ExportFile();
		$this->view->title = academico::t("Academico", "Reporte Distributivo"); // Titulo del reporte

		$rep->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
		$rep->createReportPdf(
			$this->render('_reportView', ['res' => $res]));
		// get your HTML raw content without any layouts or scripts

		// return the pdf output as per the destination setting
		$rep->mpdf->Output('Reporte_distributivo_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
	}

	public function actionReportematrizdistributivo() {
		$searchModel = new DistributivoAcademicoSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->getReportematrizdistributivo($params, false, 1);
		return $this->render('reportematrizdistributivo', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionExpexcelmatrizdistributivo() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S");
		$arrHeader = array(
			Yii::t("formulario", "C??digo_IES"),
			Yii::t("formulario", "Docente"),
			Yii::t("formulario", "Tipo de Identificaci??n"),
			Yii::t("formulario", "Identificaci??n"),
			Yii::t("formulario", "N?? Documento"),
			Yii::t("formulario", "Horas Clase"),
			Yii::t("formulario", "Horas Tutor??as"),
			Yii::t("formulario", "Horas Administrativas"),
			Yii::t("formulario", "Horas Investigaci??n"),
			Yii::t("formulario", "Horas Vinculaci??n"),
			Yii::t("formulario", "Horas Otras Actividades"),
			Yii::t("formulario", "Horas Clase Tercer Nivel"),
			Yii::t("formulario", "Horas Clase Cuarto Nivel"),
			Yii::t("formulario", "Calificaci??n Actividades Docencia "),
			Yii::t("formulario", "Calificaci??n Actividades Investigaci??n"),
		);
		$searchModel = new DistributivoAcademicoSearch();
		$data = Yii::$app->request->get();
		$arrSearch["periodo"] = $data['periodo'];
		$arrSearch["dedicacion"] = $data['dedicacion'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getReportematrizdistributivoexcel(NULL, true);
		} else {
			$arrData = $searchModel->getReportematrizdistributivoexcel($arrSearch, true);
		}
		/*for ($i = 0; $i < count($arrData); $i++) {
			            unset($arrData[$i]['est_id']);
		*/
		$nameReport = academico::t("Academico", "Reporte Matriz Distributivo Docente");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}
	public function actionReportemateriasparalelos() {
		$searchModel = new DistributivoAcademicoSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->getReportemateriasparalelos($params, false, 1);
		return $this->render('reportemateriasparalelos', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionExpexcelmateriasparalelo() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
		$arrHeader = array(
			Yii::t("formulario", "Docente"),
			Yii::t("formulario", "Materia"),
			Yii::t("formulario", "Paralelo"),
			Yii::t("formulario", "Horario"),
		);
		$searchModel = new DistributivoAcademicoSearch();
		$data = Yii::$app->request->get();
		$arrSearch["periodo"] = $data['periodo'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getReportemateriasparalelosexcel(NULL, true);
		} else {
			$arrData = $searchModel->getReportemateriasparalelosexcel($arrSearch, true);
		}

		$nameReport = academico::t("Academico", "Reporte Materias Paralelo");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionReportdistributivoposgrado() {
		$searchModel = new DistributivoAcademicoSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->getListadoDistributivoPosgrados($params, false, 1);
		return $this->render('reportdistributivo_posgrado', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);

	}

	public function actionExpexceldistributivoposgrado() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T");
		$arrHeader = array(
			Yii::t("formulario", "Profesor"),
			Yii::t("formulario", "3er. Nivel"),
			Yii::t("formulario", "4to Nivel"),
			Yii::t("formulario", "Maestr??a"),
			Yii::t("formulario", "Grupo Paralelo"),
			Yii::t("formulario", "Materias"),
			Yii::t("formulario", "D??as"),
			Yii::t("formulario", "Hora"),
			Yii::t("formulario", "No. Estudiantes"),
			Yii::t("formulario", "Total Horas a dictar"),
			Yii::t("formulario", "Modalidad"),
			Yii::t("formulario", "Total cr??dito"),
		);
		$searchModel = new DistributivoAcademicoSearch();
		$data = Yii::$app->request->get();
		$arrSearch = array();
		if (count($data) > 0) {
			$arrSearch["periodo"] = $data['periodo'];
			$arrSearch["tipo_asignacion"] = $data['tipo_asignacion'];
			$arrSearch["modalidad"] = $data['modalidad'];
			$arrSearch["mes"] = $data['mes'];
		}
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getListadoDistributivoPosgradosexcel(array(), true);
		} else {
			$arrData = $searchModel->getListadoDistributivoPosgradosexcel($arrSearch, true);
		}
		for ($i = 0; $i < count($arrData); $i++) {
			unset($arrData[$i]['no_cedula']);
			unset($arrData[$i]['tiempo_dedicacion']);
			unset($arrData[$i]['tdis_nombre']);
			unset($arrData[$i]['promedio']);
		}
		$nameReport = academico::t("Academico", "Reporte Distributivo Posgrado");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionExppdfdistributivoposgrado() {
		$report = new ExportFile();
		$this->view->title = academico::t("Academico", "Reporte Distributivo Posgrado"); // Titulo del reporte
		$arr_head = array(
			Yii::t("formulario", "Profesor"),
			Yii::t("formulario", "3er. Nivel"),
			Yii::t("formulario", "4to Nivel"),
			Yii::t("formulario", "Maestr??a"),
			Yii::t("formulario", "Grupo Paralelo"),
			Yii::t("formulario", "Materias"),
			Yii::t("formulario", "D??as"),
			Yii::t("formulario", "Hora"),
			Yii::t("formulario", "No. Estudiantes"),
			Yii::t("formulario", "Total Horas a dictar"),
			Yii::t("formulario", "Modalidad"),
			Yii::t("formulario", "Aula"),
			Yii::t("formulario", "Total cr??dito"),
			Yii::t("formulario", "Promedio"),
		);
		$searchModel = new DistributivoAcademicoSearch();
		//$paca = new PeriodoAcademico();
		//$saca = new SemestreAcademico();
		$data = Yii::$app->request->get();
		$arrSearch = array();
		if (count($data) > 0) {
			$arrSearch["periodo"] = $data['periodo'];
			$arrSearch["tipo_asignacion"] = $data['tipo_asignacion'];
			$arrSearch["modalidad"] = $data['modalidad'];
			$arrSearch["mes"] = $data['mes'];
		}

		if (empty($arrSearch)) {
			$arr_body = $searchModel->getListadoDistributivoPosgradosexcel(array(), true);
		} else {
			$arr_body = $searchModel->getListadoDistributivoPosgradosexcel($arrSearch, true);
		}

		$paca = PeriodoAcademico::findOne(['paca_id' => $arrSearch['periodo'], 'paca_estado' => 1, 'paca_estado_logico' => 1]);
		$baca = BloqueAcademico::findOne(['baca_id' => $paca['baca_id'], 'baca_estado' => 1, 'baca_estado_logico' => 1]);
		$pame = PeriodoAcademicoMensualizado::findOne(['pame_id' => $arrSearch['mes'], 'pame_estado' => 1, 'pame_estado_logico' => 1]);
		$report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
		$report->createReportPdf(
			$this->render('exportpospdf', [
				'arr_head' => $arr_head,
				'arr_body' => $arr_body,
				'baca' => $baca,
				'pame' => $pame,
			])
		);
		$report->mpdf->Output('Reporte_distributivo_posgrado_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
		return;
	}

	public function actionMatriculados() {
		$searchModel = new PlanificacionSearch();
		//$searchModel = new ModalidadEstudioUnidadSearch();
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->getListadoMatriculados($params, false, 1);
		return $this->render('matriculados', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	public function actionExpexcelmatriculados() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
		$arrHeader = array(
			//Yii::t("formulario", "No."),
			Yii::t("formulario", "Estudiante"),
			Yii::t("formulario", "Cedula"),
			Yii::t("formulario", "Semestre Academico"),
			Yii::t("formulario", "Carrera"),
			Yii::t("formulario", "Unidad Academica"),
			Yii::t("formulario", "Modalidad"),
			Yii::t("formulario", "Matricula"),
		);
		$searchModel = new PlanificacionSearch();
		$data = Yii::$app->request->get();
		$arrSearch["periodo"] = $data['periodo'];
		$arrSearch["modalidad"] = $data['modalidad'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getListadoMatriculadosexcel(NULL, true);
		} else {
			$arrData = $searchModel->getListadoMatriculadosexcel($arrSearch, true);
		}
		for ($i = 0; $i < count($arrData); $i++) {
			unset($arrData[$i]['est_id']);
		}
		$nameReport = academico::t("Academico", "Reporte Matriculados");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionMatriculadospormateria() {
		$searchModel = new DistributivoAcademicoSearch();
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->getListadoMatriculadosporMateria($params, false, 1);
		return $this->render('matriculadospormateria', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionExpexcel() {
		\app\models\Utilities::putMessageLogFile("AAAAA");
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
		$arrHeader = array(
			//Yii::t("formulario", "No."),
			Yii::t("formulario", "Periodo"),
			Yii::t("formulario", "Estudiante"),
			Yii::t("formulario", "C??dula"),
			Yii::t("formulario", "Asignatura"),
			Yii::t("formulario", "Unidad Academica"),
			Yii::t("formulario", "Modalidad"),
			Yii::t("formulario", "Matr??cula"),
			Yii::t("formulario", "Carrera"),
		);

		$searchModel = new DistributivoAcademicoSearch();
		$data = Yii::$app->request->get();
		$arrSearch["periodos"] = $data['periodos'];
		$arrSearch["modalidades"] = $data['modalidades'];
		$arrSearch["asignaturas"] = $data['asignaturas'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getListadoMatripormateriaexcel(NULL, true);
		} else {
			$arrData = $searchModel->getListadoMatripormateriaexcel($arrSearch, true);
		}
		for ($i = 0; $i < count($arrData); $i++) {
			unset($arrData[$i]['est_id']);
		}
		$nameReport = academico::t("Academico", "Reporte Matriculados por Materia");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionReportemallas() {
		$emp_id = @Yii::$app->session->get("PB_idempresa");
		$mod_reporte = new Reporte();
		$mod_unidad = new UnidadAcademica();
		$mod_modalidad = new Modalidad();
		$modcarrera = new EstudioAcademico();
		$mod_malla = new MallaAcademica();
		$data = Yii::$app->request->get();

		if ($data['PBgetFilter']) {
			$arrSearch["unidad"] = $data['unidad'];
			$arrSearch["modalidad"] = $data['modalidad'];
			$arrSearch["carrera"] = $data['carrera'];
			$arrSearch["malla"] = $data['malla'];
			\app\models\Utilities::putMessageLogFile('unidad  controlrrrr: ' . $data['unidad']);
			\app\models\Utilities::putMessageLogFile('modalidad  controlrrrr: ' . $data['modalidad']);
			\app\models\Utilities::putMessageLogFile('carrera  controlrrrr: ' . $data['carrera']);
			\app\models\Utilities::putMessageLogFile('malla  controlrrrr: ' . $data['malla']);

			$model = $mod_reporte->consultarMallasacademicas($arrSearch, false);
			return $this->render('reportemallas', [
				'model' => $model,
			]);
		} else {
			$model = $mod_reporte->consultarMallasacademicas(null, false);
		}
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			if (isset($data["getmodalidad"])) {
				\app\models\Utilities::putMessageLogFile('unidad 3333: ' . $data['uaca_id']);
				$modalidad = $mod_modalidad->consultarModalidad($data['uaca_id'], $emp_id);
				$message = array("modalidad" => $modalidad);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data["getcarrera"])) {
				\app\models\Utilities::putMessageLogFile('unidad 44444: ' . $data['uaca_id']);
				\app\models\Utilities::putMessageLogFile('modalidad 55555: ' . $data['mod_id']);
				$carrera = $modcarrera->consultarCarreraModalidad($data["uaca_id"], $data["mod_id"]);
				$message = array("carrera" => $carrera);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
			if (isset($data['getmalla'])) {
				\app\models\Utilities::putMessageLogFile('unidad lllll: ' . $data['uaca_id']);
				\app\models\Utilities::putMessageLogFile('modalidad llllll: ' . $data['mod_id']);
				\app\models\Utilities::putMessageLogFile('carrera llllll: ' . $data['eaca_id']);
				$mallaca = $mod_malla->consultarmallasxcarrera($data['uaca_id'], $data['mod_id'], $data['eaca_id']);
				$message = array('mallaca' => $mallaca);
				return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
			}
		}
		$arr_unidad = $mod_unidad->consultarUnidadAcademicasxUteg();
		$modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], $emp_id);
		$carrera = $modcarrera->consultarCarreraModalidad($arr_unidad[0]["id"], $modalidad[0]["id"]);
		$mallaca = $mod_malla->consultarmallasxcarrera($arr_unidad[0]["id"], $modalidad[0]["id"], $carrera[0]["id"]);
		\app\models\Utilities::putMessageLogFile('unidad sssss: ' . $arr_unidad[0]["id"]);
		\app\models\Utilities::putMessageLogFile('modalidad  sssss: ' . $modalidad[0]["id"]);
		\app\models\Utilities::putMessageLogFile('carrera  sssss: ' . $carrera[0]["id"]);
		\app\models\Utilities::putMessageLogFile('malla  ssss: ' . $mallaca[0]["id"]);
		//$arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], $emp_id);
		//$arr_carrera = $modcarrera->consultarCarreraModalidad($arr_unidad[0]["id"], $modalidad[0]["id"]);
		//$arr_malla = $mod_malla->consultarmallasxcarrera($arr_unidad[0]["id"], $modalidad[0]["id"], $carrera[0]["id"]);

		return $this->render('indexmallas', [
			'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_unidad), "id", "name"),
			'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $modalidad), 'id', 'name'),
			'arr_carrera' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $carrera), 'id', 'name'),
			'arr_malla' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $mallaca), 'id', 'name'),
			'model' => $model,
		]);

	}

	public function actionExpexcelmallas() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
		$arrHeader = array(
			//Yii::t("formulario", "No."),
			Yii::t("formulario", "Malla Academica"),
			Yii::t("formulario", "Asignaturas"),
			Yii::t("formulario", "Unidad"),
			Yii::t("formulario", "Modalidad"),
			Yii::t("formulario", "Semestre"),
			Yii::t("formulario", "Cr??dito"),
			Yii::t("formulario", "Formaci??n Malla Academica"),
			Yii::t("formulario", "Materia Requisito"),
		);
		$mod_reporte = new Reporte();
		$data = Yii::$app->request->get();
		\app\models\Utilities::putMessageLogFile('unidad ' . $data['unidad']);
		\app\models\Utilities::putMessageLogFile('modalidad ' . $data['modalidad']);
		\app\models\Utilities::putMessageLogFile('carrera ' . $data['carrera']);
		\app\models\Utilities::putMessageLogFile('malla ' . $data['malla']);
		$arrSearch["unidad"] = $data['unidad'];
		$arrSearch["modalidad"] = $data['modalidad'];
		$arrSearch["carrera"] = $data['carrera'];
		$arrSearch["malla"] = $data['malla'];
		//\app\models\Utilities::putMessageLogFile('datos de base '. $arrData);
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $mod_reporte->consultarMallasacademicas($arrSearch, true);
		} else {
			$arrData = $mod_reporte->consultarMallasacademicas(NULL, true);
		}
		for ($i = 0; $i < count($arrData); $i++) {
			unset($arrData[$i]['carrera']);
			unset($arrData[$i]['unidad_estudio']);
		}
		$nameReport = academico::t("Academico", "Reporte Malla Acad??mica");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionReporteinscritos() {
		$searchModel = new DistributivoAcademicoSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->getListadoreportInscriptos($params, false, 1);
		return $this->render('reporteinscritos', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionExpexcelinscritos() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
		$arrHeader = array(
			//Yii::t("formulario", "No."),
			Yii::t("formulario", "Nombre Completo"),
			Yii::t("formulario", "C??dula"),
			Yii::t("formulario", "Correo"),
			Yii::t("formulario", "Tel??fono"),
			Yii::t("formulario", "Matr??cula"),
			Yii::t("formulario", "Unidad Acad??mica"),
			Yii::t("formulario", "Modalidad"),
			Yii::t("formulario", "Carrera"),
		);
		$searchModel = new DistributivoAcademicoSearch();
		$data = Yii::$app->request->get();
		$arrSearch["periodo"] = $data['periodo'];
		$arrSearch["modalidad"] = $data['modalidad'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getListadoReporteinscritosexcel(NULL, true);
		} else {
			$arrData = $searchModel->getListadoReporteinscritosexcel($arrSearch, true);
		}
		for ($i = 0; $i < count($arrData); $i++) {
			unset($arrData[$i]['unidad_estudio']);
		}
		$nameReport = academico::t("Academico", "Reporte Inscritos");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}

	public function actionPromedios() {
		//$searchModel = new EstudianteCarreraProgramaSearch();
		$mod_reporte = new Reporte();
		$mod_periodo = new PlanificacionEstudiante();
		$data = Yii::$app->request->get();
		if ($data['PBgetFilter']) {
			\app\models\Utilities::putMessageLogFile('estudiante********: ' . $data['estudiante']);
			$arrSearch["estudiante"] = $data['estudiante'];

			$model = $mod_reporte->getListadoPromedio($arrSearch, false);
			return $this->render('promedios', [
				"model" => $model,
			]);
		} else {
			$model = $mod_reporte->getListadoPromedio(null, false);
		}
		$arr_estudiante = $mod_periodo->busquedaEstudianteplanificacion();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$params = Yii::$app->request->queryParams;
		//$dataProvider = $searchModel->getListadoPromedio($params,false,1);
		//$model = $mod_reporte->
		return $this->render('indexpromedios', [
			//'searchModel' => $searchModel,
			'arr_estudiante' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_estudiante), 'id', 'name'),
			'model' => $model,
		]);
	}

	public function actionExpexcelpromedios() {
		ini_set('memory_limit', '256M');
		$content_type = Utilities::mimeContentType("xls");
		$nombarch = "Report-" . date("YmdHis") . ".xls";
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=" . $nombarch);
		header('Cache-Control: max-age=0');
		$colPosition = array("C", "D", "E", "F", "G", "H", "I");
		$arrHeader = array(
			Yii::t("formulario", "Carrera"),
			Yii::t("formulario", "Nombres Completos"),
			Yii::t("formulario", "Asignaturas"),
			Yii::t("formulario", "Promedio"),
		);
		$searchModel = new EstudianteCarreraProgramaSearch();
		$data = Yii::$app->request->get();
		$arrSearch["estudiante"] = $data['estudiante'];
		$arrData = array();
		if (empty($arrSearch)) {
			$arrData = $searchModel->getListadoPromediosexcel(NULL, true);
		} else {
			$arrData = $searchModel->getListadoPromediosexcel($arrSearch, true);
		}
		for ($i = 0; $i < count($arrData); $i++) {
			unset($arrData[$i]['estado_nota']);
			unset($arrData[$i]['malla']);
		}
		$nameReport = academico::t("Academico", "Reporte Promedios");
		Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
		exit;
	}
}