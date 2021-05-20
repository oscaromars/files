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
use app\modules\academico\models\EstudianteCarreraPrograma;
use app\modules\academico\models\Profesor;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\CabeceraCalificacion;
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

use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\ComponenteUnidad;


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

    private function parciales(){
        return [
            1 => "1° " . academico::t("Academico", "Partial"),
            2 => "2° " . academico::t("Academico", "Partial"),
        ];
    }

    public function actionIndex() {
        $grupo_model    = new Grupo();
        $mod_estudiante = new Estudiante();
        $mod_programa   = new EstudioAcademico();
        $mod_modalidad  = new Modalidad();
        $mod_unidad     = new UnidadAcademica();
        $mod_profesor   = new Profesor();
        $cabeceraCalificacion = new CabeceraCalificacion();
        $mod_periodoActual    = new PeriodoAcademico();   
        $per_id = Yii::$app->session->get("PB_perid");
        //$user_usermane = 'carlos.carrera@mbtu.us';//Yii::$app->session->get("PB_username");
        $user_usermane = Yii::$app->session->get("PB_username");

        $Asignatura_distri = new Asignatura();
        Utilities::putMessageLogFile('58 $user_usermane: ' .$user_usermane);

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            /*if (isset($data["getasignaturas"])) {
                $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"],$data['pro_id'],$data["uaca_id"],$data["mod_id"]);
                $message = array("asignatura" => $asignatura);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }*/
             \app\models\Utilities::putMessageLogFile('Id de profesor: '.$data['pro_id']);
            if (isset($data["getasignaturas_prof_periodo"])) {
                $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"],$data['pro_id'],$data["uaca_id"],$data["mod_id"]);
                $paralelo_clcf= $Asignatura_distri->getCourseProfesor($data['pro_id'],$data["paca_id"],$asignatura[0]["id"]);
                $message = array("asignatura" => $asignatura,"paralelo_clcf" => $paralelo_clcf);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            //Peridood academico
            if (isset($data["getasignaturas_prof"])) {
                $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"],$data['pro_id'],$data["uaca_id"],$data["mod_id"]);

                $paralelo_clcf= $Asignatura_distri->getCourseProfesor($data['pro_id'],$data["paca_id"],$asignatura[0]["id"]);


                $message = array("asignatura" => $asignatura,"paralelo_clcf" => $paralelo_clcf,);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

             if (isset($data["getparalelos_asig"])) {
                $asignatura = $data['asi_id'] ; //$Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"],$data['pro_id'],$data["uaca_id"],$data["mod_id"]);

                $paralelo_clcf= $Asignatura_distri->getCourseProfesor($data['pro_id'],$data["paca_id"],$asignatura);


                $message = array("paralelo_clcf" => $paralelo_clcf,);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }


        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            //$search = $data['search'];
             Utilities::putMessageLogFile("124  PBgetFilter");
            $unidad = (isset($data['unidad']) && $data['unidad'] > 0)?$data['unidad']:NULL;
            //$modalidad = (isset($data['modalidad']) && $data['modalidad'] > 0)?$data['modalidad']:NULL;
            $periodo = (isset($data['periodo']) && $data['periodo'] > 0)?$data['periodo']:NULL;
            $materia = (isset($data['materia']) && $data['materia'] > 0)?$data['materia']:NULL;
            $profesor = (isset($data['profesor']) && $data['profesor'] > 0)?$data['profesor']:NULL;
            $paralelo = (isset($data['paralelo']) && $data['paralelo'] > 0)?$data['paralelo']:NULL;
            //$model = $distributivo_model->getListadoDistributivo($search, NULL, $periodo);
            if($unidad <= 0){
                $unidad="";
            }

            $arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllSearch($unidad,$periodo,$materia,$profesor,$paralelo);
             return $this->render('index-grid', [
                            "model" => $arr_estudiante,
                ]);
        }

        $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
        
        $arr_periodoActual = [$mod_periodoActual->getPeriodoAcademicoActual()];

        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);

        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);


        if (in_array(['id' => '6'], $arr_grupos)) {
            //Es Cordinados
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas(); 
            Utilities::putMessageLogFile("Paso por cordinador");
            // Utilities::putMessageLogFile(print_r($arr_profesor_all,true));
            //$arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
            $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
             //Obtener paralelo
            $arr_paralelo_clcf= $Asignatura_distri->getCourseProfesor($arr_profesor_all[0]['pro_id'],$arr_periodoActual[0]["id"],$asignatura[0]["id"]);
            //$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocente($arr_periodoActual[0]["id"],$asignatura[0]['id'],$arr_profesor_all[0]["pro_id"]);
            $arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteSearch($arr_ninteres[0]["id"],$arr_periodoActual[0]["id"],$asignatura[0]['id'],$arr_profesor_all[0]["pro_id"],$arr_paralelo_clcf[0]["id"]);
        }else{
            Utilities::putMessageLogFile("Paso  no Cordinador");
            //No es Cordinador
            //$arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
           
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
            // Utilities::putMessageLogFile(print_r($arr_profesor_all,true));

            $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
            //$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocente($arr_periodoActual[0]["id"],$asignatura[0]['id'],$arr_profesor["Id"]);
             //Obtener paralelo
             // $arr_paralelo_clcf = $Asignatura_distri->getCourseProfesor($arr_profesor_all[0]['pro_id'],$arr_periodoActual[0]["id"],$asignatura[0]["id"]);
             $arr_paralelo_clcf = [];
             $arr_estudiante    = $cabeceraCalificacion->consultaCalificacionRegistroDocenteSearch($arr_ninteres[0]["id"],$arr_periodoActual[0]["id"],$asignatura[0]['id'],$arr_profesor_all[0]['pro_id']);
        }
        //Obtiene el grupo id del suaurio

        return $this->render('index', [
                    'model' => $arr_estudiante,
                    'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
                    'arr_periodoActual' => ArrayHelper::map(array_merge($arr_periodoActual), "id", "nombre"),
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge( $arr_modalidad), "id", "name"),
                   // 'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_carrerra1), "id", "name"),
                    'arr_estados' => $this->estados(),
                    'arr_profesor_all' => ArrayHelper::map(array_merge( $arr_profesor_all), "pro_id", "nombres"),
                    'arr_paralelo_clcf' => ArrayHelper::map(array_merge( $arr_paralelo_clcf), "id", "name"),
        ]);//
    }//function actionIndex
   
    public function actionView() {
        $per_id         = base64_decode($_GET['per_id']);
        $est_id         = base64_decode($_GET['est_id']);
        $persona_model  = new Persona();
        $mod_modalidad  = new Modalidad();
        $mod_unidad     = new UnidadAcademica();
        $modcanal       = new Oportunidad();
        $mod_Estudiante = new Estudiante();

        $dataPersona    = $persona_model->consultaDatosPersonaid($per_id);
        $dataEstudiante = $mod_Estudiante->getEstudiantexestid($est_id);
        $arr_ninteres   = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad  = $mod_modalidad->consultarModalidad($dataEstudiante['unidad'], 1);
        $arr_carrerra1  = $modcanal->consultarCarreraModalidad($dataEstudiante['unidad'], $dataEstudiante['modalidad']);
        return $this->render('view', [
            'arr_persona'    => $dataPersona,
            'arr_estudiante' => $dataEstudiante,
            'arr_ninteres'   => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_ninteres), "id", "name"),
            'arr_modalidad'  => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
            'arr_carrera'    => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_carrerra1), "id", "name"),
            'arr_categorias' => $this->categorias(),
        ]);
    }//function actionView

    public function actionRegistro() {
        $per_id = @Yii::$app->session->get("PB_perid");

        $user_usermane = Yii::$app->session->get("PB_username");

        $mod_programa      = new EstudioAcademico();
        $mod_modalidad     = new Modalidad();
        $mod_unidad        = new UnidadAcademica();
        $Asignatura_distri = new Asignatura();        
        $mod_periodoActual = new PeriodoAcademico(); 
        $mod_profesor      = new Profesor();
        $mod_registro      = new DistributivoAcademico();
        $mod_calificacion  = new CabeceraCalificacion();
        $grupo_model       = new Grupo();
        
        $data = Yii::$app->request->get();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();    

            if (isset($data["getparcial"])) {
                $parcial = $mod_periodoActual->getParcialUnidad($data["uaca_id"]);
                $message = array("parcial" => $parcial);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmateria"])) {               
                $materia       = $Asignatura_distri->getAsignaturaRegistro($data["pro_id"], $data["uaca_id"], $data["mod_id"], $data["paca_id"]);

                $paralelo_clcf = $Asignatura_distri->getCourseProfesor($data['pro_id'],$data["paca_id"],$materia[0]["id"]);

                $message = array("materia" => $materia,"paralelo_clcf" => $paralelo_clcf);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }    
            if (isset($data["getparalelos"])) {               
                $paralelo_clcf = $Asignatura_distri->getCourseProfesor2($data['pro_id'],$data["paca_id"],$data["materia"]);
                //print_r($paralelo_clcf);
                $message = array("paralelo_clcf" => $paralelo_clcf);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }    
        }//if

        $arr_periodoActual = $mod_periodoActual->getAllGrupoEstacion(true);   
        $arr_ninteres      = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad     = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);  
        //$asignatura        = $Asignatura_distri->getAsignaturaRegistro($arr_profesor["Id"],1,1,$arr_periodoActual[0]["id"]);      
        $arr_parcialunidad = $mod_periodoActual->getParcialUnidad(1);

        $arr_grupos        = $grupo_model->getAllGruposByUser($user_usermane);

        

        if (in_array(['id' => '6'], $arr_grupos)) {
            //Es Cordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas();
            $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
            $arr_paralelo_clcf = $Asignatura_distri->getCourseProfesor($arr_profesor_all[0]['pro_id'],$arr_periodoActual[0]["id"],$asignatura[0]["id"]);
            //print_r("Es Cordinador");
        }else{
            //No es coordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
            $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
            $arr_paralelo_clcf = $Asignatura_distri->getCourseProfesor($arr_profesor_all[0]['pro_id'],$arr_periodoActual[0]["id"],$asignatura[0]["id"]);
            //print_r("NO Es Cordinador");
        }

        
       
        return $this->render('register', [
            'arr_periodoActual' => ArrayHelper::map($arr_periodoActual, "id", "value"),
            //'arr_ninteres'      => ArrayHelper::map(array_merge([["id" => "", "name" => Yii::t("formulario", "All")]], $arr_ninteres), "id", "name"),
            'arr_ninteres'      => ArrayHelper::map($arr_ninteres, "id", "name"),
            'arr_modalidad'     => ArrayHelper::map($arr_modalidad, "id", "name"),
            'arr_asignatura'    => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
            'arr_parcial'       => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_parcialunidad), "id", "name"),
            'pro_id'            => $arr_profesor["Id"],
            'model'             => "",
            'arr_profesor_all'  => ArrayHelper::map($arr_profesor_all, "pro_id", "nombres"),
            'arr_paralelo_clcf' => ArrayHelper::map(array_merge([["id" => "", "name" => Yii::t("formulario", "All")]], $arr_paralelo_clcf), "id", "name"),
            //'componente'        => $componenteuni,
            //'campos'            => $campos,
        ]);
    }//function actionRegistro

    public function actionTraermodelo() {
        $per_id         = @Yii::$app->session->get("PB_perid");

        $mod_calificacion  = new CabeceraCalificacion();
        $data = Yii::$app->request->post();

        $arrSearch["periodo"]   = $data['periodo'];
        $arrSearch["unidad"]    = $data['uaca_id'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["materia"]   = $data['materia'];
        $arrSearch["parcial"]   = $data['parcial'];
        $arrSearch["profesor"]  = $data['profesor'];
        $arrSearch["paralelo"]  = $data['paralelo'];

        $model = $mod_calificacion->getRegistroCalificaciones($arrSearch);
        return json_encode($model);
    }//function actionTraerModelo



    public function actionActualizarnota() {
        $per_id            = @Yii::$app->session->get("PB_perid");
        $mod_calificacion  = new CabeceraCalificacion();

        $data   = Yii::$app->request->post();

        $row_id  = array_key_first ( $data['data'] );

        $ccal_id = $data['data'][$row_id]['ccal_id'];

        $total = 0;
        
        $valor  = array();

        $valor["DT_RowId"] = "row_".$row_id;
        $valor["row_num"]  = $row_id;
        //$valor["nparcial"] = $data['data'][$row_id]['nparcial'];
        //$valor["paca_id"]  = $data['data'][$row_id]['paca_id'];
        //$valor["est_id"]   = $data['data'][$row_id]['est_id'];
        //$valor["pro_id"]   = $data['data'][$row_id]['pro_id'];
        //$valor["asi_id"]   = $data['data'][$row_id]['asi_id'];
        //$valor["ecal_id"]  = $data['data'][$row_id]['ecal_id'];
        //$valor["uaca_id"]  = $data['data'][$row_id]['uaca_id'];

        //print_r($valor);die();

        if($ccal_id != 0){
            $valor["ccal_id"] = $data['data'][$row_id]['ccal_id'];

            foreach ($data['data'][$row_id] as $key => $value) {
                if($key!='nparcial' &&
                    $key!='paca_id'  &&
                    $key!='est_id'   &&
                    $key!='pro_id'   &&
                    $key!='asi_id'   &&
                    $key!='ecal_id'  &&
                    $key!='ccal_id'  &&
                    $key!='uaca_id')
                    if($value!=''){
                        $mod_calificacion->actualizarDetalleCalificacionporcomponente($ccal_id,$key,$value);

                        if(!(is_null($value)) && $value != ''){
                            $valor[$key] = $value;
                        }
                        $total = $total + intval($value); 
                    }//if
            }
        }else{
            $paca_id = $data['data'][$row_id]['paca_id'];
            $est_id  = $data['data'][$row_id]['est_id'];
            $pro_id  = $data['data'][$row_id]['pro_id'];
            $asi_id  = $data['data'][$row_id]['asi_id'];
            //$ecal_id = $data['data'][$ccal_id]['nparcial'];

            if($data['data'][$row_id]['nparcial'] == 'Parcial I' )
                $ecal_id = 1;
            if($data['data'][$row_id]['nparcial'] == 'Parcial II' )
                $ecal_id = 2;
            
            $uaca_id = $data['data'][$row_id]['uaca_id'];

            //print_r($ecal_id);die();

            $ccal_id = $mod_calificacion->crearCabeceraCalificacionporcomponente($paca_id,$est_id,$pro_id,$asi_id,$ecal_id,$uaca_id);

            foreach ($data['data'][$row_id] as $key => $value) {

                //if($value == '')$value = 'null';

                if($key!='nparcial' &&
                    $key!='paca_id'  &&
                    $key!='est_id'   &&
                    $key!='pro_id'   &&
                    $key!='asi_id'   &&
                    $key!='ecal_id'  &&
                    $key!='ccal_id'  &&
                    $key!='uaca_id'){
                        if($value!=''){
                            $mod_calificacion->crearDetalleCalificacionporcomponente($ccal_id,$key,$value,$uaca_id);

                            if(!(is_null($value)) && $value != ''){
                                $valor[$key] = $value;
                            }
                            $total = $total + intval($value); 
                        }
                }      
            }
        }//else

        $mod_calificacion->actualizarDetalleCalificacion2($ccal_id,$total);

        header('Content-Type: application/json');

        $valor["total"]  = $total;

        $respuesta["data"] = array();
        $respuesta['data'][] = $valor;
        return json_encode($respuesta, JSON_PRETTY_PRINT); 
    }//function actionActualizarnota

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

                    $mod_asig_per = new AsignaturasPorPeriodo();
                    $per_id = $data['per_id'];
                    $ecal_id = $data['ecal_id'];
                    $aspe_id = $data['aspe_id'];

                    // \app\models\Utilities::putMessageLogFile($asignatura);
                    // \app\models\Utilities::putMessageLogFile($per_id);

                    $carga_archivo = $this->procesarArchivoCalificaciones($archivo_nombre, $aspe_id, $per_id, $mod_asig_per, $ecal_id);

                    if ($carga_archivo['status']) {
                        \app\models\Utilities::putMessageLogFile('no estudiante controller...: ' . $carga_archivo['noalumno']);

                        if (!empty($carga_archivo['noalumno'])){                        
                            $noalumno = ' Se encontró las cédulas '. $carga_archivo['noalumno'] . ' que no pertencen a estudiantes por ende no se cargaron. ';
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
                Utilities::putMessageLogFile($data);
                $per_id = $data['per_id'];
                Utilities::putMessageLogFile($per_id);
                $pro_id = (new Profesor())->getProfesoresxid($per_id)['Id'];
                Utilities::putMessageLogFile($pro_id);
                $materias = (new Asignatura())->getAsignaturasBy($pro_id, NULL, $data['paca_id']);
                Utilities::putMessageLogFile($materias);
                $message = array("asignaturas" => $materias, "paralelos" => $paralelos);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        else {
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

            if($admin){// Es administrador
                $pro_id = $mod_profesor->getProfesoresxid($per_id)['Id'];
                if(isset($pro_id)){ // Y profesor
                    $materias = $asig_mod->getAsignaturasBy($pro_id, NULL, $periodo_actual['id']);
                }
                else{
                    $materias = $asig_mod->getAsignaturasBy();
                }
            }
            else{ // No es administrador
                $pro_id = $mod_profesor->getProfesoresxid($per_id)['Id'];
                if(!isset($pro_id)){ // Ni profesor
                    $materias = []; // En realidad no se debería permitir entrar en la pantalla, pero por si acaso
                }
                else{
                    $materias = $asig_mod->getAsignaturasBy($pro_id, NULL, $periodo_actual['id']);
                }
            }

            // Utilities::putMessageLogFile($periodo_actual['id']);
            
            return $this->render('cargarcalificaciones', [
                'periodos' => ArrayHelper::map(array_merge($periodos), "paca_id", "paca_nombre"),
                'periodo_actual' => $periodo_actual,
                'materias' => ArrayHelper::map(array_merge($materias), "asi_id", "asi_descripcion"),
                'parciales' => $this->parciales(),
                'profesores' => ArrayHelper::map(array_merge($profesores), "per_id", "nombres"),
                'admin' => $admin
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
                    if (!empty($carga_archivo['noalumno'])){                        
                    $noalumno = ' Se encontró las cédulas '. $carga_archivo['noalumno'] . ' que no pertencen a estudiantes por ende no se cargaron. ';
                    }
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data'] .  $noalumno),
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
     * @author  Jorge Paladines analista.desarrollo@uteg.edu.ec
     * @param   
     * @return  
     */
    public function procesarArchivoCalificaciones($fname, $aspe_id, $per_id, $mod_asig_per, $ecal_id){
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "calificaciones/" . $fname;
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = Yii::$app->db_facturacion;
        $transaccion = $con->getTransaction();

        if ($transaccion !== null) { $transaccion = null; }
        else { $transaccion = $con->beginTransaction(); }

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
                $tipo = -1; // O = Licenciado/Asociado; 1 = Masters

                $paca_id = (new PeriodoAcademico())->getPeriodoAcademicoActual()[0]['id'];
                $pro_id = (new Profesor())->getProfesoresxid($per_id)['Id'];

                $ecun_id_master = 7; // Masters

                foreach ($dataArr as $val) {
                    $fila++;

                    if (!is_null($val[5]) || $val[5]) {
                        if($tipo == 0 || $val[5] == "1° PARCIAL") // BACHELOR Y ASSOCIATE
                        {
                            $tipo = 0;
                            if($fila == 1 || $fila == 2){ // No leer la primera fila ni la 2da
                                continue;
                            }
                            else{ //Aquí se hace el cálculo

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

                                $matricula = $val[2];
                                $nombre = $val[4];

                                $estudiante = Estudiante::find()->where(['est_matricula' => $matricula])->asArray()->one();
                                // Si el estudiante no existe, continuar al siguiente, y colocarlo en la lista
                                if(!isset($estudiante)){
                                    $noalumno .= $nombre . " (no es un estudiante registrado), ";
                                    continue;
                                }

                                $est_id = $estudiante['est_id'];

                                $meun_id = EstudianteCarreraPrograma::find()->where(['est_id' => $est_id])->asArray()->one()['meun_id'];
                                $uaca_id = ModalidadEstudioUnidad::find()->where(['meun_id' => $meun_id])->asArray()->one()['uaca_id'];
                                // Si el estudiante no es parte de Asociado/Licenciatura
                                if($uaca_id != 1 && $uaca_id != 2){
                                    $noalumno .= $nombre . " (no pertenece a la Unidad Académica de Asociado ni Licenciatura), ";
                                    continue;
                                }

                                // Esquema Calificación Unidad
                                if($ecal_id == 1){ // 1er Parcial
                                    if($uaca_id == 1){ // Asociado
                                        $ecun_id = 1;
                                    }
                                    elseif ($uaca_id == 2) { // Licenciatura
                                        $ecun_id = 4;
                                    }
                                }
                                elseif($ecal_id == 2){ // 2do Parcial
                                    if($uaca_id == 1){ // Asociado
                                        $ecun_id = 2;
                                    }
                                    elseif ($uaca_id == 2) { // Licenciatura
                                        $ecun_id = 5;
                                    }
                                }

                                // Sacar la asignatura correcta
                                $asignatura = $mod_asig_per->consultarAsignaturaPorUnidad($aspe_id, $uaca_id);
                                if(!isset($asignatura)){
                                    $noalumno .= $nombre . " (no pertenece a esta asignatura), ";
                                    continue;
                                }
                                $asi_id = $asignatura['asi_id'];

                                // Tomar las calificaciones dependiendo del parcial elegido
                                if($ecal_id == 1){ // 1er Parcial
                                    $cal_asin = $val[5]; if($cal_asin > 10 || $cal_asin < 0){ $noalumno .= $nombre . " (la nota 'Act. Asincro 10P' está mal colocada), "; continue; }
                                    $cal_sinc = $val[6]; if($cal_sinc > 10 || $cal_sinc < 0){ $noalumno .= $nombre . " (la nota 'Act. Sincro 10P' está mal colocada), "; continue; }
                                    $cal_aut = $val[7]; if($cal_aut > 20 || $cal_aut < 0){ $noalumno .= $nombre . " (la nota 'Autónomas 20P' está mal colocada), "; continue; }
                                    $cal_eval = $val[8]; if($cal_eval > 20 || $cal_eval < 0){ $noalumno .= $nombre . " (la nota 'Evaluación Par. 20P' está mal colocada), "; continue; }
                                    $cal_exam = $val[9]; if($cal_exam > 40 || $cal_exam < 0){ $noalumno .= $nombre . " (la nota 'Examen 40P' está mal colocada), "; continue; }
                                    // $cal_calif = ($cal_asin * 0.1) + ($cal_sinc * 0.1) + ($cal_aut * 0.2) + ($cal_eval * 0.2) + ($cal_exam * 0.4);
                                    $cal_calif = $cal_asin + $cal_sinc + $cal_aut + $cal_eval + $cal_exam;
                                }
                                elseif($ecal_id == 2){ // 2do Parcial
                                    $cal_asin = $val[12]; if($cal_asin > 10 || $cal_asin < 0){ $noalumno .= $nombre . " (la nota 'Act. Asincro 10P' está mal colocada), "; continue; }
                                    $cal_sinc = $val[13]; if($cal_sinc > 10 || $cal_sinc < 0){ $noalumno .= $nombre . " (la nota 'Act. Sincro 10P' está mal colocada), "; continue; }
                                    $cal_aut = $val[14]; if($cal_aut > 20 || $cal_aut < 0){ $noalumno .= $nombre . " (la nota 'Autónomas 20P' está mal colocada), "; continue; }
                                    $cal_eval = $val[15]; if($cal_eval > 20 || $cal_eval < 0){ $noalumno .= $nombre . " (la nota 'Evaluación Par. 20P' está mal colocada), "; continue; }
                                    $cal_exam = $val[16]; if($cal_exam > 40 || $cal_exam < 0){ $noalumno .= $nombre . " (la nota 'Examen 40P' está mal colocada), "; continue; }
                                    // $cal_calif = ($cal_asin * 0.1) + ($cal_sinc * 0.1) + ($cal_aut * 0.2) + ($cal_eval * 0.2) + ($cal_exam * 0.4);
                                    $cal_calif = ($cal_asin + $cal_sinc + $cal_aut + $cal_eval + $cal_exam) / 5;
                                }

                                $cal_prom = $val[19]; // No usada

                                // Componentes Unidades
                                $componente_unidad_asincrono = ComponenteUnidad::find()->where(['com_id' => 1, 'uaca_id' => $uaca_id])->asArray()->one();
                                $componente_unidad_sincrono = ComponenteUnidad::find()->where(['com_id' => 2, 'uaca_id' => $uaca_id])->asArray()->one();
                                $componente_unidad_autonoma = ComponenteUnidad::find()->where(['com_id' => 3, 'uaca_id' => $uaca_id])->asArray()->one();
                                $componente_unidad_evaluacion = ComponenteUnidad::find()->where(['com_id' => 4, 'uaca_id' => $uaca_id])->asArray()->one();
                                $componente_unidad_examen = ComponenteUnidad::find()->where(['com_id' => 5, 'uaca_id' => $uaca_id])->asArray()->one();

                                $mod_cab_cal = new CabeceraCalificacion();

                                // Si el estudiaante ya tiene calificación, actualizar, sino, insertar
                                $has_cabecera_calificacion = CabeceraCalificacion::find()->where(['est_id' => $est_id, 'asi_id' => $asi_id, 'paca_id' => $paca_id, 'ecun_id' => $ecun_id, 'pro_id' => $pro_id])->asArray()->all();

                                if(empty($has_cabecera_calificacion)){ // INSERT
                                    $ccal_id = $mod_cab_cal->insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id, $cal_calif);

                                    $idDetAsin = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_asincrono['cuni_id'], $cal_asin);
                                    $idDetSinc = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_sincrono['cuni_id'], $cal_sinc);
                                    $idDetAut = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_autonoma['cuni_id'], $cal_aut);
                                    $idDetEval = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_evaluacion['cuni_id'], $cal_eval);
                                    $idDetExam = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_examen['cuni_id'], $cal_exam);
                                }
                                else{ // UPDATE
                                    $cabecera = $has_cabecera_calificacion[0];

                                    $ccal_id = $cabecera['ccal_id'];

                                    $mod_cab_cal->actualizarCabeceraCalificacion($ccal_id, $cal_calif);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_asincrono['cuni_id'], $cal_asin);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_sincrono['cuni_id'], $cal_sinc);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_autonoma['cuni_id'], $cal_aut);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_evaluacion['cuni_id'], $cal_eval);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_examen['cuni_id'], $cal_exam);
                                }
                            }
                        }
                        else if ($tipo == 1 || $val[5] != "1° PARCIAL") // MASTERS
                        { 
                            $tipo = 1;
                            if($fila == 1){ // No leer la primera fila ni la 2da
                                continue;
                            }
                            else
                            {
                                /* 
                                ** $val[5] -> 'Act. Asincro 10P
                                ** $val[6] -> 'Autónomas 20P'
                                ** $val[7] -> 'Evaluación Par. 20P'
                                ** $val[8] -> 'Trabajo Final'
                                ** $val[9] -> 'Calificación' No se usa porque el sistema lo calcula por si acaso esté mal calculado
                                */

                                $matricula = $val[2];
                                $nombre = $val[4];

                                $estudiante = Estudiante::find()->where(['est_matricula' => $matricula])->asArray()->one();
                                // Si el estudiante no existe, continuar al siguiente, y colocarlo en la lista
                                if(!isset($estudiante)){
                                    $noalumno .= $nombre . " (no es un estudiante registrado), ";
                                    continue;
                                }

                                $est_id = $estudiante['est_id'];

                                $meun_id = EstudianteCarreraPrograma::find()->where(['est_id' => $est_id])->asArray()->one()['meun_id'];
                                $uaca_id = ModalidadEstudioUnidad::find()->where(['meun_id' => $meun_id])->asArray()->one()['uaca_id'];
                                // Si el estudiante no es parte de Masters
                                if($uaca_id != 3){
                                    $noalumno .= $nombre . " (no pertenece a la Unidad Académica de Masters), ";
                                    continue;
                                }

                                // Sacar la asignatura correcta
                                $asignatura = $mod_asig_per->consultarAsignaturaPorUnidad($aspe_id, $uaca_id);
                                if(!isset($asignatura)){
                                    $noalumno .= $nombre . " (no pertenece a esta asignatura), ";
                                    continue;
                                }
                                $asi_id = $asignatura['asi_id'];

                                $cal_asin = $val[5]; if($cal_asin > 10 || $cal_asin < 0){ $noalumno .= $nombre . " (la nota 'Act. Asincro 10P' está mal colocada), "; continue; }
                                $cal_aut = $val[6]; if($cal_aut > 20 || $cal_aut < 0){ $noalumno .= $nombre . " (la nota 'Autónomas 20P' está mal colocada), "; continue; }
                                $cal_eval = $val[7]; if($cal_eval > 20 || $cal_eval < 0){ $noalumno .= $nombre . " (la nota 'Evaluación Par. 20P' está mal colocada), "; continue; }
                                $cal_final = $val[8]; if($cal_final > 50 || $cal_final < 0){ $noalumno .= $nombre . " (la nota 'Trabajo Final' está mal colocada), "; continue; }
                                // $cal_calif = ($cal_asin * 0.1) + ($cal_aut * 0.2) + ($cal_eval * 0.2) + ($cal_final * 0.5);
                                $cal_calif = ($cal_asin + $cal_aut + $cal_eval + $cal_final) / 5;

                                // Componentes Unidades
                                $componente_unidad_asincrono = ComponenteUnidad::find()->where(['com_id' => 1, 'uaca_id' => $uaca_id])->asArray()->one();
                                $componente_unidad_autonoma = ComponenteUnidad::find()->where(['com_id' => 3, 'uaca_id' => $uaca_id])->asArray()->one();
                                $componente_unidad_evaluacion = ComponenteUnidad::find()->where(['com_id' => 4, 'uaca_id' => $uaca_id])->asArray()->one();
                                $componente_unidad_final = ComponenteUnidad::find()->where(['com_id' => 6, 'uaca_id' => $uaca_id])->asArray()->one();

                                $mod_cab_cal = new CabeceraCalificacion();

                                // Si el estudiaante ya tiene calificación, actualizar, sino, insertar
                                $has_cabecera_calificacion = CabeceraCalificacion::find()->where(['est_id' => $est_id, 'asi_id' => $asi_id, 'paca_id' => $paca_id, 'pro_id' => $pro_id])->asArray()->all();

                                if(empty($has_cabecera_calificacion)){ // INSERT
                                    $ccal_id = $mod_cab_cal->insertarCabeceraCalificacion($paca_id, $est_id, $pro_id, $asi_id, $ecun_id_master, $cal_calif);

                                    $idDetAsin = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_asincrono['cuni_id'], $cal_asin);
                                    $idDetAut = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_autonoma['cuni_id'], $cal_aut);
                                    $idDetEval = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_evaluacion['cuni_id'], $cal_eval);
                                    $idDetFinal = $mod_cab_cal->insertarDetalleCalificacion($ccal_id, $componente_unidad_final['cuni_id'], $cal_final);
                                }
                                else{ // UPDATE
                                    $cabecera = $has_cabecera_calificacion[0];

                                    $ccal_id = $cabecera['ccal_id'];

                                    $mod_cab_cal->actualizarCabeceraCalificacion($ccal_id, $cal_calif);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_asincrono['cuni_id'], $cal_asin);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_autonoma['cuni_id'], $cal_aut);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_evaluacion['cuni_id'], $cal_eval);
                                    $mod_cab_cal->actualizarDetalleCalificacion($ccal_id, $componente_unidad_final['cuni_id'], $cal_final);
                                }
                            }
                        }
                    }
                }

                $arroout['status'] = TRUE;
                $arroout['noalumno'] = $noalumno;

                return $arroout;
            }
            catch (Exception $ex)
            {
                if ($trans !== null){ $trans->rollback(); }
                $arroout["status"] = FALSE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                return $arroout;
            }
        }
    }

    public function actionDownloadplantillaassociatebachelor() {
        $file = 'plantilla_associate_bachelor.xlsx';
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
                header('Content-Disposition: attachment; filename="plantillacalificacion_ASOCCIATE_BACHELOR' . '.xlsx";');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($url_file));
                readfile($url_file);
            }
        }
    }

    public function actionDownloadplantillamasters() {
        $file = 'plantilla_masters.xlsx';
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
                header('Content-Disposition: attachment; filename="plantillacalificacion_MASTERS' . '.xlsx";');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($url_file));
                readfile($url_file);
            }
        }
    }

    /**
     * Determina si el usuario logueado tiejne privilegios más avanzados
     * @author  Jorge Paladines analista.desarrollo@uteg.edu.ec
     * @param   
     * @return  
     */
    private function isAdmin($usu_id){ 
        $con = Yii::$app->db_academico;
        $sql1 = "SELECT * FROM db_asgard.usua_grol_eper where usu_id = $usu_id";
        $comando = $con->createCommand($sql1);
        $res = $comando->queryOne();

        $sql2 = "SELECT * FROM db_asgard.grup_rol where grol_id = " . $res['grol_id'];
        $comando = $con->createCommand($sql2);
        $res2 = $comando->queryAll();

        if($res2[0]['gru_id'] == 6){
            return true;
        }
        else{
            return false;
        }
    }


    public function actionExportpdfclfc() { // accion para descargar pdf de materias registradas

        $report = new ExportFile(); //Instancio Objeto de ExportaFile

        $this->view->title = Academico::t("academico", "Acta de calificaciones"); // Titulo del reporte

        //$matriculacion_model = new Matriculacion();
        $cabeceraCalificacion = new CabeceraCalificacion();

        $data = Yii::$app->request->get();

        $periodo = $data['paca'];
        $unidad = $data['unidad'];
        $materia = $data['materia'];
        $profesor = $data['profesor'];
        $paralelo = $data['paralelo'];
        \app\models\Utilities::putMessageLogFile($periodo);
        \app\models\Utilities::putMessageLogFile($unidad);
        \app\models\Utilities::putMessageLogFile($materia);
        \app\models\Utilities::putMessageLogFile($profesor);
        /* return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $ron_id); */

        //$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteSearch($unidad,$periodo,$materia,$profesor);
        $arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllSearch($unidad,$periodo,$materia,$profesor,$paralelo,true);

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
                    //"data_student" => $data_student,
                ])
        );
        $report->mpdf->Output('Acta_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }
}