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
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\EstudianteCarreraPrograma;
use app\modules\academico\models\Profesor;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\CabeceraCalificacion;
use app\modules\academico\models\CabeceraAsistencia;
use app\modules\academico\models\Paralelo;
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
use app\modules\academico\models\ComponenteUnidad;


academico::registerTranslations();
admision::registerTranslations();
financiero::registerTranslations();

class AsistenciaregistrodocenteController extends \app\components\CController {

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

    /*public function actionIndex() {

         \app\models\Utilities::putMessageLogFile('actionIndex');

        $mod_estudiante = new Estudiante();
        $mod_programa = new EstudioAcademico();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_profesor = new Profesor();
        $per_id = "1001"; //Yii::$app->session->get("PB_perid");

        $Asignatura_distri = new Asignatura();

        //$data = Yii::$app->request->get();
        $mod_periodoActual = new PeriodoAcademico();  

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getasignaturas"])) {
                $asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo($data["paca_id"],$per_id,$data["uaca_id"],$data["mod_id"]);
                $message = array("asignatura" => $asignatura);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }

        $arr_periodoActual = $mod_periodoActual->getAllGrupoEstacion(true);
        $arr_profesor = $mod_profesor->getProfesoresxid($per_id);
        //$asignatura = $Asignatura_distri->getAsignaturaByProfesorDistributivo("0",$per_id);
        $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor["Id"],1,1,$arr_periodoActual[0]["id"]);
        $asignatura = $Asignatura_distri->getAsignaturaRegistro(1,1,1,$arr_periodoActual[0]["id"]);
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        //$arr_carrerra1 = $modcanal->consultarCarreraModalidad($arr_ninteres[0]["id"], $arr_modalidad[0]["id"]);

        $arr_estudiante = $mod_estudiante->consultarEstudiante();
   
        return $this->render('index', [
                    'model' => $arr_estudiante,
                    'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
                    'arr_periodoActual' => ArrayHelper::map(array_merge($arr_periodoActual), "id", "value"),
                    'arr_ninteres' => ArrayHelper::map(array_merge( $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge( $arr_modalidad), "id", "name"),
                   // 'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_carrerra1), "id", "name"),
                    'arr_estados' => $this->estados()
        ]);
    }*/

