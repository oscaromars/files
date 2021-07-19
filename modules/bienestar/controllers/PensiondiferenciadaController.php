<?php

namespace app\modules\bienestar\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\models\ExportFile;

use app\modules\bienestar\models\CriterioCabecera;
use app\modules\bienestar\models\CriterioDetalle;
use app\modules\bienestar\models\FormularioCondicionesPonderaciones;
use app\modules\bienestar\models\FormularioEstudiante;
use app\modules\bienestar\models\FormularioEstudianteCampo;
use app\modules\bienestar\models\FormularioEstudianteCriterio;
use app\modules\bienestar\models\FormularioFamiliaresDiscapacitados;
use app\modules\bienestar\models\FormularioSeccion;
use app\modules\bienestar\models\FormularioSeccionCampo;

use app\modules\bienestar\Module as bienestar;

bienestar::registerTranslations();

/**
 * Controlador para el manejo de la pensión diferenciada para un estudiante. Este comprende lo que son las pantallas de consulta y revisión para las entidades como Bienestar Estudiantil, y el formulario para el estudiante.
 * @author Jorge Paladines <analistadesarrollo04@uteg.edu.ec>
 */

class PensiondiferenciadaController extends \app\components\CController
{
	/**
     * {@inheritdoc}
     */
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionFormulario(){
        $_SESSION['JSLANG']['Must be Fill all information in fields with label *.'] = Academico::t("profesor", "Must be Fill all information in fields with label *.");

        
    }

    public function actionIndex(){
    	// Get todos los períodos
    	$model_periodo = new PeriodoAcademico();
    	$arr_periodos = $model_periodo->consultarPeriodosActivos();

    	// Get asignaturas por período académico
    	$model_asignaturas_por_periodo = new AsignaturasPorPeriodo();
    	$arr_asig_por_per = $model_asignaturas_por_periodo->consultarAsignaturasPorPeriodo(true);

        // Sección de filtrado
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter'])
        {
            $arrSearch["periodo"] = $data['periodo'];
            $arrSearch["asignatura"] = $data['asignatura'];
            $arrSearch["numero_paralelos"] = $data['numero_paralelos'];
            $arrSearch["cupos"] = $data['cupos'];

            $dataProvider = $this->consultarReportParalelosPorMateria($arrSearch);

            return $this->render('index-grid', [
	            'model' => $dataProvider
            ]);
        }
        else
        {
            $dataProvider = $this->consultarTodosParalelosPorMateria();
        }

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        return $this->render('index', [
            'model' => $dataProvider,
            'arr_periodos' => ArrayHelper::map(array_merge([["id" => 0, "nombre" => Yii::t("formulario", "Grid")]], $arr_periodos), "id", "nombre"),
            'arr_asig_por_per' => ArrayHelper::map(array_merge([["id" => 0, "asi_descripcion" => Yii::t("formulario", "Grid")]], $arr_asig_por_per), "aspe_id", "asi_descripcion"),
        ]);
    }

    public function actionNew(){
    	// Get todos los períodos
    	$model_periodo = new PeriodoAcademico();
    	$arr_periodos = $model_periodo->consultarPeriodosActivos();

    	// Get asignaturas por período académico
    	$model_asignaturas_por_periodo = new AsignaturasPorPeriodo();
    	$arr_asig_por_per = $model_asignaturas_por_periodo->consultarAsignaturasPorPeriodo(true);

    	// Arreglo de solamente los códigos de las materias
    	$arr_codigos = array();
    	foreach ($arr_asig_por_per as $key => $value) {
    		$arr_codigos[] = $value['asi_codigo'];
    	}

        $mod_par = new Paralelo();
        $paralelos = $mod_par->getAllParalelos();

    	return $this->render('new', [
            'arr_periodos' => $arr_periodos,
            'arr_asig_por_per' => $arr_asig_por_per,
            'arr_codigos' => $arr_codigos,
            'paralelos' => $paralelos
        ]);
    }

