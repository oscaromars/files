<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use yii\helpers\ArrayHelper;
use app\models\Utilities;

use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\CabeceraCalificacion;
use app\modules\academico\models\DetalleCalificacion;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\DistributivoAcademico;
use app\models\Persona;
use app\models\Usuario;
use yii\base\Security;
use yii\base\Exception;
use app\models\EmpresaPersona;
use app\models\UsuaGrolEper;
use app\models\Grupo;
use app\modules\academico\models\Profesor;
use app\modules\academico\models\NumeroMatricula;
use app\modules\academico\models\AsignaturasPorPeriodo;
use app\modules\academico\models\ComponenteUnidad;

use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;

academico::registerTranslations();
admision::registerTranslations();
financiero::registerTranslations();

class CalificacionestudianteController extends \app\components\CController {

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

    public function actionIndex() {
        $grupo_model = new Grupo();
        $mod_estudiante = new Estudiante();
        $mod_programa = new EstudioAcademico();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new CabeceraCalificacion();
        $mod_profesor = new Profesor();
        $cabeceraCalificacion = new CabeceraCalificacion();
        $mod_Estudiante = new Estudiante();
        $mod_periodoActual = new PeriodoAcademicoMetIngreso();  
        $modcanal       = new CabeceraCalificacion();
        $Asignatura_distri = new Asignatura();

        $per_id = Yii::$app->session->get("PB_perid");
        $user_usermane = Yii::$app->session->get("PB_username");
        
        $resp_estudianteid = $mod_Estudiante->getEstudiantexperid($per_id);
    Utilities::putMessageLogFile("LINEA 84  per_id: " .$per_id);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidadEstudiante($resp_estudianteid["est_id"],$data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }

        $data = Yii::$app->request->get();

        $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);        
        $arr_periodoActual = $mod_periodoActual->consultarPeriodoAcademico();
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEstudiante(1, $resp_estudianteid["est_id"]);         
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        $arr_carrera = $modcanal->consultarCarreraModalidadEstudiante($resp_estudianteid["est_id"], $arr_ninteres[0]["id"], $arr_modalidad[0]["id"]);
        
        $perfil_user = $arr_grupos[0]["id"];
        $per_id = @Yii::$app->session->get("PB_perid");
        Utilities::putMessageLogFile("LINEA 108  perfil_user: " .$perfil_user);

        if ($data['PBgetFilter']) {
            //Utilities::putMessageLogFile("LINEA 144  PBgetFilter");

            /*$unidad = (isset($data['unidad']) && $data['unidad'] > 0)?$data['unidad']:NULL;
            $modalidad = (isset($data['modalidad']) && $data['modalidad'] > 0)?$data['modalidad']:NULL;
            $carrera = (isset($data['carrera']) && $data['carrera'] > 0)?$data['carrera']:NULL;
            $periodo = (isset($data['periodo']) && $data['periodo'] > 0)?$data['periodo']:NULL;

             $arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllStudentSearch($per_id, $unidad, $carrera,$periodo,$materia,$profesor,$paralelo, $perfil_user);
             return $this->renderPartial('index-grid', [
                            "model" => $arr_estudiante,
                ]);*/

            $arrSearch["unidad"] = (isset($data['unidad']) && $data['unidad'] > 0)?$data['unidad']:NULL;
            $arrSearch["modalidad"] = (isset($data['modalidad']) && $data['modalidad'] > 0)?$data['modalidad']:NULL;
            $arrSearch["carrera"] = (isset($data['carrera']) && $data['carrera'] > 0)?$data['carrera']:NULL;
            $arrSearch["periodo"] = (isset($data['periodo']) && $data['periodo'] > 0)?$data['periodo']:NULL;
            // $arrSearch["per_id"] = $per_id;
            
            $arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllStudentSearch($arrSearch, $per_id, false);
             return $this->renderPartial('index-grid', [
                            "model" => $arr_estudiante,
                ]);
        }