    public function actionCargararchivoasistencia() {
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
                if ($typeFile == 'xlsx' || $typeFile == 'xls') {

                    $dirFileEnd = Yii::$app->params["documentFolder"] . "asistencias/" . $data["name_file"] . "." . $typeFile;
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
                    $ecal_id = $data['ecal_id'];//parcial
                    $paca_id = $data['paca_id'];//periodo
                    $asi_id = $data['asi_id'];//id asignatura

                    //\app\models\Utilities::putMessageLogFile($asignatura);
                    //\app\models\Utilities::putMessageLogFile('LINEA 153 : '.$per_id);
                    
                    $carga_archivo = $this->procesarArchivoAsistencias($archivo_nombre, $paca_id, $ecal_id, $asi_id, $per_id, $mod_asig);

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
                $per_id = $data['per_id'];
                $pro_id = (new Profesor())->getProfesoresxid($per_id)['Id'];
                $materias = (new Asignatura())->getAsignaturasBy($pro_id, NULL, $data['paca_id']);
                $message = array("asignaturas" => $materias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        else {
            //carga combos
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

            return $this->render('cargararchivoasistencias', [
                'periodos' =>  ArrayHelper::map(array_merge($periodos), "paca_id", "paca_nombre"),
                'periodo_actual' => $periodo_actual,
                'materias' => ArrayHelper::map(array_merge($materias), "asi_id", "asi_descripcion"),
                'parciales' => $this->parciales(),
                'profesores' => ArrayHelper::map(array_merge($profesores), "per_id", "nombres"),
                'admin' => $admin
            ]);
        }
    }

    public function procesarArchivoAsistencias($fname, $paca_id, $ecal_id, $asi_id, $per_id, $mod_asig){
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "asistencias/" . $fname;
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = Yii::$app->db_facturacion;
        $transaccion = $con->getTransaction();
        $model = new CabeceraAsistencia();

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

                $pro_id = (new Profesor())->getProfesoresxid($per_id)['Id'];

                foreach ($dataArr as $val) {
                    $fila++;

                    if( $fila == 1 ){ // No leer la primera fila ni la 2da
                        continue;
                    }else{

                        $matricula = $val[1];
                        $nombre = $val[2] . ' ' . $val[3];
                        //obtengo:  est_id, per_id
                        $estudiante = Estudiante::find()->where(['est_matricula' => $matricula])->asArray()->one();
                        // Si el estudiante no existe, continuar al siguiente, y colocarlo en la lista
                        if(!isset($estudiante)){
                            $val_estudiante = 1;
                            $exito = 1;
                            $noalumno .= $nombre . " (no es un estudiante registrado), ";
                            continue;
                        }

                        $est_id = $estudiante['est_id'];
                        $meun_id = EstudianteCarreraPrograma::find()->where(['est_id' => $est_id])->asArray()->one()['meun_id'];
                        $uaca_id = ModalidadEstudioUnidad::find()->where(['meun_id' => $meun_id])->asArray()->one()['uaca_id'];

                        // Esquema Calificación Unidad
                        $respEsquemaCalUni = $model->selectEsquemaCalificacionUnidad($ecal_id, $uaca_id);
                        //$ecun_id = $respEsquemaCalUni[0]["ecun_id"];
                        $aeun_id = $respEsquemaCalUni[0]["aeun_id"];

                        // Sacar la asignatura correcta
                        $asignatura = $mod_asig->consultarAsignaturaConUnidad($asi_id, $uaca_id);
                        if(!isset($asignatura)){
                            $val_asignatura = 1;
                            $exito = 1;
                            $noalumno .= $nombre . " (no pertenece a esta asignatura), ";
                            continue;
                        }

                        // Tomar las asistencias dependiendo del parcial elegido
                        if($ecal_id == 1){ // 1er Parcial
                            $asi_u1 = $val[5];
                            $asi_u2 = $val[6];
                            $dasi_tipo_v1 = "u1";
                            $dasi_tipo_v2 = "u2";
                            $casi_cant_total = $asi_u1 + $asi_u2;                            
                            $casi_porc_total = ($asi_u1 + $asi_u2) / 2;
                        }
                        elseif($ecal_id == 2){ // 2do Parcial
                            $asi_u1 = $val[7];
                            $asi_u2 = $val[8];
                            $dasi_tipo_v1 = "u3";
                            $dasi_tipo_v2 = "u4";
                            $casi_cant_total = $asi_u1 + $asi_u2;
                            $casi_porc_total = ($asi_u1 + $asi_u2) / 2;
                        }

                        if ( $asi_u1 > 100 ){
                            $noalumno .= "El estudiante " . $nombre . " (excede el 100% en la asistencia en el campo 'U1' ), "; 
                            continue;
                        }elseif ( $asi_u2 > 100){
                            $noalumno .= "El estudiante " . $nombre . " (excede el 100% en la asistencia en el campo 'U2' ), "; 
                            continue;
                        }

                        $mod_cab_asi = new CabeceraAsistencia();
                        $has_cabecera_asistencia = CabeceraAsistencia::find()->where(['paca_id' => $paca_id, 'est_id' => $est_id, 'pro_id' => $pro_id, 'asi_id' => $asi_id,
                            'aeun_id' =>$aeun_id])->asArray()->all();

                        if(empty($has_cabecera_asistencia)){
                            $casi_id = $mod_cab_asi->insertCabeceraAsistencia($paca_id, $est_id, $pro_id, $asi_id, $aeun_id, $casi_cant_total, $casi_porc_total);
                            $idDetParcial1 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v1, $asi_u1);
                            $idDetParcial2 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v2, $asi_u2);  

                            if ($idDetParcial1 >0 && $idDetParcial2 > 0) {
                                $exito = 1;
                            }
                        }else{
                            $cabecera = $has_cabecera_asistencia[0];
                            $casi_id = $cabecera['casi_id'];
                            $mod_cab_asi->updateCabeceraAsistencia($casi_id, $casi_cant_total, $casi_porc_total);
                            $detalle_model = new CabeceraAsistencia();
                            $respuesta_detalle = $detalle_model->selectDetalleAsistencia($casi_id);
                            for ($det = 0; $det < sizeof($respuesta_detalle); $det++) { 
                                $dasi_id = $respuesta_detalle[0]["dasi_id"];
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_u1);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_u2);
                                
                                if ($mod_cab_asi >0 && $mod_cab_asi > 0) {
                                    $exito = 1;
                                }
                            }
                        }
                    }
                }

                if ( $exito == 1){
                    $transaccion->commit();   
                    $arroout['status'] = TRUE;
                    $arroout['noalumno'] = $noalumno;

                    return $arroout;
                }else{
                    $transaccion->rollback();
                    $arroout["status"] = FALSE;
                    $arroout["error"] = null;
                    $arroout["message"] = null;
                    $arroout["data"] = null;
                    return $arroout;
                }
            }
            catch (Exception $ex)
            {   
                if ($transaccion !== null){ $transaccion->rollback(); }
                $arroout["status"] = FALSE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                return $arroout;
            }
        }
    }

    public function actionDownloadplantillaasistencia() {
        $file = 'teacher_assistance.xlsx';
        $route = str_replace("../", "", $file);
        $url_file = Yii::$app->basePath . "/uploads/asistencias/plantilla/" . $route;
        $arrfile = explode(".", $url_file);
        $typeImage = $arrfile[count($arrfile) - 1];
        if (file_exists($url_file)) {
            if (strtolower($typeImage) == "xlsx") {
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false);
                header("Content-type: application/xlsx");
                header('Content-Disposition: attachment; filename="teacherassistance_FINAL' . '.xlsx";');
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
        $res2 = $comando->queryOne();

        if($res2['gru_id'] == 6){
            return true;
        }
        else{
            return false;
        }
    }   

    public function actionRegistro() {
        $per_id = @Yii::$app->session->get("PB_perid");

        $user_usermane = Yii::$app->session->get("PB_username");

        $mod_estudiante    = new Estudiante();
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
                $materia = $Asignatura_distri->getAsignaturaRegistro($data["pro_id"], $data["uaca_id"], $data["mod_id"], $data["paca_id"]);
                $message = array("materia" => $materia);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }        
        }//if

         $arr_grupos        = $grupo_model->getAllGruposByUser($user_usermane);

        if (in_array(['id' => '6'], $arr_grupos)) {
            //Es Cordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas(); 
            //print_r("Es Cordinador");
        }else{
            //No es coordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
            //print_r("NO Es Cordinador");
        }

        $arr_profesor      = $mod_profesor->getProfesoresxid($per_id);
        $arr_periodoActual = $mod_periodoActual->consultarPeriodosActivos();     
        $asignatura        = $Asignatura_distri->getAsignaturaRegistro($arr_profesor["Id"],1,1,$arr_periodoActual[0]["id"]);
        $arr_ninteres      = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad     = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);        
        $arr_estudiante    = $mod_estudiante->consultarEstudiante();        
        $arr_parcialunidad = $mod_periodoActual->getTodosParciales();
        $arr_componente    = $mod_calificacion->getComponenteUnidad($arr_ninteres[0]["id"]);
        $componenteuni     = $mod_calificacion->getComponente($arr_componente[0]["id"], $arr_componente[0]["columna"], $arr_componente[0]["nombre"], $arr_ninteres[0]["id"]);
        
        $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
        
        return $this->render('register', [
            'arr_asignatura'    => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
            //'arr_periodoActual' => ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Select")]], $arr_periodoActual), "id", "value"),
            'arr_periodoActual' => ArrayHelper::map($arr_periodoActual, "paca_id", "paca_nombre"),
            //'arr_ninteres'      => ArrayHelper::map($arr_ninteres, "id", "name"),
            'arr_ninteres'      => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_ninteres), "id", "name"),
            //'arr_modalidad'     => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
            'arr_modalidad'     => ArrayHelper::map($arr_modalidad, "id", "name"),
            'arr_parcial'       => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_parcialunidad), "id", "name"),
            'pro_id'            => $arr_profesor["Id"],
            'model'             => "",
            'componente'        => $componenteuni,
            'campos'            => $campos,
            'unidad'            => $unidad,
            'arr_profesor_all'  => ArrayHelper::map($arr_profesor_all, "pro_id", "nombres"),
        ]);
    }//function actionRegistro
    
    public function actionTraermodelo() {
        $per_id         = @Yii::$app->session->get("PB_perid");

        $model_cabasistencia = new CabeceraAsistencia();
        $data = Yii::$app->request->post();

        $arrSearch["periodo"]   = $data['periodo'];
        $arrSearch["unidad"]    = $data['uaca_id'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["materia"]   = $data['materia'];
        $arrSearch["parcial"]   = $data['parcial'];
        $arrSearch["profesor"]  = $data['profesor'];

        //print_r($data);die();

        $model = $model_cabasistencia->getAsistencia($arrSearch);
        return json_encode($model);
    }//function actionTraerModelo

    public function actionActualizarnotaasistencia() {
        $per_id         = @Yii::$app->session->get("PB_perid");
        $mod_asistencia = new CabeceraAsistencia();

        $data = Yii::$app->request->post();

        
        $matricula = array_key_first ( $data['data'] );

        print_r($data); die();

        $mod_asistencia->ActualizarNotaAsistencia($data['data'][$matricula], $data['unidad']);

        return json_encode( array('data' =>  ""));
    }//function actionActualizarnota
}