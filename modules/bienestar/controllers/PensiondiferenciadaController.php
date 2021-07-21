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

use app\models\Persona;
use app\models\academico\models\Estudiante;
use app\models\academico\models\EstudianteCarreraPrograma;
use app\models\academico\models\ModalidadEstudioUnidad;

use app\modules\bienestar\Module as Bienestar;

Bienestar::registerTranslations();

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
        $_SESSION['JSLANG']['Must be Fill all information in fields with label *.'] = Bienestar::t("profesor", "Must be Fill all information in fields with label *.");       
    }

    public function actionIndex(){
        
        
        return $this->render('index', [
            
        ]);
    }

    public function actionNew(){
        $per_id = Yii::$app->session->get("PB_perid");

        // Para los títulos de las secciones
        $secciones = FormularioSeccion::find()->where(['fsec_estado' => 1, 'fsec_estado_logico' => 1])->asArray()->all();
        $tabs = []; // Para almacenar las pestañas del formulario

        // Sección 1
        $persona_model = Persona::find()->where(['per_id' => $per_id, 'per_estado' => 1, 'per_estado_logico' => 1])->asArray()->one();
        $tabs[] = $this->renderPartial('newFormTab1', [
            'persona' => $persona_model
        ]);

        // Sección 2
        $estudiante_modelo = Estudiante::find()->where(['per_id' => $per_id, 'est_activo' => 1, 'est_estado' => 1, 'est_estado_logico' => 1])->asArray()->one();
        $est_id = $estudiante_modelo['est_id'];
        $estudiante_carrera_programa_modelo = EstudianteCarreraPrograma::find()->where(['est_id' => $est_id, 'ecpr_estado' => 1, 'ecpr_estado_logico' => 1])->asArray()->one();
        $meun_id = $estudiante_carrera_programa_modelo['meun_id'];
        $eaca_id = ModalidadEstudioUnidad::find()->where(['meun_id' => $meun_id])->asArray()->one()['eaca_id'];

        

        $items = [];
        for ($i = 0; $i < count($tabs); $i++) { 
            $nombre_seccion = $secciones[$i]['fsec_descripcion'];
            if($i == 0){
                $items[] = [
                    'label' => Bienestar::t('pensiondiferenciada', $nombre_seccion),
                    'content' => $tabs[$i],
                    'active' => true
                ];
            }
            else{
                $items[] = [
                    'label' => Bienestar::t('pensiondiferenciada', $nombre_seccion),
                    'content' => $tabs[$i]
                ];
            }
        }

    	/*$items = [
            [
                'label' => Bienestar::t('pensiondiferenciada', "STUDENT'S PERSONAL DATA"),
                'content' => $newFormTab1,
                'active' => true
            ],
            [
                'label' => Bienestar::t('pensiondiferenciada', "ACADEMIC DATA"),
                'content' => $newFormTab2,
            ],
            [
                'label' => Bienestar::t('pensiondiferenciada', "SOCIAL AND ECONOMIC ASPECTS VALIDATION"),
                'content' => $newFormTab3,
            ],
            [
                'label' => Bienestar::t('pensiondiferenciada', "DATA OF THE PERSON FUNDING YOUR STUDIES"),
                'content' => $newFormTab4,
            ],
            [
                'label' => Bienestar::t('pensiondiferenciada', "CATASTROPHIC SITUATION"),
                'content' => $newFormTab5,
            ],
            [
                'label' => Bienestar::t('pensiondiferenciada', "DISABILITY AND MINORITY GROUPS"),
                'content' => $newFormTab6,
            ],
            [
                'label' => Bienestar::t('pensiondiferenciada', "DISABILITY CONDITION CHARACTERISTICS"),
                'content' => $newFormTab7,
            ],
            [
                'label' => Bienestar::t('pensiondiferenciada', "OUTSTANDING ACTIVITIES"),
                'content' => $newFormTab8,
            ]
        ];*/

        return $this->render('new', ['items' => $items]);
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