    public function actionSave(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $per_id = $data['per_id'];
            $asper_id = $data['asper_id'];
            $asi_codigo = $data['asi_codigo'];
            $mppe_num_paralelos = $data['mppe_num_paralelos'];
            $mppd_cupo = $data['mppd_cupo'];

            $max_par = $data['max_par'];
            $max_cupo = $data['max_cupo'];
            $min_par = $data['min_par'];
            $min_cupo = $data['min_cupo'];

            $con = Yii::$app->db_bienestar;
            $transaction = $con->beginTransaction();

            try {
                if (!Utilities::validateTypeField($per_id, "number") ||
                    !Utilities::validateTypeField($asper_id, "numer") ||
                    !Utilities::validateTypeField($asi_codigo, "alfanumerico") ||
                    !Utilities::validateTypeField($mppe_num_paralelos, "number") ||
                    !Utilities::validateTypeField($mppd_cupo, "number") ||
                    !($min_par <= $mppe_num_paralelos && $mppe_num_paralelos <= $max_par) ||
                    !($min_cupo <= $mppd_cupo && $mppd_cupo <= $max_cupo) 
                	){
                    $message = array(
                        "wtmessage" => bienestar::t("paralelospormateria", "Please correctly fill all the fields"),
                        "title" => Yii::t('jslang', 'Fail'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }

                $respuesta = $this->insertarParalelosPorMateria($con, $transaction, $asper_id, $mppe_num_paralelos, $mppd_cupo);

                if ($respuesta) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            }
            catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error"),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;            
        }
    }

    public function actionEdit($id){
    	// Decodificar el id
        $mppd_id = base64_decode($id);

        // Get todos los períodos
    	$model_periodo = new PeriodoAcademico();
    	$arr_periodos = $model_periodo->consultarPeriodosActivos();

    	// Get asignaturas por período académico
    	$model_asignaturas_por_periodo = new AsignaturasPorPeriodo();
    	$arr_asig_por_per = $model_asignaturas_por_periodo->consultarAsignaturasPorPeriodo(true);

    	// Arreglo de solamente los códigos de las materias
    	$arr_codigos = array();
    	foreach ($arr_asig_por_per as $key => $value) {
    		$arr_codigos[] = $value['asi_codigo'];
    	}

    	// Máximo número de paralelos disponibles
    	$max_paralelos = $this->getMaxNumParalelos();

    	// Información del "modelo" a editar
    	$dataProvider = $this->consultarParaleloPorMateria($mppd_id);
        
    	return $this->render('edit', [
    		'model' => $dataProvider,
            "arr_periodos" => $arr_periodos,
            'arr_asig_por_per' => $arr_asig_por_per,
            'arr_codigos' => $arr_codigos,
            'max_paralelos' => $max_paralelos,
        ]);
    }

    public function actionUpdate(){
    	if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $mppd_id = $data['mppd_id'];
            $mppd_cupo_old = $data['mppd_cupo_old'];
            $mppd_cupo = $data['mppd_cupo'];
            $mppd_cupo_actual = $data['mppd_cupo_actual'];

            $max_cupo = $data['max_cupo'];
            $min_cupo = $data['min_cupo'];

            $mppd_fecha_inicio = $data['mppd_fecha_inicio'];
            $mppd_fecha_fin = $data['mppd_fecha_fin'];
            
            $con = Yii::$app->db_bienestar;
            $transaction = $con->beginTransaction();
            
            try {
            	if (!Utilities::validateTypeField($mppd_cupo, "number") ||
                    !($min_cupo <= $mppd_cupo && $mppd_cupo <= $max_cupo) 
                	){
                    $message = array(
                        "wtmessage" => bienestar::t("paralelospormateria", "Please correctly fill all the fields"),
                        "title" => Yii::t('jslang', 'Fail'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }

                $cupo_dif = $mppd_cupo - $mppd_cupo_old;
                $cupo_actual_nuevo = $mppd_cupo_actual + $cupo_dif;

                $respuesta = $this->actualizarParalelo($con, $transaction, $mppd_id, $mppd_cupo, $cupo_actual_nuevo, $mppd_fecha_inicio, $mppd_fecha_fin);

                if ($respuesta) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully update."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error"),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;            
        }
    }

    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $mppd_id = $data["mppd_id"];

                $respuesta = $this->eliminarParalelo($mppd_id);

                if ($respuesta) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully update."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error"),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
        }
    }

    // Función para exportar un excel. Esta es llamada desde el javascript paralelospormateria.js
    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrHeader = array(
        	bienestar::t("paralelospormateria", "#"),
            bienestar::t("paralelospormateria", "Period"),
            bienestar::t("paralelospormateria", "Course"),
            bienestar::t("paralelospormateria", "Code"),
            bienestar::t("paralelospormateria", "Classroom"),
            bienestar::t("paralelospormateria", "Total Classrooms"),
            bienestar::t("paralelospormateria", "Quota"),
            bienestar::t("paralelospormateria", "Current Quota")
        );

        $data = Yii::$app->request->get();

        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["numero_paralelos"] = $data['numero_paralelos'];
        $arrSearch["cupos"] = $data['cupos'];

        $arrData = array();

        if (empty($arrSearch)) {
            $arrData = $this->consultarReportParalelosPorMateria(array(), true);
        }
        else {
            $arrData = $this->consultarReportParalelosPorMateria($arrSearch, true);
        }

        $nameReport = bienestar::t("paralelospormateria", "Classroom Allocation");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        
        exit;
    }

    // Función para exportar un pdf. Esta es llamada desde el javascript paralelospormateria.js
    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = bienestar::t("paralelospormateria", "Classroom Allocation"); // Titulo del reporte
        $arrHeader = array(
        	bienestar::t("paralelospormateria", "#"),
            bienestar::t("paralelospormateria", "Period"),
            bienestar::t("paralelospormateria", "Course"),
            bienestar::t("paralelospormateria", "Code"),
            bienestar::t("paralelospormateria", "Classroom"),
            bienestar::t("paralelospormateria", "Total Classrooms"),
            bienestar::t("paralelospormateria", "Quota"),
            bienestar::t("paralelospormateria", "Current Quota")
        );

        $data = Yii::$app->request->get();

        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["numero_paralelos"] = $data['numero_paralelos'];
        $arrSearch["cupos"] = $data['cupos'];

        $arrData = array();

        if (empty($arrSearch)) {
            $arrData = $this->consultarReportParalelosPorMateria(array(), true);
        } else {
            $arrData = $this->consultarReportParalelosPorMateria($arrSearch, true);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical

        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );

        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
}