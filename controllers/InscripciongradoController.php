<?php

namespace app\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Persona;
use app\models\Empresa;
use app\models\EmpresaPersona;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use app\models\Grupo;
use app\models\Rol;
use app\models\GrupRol;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademico;
use app\models\Pais;
use app\models\Provincia;
use app\models\Canton;
use app\models\EstadoCivil;
use app\models\TipoParentesco;
use app\models\PersonaContacto;
use app\modules\academico\models\MallaAcademica;
use app\models\InscripcionGrado;
use app\modules\admision\models\MetodoIngreso;
use app\modules\admision\models\ConvenioEmpresa;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\models\ExportFile;

academico::registerTranslations();

class InscripciongradoController extends \yii\web\Controller {

    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    private function financiamiento() {
        return [
            '1' => Yii::t("formulario", "Crédito directo"),
            '2' => Yii::t("formulario", "Crédito bancario"),
            '3' => Yii::t("formulario", "Beca"),
        ];
    }

    public function actionIndex() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $mod_persona = new Persona();
        $mod_unidad = new UnidadAcademica();
        $mod_carrera = new EstudioAcademico();
        $mod_modalidad = new Modalidad();
        $mod_periodo = new PeriodoAcademico();
        $mod_malla = new MallaAcademica();
        $modcanal = new Oportunidad();
        $emp_id = 1;
        $arr_unidad = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]['id'],  $emp_id);
        $arr_carrera = $modcanal->consultarCarreraModalidad($arr_unidad[0]['id'], $arr_modalidad[0]["id"]);
        $arr_periodo = $mod_periodo->consultarPeriodosActivos(1);

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            //Utilities::putMessageLogFile('cedula en change.. ' .$data['cedulacons'] );
            if (isset($data["getcedula"])) {
                $persids = $mod_persona->consultaPeridxdni($data['cedulacons']);
                $message = array("persids" => $persids['per_id']);
                Utilities::putMessageLogFile('per_id consultado.. ' .$persids['per_id'] );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais en informacion personal
                $area = $mod_pais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data['getmodalidad'])) {
                $modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]['id'],  $emp_id);
                $message = array('modalidad' => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_ciudad_nac= Canton::find()->select("can_id AS id, can_nombre AS value")->where(["can_estado_logico" => "1", "can_estado" => "1"])->asArray()->all();
        $arr_nacionalidad = Pais::find()->select("pai_id AS id, pai_nacionalidad AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_provincia = Provincia::provinciaXPais($arr_pais[0]["id"]);
        $arr_ciudad= Canton::cantonXProvincia($arr_provincia[0]["id"]);
        $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $mod_metodo = new MetodoIngreso();
        $arr_metodos = $mod_metodo->consultarMetodoUnidadAca_2($arr_unidad[0]["id"]);
        $mod_conempresa = new ConvenioEmpresa();
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $_SESSION['JSLANG']['Your information has not been saved. Please try again.'] = Yii::t('notificaciones', 'Your information has not been saved. Please try again.');
        return $this->render('index', [
            "arr_unidad" => ArrayHelper::map($arr_unidad, "id", "name"),
            "arr_carrera" => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_carrera), 'id', 'name'),
            "arr_modalidad" => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_modalidad), 'id', 'name'),
            "arr_periodo" => ArrayHelper::map(array_merge([['id' => '0', 'paca_nombre' => 'Seleccionar']], $arr_periodo), 'id', 'paca_nombre'),
            "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
            "tipos_dni2" => array("CED" => Yii::t("formulario", "DNI Document1"), "PASS" => Yii::t("formulario", "Passport1")),
            "arr_ciudad_nac" => ArrayHelper::map($arr_ciudad_nac, "id", "value"),
            "arr_nacionalidad" => ArrayHelper::map($arr_nacionalidad, "id", "value"),
            "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
            "arr_pais" => ArrayHelper::map($arr_pais, "id", "value"),
            "arr_provincia" => ArrayHelper::map($arr_provincia, "id", "value"),
            "arr_ciudad" => ArrayHelper::map($arr_ciudad, "id", "value"),
            "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
            "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
            "arr_convenio_empresa" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Ninguna")]], $arr_convempresa), "id", "name"),
            "resp_datos" => $resp_datos,
            "arr_financiamiento" => $this->financiamiento(),
        ]);
    }

    public function actionGuardarinscripciongrado() {
        $mod_persona = new Persona();
        $user_ingresa = Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                //if ($per_id > 0) {
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'pdf' /*|| $typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg'*/) {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                       }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes pdf, jpg, png.")]);
                 //}
                }
            }

               if ($data["upload_foto"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                //if ($per_id > 0) {
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg') {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                       }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes jpg, png.")]);
                 //}
                }
            }

            $con = \Yii::$app->db;
            $transaction = $con->beginTransaction();

            $timeSt = date(Yii::$app->params["dateByDefault"]);
            try {

                $unidad = $data['unidad'];
                $carrera = $data['carrera'];
                $modalidad = $data['modalidad'];
                $periodo = $data['periodo'];
                $ming_id = $data['ming_id'];
                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                $per_dni = $data['cedula'];
                //if ($per_id > 0) {
                $inscripgrado_id = $data["igra_id"];
                if (isset($data["igra_ruta_doc_documento"]) && $data["igra_ruta_doc_documento"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_documento"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_documentoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_documento_per_" . $per_id . "." . $typeFile;
                    $titulo_documento = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_documentoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_documento"] = $titulo_documento;
                    if ($titulo_documento === false)
                        throw new Exception('Error Documento no renombrado.');
                }
                if (isset($data["igra_ruta_doc_titulo"]) && $data["igra_ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_titulo_per_" . $per_id . "." . $typeFile;
                $titulo_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_archivoOld, '' /*$timeSt*/);
                //Utilities::putMessageLogFile('titulo_archivo xXx.. ' .$titulo_archivo );
                $data["igra_ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["igra_ruta_doc_dni"]) && $data["igra_ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_dni_per_" . $per_id . "." . $typeFile;
                    $dni_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $dni_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["igra_ruta_doc_certvota"]) && $data["igra_ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_certvota_per_" . $per_id . "." . $typeFile;
                    $certvota_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certvota_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["igra_ruta_doc_foto"]) && $data["igra_ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_foto_per_" . $per_id . "." . $typeFile;
                    $foto_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $foto_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["igra_ruta_doc_comprobantepago"]) && $data["igra_ruta_doc_comprobantepago"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_comprobantepago"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $comprobantepago_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_comprobantepago_per_" . $per_id . "." . $typeFile;
                    $comprobantepago_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $comprobantepago_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_comprobantepago"] = $comprobantepago_archivo;
                    if ($comprobantepago_archivo === false)
                        throw new Exception('Error doc Comprobante de pago de matrícula no renombrado.');
                }

                if (isset($data["igra_ruta_doc_record"]) && $data["igra_ruta_doc_record"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_record"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $record_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_record_per_" . $per_id . "." . $typeFile;
                    $record_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $record_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_record"] = $record_archivo;
                    if ($record_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                }
                if (isset($data["igra_ruta_doc_certificado"]) && $data["igra_ruta_doc_certificado"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certificado"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certificado_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_certificado_per_" . $per_id . "." . $typeFile;
                    $certificado_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certificado_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_certificado"] = $certificado_archivo;
                    if ($certificado_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["igra_ruta_doc_syllabus"]) && $data["igra_ruta_doc_syllabus"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_syllabus"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $syllabus_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_syllabus_per_" . $per_id . "." . $typeFile;
                    $syllabus_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $syllabus_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_syllabus"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["igra_ruta_doc_homologacion"]) && $data["igra_ruta_doc_homologacion"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_homologacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $homologacion_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_especievalorada_per_" . $per_id . "." . $typeFile;
                    $homologacion_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $homologacion_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_homologacion"] = $homologacion_archivo;
                    if ($homologacion_archivo === false)
                        throw new Exception('Error doc Especie valorada por homologación no renombrado.');
                 }
                //}
                //Utilities::putMessageLogFile('cedula o pasaporte.. ' . $per_dni );
                //datos academicos
                $uaca_id = $data['unidad'];
                $eaca_id = $data['carrera'];
                $mod_id = $data['modalidad'];
                $paca_id = $data['periodo'];

                //datos personales
                $per_dni = $data['cedula'];
                $per_pri_nombre = ucwords(strtolower($data["primer_nombre"]));
                $per_seg_nombre = ucwords(strtolower($data["segundo_nombre"]));
                $per_pri_apellido = ucwords(strtolower($data["primer_apellido"]));
                $per_seg_apellido = ucwords(strtolower($data["segundo_apellido"]));
                $can_id_nacimiento = $data["cuidad_nac"];
                $per_fecha_nacimiento = $data["fecha_nac"];
                $per_nacionalidad = $data["nacionalidad"];
                $eciv_id = $data["estado_civil"];

                //datos contacto
                $pai_id_domicilio = $data["pais"];
                $pro_id_domicilio = $data["provincia"];
                $can_id_domicilio = $data["canton"];
                $per_domicilio_csec = ucwords(strtolower($data["parroquia"]));
                $per_domicilio_ref = ucwords(strtolower($data["dir_domicilio"]));
                $per_celular = $data["celular"];
                $per_domicilio_telefono = $data["telefono"];
                $per_correo = ucwords(strtolower($data["correo"]));

                //datos en caso de emergencias
                $per_trabajo_direccion = ucwords(strtolower($data["dir_trabajo"]));
                $pcon_nombre = ucwords(strtolower($data["cont_emergencia"]));
                $tpar_id = $data["parentesco"];
                $pcon_celular = ucwords(strtolower($data["tel_emergencia"]));
                $pcon_direccion = ucwords(strtolower($data["dir_personacontacto"]));

                //Datos de financiamiento
                $tfinanciamiento = $data["financiamiento"];
                $instituto_beca = ucwords(strtolower($data["instituto"]));

                //imagenes
                $igra_ruta_doc_documento = $data['igra_ruta_doc_documento'];
                $igra_ruta_doc_titulo = $data['igra_ruta_doc_titulo'];
                $igra_ruta_doc_dni = $data['igra_ruta_doc_dni'];
                $igra_ruta_doc_certvota = $data['igra_ruta_doc_certvota'];
                $igra_ruta_doc_foto = $data['igra_ruta_doc_foto'];
                $igra_ruta_doc_comprobantepago = $data['igra_ruta_doc_comprobantepago'];
                $igra_ruta_doc_record = $data['igra_ruta_doc_record'];
                $igra_ruta_doc_certificado = $data['igra_ruta_doc_certificado'];
                $igra_ruta_doc_syllabus = $data['igra_ruta_doc_syllabus'];
                $igra_ruta_doc_homologacion = $data['igra_ruta_doc_homologacion'];
                if ($per_id > 0) {
                    $model = new InscripcionGrado();
                    // persona ya exite se actualizan datos
                    $respPersona = $mod_persona->modificaPersonaInscripciongrado($per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $per_dni, $eciv_id,  $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad, $per_trabajo_direccion);
                    //consultar si existe  la persona en la tabla inscripcion_grado
                    $resp_inscripcion = $model->consultarDatosInscripciongrado($per_id);
                    //si existe modificar los datos
                    if ($resp_inscripcion['existe_inscripcion'] > 0){
                        // modificar la tabla
                        $cone = \Yii::$app->db_inscripcion;
                        $mod_inscripciongrado = new InscripcionGrado();
                        $inscripciongrado = $mod_inscripciongrado->updateDataInscripciongrado($cone, $per_id, $uaca_id , $eaca_id, $mod_id , $paca_id, $per_dni, $tfinanciamiento, $instituto_beca, $igra_ruta_doc_documento, $igra_ruta_doc_titulo, $igra_ruta_doc_dni, $igra_ruta_doc_certvota, $igra_ruta_doc_foto, $igra_ruta_doc_comprobantepago, $igra_ruta_doc_record, $igra_ruta_doc_certificado, $igra_ruta_doc_syllabus, $igra_ruta_doc_homologacion);
                        $exito=1;
                    }else{ // caso contrario crear
                        $resul = $model->insertarDataInscripciongrado($per_id, $uaca_id, $eaca_id, $mod_id, $paca_id, $per_dni, $data);
                    }
                        //consultar persona contacto
                        $insc_personacont = new PersonaContacto();
                        $exist_personacon = $insc_personacont->consultaPersonaContacto($per_id);
                        // si existe modificar
                        if ($exist_personacon['contacto_id'] > 0) {
                            $modi_personacon = $insc_personacont->modificarPersonacontacto($per_id, $tpar_id, $pcon_nombre, null, $pcon_celular, $pcon_direccion);
                        }
                        // sino crear
                        else{
                            $crea_personacon = $insc_personacont->crearPersonaContacto($per_id, $tpar_id, $pcon_nombre, null, $pcon_celular, $pcon_direccion);
                            //if($crea_personacon){
                            $exito=1;
                            //}
                      }
                    if($exito){
                        //AQUI SE NECEITA ENVIAR EL CORREO A ADMISONES QUE ALGUIEN INGRESO
                        /* obterner las variables de nombres, unidad y dni */
                        $nombre_completo = $per_pri_nombre .' '.$per_seg_nombre. ' '. $per_pri_apellido .' '.$per_seg_apellido;
                        $tituloMensaje = Yii::t("interesado", "UTEG - Inscripción Grado");
                        $asunto = Yii::t("interesado", "UTEG - Inscripción Grado");
                        $bodyadmision = Utilities::getMailMessage("Requestregistration", array("[[nombres]]" => $nombre_completo, "[[dni]]" => $per_dni, "[[unidad]]" => $uaca_id), Yii::$app->language);
                        Utilities::sendEmail($tituloMensaje, [Yii::$app->params["admisionespri"] => "Jefe"], $asunto, $bodyadmision);
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "The information have been saved"),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }else{
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "The information have not been saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);
                    }
                    }else{

                    //Aqui debe ser un mensaje que no existe la persona
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "No se encuentra documento de identidad de la persona registrada como aspirante, no se puede actualizar la información"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => $ex->getMessage(),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }


    public function actionTerminogrado() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/terminos.php';
        return $this->render('terminogrado', [
        ]);
    }
}