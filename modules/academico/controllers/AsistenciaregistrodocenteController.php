<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use app\modules\academico\models\Planificacion;
use app\modules\academico\models\EstudioAcademico; 
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\ModalidadEstudioUnidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\UsuarioEducativa;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\EstudianteCarreraPrograma;
use app\modules\academico\models\Profesor;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\CabeceraCalificacion;
use app\modules\academico\models\CabeceraAsistencia;
use app\modules\academico\models\CabeceraAsistenciaClases;
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
use app\modules\academico\models\DetalleAsistencia;
use app\modules\academico\models\PlanificacionEstudiante;


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

    public function actionIndexno() {

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
    }

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
                    $uaca_id = $data['uaca_id'];//id unidad
                    $mod_id = $data['mod_id'];//id modalidad

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
            $mod_modalidad  = new Modalidad();
            $mod_unidad     = new UnidadAcademica();
            $mod_periodo = new PeriodoAcademico();
            $asig_mod = new Asignatura();
            $mod_profesor = new Profesor();
            

            $periodos = $mod_periodo->consultarPeriodosActivos();
            $periodo_actual = $mod_periodo->getPeriodoAcademicoActual();
            $profesores = $mod_profesor->getProfesoresEnAsignaturas();   
             $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
            $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);  

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
                'unidades' =>  ArrayHelper::map(array_merge($arr_unidad), "id", "name"),
                'modalidades' =>  ArrayHelper::map(array_merge($arr_modalidad), "id", "name"),
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
        $modelpaca = new PeriodoAcademico();
        $daes = $modelpaca->getDaesbyperiodo($paca_id, $asi_id, $pro_id);
        $horasasignatura = $modelpaca->getHorasmaxAsistenciaxest($daes[0]['daes_id']);
        $sems = $horasasignatura['paca_semanas_periodo'];
        $hours = $horasasignatura['daho_total_horas'];
        

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
                        // VALIDATE IF ONLINE & BACHELLOR DEGREE       --------------------------------------------------------------
                        $matricula = $val[1]; 
                        
                          \app\models\Utilities::putMessageLogFile('Matricula' . $matricula);
                          
                        $nombre = $val[2] . ' ' . $val[3];
                        //obtengo:  est_id, per_id
                        
                                 \app\models\Utilities::putMessageLogFile('nombre' . $nombre);
                        
                       $estudiante = Estudiante::find()->where(['est_matricula' => $matricula])->asArray()->one();
                     
                       
                       
                        $estudiante_online= UsuarioEducativa::find()->where(['uedu_usuario' => $matricula])->asArray()->one();

                 \app\models\Utilities::putMessageLogFile('EDUCATIVA '.$estudiante_online['est_id']);
                    
                        if ($estudiante_online){
                        
                        $estudiante = $estudiante_online;

                        }
                      //   $mod_usueducativa = new UsuarioEducativa();
                       //  $estudiante = $mod_usueducativa->getusuarioeducativaby($matricula);
                        
                        // ----------------------------------------------------------------------------------------------------------
                        
                        // Si el estudiante no existe, continuar al siguiente, y colocarlo en la lista
                        if(!isset($estudiante)){
                            $val_estudiante = 1;
                            $exito = 1;
                            $noalumno .= $nombre . " (no es un estudiante registrado), ";
                            continue;
                        }
                  
                        $est_id = $estudiante['est_id'];
                               \app\models\Utilities::putMessageLogFile('ESTID' . $est_id);
                        $meun_id = EstudianteCarreraPrograma::find()->where(['est_id' => $est_id])->asArray()->one()['meun_id'];
                          \app\models\Utilities::putMessageLogFile('MEUNID' . $meun_id);
                        $uaca_id = ModalidadEstudioUnidad::find()->where(['meun_id' => $meun_id])->asArray()->one()['uaca_id'];
                          \app\models\Utilities::putMessageLogFile('UACAID' . $uaca_id);
                      
                                

                        // Esquema Calificación Unidad
                        $respEsquemaCalUni = $model->selectEsquemaCalificacionUnidad($ecal_id, $uaca_id);
                                 \app\models\Utilities::putMessageLogFile('UACAID' . $respEsquemaCalUni);
                        //$ecun_id = $respEsquemaCalUni[0]["ecun_id"];
                        $aeun_id = $respEsquemaCalUni[0]["aeun_id"];
                         \app\models\Utilities::putMessageLogFile('AEUNID' . $aeun_id);
                      
                             
                        
                         
                                  

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
                         //   $asi_u2 = $val[6];
                            $dasi_tipo_v1 = "u1";
                         //   $dasi_tipo_v2 = "u2";
                            $casi_cant_total = $asi_u1;     

                            $casi_porc_total = ($asi_u1 / 30);
                           $casi_porc_total = round($casi_porc_total, 2); 
                        }
                        elseif($ecal_id == 2){ // 2do Parcial
                          //  $asi_u1 = $val[5];
                            $asi_u2 = $val[6];
                          //  $dasi_tipo_v1 = "u1";
                            $dasi_tipo_v2 = "u2";
                            $casi_cant_total = $asi_u2;
                            $casi_porc_total = ($asi_u2 / 30);
                             $casi_porc_total = round($casi_porc_total, 2); 
                        }

                        if ( $asi_u1 > 30 ){
                            $noalumno .= "El estudiante " . $nombre . " (excede el maximo 30 en la asistencia en el campo 'U1' ), "; 
                            continue;
                        }elseif ( $asi_u2 > 30){
                            $noalumno .= "El estudiante " . $nombre . " (excede el maximo 30 en la asistencia en el campo 'U2' ), "; 
                            continue;
                        }

                        $mod_cab_asi = new CabeceraAsistencia();
                        $has_cabecera_asistencia = CabeceraAsistencia::find()->where(['paca_id' => $paca_id, 'est_id' => $est_id, 'pro_id' => $pro_id, 'asi_id' => $asi_id,
                            'aeun_id' =>$aeun_id])->asArray()->all();

                        if(empty($has_cabecera_asistencia)){
                            $casi_id = $mod_cab_asi->insertCabeceraAsistencia($paca_id, $est_id, $pro_id, $asi_id, $aeun_id, $casi_cant_total, $casi_porc_total);
                    

                      if ($ecal_id == 1 ) {

                               $idDetParcial1 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v1, $asi_u1);
                    
                     if ($idDetParcial1 >0 ) {
                                $exito = 1;

                                  }

                                     }

                      if ($ecal_id == 2 ) {

                            $idDetParcial2 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v2, $asi_u2);  

                            if ($idDetParcial2 > 0) {
                                $exito = 1;
                            }

                               }


                        }else{
                            $cabecera = $has_cabecera_asistencia[0];
                            $casi_id = $cabecera['casi_id'];
                            $mod_cab_asi->updateCabeceraAsistencia($casi_id, $casi_cant_total, $casi_porc_total);
                            $detalle_model = new CabeceraAsistencia();
                            $respuesta_detalle = $detalle_model->selectDetalleAsistencia($casi_id);
                            for ($det = 0; $det < sizeof($respuesta_detalle); $det++) { 
                                $dasi_id = $respuesta_detalle[0]["dasi_id"];
                    
                             if ($ecal_id == 1 ) {
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_u1);
                                }
                    
                             if ($ecal_id == 2 ) {

                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_u2);
                                
                                }

                                if ($mod_cab_asi >0 or $mod_cab_asi > 0) {
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
         $emp_id = @Yii::$app->session->get("PB_idempresa");
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

             
              if (isset($data["getuni"])) {
                 $profesorup = $mod_profesor->getProfesoresEnAsignaturasByall($data["paca_id"],$data["uaca_id"],$data["mod_id"]);
                $message = array("profesorup" => $profesorup);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

            

             if (isset($data["getmateria"])) {
                $materia = $Asignatura_distri->getAsignaturasBy($data["pro_id"], $data["uaca_id"], $data["paca_id"]);
                $message = array("materia" => $materia);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

              /*      if (isset($data["getmateria"])) {               
                $materia = $Asignatura_distri->getAsignaturaRegistro($data["pro_id"], $data["uaca_id"], $data["mod_id"], $data["paca_id"]);
                $message = array("materia" => $materia);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
          } */
        }//if

         $arr_grupos        = $grupo_model->getAllGruposByUser($user_usermane);

              
        if ( in_array(['id' => '6'], $arr_grupos) ||
             in_array(['id' => '7'], $arr_grupos) ||
             in_array(['id' => '8'], $arr_grupos)
        )
        {
            //Es Cordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas(); 
            //print_r("Es Cordinador");
        }else{
            //No es coordinador
            //$arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId2($per_id);
           //   $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas();
            //print_r("NO Es Cordinador");
        }

        $arr_profesor      = $mod_profesor->getProfesoresxid($per_id);
        //$arr_periodoActual = $mod_periodoActual->consultarPeriodosActivos();    
        $arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual(); 
        //$asignatura        = $Asignatura_distri->getAsignaturaRegistro($arr_profesor["Id"],1,1,$arr_periodoActual[0]["id"]);
        $arr_ninteres      = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad     = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);        
        $arr_estudiante    = $mod_estudiante->consultarEstudiante();        
        $arr_parcialunidad = $mod_periodoActual->getTodosParciales();
        $arr_componente    = $mod_calificacion->getComponenteUnidad($arr_ninteres[0]["id"]);
        //$componenteuni     = $mod_calificacion->getComponente($arr_componente[0]["id"], $arr_componente[0]["columna"], $arr_componente[0]["nombre"], $arr_ninteres[0]["id"]);
        
        //$asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
         $asignatura = $Asignatura_distri->getAsignaturasBy($arr_profesor_all[0]['pro_id'],
                                                               $arr_ninteres[0]["id"],
                                                               $arr_periodoActual[0]["id"]);
         
        return $this->render('register', [
            'arr_asignatura'    => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
            //'arr_periodoActual' => ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Select")]], $arr_periodoActual), "id", "value"),
           // 'arr_periodoActual' => ArrayHelper::map($arr_periodoActual, "paca_id", "paca_nombre"),
            'arr_periodoActual' => ArrayHelper::map($arr_periodoActual, "id", "nombre"),
            //'arr_ninteres'      => ArrayHelper::map($arr_ninteres, "id", "name"),
            'arr_ninteres'      => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_ninteres), "id", "name"),
            //'arr_modalidad'     => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
            'arr_modalidad'     => ArrayHelper::map($arr_modalidad, "id", "name"),
            'arr_parcial'       => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_parcialunidad), "id", "name"),
            'pro_id'            => $arr_profesor["Id"],
            'model'             => "",
          //  'componente'        => $componenteuni,
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
        \app\models\Utilities::putMessageLogFile('PACA'.$data['periodo']);
              \app\models\Utilities::putMessageLogFile('UACA'.$data['uaca_id']);
                    \app\models\Utilities::putMessageLogFile('MOD'.$data['modalidad']);
                          \app\models\Utilities::putMessageLogFile('ASI'.$data['materia']);
                                \app\models\Utilities::putMessageLogFile('PARCIAL'.$data['parcial']);
                                      \app\models\Utilities::putMessageLogFile('PROF'.$data['profesor']);
                                      

        $model = $model_cabasistencia->getAsistencia($arrSearch);
         \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['row_num']);
          \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['matricula']);
           \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['nombre']);
            \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['materia']);
             \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['u1']);
              \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['u2']);

                 \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['paca_id']);
                  \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['est_id']);
                   \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['pro_id']);
                    \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['asi_id']);
                      \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['uaca_id']);
                        \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['daca_id']);
                          \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['daes_id']);
                    
         
        
        return json_encode($model);
    }//function actionTraerModelo

   
    
    
    public function actionActualizarnotaasistencia() {
        $per_id         = @Yii::$app->session->get("PB_perid");
        $mod_asistencia = new CabeceraAsistencia();

        $data = Yii::$app->request->post();

        
        $row_id = array_key_first ( $data['data'] );

        $valor  = array();

        $valor["DT_RowId"] = "row_".$row_id;
        $valor["row_num"]  = $row_id;
        $valor["u1"]       = $data['data'][$row_id]['u1'];
        $valor["u2"]       = $data['data'][$row_id]['u2'];
         //$valor["u3"]       = $data['data'][$row_id]['u3'];
         //$valor["u4"]       = $data['data'][$row_id]['u4'];

        //print_r($data); die();

        $mod_asistencia->ActualizarNotaAsistencia($data['data'][$row_id]);

        $respuesta["data"] = array();
        $respuesta['data'][] = $valor;
        return json_encode($respuesta, JSON_PRETTY_PRINT); 
    }//function actionActualizarnota
    
    
    
    
    public function actionRegistrosemanal() {
        $per_id = @Yii::$app->session->get("PB_perid");
         $emp_id = @Yii::$app->session->get("PB_idempresa");
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
                $materia = $Asignatura_distri->getAsignaturasBy($data["pro_id"], $data["uaca_id"], $data["paca_id"]);
                $message = array("materia" => $materia);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

           /* if (isset($data["getmateria"])) {               
                $materia = $Asignatura_distri->getAsignaturaRegistro($data["pro_id"], $data["uaca_id"], $data["mod_id"], $data["paca_id"]);
                $message = array("materia" => $materia);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }        */
        }//if

         $arr_grupos        = $grupo_model->getAllGruposByUser($user_usermane);

         if ( in_array(['id' => '6'], $arr_grupos) ||
              in_array(['id' => '7'], $arr_grupos) ||
             in_array(['id' => '8'], $arr_grupos)
        ) {
            //Es Cordinador
            $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas(); 
            //print_r("Es Cordinador");
        }else{
            //No es coordinador
           // $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
              //$arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas();
                  $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId2($per_id);
            //print_r("NO Es Cordinador");
        }

        $arr_profesor      = $mod_profesor->getProfesoresxid($per_id);
        //$arr_periodoActual = $mod_periodoActual->consultarPeriodosActivos();  
        $arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual();    
        $asignatura        = $Asignatura_distri->getAsignaturaRegistro($arr_profesor["Id"],1,1,$arr_periodoActual[0]["id"]);
        $arr_ninteres      = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad     = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);        
        $arr_estudiante    = $mod_estudiante->consultarEstudiante();        
        $arr_parcialunidad = $mod_periodoActual->getTodosParciales();
        $arr_componente    = $mod_calificacion->getComponenteUnidad($arr_ninteres[0]["id"]);
       // $componenteuni     = $mod_calificacion->getComponente($arr_componente[0]["id"], $arr_componente[0]["columna"], $arr_componente[0]["nombre"], $arr_ninteres[0]["id"]);
        
        //$asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
         $asignatura = $Asignatura_distri->getAsignaturasBy($arr_profesor_all[0]['pro_id'],
                                                               $arr_ninteres[0]["id"],
                                                               $arr_periodoActual[0]["id"]);
         
        return $this->render('registersemanal', [
            'arr_asignatura'    => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
            //'arr_periodoActual' => ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Select")]], $arr_periodoActual), "id", "value"),
            'arr_periodoActual' => ArrayHelper::map($arr_periodoActual, "id", "nombre"),
            //'arr_ninteres'      => ArrayHelper::map($arr_ninteres, "id", "name"),
            'arr_ninteres'      => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_ninteres), "id", "name"),
            //'arr_modalidad'     => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
            'arr_modalidad'     => ArrayHelper::map($arr_modalidad, "id", "name"),
            'arr_parcial'       => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_parcialunidad), "id", "name"),
            'pro_id'            => $arr_profesor["Id"],
            'model'             => "",
           // 'componente'        => $componenteuni,
            'campos'            => $campos,
            'unidad'            => $unidad,
            'arr_profesor_all'  => ArrayHelper::map($arr_profesor_all, "pro_id", "nombres"),
        ]);
    }//function actionRegistro
    
     public function actionActualizarnotaasistenciasemanal() {
        $per_id         = @Yii::$app->session->get("PB_perid");
        $mod_asistencia = new CabeceraAsistencia();

        $data = Yii::$app->request->post();

        
        $row_id = array_key_first ( $data['data'] );

        $valor  = array();

        $valor["DT_RowId"] = "row_".$row_id;
        $valor["row_num"]  = $row_id;
        $valor["s1"]       = $data['data'][$row_id]['s1'];
        $valor["s2"]       = $data['data'][$row_id]['s2'];
        $valor["s3"]       = $data['data'][$row_id]['s3'];
        $valor["s4"]       = $data['data'][$row_id]['s4'];
        $valor["s5"]       = $data['data'][$row_id]['s5'];
        $valor["s6"]       = $data['data'][$row_id]['s6'];
        $valor["s7"]       = $data['data'][$row_id]['s7'];
        $valor["s8"]       = $data['data'][$row_id]['s8'];
        $valor["s9"]       = $data['data'][$row_id]['s9'];
        $valor["s0"]       = $data['data'][$row_id]['s0'];

        //print_r($data); die();

        $mod_asistencia->ActualizarNotaAsistenciasemanal($data['data'][$row_id]);

        $respuesta["data"] = array();
        $respuesta['data'][] = $valor;
        return json_encode($respuesta, JSON_PRETTY_PRINT); 
    }//function actionActualizarnota
    
        public function actionTraermodelosemanal() {
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
        \app\models\Utilities::putMessageLogFile('PACA'.$data['periodo']);
              \app\models\Utilities::putMessageLogFile('UACA'.$data['uaca_id']);
                    \app\models\Utilities::putMessageLogFile('MOD'.$data['modalidad']);
                          \app\models\Utilities::putMessageLogFile('ASI'.$data['materia']);
                                \app\models\Utilities::putMessageLogFile('PARCIAL'.$data['parcial']);
                                      \app\models\Utilities::putMessageLogFile('PROF'.$data['profesor']);
                                      

        $model = $model_cabasistencia->getAsistenciasemanal($arrSearch);
         \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['row_num']);
          \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['matricula']);
           \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['nombre']);
            \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['materia']);
             \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['u1']);
              \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['u2']);

                 \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['paca_id']);
                  \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['est_id']);
                   \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['pro_id']);
                    \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['asi_id']);
                      \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['uaca_id']);
                        \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['daca_id']);
                          \app\models\Utilities::putMessageLogFile('PROF'.$model[2]['daes_id']);
                    
         
        
        return json_encode($model);
    }//function actionTraerModelosemanal
    
       public function actionDownloadplantillaasistenciasemanal() {
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
    
        public function procesarArchivoAsistenciasemanal($fname, $paca_id, $ecal_id, $asi_id, $per_id, $mod_asig){
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
                        // VALIDATE IF ONLINE & BACHELLOR DEGREE       --------------------------------------------------------------
                        $matricula = $val[1]; 
                        
                          \app\models\Utilities::putMessageLogFile('Matricula' . $matricula);
                          
                        $nombre = $val[2] . ' ' . $val[3];
                        //obtengo:  est_id, per_id
                        
                                 \app\models\Utilities::putMessageLogFile('nombre' . $nombre);
                        
                       $estudiante = Estudiante::find()->where(['est_matricula' => $matricula])->asArray()->one();
                        \app\models\Utilities::putMessageLogFile('ID' . $estudiante['est_id']);
                       
                       
                       //    $estudiante = UsuarioEducativa::find()->where(['uedu_usuario' => $matricula])->asArray()->one();
                      //   $mod_usueducativa = new UsuarioEducativa();
                       //  $estudiante = $mod_usueducativa->getusuarioeducativaby($matricula);
                        
                        // ----------------------------------------------------------------------------------------------------------
                        
                        // Si el estudiante no existe, continuar al siguiente, y colocarlo en la lista
                        if(!isset($estudiante)){
                            $val_estudiante = 1;
                            $exito = 1;
                            $noalumno .= $nombre . " (no es un estudiante registrado), ";
                            continue;
                        }
                  
                        $est_id = $estudiante['est_id'];
                               \app\models\Utilities::putMessageLogFile('ESTID' . $est_id);
                        $meun_id = EstudianteCarreraPrograma::find()->where(['est_id' => $est_id])->asArray()->one()['meun_id'];
                          \app\models\Utilities::putMessageLogFile('MEUNID' . $meun_id);
                        $uaca_id = ModalidadEstudioUnidad::find()->where(['meun_id' => $meun_id])->asArray()->one()['uaca_id'];
                          \app\models\Utilities::putMessageLogFile('UACAID' . $uaca_id);
                      
                                

                        // Esquema Calificación Unidad
                        $respEsquemaCalUni = $model->selectEsquemaCalificacionUnidad($ecal_id, $uaca_id);
                                 \app\models\Utilities::putMessageLogFile('UACAID' . $respEsquemaCalUni);
                        //$ecun_id = $respEsquemaCalUni[0]["ecun_id"];
                        $aeun_id = $respEsquemaCalUni[0]["aeun_id"];
                         \app\models\Utilities::putMessageLogFile('AEUNID' . $aeun_id);
                      
                             
                        
                         
                                  

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
                            $asi_s2 = $val[6];
                            $asi_s3 = $val[7];
                            $asi_s4 = $val[8];
                            $asi_s5 = $val[9];
                               
                            $dasi_tipo_v1 = "u1";
                            $dasi_tipo_v2 = "s2";
                            $dasi_tipo_v3 = "s3";
                            $dasi_tipo_v4 = "s4";
                            $dasi_tipo_v5 = "s5";
                            
                            
                            $casi_cant_total = $asi_u1 + $asi_s2 + $asi_s3 + $asi_s4 + $asi_s5 ;  
                            $casi_porc_total = ($casi_cant_total  / 30);
                            $casi_porc_total = round($casi_porc_total, 2); 
                        }
                        elseif($ecal_id == 2){ // 2do Parcial
                          //  $asi_u1 = $val[5];
                            $asi_u2 = $val[10];
                            $asi_s7 = $val[11];
                            $asi_s8 = $val[12];
                            $asi_s9 = $val[13];
                            $asi_s0 = $val[14];
                            
                            
                            $dasi_tipo_v1 = "u2";
                            $dasi_tipo_v2 = "s7";
                            $dasi_tipo_v3 = "s8";
                            $dasi_tipo_v4 = "s9";
                            $dasi_tipo_v5 = "s0";
                            
                            
                           $casi_cant_total = $asi_u1 + $asi_s2 + $asi_s3 + $asi_s4 + $asi_s5 ; 
                            $casi_porc_total = ($casi_cant_total / 30);
                            $casi_porc_total = round($casi_porc_total, 2); 
                        }

                        if ( $asi_u1 > 30 ){
                            $noalumno .= "El estudiante " . $nombre . " (excede el maximo 30 en la asistencia en el campo 'U1' ), "; 
                            continue;
                        }elseif ( $asi_u2 > 30){
                            $noalumno .= "El estudiante " . $nombre . " (excede el maximo 30 en la asistencia en el campo 'U2' ), "; 
                            continue;
                        }

                        $mod_cab_asi = new CabeceraAsistencia();
                        $has_cabecera_asistencia = CabeceraAsistencia::find()->where(['paca_id' => $paca_id, 'est_id' => $est_id, 'pro_id' => $pro_id, 'asi_id' => $asi_id,
                            'aeun_id' =>$aeun_id])->asArray()->all();

                        if(empty($has_cabecera_asistencia)){
                            $casi_id = $mod_cab_asi->insertCabeceraAsistencia($paca_id, $est_id, $pro_id, $asi_id, $aeun_id, $casi_cant_total, $casi_porc_total);
                    

                      if ($ecal_id == 1 ) {

                               $idDetParcial1 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v1, $asi_u1);
                               $idDetParcial1 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v2, $asi_s2);
                               $idDetParcial1 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v3, $asi_s3);
                               $idDetParcial1 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v4, $asi_s4);
                               $idDetParcial1 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v5, $asi_s5);
                        
                     if ($idDetParcial1 >0 ) {
                                $exito = 1;

                                  }

                                     }

                      if ($ecal_id == 2 ) {

                            $idDetParcial2 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v2, $asi_u2);  
                            $idDetParcial2 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v2, $asi_s7);
                            $idDetParcial2 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v3, $asi_s8);
                            $idDetParcial2 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v4, $asi_s9);
                            $idDetParcial2 = $mod_cab_asi->insertDetalleAsistencia($casi_id, $ecal_id, $dasi_tipo_v5, $asi_s0);
                              
                              

                            if ($idDetParcial2 > 0) {
                                $exito = 1;
                            }

                               }


                        }else{
                            $cabecera = $has_cabecera_asistencia[0];
                            $casi_id = $cabecera['casi_id'];
                            $mod_cab_asi->updateCabeceraAsistencia($casi_id, $casi_cant_total, $casi_porc_total);
                            $detalle_model = new CabeceraAsistencia();
                            $respuesta_detalle = $detalle_model->selectDetalleAsistencia($casi_id);
                            for ($det = 0; $det < sizeof($respuesta_detalle); $det++) { 
                                $dasi_id = $respuesta_detalle[0]["dasi_id"];
                    
                             if ($ecal_id == 1 ) {
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_u1);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_s2);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_s3);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_s4);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_s5);
                                
                                
                                
                                
                                
                                
                                }
                    
                             if ($ecal_id == 2 ) {

                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_u2);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_s7);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_s8);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_s9);
                                $mod_cab_asi->updateDetalleAsistencia($dasi_id, $casi_id, $ecal_id, $asi_s0);
                                
                                
                                }

                                if ($mod_cab_asi >0 or $mod_cab_asi > 0) {
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
    

      public function actionRegistrodin() {
        $per_id = @Yii::$app->session->get("PB_perid");
         $emp_id = @Yii::$app->session->get("PB_idempresa");
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
           // $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
              $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas();
            //print_r("NO Es Cordinador");
        }

        $arr_profesor      = $mod_profesor->getProfesoresxid($per_id);
        $arr_periodoActual = $mod_periodoActual->consultarPeriodosActivos();     
        $asignatura        = $Asignatura_distri->getAsignaturaRegistro($arr_profesor["Id"],1,1,$arr_periodoActual[0]["id"]);
        $arr_ninteres      = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad     = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);        
        $arr_estudiante    = $mod_estudiante->consultarEstudiante();        
        $arr_parcialunidad = $mod_periodoActual->getTodosParciales();
        $arr_componente    = $mod_calificacion->getComponenteUnidad($arr_ninteres[0]["id"]);
        $componenteuni     = $mod_calificacion->getComponente($arr_componente[0]["id"], $arr_componente[0]["columna"], $arr_componente[0]["nombre"], $arr_ninteres[0]["id"]);
        
        $asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
         
        return $this->render('registerdin', [
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


     public function actionTraermodelodin() {
        $per_id         = @Yii::$app->session->get("PB_perid");

        $model_cabasistencia = new CabeceraAsistencia();
        $data = Yii::$app->request->post();

        $arrSearch["periodo"]   = $data['periodo'];
        $arrSearch["unidad"]    = $data['uaca_id'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["materia"]   = $data['materia'];
        $arrSearch["parcial"]   = $data['parcial'];
        $arrSearch["profesor"]  = $data['profesor'];       

         \app\models\Utilities::putMessageLogFile('PACA'.$data['periodo']);
        \app\models\Utilities::putMessageLogFile('UACA'.$data['uaca_id']);
        \app\models\Utilities::putMessageLogFile('MOD'.$data['modalidad']);
         \app\models\Utilities::putMessageLogFile('ASI'.$data['materia']);
         \app\models\Utilities::putMessageLogFile('PARCIAL'.$data['parcial']);
         \app\models\Utilities::putMessageLogFile('PROF'.$data['profesor']);     
     
       $modelpaca = new PeriodoAcademico();
        $daes = $modelpaca->getDaesbyperiodo($paca_id, $asi_id, $pro_id);
        \app\models\Utilities::putMessageLogFile('DAES '.$daes[0]['daes_id']);  
        $horasasignatura = $modelpaca->getHorasmaxAsistenciaxest($daes[0]['daes_id']);
        $sems = $horasasignatura['paca_semanas_periodo'];
        $hours = $horasasignatura['daho_total_horas'];

       /*  if ($data['modalidad'] == 1) {
         $sems = $horasasignatura['paca_semanas_periodo'];  $sems =2;
           $hours = $horasasignatura['daho_total_horas'];   $hours = 30;
        }Else {*/
         $sems = $horasasignatura['paca_semanas_periodo'];  $sems =10;
         $hours = $horasasignatura['daho_total_horas'];   $hours = 6;
       // } 

         
         \app\models\Utilities::putMessageLogFile('SEMANAS '.$horasasignatura['paca_semanas_periodo']);  
         \app\models\Utilities::putMessageLogFile('HORAS '.$horasasignatura['daho_total_horas']); 
        $model       = array();
        $componentes = array();
        $model['data']        = $model_cabasistencia->getAsistenciadin($arrSearch);    

        for ($x = 0; $x< $sems; ++$x) {
             $nombre ='s'.$x;
            $componentes[$nombre] = array(
                'id'=> 's'.$x ,
                'notamax'=>$hours,
            );
            }
           \app\models\Utilities::putMessageLogFile('resultado es ok'.$componentes["s0"]["id"]);
        $model['componentes'] = $componentes;       
       
        
        
        return json_encode($model);
    }//function actionTraerModelodin



      public function actionActualizarnotaasistenciadin() {
        $per_id            = @Yii::$app->session->get("PB_perid");
        $mod_calificacion  = new CabeceraAsistencia();

        $data   = Yii::$app->request->post();

        $row_id  = array_key_first ( $data['data'] );

        $casi_id = $data['data'][$row_id]['casi_id'];

        $total = 0;
        
        $valor  = array();

        $valor["DT_RowId"] = "row_".$row_id;
        $valor["row_num"]  = $row_id;

        if($casi_id != 0){
            $valor["casi_id"] = $data['data'][$row_id]['casi_id'];

            foreach ($data['data'][$row_id] as $key => $value) {
                if( $key!='paca_id'  &&
                    $key!='est_id'   &&
                    $key!='pro_id'   &&
                    $key!='asi_id'   &&
                    $key!='uaca_id'  &&
                    $key!='mod_id'   &&
                    $key!='daes_id'  &&
                    $key!='daho_id'  &&
                    $key!='daho_total_horas'  &&
                    $key!='semanas')
                    if($value!=''){
                       // $mod_calificacion->actualizarDetalleporcomponente($ccal_id,$key,$value);

                        if(!(is_null($value)) && $value != ''){
                            $valor[$key] = $value;
                            $total = $total + intval($value); 
                        }
                        
                    }//if
            }
        }else{
            $paca_id = $data['data'][$row_id]['paca_id'];
            $est_id  = $data['data'][$row_id]['est_id'];
            $pro_id  = $data['data'][$row_id]['pro_id'];
            $asi_id  = $data['data'][$row_id]['asi_id'];
            $mod_id  = $data['data'][$row_id]['mod_id'];
            $uaca_id = $data['data'][$row_id]['uaca_id'];
 
          //  $ccal_id = $mod_calificacion->crearCabeceraporcomponente($paca_id,$est_id,$pro_id,$asi_id,$ecal_id,$uaca_id);

            $valor["casi_id"] = $casi_id;
            
            foreach ($data['data'][$row_id] as $key => $value) {

                if( $key!='paca_id'  &&
                    $key!='est_id'   &&
                    $key!='pro_id'   &&
                    $key!='asi_id'   &&
                    $key!='uaca_id'  &&
                    $key!='mod_id'   &&
                    $key!='daes_id'  &&
                    $key!='daho_id'  &&
                    $key!='daho_total_horas'  &&
                    $key!='semanas')

                {
                        //if($value!=''){
                          //  $mod_calificacion->crearDetalleporcomponente($casi_id,$key,$value,$uaca_id,$mod_id,$ecal_id);

                            if(!(is_null($value)) && $value != ''){
                                $valor[$key] = $value;
                                $total = $total + intval($value); 
                            }
                            
                        //}//if
                }//if  
            }//foeach
        }//else

        //$mod_calificacion->actualizarDetalleCalificacion2($casi_id,$total);

        header('Content-Type: application/json');

        $valor["total"]  = $total;

        $respuesta["data"] = array();
        $respuesta['data'][] = $valor;
        return json_encode($respuesta, JSON_PRETTY_PRINT); 
    }//function actionActualizarnotadin

    
    
     public function actionCargararchivoasistenciasemanal() {
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
                    $uaca_id = $data['uaca_id'];//id unidad
                    $mod_id = $data['mod_id'];//id modalidad

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
            $mod_modalidad  = new Modalidad();
            $mod_unidad     = new UnidadAcademica();
            $mod_periodo = new PeriodoAcademico();
            $asig_mod = new Asignatura();
            $mod_profesor = new Profesor();
            

            $periodos = $mod_periodo->consultarPeriodosActivos();
            $periodo_actual = $mod_periodo->getPeriodoAcademicoActual();
            $profesores = $mod_profesor->getProfesoresEnAsignaturas();   
             $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
            $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);  

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
                'unidades' =>  ArrayHelper::map(array_merge($arr_unidad), "id", "name"),
                'modalidades' =>  ArrayHelper::map(array_merge($arr_modalidad), "id", "name"),
                'periodo_actual' => $periodo_actual,
                'materias' => ArrayHelper::map(array_merge($materias), "asi_id", "asi_descripcion"),
                'parciales' => $this->parciales(),
                'profesores' => ArrayHelper::map(array_merge($profesores), "per_id", "nombres"),
                'admin' => $admin
            ]);
        }
    }
    
    public function actionMarcarasistencia(){
        try{
            $per_id = @Yii::$app->session->get("PB_perid");
            $emp_id = @Yii::$app->session->get("PB_idempresa");
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
           $mod_periodo = new PlanificacionEstudiante();
           $busquedalumno = $mod_periodo->busquedaEstudianteplanificacion();

           if ($_GET['PBgetFilter'] == 1) {
            
            $periodo  = $_GET['periodo'];
            $modalidad = $_GET['modalidad'];
            $profesor = $_GET['profesor'];
            $uaca_id = $_GET['uaca_id'];
            $materia = $_GET['materia'];
            $sesion = $_GET['sesion']?$_GET['sesion']:0;
            $filtro = $_GET['PBgetFilter'];
            //print_r('true '.$periodo.'-'.$modalidad.'-'.$filtro);
            \app\models\Utilities::putMessageLogFile('Botón Buscar: '.$data);
            try{
                    $arrSearch['modalidad'] = $modalidad?$modalidad:0;
                    $arrSearch['periodo'] = $periodo?$periodo:0;
                    $arrSearch['profesor'] = $profesor?$profesor:0;
                    $arrSearch['unidad'] = $uaca_id?$uaca_id:0;
                    $arrSearch['materia'] = $materia?$materia:0;
                    $arrSearch['sesion'] = $sesion?$sesion:0;
                    $sesion = explode('/',$sesion);
                    $parcial = $sesion[1];
                    $sesion = $sesion[0];
                    $arrSearch['sesion'] = $sesion?$sesion:0;
                    $arrSearch['parcial'] = $parcial?$parcial:0;

                    $model_cabasistencia = new CabeceraAsistencia();
                    // $model = $model_cabasistencia->getAsistencia($arrSearch);
                    $model = $model_cabasistencia->consultarAlumnosxSesion($arrSearch);

                    $arr_profesor      = $mod_profesor->getProfesoresxid($per_id);
                    $arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual(); 
                    $arr_ninteres      = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
                    $arr_modalidad     = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1); 
                    $arr_parcialunidad = $mod_periodoActual->getTodosParciales();
                    $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId2($per_id);

                    $asignatura = $Asignatura_distri->getAsignaturasBy($arr_profesor["Id"],
                                                                  $arr_ninteres[0]["id"],
                                                                  $arr_periodoActual[0]["id"]);
                    $inicial = $model_cabasistencia->consultarAsistenciaInicialFinal($sesion);
                    if($inicial > 0){
                        $status_i   = 'disabled';
                        $status_f   = 'enabled';
                        $marcado    = 'de Final';
                    }else{
                        $status_i   = 'enabled';
                        $status_f   = 'disabled';
                        $marcado    = 'de Inicio';
                    }                                                                  

                    //print_r($modalidad_data[$modalidad]);die();
                    return $this->render('marcarasistencia', [
                        'arr_asignatura'    => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
                        // 'arr_asignatura'    => ArrayHelper::map(array_merge([['id' => ($arrSearch['materia']),'name' =>  ($arrSearch['materia']?$asignatura[$arrSearch['materia']]:'Seleccionar')]], $asignatura), 'id', 'name'),
                        'arr_periodoActual' => ArrayHelper::map(array_merge([["id" => "0", "nombre" => Yii::t("formulario", "Select")]], $arr_periodoActual), "id", "nombre"),
                        'arr_ninteres'      => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_ninteres), "id", "name"),
                        'arr_modalidad'     => ArrayHelper::map($arr_modalidad, "id", "name"),
                        'arr_parcial'       => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_parcialunidad), "id", "name"),
                        'pro_id'            => $arr_profesor["Id"],
                        'arr_profesor_all'  => ArrayHelper::map($arr_profesor_all, "pro_id", "nombres"),
                        'model'             => $model,
                        'arr_paralelo'      => $this->Paralelo(),
                        'arr_sesion'        => $this->Paralelo(),
                        'status_i'          => $status_i,
                        'status_f'          => $status_f,
                        'marcado'           => $marcado,
                        'periodo'           => $periodo,
                        'materia'           => $materia,
                        'unidad'            => $uaca_id,
                        'sesion'            => $sesion,
                        'arr_alumno' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $busquedalumno), 'id', 'name'),
                    ]);
                
            }catch(Exception $ex) {
                \app\models\Utilities::putMessageLogFile('catch...: ');
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al buscar.".$ex->getMessage()),
                    "title" => Yii::t('jslang', 'Error'),
                );
                \app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('actionMarcarasistencia: '.$message);
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
   
           if (Yii::$app->request->isAjax) {
               $data = Yii::$app->request->post();    
   
                
                 if (isset($data["getuni"])) {
                    $profesorup = $mod_profesor->getProfesoresEnAsignaturasByall($data["paca_id"],$data["uaca_id"],$data["mod_id"]);
                   $message = array("profesorup" => $profesorup);
                   return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
               }
   
               
   
                if (isset($data["getmateria"])) {
                   $materia = $Asignatura_distri->getAsignaturasBy($data["pro_id"], $data["uaca_id"], $data["paca_id"]);
                   $message = array("materia" => $materia);
                   return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
               }
   
           }//if
   
            $arr_grupos        = $grupo_model->getAllGruposByUser($user_usermane);
   
                 
           if ( in_array(['id' => '6'], $arr_grupos) ||
                in_array(['id' => '7'], $arr_grupos) ||
                in_array(['id' => '8'], $arr_grupos)
           )
           {
               //Es Cordinador
               $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas(); 
               //print_r("Es Cordinador");
           }else{
               //No es coordinador
               //$arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId($per_id);
               $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturasByPerId2($per_id);
              //   $arr_profesor_all = $mod_profesor->getProfesoresEnAsignaturas();
               //print_r("NO Es Cordinador");
           }
   
           $arr_profesor      = $mod_profesor->getProfesoresxid($per_id);
           //$arr_periodoActual = $mod_periodoActual->consultarPeriodosActivos();    
           $arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual(); 
           //$asignatura        = $Asignatura_distri->getAsignaturaRegistro($arr_profesor["Id"],1,1,$arr_periodoActual[0]["id"]);
           $arr_ninteres      = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
           $arr_modalidad     = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);        
           $arr_estudiante    = $mod_estudiante->consultarEstudiante();        
           $arr_parcialunidad = $mod_periodoActual->getTodosParciales();
           $arr_componente    = $mod_calificacion->getComponenteUnidad($arr_ninteres[0]["id"]);
           //$componenteuni     = $mod_calificacion->getComponente($arr_componente[0]["id"], $arr_componente[0]["columna"], $arr_componente[0]["nombre"], $arr_ninteres[0]["id"]);
           
           //$asignatura = $Asignatura_distri->getAsignaturaRegistro($arr_profesor_all[0]['pro_id'],$arr_ninteres[0]["id"],1,$arr_periodoActual[0]["id"]);
            $asignatura = $Asignatura_distri->getAsignaturasBy($arr_profesor_all[0]['pro_id'],
                                                                  $arr_ninteres[0]["id"],
                                                                  $arr_periodoActual[0]["id"]);
            
            $periodo = Planificacion::getPeriodosAcademicoMod();

            $model = [];
            

            return $this->render('marcarasistencia', [
                'arr_asignatura'    => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $asignatura), "id", "name"),
                'arr_periodoActual'    => ArrayHelper::map(array_merge([["id" => "0", "nombre" => Yii::t("formulario", "Select")]], $arr_periodoActual), "id", "nombre"),
                'arr_ninteres'      => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_ninteres), "id", "name"),
                'arr_modalidad'     => ArrayHelper::map($arr_modalidad, "id", "name"),
                'arr_parcial'       => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_parcialunidad), "id", "name"),
                'pro_id'            => $arr_profesor["Id"],
                'model'             => $model,
                'campos'            => $campos,
                'unidad'            => $unidad,
                'arr_profesor_all'  => ArrayHelper::map($arr_profesor_all, "pro_id", "nombres"),
                'arr_paralelo'      => $this->Paralelo(),
                'arr_sesion'      => $this->Paralelo(),
            ]);
        }catch(Exception $e){
             $message = array(
                 "wtmessage" => Yii::t("notificaciones", $e->getMessage()),
                 "title" => Yii::t('jslang', 'Error'),
             );
             \app\models\Utilities::putMessageLogFile('actionExportexcel: error ' . $e->getMessage());
             print_r($message);die();
             exit;
        }
     }

    public function actionListarsesiones(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $asi_id = $data['asi_id'];
            $paca_id = $data['paca_id'];
            $pro_id = $data['pro_id'];
            $paralelo = $data['paralelo'];
            $asi= new Asignatura(); 
            $paralelos = $asi->consultaSesionesxProfesorxMateria($asi_id,$paca_id,$pro_id,$paralelo);
            return json_encode($paralelos);
        }
    }
    public function action(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $asi_id = $data['asi_id'];
            $paca_id = $data['paca_id'];
            $pro_id = $data['pro_id'];
            $asi= new Asignatura(); 
            $paralelos = $asi->consultaSesionesxProfesorxMateria($asi_id,$paca_id,$pro_id);
            return json_encode($paralelos);
        }
    }
    public function actionListarasignaturas(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $paca_id = $data['paca_id'];
            $pro_id = $data['pro_id'];
            $asi= new Asignatura(); 
            $asignaturas = $asi->consultaAsignaturaxProfesor($paca_id,$pro_id);
            return json_encode($asignaturas);
        }
    }
    public function actionListarparalelos(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $asi_id     = $data['asi_id'];
            $paca_id    = $data['paca_id'];
            $mod_id     = $data['mod_id'];
            $pro_id     = $data['pro_id'];
            $asi= new Asignatura(); 
            $paralelos = $asi->consultaParalelosxMateria($asi_id,$paca_id,$mod_id,$pro_id);
            return json_encode($paralelos);
        }
    }
    public function actionRegistrarasistencia(){
        try{
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post();
                $contenido  = $data['contenido'];
                $rmtm_id    = $data['sesion'];
                $pro        = $data['profesor'];
                $paca       = $data['periodo'];
                $asi_id     = $data['materia'];
                $list = explode(",",$contenido);
                $cab= new CabeceraAsistenciaClases(); 
                $cabAsi = new CabeceraAsistencia();
                $cabecera = $cab->detalleSesiones($pro, $paca, $asi_id);
                foreach($list as $element){
                    $cont = explode("-",$element);
                    $per_id     = $cont[0];//['id'];
                    $asi_id     = $cont[1];//['asi_id'];
                    $paca_id    = $cont[2];//['paca_id'];
                    $mod_id     = $cont[3];//['mod_id'];
                    $pro_id     = $cont[4];//['pro_id'];
                    $est_id     = $cont[5];//['est_id'];
                    $daho_id    = $cont[6];//['daho_id'];
                    $sesion     = $cont[7];//['rmtm_id'];
                    $min_atraso = $cont[8]?$cont[8]:0;
                    $min_retiro = $cont[9]?$cont[9]:0;
                    $parcial    = $cont[10]?$cont[10]:1;
                    // $sesion = explode('/',$sesion);
                    // $parcial = $sesion[1];
                    // $rmtm_id = $sesion[0];
                    $rmtm_id = $sesion;
                    /*Calcular porcentajes de asistencia a restar por atraso o retiro anticipado de clases  */
                    $porcentaje_atraso = ((100 / 60) * $min_atraso)/100;
                    $porcentaje_retiro = ((100 / 60) * $min_retiro)/100;
                    /* Se crea la cabecera de Asistencia Porcentajes*/
                    $existeCab = $cab->existeCabeceraAsistenciaClases($est_id, $paca_id, $asi_id, $pro_id);
                    if($existeCab['cantidad'] == 0){
                        $cabDet = $cab->insertCabeceraAsistenciaClases($paca_id, $est_id, $pro_id, $asi_id, $cabecera['duracion'],$cabecera['porcentaje']);
                        $caac_id = $cabDet;
                    }else{
                        $cabDet = $cab->consultarCabeceraAsistenciaClases($est_id, $paca_id, $asi_id, $pro_id);
                        $caac_id = $cabDet['caac_id'];
                    }
                    /* Se crea la Detalle de Asistencia Porcentajes*/
                    $existeDet = $cab->existeDetalleAsistenciaClases($caac_id, $rmtm_id);
                    if($existeDet['cantidad'] == 0){
                        $horas = $cab->getDetalleHorario( $rmtm_id);
                        $detAsis = $cab->insertDetalleAsistenciaClases($caac_id, $asi_id, $paca_id, $mod_id, $pro_id,
                        $est_id, $daho_id, $horas['hape_id'], $rmtm_id, $horas['hora_ini'],
                        $min_atraso);
                    }else{
                        $deac_id = $cab->consultarDetalleAsistenciaClases($caac_id, $rmtm_id);
                        $horas = $cab->getDetalleHorario( $rmtm_id);
                        if($deac_id['fin'] == null && $deac_id['retiro'] == null){
                            $fin = $cab->actualizarHoraFinClase( $rmtm_id, $min_retiro, $caac_id, $deac_id['deac_id'], $horas['hora_fin']);
                        }
                    }
                    /* Se crea la Cabecera de Asistencia para promedios final*/
                    $existeCabAsi = $cabAsi->consultarExisteCabecera($paca_id,$est_id,$pro_id,$asi_id,$parcial);
                    if($existeCabAsi['cant'] == 0){
                        $casi_id = $cabAsi->crearIdCabecera($paca_id,$est_id,$pro_id,$asi_id,$parcial,$cabecera['porcentaje']);
                    }else{
                        $caac_id = $cabAsi->consultarIdCabecera($paca_id,$est_id,$pro_id,$asi_id,$parcial);
                        $asistido = $cabecera['porcentaje'] * (1 - $porcentaje_atraso - $porcentaje_retiro);
                        $casi = $cabAsi->actualizarAsistenciaCabecera($caac_id['casi_id'],$asistido);
                        $casi_id = $caac_id['casi_id'];
                    }
                    /* Se crea el Detalle de Asistencia  */
                    $detalle = new DetalleAsistencia();
                    $cantDet = $cabAsi->consultarIdDetalle($casi_id);
                    // if($cantDet == 0){
                        $dasi_id = $cabAsi->crearIdDetalle($casi_id,$parcial,$cabecera['duracion']);
                    // }
                };
                \app\models\Utilities::putMessageLogFile('Lista Estudiantes: '.$contenido);
                $message = ['info' => Yii::t('exception', '<strong>Well done!</strong> your information was successfully saved.')];
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }catch(Exception $ex){
            $message = ['info' => Yii::t('exception', 'The above error occurred while the Web server was processing your request.')];
            echo Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
            \app\models\Utilities::putMessageLogFile('Error listado estudiates '.$ex->getMessage());
        }
    }
}