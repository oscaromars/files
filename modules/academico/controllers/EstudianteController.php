<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use app\modules\academico\models\EstudioAcademico;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\Estudiante;
use app\modules\admision\models\Oportunidad;
use app\models\Persona;
use app\models\Usuario;
use yii\base\Security;
use yii\base\Exception;
use app\models\EmpresaPersona;
use app\models\UsuaGrolEper;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;

academico::registerTranslations();
admision::registerTranslations();
financiero::registerTranslations();

class EstudianteController extends \app\components\CController {

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
            '9' => Yii::t("formulario", "R"),
        ];
    }

    private function estados() {
        return [
            '-1' => Yii::t("formulario", "All"),
            'null' => Yii::t("formulario", "No estudiante"),
            '0' => Yii::t("formulario", "Inactivo"),
            '1' => Yii::t("formulario", "Activo"),
        ];
    }

    public function actionIndex() {
        $mod_estudiante = new Estudiante();
        $mod_programa = new EstudioAcademico();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new Oportunidad();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["carrera"] = $data['carrera'];
            $arrSearch["estado"] = $data['estado'];
            $arr_estudiante = $mod_estudiante->consultarEstudiante($arrSearch);
            return $this->renderPartial('index-grid', [
                        "model" => $arr_estudiante,
            ]);
        } else {
            $arr_estudiante = $mod_estudiante->consultarEstudiante();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $mod_programa->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        $arr_carrerra1 = $mod_programa->consultarCarreraModalidad($arr_ninteres[0]["id"], $arr_modalidad[0]["id"]);
        return $this->render('index', [
                    'model' => $arr_estudiante,
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_carrerra1), "id", "name"),
                    'arr_estados' => $this->estados(),
        ]);
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M");

        $arrHeader = array(
            Yii::t("formulario", "First Names"),
            academico::t("diploma", "DNI"),
            Yii::t("formulario", "Email"),
            academico::t("Academico", 'Enrollment Number'),
            Yii::t("formulario", "Category"),
            Yii::t("formulario", 'Date Create'),
            academico::t("Academico", "Academic unit"),
            academico::t("matriculacion", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Status")
        );

        $mod_estudiante = new Estudiante();
        $data = Yii::$app->request->get();

        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["estadoSol"] = $data['estadoSol'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["carrera"] = $data['carrera'];
        $arrSearch["estado"] = $data['estado'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_estudiante->consultarEstudiante(array(), true);
        } else {
            $arrData = $mod_estudiante->consultarEstudiante($arrSearch, true);
        }

        $nameReport = academico::t("Academico", "List students");

        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "List students"); // Titulo del reporte

        $mod_estudiante = new Estudiante();
        $data = Yii::$app->request->get();
        $arr_body = array();

        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["carrera"] = $data['carrera'];
        $arrSearch["estado"] = $data['estado'];

        $arr_head = array(
            Yii::t("formulario", "First Names"),
            academico::t("diploma", "DNI"),
            Yii::t("formulario", "Email"),
            academico::t("Academico", 'Enrollment Number'),
            Yii::t("formulario", "Category"),
            Yii::t("formulario", 'Date Create'),
            academico::t("Academico", "Academic unit"),
            academico::t("matriculacion", "Modality"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Status")
        );

        if (empty($arrSearch)) {
            $arr_body = $mod_estudiante->consultarEstudiante(array(), true);
        } else {
            $arr_body = $mod_estudiante->consultarEstudiante($arrSearch, true);
        }

        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arr_head,
                    'arr_body' => $arr_body
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionNew() {
        $per_id = base64_decode($_GET['per_id']);
        $persona_model = new Persona();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new EstudioAcademico();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $dataPersona = $persona_model->consultaPersonaId($per_id);
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        $arr_carrerra1 = $modcanal->consultarCarreraModalidad($arr_ninteres[0]["id"], $arr_modalidad[0]["id"]);
        return $this->render('new', [
                    'arr_persona' => $dataPersona,
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
                    'arr_carrera' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_carrerra1), "id", "name"),
                    'arr_categorias' => $this->categorias(),
        ]);
    }

    public function actionSave() {
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        $emp_id = 1;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $per_id = $data["per_id"];
            $uaca_id = $data["unidad"];
            $mod_id = $data["modalidad"];
            $eaca_id = $data["carrera"];
            $categoria = $data["categoria"];
            $matricula = $data["matricula"];

            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            $con2 = \Yii::$app->db_asgard;
            $transaction2 = $con2->beginTransaction();
            try {
                $mod_Estudiante = new Estudiante();
                $mod_Modestuni = new ModuloEstudio();
                $mod_persona = new Persona();
                $usuario = new Usuario();
                $mod_emp_persona = new EmpresaPersona();
                $usergrol = new UsuaGrolEper();
                // consultar el per_id sino esta en estudiante si esta un else q diga ya existe estudiante getEstudiantexperid($per_id)
                $resp_estudianteid = $mod_Estudiante->getEstudiantexperid($per_id); 
                if ($resp_estudianteid["est_id"] == "") {
                    // consultar datos de la person con per_id consultaPersonaId($per_id)
                    $resp_persona = $mod_persona->consultaPersonaId($per_id);
                    // actualizar clave de usuario a numero de cedula sino crear en tabla usuario
                    if ($resp_persona["usu_id"] == "") { // No existe el usuario
                        // se crea en la tabla usuario OJO FALTA
                        $usuarioid = $usuario->crearUsuario($resp_persona["per_correo"], $resp_persona["per_cedula"], $per_id);
                        // consultar si existe en empresa_persona, sino guardar en empresa_persona 
                        $emp_per_id = $mod_emp_persona->consultarIdEmpresaPersona($per_id, $emp_id);
                        if ($emp_per_id == 0) {
                            $keys = ['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'];
                            $parametros = [$emp_id, $per_id, 1, 1];
                            $emp_per_id = $mod_emp_persona->insertarEmpresaPersona($con2, $parametros, $keys, 'empresa_persona');
                        }
                        if ($emp_per_id) {
                            // se crea en usuario_grol con rol 37
                            $grol_id = 37;
                            $keys = ['eper_id', 'usu_id', 'grol_id', 'ugep_estado', 'ugep_estado_logico'];
                            $parametros = [$emperid, $usuarioid, $grol_id, 1, 1];
                            $usgrol_id = $usergrol->insertarUsuaGrolEper($con2, $parametros, $keys, 'usua_grol_eper');
                            // guardar en tabla estudiante
                            if ($usgrol_id) {
                                $resp_estudiante = $mod_Estudiante->insertarEstudiante($per_id, $matricula, $categoria, $usu_autenticado, null, $fecha, null);
                                if ($resp_estudiante) {
                                    // if guarda estudiante consultar la tabla modalidad_estudio_unidad con uaca_id, mod_id y eaca_id, si no existe error de que no hay modalidad_estudio_unidad, caso contrario seguir
                                    $resp_mestuni = $mod_Modestuni->consultarModalidadestudiouni($uaca_id, $mod_id, $eaca_id);
                                    if ($resp_mestuni) {
                                        // guardar en modalidad_estudio_unidad  
                                        $resp_estudcarreprog = $mod_Estudiante->insertarEstcarreraprog($resp_estudiante, $resp_mestuni["meun_id"], $fecha, $usu_autenticado, $fecha);
                                        if ($resp_estudcarreprog) {
                                            $exito = 1;
                                        }
                                    }
                                }
                            }
                        }
                    } else { // existe usuario
                        // se actualizar clave a la cedula y estado activo
                        $security = new Security();
                        $usu_sha = $security->generateRandomString();
                        $usu_pass = base64_encode($security->encryptByPassword($usu_sha, $resp_persona["per_cedula"]));
                        $respUsu = $usuario->actualizarDataUsuario($usu_sha, $usu_pass, $resp_persona["usu_id"]);

                        if ($respUsu) {
                            // consultar si existe en la tabla empresa_persona con el per_id, sino existe crear
                            $emp_per_id = $mod_emp_persona->consultarIdEmpresaPersona($per_id, $emp_id);
                            if ($emp_per_id == 0) {
                                $keys = ['emp_id', 'per_id', 'eper_estado', 'eper_estado_logico'];
                                $parametros = [$emp_id, $per_id, 1, 1];
                                $emp_per_id = $mod_emp_persona->insertarEmpresaPersona($con2, $parametros, $keys, 'empresa_persona');
                            }
                            // Actualizar a rol de estudiante 37
                            $respUsugrol = $usergrol->actualizarRolEstudiante($resp_persona["usu_id"]);
                            if ($respUsugrol) {
                                $respUsugrol = $usergrol->actualizarRolEstudiante($resp_persona["usu_id"]);
                                // guardar en tabla estudiante
                                $resp_estudiante = $mod_Estudiante->insertarEstudiante($per_id, $matricula, $categoria, $usu_autenticado, null, $fecha, null);
                                if ($resp_estudiante) {
                                    // if guarda estudiante consultar la tabla modalidad_estudio_unidad con uaca_id, mod_id y eaca_id, si no existe error de que no hay modalidad_estudio_unidad, caso contrario seguir
                                    $resp_mestuni = $mod_Modestuni->consultarModalidadestudiouni($uaca_id, $mod_id, $eaca_id);
                                    if ($resp_mestuni) {
                                        // guardar en modalidad_estudio_unidad  
                                        $resp_estudcarreprog = $mod_Estudiante->insertarEstcarreraprog($resp_estudiante, $resp_mestuni["meun_id"], $fecha, $usu_autenticado, $fecha);
                                        if ($resp_estudcarreprog) {
                                            $exito = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($exito) {
                        $transaction->commit();
                        $transaction2->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La información ha sido grabada."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $transaction2->rollback();
                        if (empty($message)) {
                            $message = array
                                (
                                "wtmessage" => Yii::t("notificaciones", "Error al grabar. " . $mensaje), "title" =>
                                Yii::t('jslang', 'Success'),
                            );
                        }
                        return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    }
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $transaction2->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
            return;
        }
    }

    public function actionView() {
        $per_id = base64_decode($_GET['per_id']);
        $est_id = base64_decode($_GET['est_id']);
        $persona_model = new Persona();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new EstudioAcademico();
        $mod_Estudiante = new Estudiante();

        $dataPersona = $persona_model->consultaPersonaId($per_id);
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
    }

    public function actionEdit() {
        $per_id = base64_decode($_GET['per_id']);
        $est_id = base64_decode($_GET['est_id']);
        $persona_model = new Persona();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new EstudioAcademico();
        $mod_Estudiante = new Estudiante();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $dataPersona = $persona_model->consultaPersonaId($per_id);
        $dataEstudiante = $mod_Estudiante->getEstudiantexestid($est_id);
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($dataEstudiante['unidad'], 1);
        $arr_carrerra1 = $modcanal->consultarCarreraModalidad($dataEstudiante['unidad'], $dataEstudiante['modalidad']);
        return $this->render('edit', [
                    'arr_persona' => $dataPersona,
                    'arr_estudiante' => $dataEstudiante,
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
                    'arr_carrera' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_carrerra1), "id", "name"),
                    'arr_categorias' => $this->categorias(),
        ]);
    }

    public function actionUpdate() {
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $per_id = $data["per_id"];
            $est_id = $data["est_id"];
            $uaca_id = $data["unidad"];
            $mod_id = $data["modalidad"];
            $eaca_id = $data["carrera"];
            $categoria = $data["categoria"];
            $matricula = $data["matricula"];

            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();

            try {
                //\app\models\Utilities::putMessageLogFile('entro: ');
                $mod_Estudiante = new Estudiante();
                $mod_Modestuni = new ModuloEstudio();
                // consultar la modalidad_estudio_unidad
                $resp_mestuni = $mod_Modestuni->consultarModalidadestudiouni($uaca_id, $mod_id, $eaca_id);
                if ($resp_mestuni["meun_id"] > 0) {
                    // modifica la tabla estudiante
                    $resp_estudiante = $mod_Estudiante->updateEstudiante($est_id, $matricula, $categoria, $usu_autenticado, $fecha);
                    if ($resp_estudiante) {
                        // consultar si existe el id de estudiante_carrera_programa no existe insertar, si existe modificar
                        $resp_estucarrera = $mod_Estudiante->consultarEstcarreraprogrma($est_id);
                        // no existe usar esa funcion
                        if ($resp_estucarrera["idestcarrera"] == "") {
                            $resp_estudiantecarrera = $mod_Estudiante->insertarEstcarreraprog($est_id, $resp_mestuni["meun_id"], $fecha, $usu_autenticado, $fecha);
                            if ($resp_estudiantecarrera) {
                                $exito = 1;
                            }
                        } else {
                            // modifica la tabla estudiante_carrera_programa   
                            $resp_estudiantecarrera = $mod_Estudiante->updateEstudiantecarreraprogr($est_id, $resp_mestuni["meun_id"], $usu_autenticado, $fecha);
                            if ($resp_estudiantecarrera) {
                                $exito = 1;
                            }
                        }
                    }
                }
                if ($exito) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido actualizada."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al actualizar."),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al actualizar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
            return;
        }
    }

    public function actionEstadoestudiante() {
        $mod_estudiante = new Estudiante();
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $est_id = $data["est_id"];
            $estado = $data["estado"];
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $resp_estado = $mod_estudiante->modificarEstadoest($est_id, $usu_autenticado, $estado, $fecha);
                if ($resp_estado) {
                    $exito = '1';
                }
                if ($exito) {
                    //Realizar accion
                    if ($estado == 0) {
                        $texto = "inactivado";
                        $texto1 = "inactivar";
                    } else {
                        $texto = "activado";
                        $texto1 = "activar";
                    }
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Se ha " . $texto . " el estudiante."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al " . $texto1 . ". "),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al realizar la acción. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }

}