        if (in_array(['id' => '6'], $arr_grupos)) {
            //Es Coordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas(); 
            //Utilities::putMessageLogFile("Paso por cordinador");
            //Utilities::putMessageLogFile(print_r($arr_profesor_all,true));

            $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
             //Obtener paralelo
            $arr_paralelo_clcf= $Asignatura_distri->getCourseProfesor($arr_profesor_all[0]['pro_id'],$arr_periodoActual[0]["id"],$asignatura[0]["id"]);

            //$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllStudentSearch($per_id,$arr_ninteres[0]["id"],$arr_carrera[0]["id"],$arr_periodoActual[0]["id"],$asignatura[0]['id'],$arr_profesor_all[0]["pro_id"],$arr_paralelo_clcf[0]["id"], $perfil_user);

            $arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllStudentSearch($arrSearch, $per_id, false);
        }else{
            //No es Cordinador
           
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
            //Utilities::putMessageLogFile(print_r($arr_profesor_all,true));

            $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
            //$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocente($arr_periodoActual[0]["id"],$asignatura[0]['id'],$arr_profesor["Id"]);
             //Obtener paralelo
            // $arr_paralelo_clcf = $Asignatura_distri->getCourseProfesor($arr_profesor_all[0]['pro_id'],$arr_periodoActual[0]["id"],$asignatura[0]["id"]);
            $arr_paralelo_clcf = [];

            /*$arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllStudentSearch($per_id, $arr_ninteres[0]["id"],$arr_ninteres[0]["id"],$arr_periodoActual[0]["id"],$asignatura[0]['id'],$arr_profesor_all[0]['pro_id'],$arr_paralelo_clcf[0]["id"], $perfil_user);*/

            $arr_estudiante = $cabeceraCalificacion->consultaCalificacionRegistroDocenteAllStudentSearch($arrSearch, $per_id, false);
        }

        return $this->render('index', [
                    'model' => $arr_estudiante,
                    'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $asignatura), "id", "name"),
                    'arr_periodoActual' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_periodoActual), "id", "name"),
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_ninteres), "id", "name"),                   
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_modalidad), "id", "name"),
                    'arr_carrera' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_carrera), "id", "name"),
                    'arr_estados' => $this->estados(),
                    'arr_profesor_all' => ArrayHelper::map(array_merge( $arr_profesor_all), "pro_id", "nombres"),
                    'arr_paralelo_clcf' => ArrayHelper::map(array_merge( $arr_paralelo_clcf), "id", "name"),
        ]);
    }//function actionIndex

   
    public function actionView($est_id, $asi_id, $pro_id, $paca_id, $asistencia_parcial_1, $asistencia_parcial_2) {
        $est_id = base64_decode($est_id);
        $asi_id = base64_decode($asi_id);
        $pro_id = base64_decode($pro_id);
        $paca_id = base64_decode($paca_id);
        $asistencia_parcial_1 = base64_decode($asistencia_parcial_1);
        $asistencia_parcial_2 = base64_decode($asistencia_parcial_2);

        $mod_estudiante = new Estudiante();
        $persona_model = new Persona();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_asignatura = new Asignatura();
        $mod_periodo = new PeriodoAcademico();
        $mod_estudio = new EstudioAcademico();

        $dataPersona = $persona_model->consultaDatosPersonaid($per_id);
        $dataEstudiante = $mod_estudiante->consultarDatosPersona($est_id);
        $uaca_id = $mod_estudiante->getEstudiantexestid($est_id)['unidad'];
        $arr_modalidad = $mod_modalidad->consultarModalidad($uaca_id, 1);

        $nombres = $dataEstudiante['nombres'];
        $matricula = $dataEstudiante['matricula'];
        $unidad = $mod_unidad->consultarNombreunidad($uaca_id)['nombre_unidad'];
        $modalidad = $arr_modalidad[0]['name'];
        $asignatura = $mod_asignatura->consultarAsignatura($asi_id)[0]['asi_descripcion'];
        $periodo = $mod_periodo->consultarPeriodo($paca_id, true)[0]['nombre'];
        $programa = $mod_estudio->consultarEstudioAcademicoPorEstudiante($est_id)['programa'];

        $mod_detalle = new DetalleCalificacion();
        $notas_estudiante = $mod_detalle->consultarDetallesDesdeIDs($est_id, $asi_id, $pro_id, $paca_id, $asistencia_parcial_1, $asistencia_parcial_2, false);
        $notas_estudiante_array = $mod_detalle->consultarDetallesDesdeIDs($est_id, $asi_id, $pro_id, $paca_id, $asistencia_parcial_1, $asistencia_parcial_2, true);

        $mod_cabecera = new CabeceraCalificacion();
        $supletorio = $mod_cabecera->getSupletorioPorIDs($est_id, $asi_id, $pro_id, $paca_id)['ccal_calificacion'];

        $promedio_final = 0;

        foreach ($notas_estudiante_array as $key => $value) {
            $promedio = $value['promedio'];
            $promedio_final += $promedio;
        }

        $promedio_final = $promedio_final / count($notas_estudiante_array);

        // \app\models\Utilities::putMessageLogFile($notas_estudiante);

        return $this->render('view', [
            'notas_estudiante' => $notas_estudiante,
            'nombres' => $nombres,
            'matricula' => $matricula,
            'unidad' => $unidad,
            'modalidad' => $modalidad,
            'asignatura' => $asignatura,
            'periodo' => $periodo,
            'promedio_final' => $promedio_final,
            'programa' => $programa,
            'supletorio' => $supletorio
        ]);
    }


        

    
    

    

   

}
