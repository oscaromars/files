<?php

namespace app\controllers;

use app\models\Canton;
use app\models\EmpresaPersona;
use app\models\EstadoCivil;
use app\models\EstudianteIdiomas;
use app\models\EstudianteInstruccion;
use app\models\Grupo;
use app\models\Idioma;
use app\models\InfoDiscapacidadEst;
use app\models\InfoDocenciaEstudiante;
use app\models\InfoEstudianteInvestigacion;
use app\models\InformacionLaboral;
use app\models\InscripcionPosgrado;
use app\models\NivelIdioma;
use app\models\Pais;
use app\models\Persona;
use app\models\PersonaContacto;
use app\models\Provincia;
use app\models\TipoDiscapacidad;
use app\models\TipoParentesco;
use app\models\Usuario;
use app\models\Utilities;
use yii\base\Security;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\admision\models\ConvenioEmpresa;
use app\modules\admision\models\MetodoIngreso;
use app\modules\academico\Module as academico;
use Yii;
use yii\helpers\Url;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\models\ExportFile;

academico::registerTranslations();

class InscripcionposgradoController extends \yii\web\Controller {

    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }
    public function actionIndex() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $per_id = Yii::$app->session->get("PB_perid");
        //$mod_persona = Persona::findIdentity($per_id);
        $mod_persona = new Persona();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $mod_programa = new EstudioAcademico();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            //Utilities::putMessageLogFile('cedula en change posg.. ' .$data['cedulacons'] );
            if (isset($data["getcedula"])) {
                $persids = $mod_persona->consultaPeridxdni($data['cedulacons']);
                $message = array("persids" => $persids['per_id']);
                //Utilities::putMessageLogFile('per_id consultado pos.. ' .$persids['per_id'] );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                //return;
            }
            if (isset($data["getprograma"])) {
                $programa = $mod_programa->consultarCarreraxunidad($data["unidad"]);
                $message = array("programa" => $programa);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_programa->consultarmodalidadxcarrera($data["programa"]);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getciudad"])) {
                $ciudad = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['provincia']])->asArray()->all();
                $message = array("ciudad" => $ciudad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais en informacion personal
                $area = $mod_pais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
        }

        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_programa = $mod_programa->consultarCarreraxunidad(2);
        $arr_modalidad = $mod_programa->consultarmodalidadxcarrera($arr_programa[0]["id"]);
        $arr_ciudad_nac= Canton::find()->select("can_id AS id, can_nombre AS value")->where(["can_estado_logico" => "1", "can_estado" => "1"])->asArray()->all();
        $arr_nacionalidad = Pais::find()->select("pai_id AS id, pai_nacionalidad AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_provincia = Provincia::provinciaXPais($arr_pais[0]["id"]);
        $arr_ciudad= Canton::cantonXProvincia($arr_provincia[0]["id"]);
        $arr_idioma= Idioma::find()->select("idi_id AS id, idi_nombre AS value")->where(["idi_estado_logico" => "1", "idi_estado" => "1"])->asArray()->all();
        $arr_nivelidioma= NivelIdioma::find()->select("nidi_id AS id, nidi_descripcion AS value")->where(["nidi_estado_logico" => "1", "nidi_estado" => "1"])->asArray()->all();
        $arr_prov_emp = Provincia::provinciaXPais($arr_nacionalidad[0]["id"]);
        $arr_ciu_emp = Canton::cantonXProvincia($arr_prov_emp[0]["id"]);
        $arr_tip_discap = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();
        $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $mod_metodo = new MetodoIngreso();
        $arr_metodos = $mod_metodo->consultarMetodoUnidadAca_2($arr_unidad[0]["id"]);
        $mod_conempresa = new ConvenioEmpresa();
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        $_SESSION['JSLANG']['Your information has not been saved. Please try again.'] = Yii::t('notificaciones', 'Your information has not been saved. Please try again.');
        /*$resp_persona = $mod_persona->consultarUltimoPer_id();
        $persona = $resp_persona["ultimo"];
        $per_id = intval( $persona );*/

        return $this->render('index', [
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
            'arr_programa' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_programa), 'id', 'name'),
            'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_modalidad), 'id', 'name'),
            "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
            "tipos_dni2" => array("CED" => Yii::t("formulario", "DNI Document1"), "PASS" => Yii::t("formulario", "Passport1")),
            "arr_ciudad_nac" => ArrayHelper::map($arr_ciudad_nac, "id", "value"),
            "arr_nacionalidad" => ArrayHelper::map($arr_nacionalidad, "id", "value"),
            "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
            "arr_pais" => ArrayHelper::map($arr_pais, "id", "value"),
            "arr_provincia" => ArrayHelper::map($arr_provincia, "id", "value"),
            "arr_ciudad" => ArrayHelper::map($arr_ciudad, "id", "value"),
            "arr_categoria" => array("1" => Yii::t("formulario", "Pública"), "2" => Yii::t("formulario", "Privada")),
            //"arr_pais_emp" => ArrayHelper::map($arr_pais_emp, "id", "value"),
            "arr_prov_emp" => ArrayHelper::map($arr_prov_emp, "id", "value"),
            "arr_ciu_emp" => ArrayHelper::map($arr_ciu_emp, "id", "value"),
            "arr_idioma" => ArrayHelper::map($arr_idioma, "id", "value"),
            "arr_idioma2" => ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Select")]], $arr_idioma), "id", "value"),
            "arr_nivelidioma" => ArrayHelper::map($arr_nivelidioma, "id", "value"),
            "arr_nivelidioma2" => ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Select")]], $arr_nivelidioma), "id", "value"),
            "arr_tip_discap" => ArrayHelper::map($arr_tip_discap, "id", "value"),
            "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
            "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
            "arr_convenio_empresa" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Ninguna")]], $arr_convempresa), "id", "name"),
            "resp_datos" => $resp_datos,
            //"per_id" => $per_id,
        ]);
    }

    public function actionGuardarinscripcionposgrado() {
        $mod_persona = new Persona();
        $user_ingresa = Yii::$app->session->get("PB_iduser");

        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);

            //$per_id = 54;
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'pdf' /*|| $typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg'*/) {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        return;
                    }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes pdf, jpg, png.")]);
                }
            }

                 if ($data["upload_foto"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg') {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        return;
                    }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes jpg, png.")]);
                }
            }

            $con = \Yii::$app->db;
            $transaction = $con->beginTransaction();
            $con1 = \Yii::$app->db_captacion;
            $transaction1 = $con1->beginTransaction();
            $timeSt = date(Yii::$app->params["dateByDefault"]);
            try {

                $unidad = $data['unidad'];
                $programa = $data['programa'];
                $modalidad = $data['modalidad'];
                $año = $data['año'];
                $tipo_financiamiento = $data['tipo_financiamiento'];
                $ming_id = $data['ming_id'];

                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                $per_dni = $data['cedula'];
                $inscriposgrado_id = $data["ipos_id"];
                if (isset($data["ipos_ruta_documento"]) && $data["ipos_ruta_documento"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_documento"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_documentoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_documento_per_" . $per_id . "." . $typeFile;
                    $titulo_documento = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $titulo_documentoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_documento"] = $titulo_documento;
                    if ($titulo_documento === false)
                        throw new Exception('Error Documento no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_foto"]) && $data["ipos_ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_foto_per_" . $per_id . "." . $typeFile;
                    $foto_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $foto_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_dni"]) && $data["ipos_ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_dni_per_" . $per_id . "." . $typeFile;
                    $dni_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $dni_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_certvota"]) && $data["ipos_ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_certvota_per_" . $per_id . "." . $typeFile;
                    $certvota_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $certvota_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_titulo"]) && $data["ipos_ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_titulo_per_" . $per_id . "." . $typeFile;
                    $titulo_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $titulo_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_comprobante"]) && $data["ipos_ruta_doc_comprobante"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_comprobante"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $comprobantepago_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_comprobante_per_" . $per_id . "." . $typeFile;
                    $comprobantepago_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $comprobantepago_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_comprobante"] = $comprobantepago_archivo;
                    if ($comprobantepago_archivo === false)
                        throw new Exception('Error doc Comprobante de pago de matrícula no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_record1"]) && $data["ipos_ruta_doc_record1"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_record1"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $record1_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_record_per_" . $per_id . "." . $typeFile;
                    $record1_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $record1_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_record1"] = $record1_archivo;
                    if ($record1_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_senescyt"]) && $data["ipos_ruta_doc_senescyt"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_senescyt"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $senescyt_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_senescyt_per_" . $per_id . "." . $typeFile;
                    $senescyt_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $senescyt_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_senescyt"] = $senescyt_archivo;
                    if ($senescyt_archivo === false)
                        throw new Exception('Error doc Senescyt no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_hojavida"]) && $data["ipos_ruta_doc_hojavida"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_hojavida"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $hojavida_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_hojavida_per_" . $per_id . "." . $typeFile;
                    $hojavida_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $hojavida_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_hojavida"] = $hojavida_archivo;
                    if ($hojavida_archivo === false)
                        throw new Exception('Error doc Hoja de Vida no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_cartarecomendacion"]) && $data["ipos_ruta_doc_cartarecomendacion"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_cartarecomendacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $carta_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_cartarecomendacion_per_" . $per_id . "." . $typeFile;
                    $carta_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $carta_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_cartarecomendacion"] = $carta_archivo;
                    if ($carta_archivo === false)
                        throw new Exception('Error doc Carta de Recomendación no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_certificadolaboral"]) && $data["ipos_ruta_doc_certificadolaboral"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_certificadolaboral"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certlaboral_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_certlaboral_per_" . $per_id . "." . $typeFile;
                    $certlaboral_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $certlaboral_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_certificadolaboral"] = $certlaboral_archivo;
                    if ($certlaboral_archivo === false)
                        throw new Exception('Error doc Certificado Laboral no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_certificadoingles"]) && $data["ipos_ruta_doc_certificadoingles"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_certificadoingles"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certingles_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_certingles_per_" . $per_id . "." . $typeFile;
                    $certingles_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $certingles_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_certificadoingles"] = $certingles_archivo;
                    if ($certingles_archivo === false)
                        throw new Exception('Error doc Certificado Ingles A2 no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_recordacademico"]) && $data["ipos_ruta_doc_recordacademico"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_recordacademico"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $recordacad_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_recordacad_per_" . $per_id . "." . $typeFile;
                    $recordacad_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $recordacad_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_recordacademico"] = $recordacad_archivo;
                    if ($recordacad_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_certnosancion"]) && $data["ipos_ruta_doc_certnosancion"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_certnosancion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certnosancion_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_certificado_per_" . $per_id . "." . $typeFile;
                    $certnosancion_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $certnosancion_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_certnosancion"] = $certnosancion_archivo;
                    if ($certnosancion_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_syllabus"]) && $data["ipos_ruta_doc_syllabus"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_syllabus"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $syllabus_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_syllabus_per_" . $per_id . "." . $typeFile;
                    $syllabus_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $syllabus_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_syllabus"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_homologacion"]) && $data["ipos_ruta_doc_homologacion"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_homologacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $homologacion_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/doc_homologacion_per_" . $per_id . "." . $typeFile;
                    $homologacion_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $homologacion_archivoOld, '' /*$timeSt*/);
                    $data["ipos_ruta_doc_homologacion"] = $homologacion_archivo;
                    if ($homologacion_archivo === false)
                        throw new Exception('Error doc Especie valorada por homologación no renombrado.');
                }

            //FORM 1 datos personal
            $per_dni = $data['cedula'];
            $primer_nombre = ucwords(strtolower($data["primer_nombre"]));
            $segundo_nombre = ucwords(strtolower($data["segundo_nombre"]));
            $primer_apellido = ucwords(strtolower($data["primer_apellido"]));
            $segundo_apellido = ucwords(strtolower($data["segundo_apellido"]));
            $can_id_nacimiento = $data["cuidad_nac"];
            $per_fecha_nacimiento = $data["fecha_nac"];
            $per_nacionalidad = $data["nacionalidad"];
            $eciv_id = $data["estado_civil"];
            $pai_id_domicilio = $data["pais"];
            $pro_id_domicilio = $data["provincia"];
            $can_id_domicilio = $data["canton"];

            //FORM 1 datos de Contacto
            $per_domicilio_ref = ucwords(strtolower($data["dir_domicilio"]));
            $per_celular = $data["celular"];
            $per_domicilio_telefono = $data["telefono"];
            $per_correo = ucwords(strtolower($data["correo"]));

            //FORM 1 datos en caso de emergencias
            $pcon_nombre = ucwords(strtolower($data["cont_emergencia"]));
            $tpar_id = $data["parentesco"];
            $pcon_celular = $data["tel_emergencia"];

            //Form2 Datos formacion profesional
            $titulo_ter = ucwords(strtolower($data["titulo_tercer"]));
            $universidad_tercer = ucwords(strtolower($data["universidad_tercer"]));
            $grado_tercer = ucwords(strtolower($data["grado_tercer"]));

            $titulo_cuarto = ucwords(strtolower($data["titulo_cuarto"]));
            $universidad_cuarto = ucwords(strtolower($data["universidad_cuarto"]));
            $grado_cuarto = ucwords(strtolower($data["grado_cuarto"]));

            //Form2 Datos laboral
            $empresa = ucwords(strtolower($data["empresa"]));
            $cargo = ucwords(strtolower($data["cargo"]));
            $telefono_emp = $data["telefono_emp"];
            $pais_emp = $data["pais_emp"];
            $prov_emp = $data["prov_emp"];
            $ciu_emp = $data["ciu_emp"];
            $parroquia = ucwords(strtolower($data["parroquia"]));
            $direccion_emp = ucwords(strtolower($data["direccion_emp"]));
            $añoingreso_emp = $data["añoingreso_emp"];
            $correo_emp = ucwords(strtolower($data["correo_emp"]));
            $cat_ocupacional = $data["cat_ocupacional"];

            //Form2 Datos idiomas
            $idioma1 = $data["idioma1"];
            $nivel1 = $data["nivel1"];

            $idioma2 = $data["idioma2"];
            $nivel2 = $data["nivel2"];

            $noidioma = '';
            $otroidioma = ucwords(strtolower($data["otroidioma"]));
            $otronivel = $data["otronivel"];

            //Form2 Datos adicionales
            $discapacidad = $data["discapacidad"];
            $tipo_discap = $data["tipo_discap"];
            $porcentaje_discap = $data["porcentaje_discap"];

            $docencias = $data["docencias"];
            $año_docencia = $data["año_docencia"];
            $area_docencia = ucwords(strtolower($data["area_docencia"]));

            $investiga = $data["investiga"];
            $articulos = $data["articulos"];
            $area_investigacion = ucwords(strtolower($data["area_investigacion"]));

            //Form2 Datos financiamiento
            $tipo_financiamiento = $data["tipo_financiamiento"];

            //archivos cargados
            $ipos_ruta_documento = $data['ipos_ruta_documento'];
            $ipos_ruta_doc_foto = $data['ipos_ruta_doc_foto'];
            $ipos_ruta_doc_dni = $data['ipos_ruta_doc_dni'];
            $ipos_ruta_doc_certvota = $data['ipos_ruta_doc_certvota'];
            $ipos_ruta_doc_titulo = $data['ipos_ruta_doc_titulo'];
            $ipos_ruta_doc_comprobantepago = $data['ipos_ruta_doc_comprobante'];
            $ipos_ruta_doc_recordacademico = $data['ipos_ruta_doc_record1'];
            $ipos_ruta_doc_senescyt = $data['ipos_ruta_doc_senescyt'];
            $ipos_ruta_doc_hojadevida = $data['ipos_ruta_doc_hojavida'];
            $ipos_ruta_doc_cartarecomendacion = $data['ipos_ruta_doc_cartarecomendacion'];
            $ipos_ruta_doc_certificadolaboral = $data['ipos_ruta_doc_certificadolaboral'];
            $ipos_ruta_doc_certificadoingles = $data['ipos_ruta_doc_certificadoingles'];
            $ipos_ruta_doc_otrorecord = $data['ipos_ruta_doc_recordacademico'];
            $ipos_ruta_doc_certificadonosancion = $data['ipos_ruta_doc_certnosancion'];
            $ipos_ruta_doc_syllabus = $data['ipos_ruta_doc_syllabus'];
            $ipos_ruta_doc_homologacion = $data['ipos_ruta_doc_homologacion'];
            $ipos_mensaje1 = $data['ipos_mensaje1'];
            $ipos_mensaje2 = $data['ipos_mensaje2'];
            if ($per_id > 0) {
                $model = new InscripcionPosgrado();
                // persona ya exite se actualizan datos
                $respPersona = $mod_persona->modificaPersonaInscripciongrado($primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $per_dni, $eciv_id,  $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad, $per_trabajo_direccion);
                //consultar si existe  la persona en la tabla inscripcion_grado
                $resp_inscripcion = $model->consultarDatosInscripcionposgrado($per_id);
                //si existe modificar los datos
                if ($resp_inscripcion['existe_inscripcionposgrado'] > 0) {
                    // modificar la tabla
                    $cone = \Yii::$app->db_inscripcion;
                    $mod_inscripcionposgrado = new InscripcionPosgrado();
                    $inscripcionposgrado = $mod_inscripcionposgrado->updateDataInscripcionposgrado($cone, $per_id, $data['unidad'], $data['programa'], $modalidad, $año, $per_dni, $tipo_financiamiento, $ming_id, $ipos_ruta_documento, $ipos_ruta_doc_foto, $ipos_ruta_doc_dni, $ipos_ruta_doc_certvota, $ipos_ruta_doc_titulo, $ipos_ruta_doc_comprobantepago, $ipos_ruta_doc_recordacademico, $ipos_ruta_doc_senescyt, $ipos_ruta_doc_hojadevida, $ipos_ruta_doc_cartarecomendacion, $ipos_ruta_doc_certificadolaboral, $ipos_ruta_doc_certificadoingles, $ipos_ruta_doc_otrorecord, $ipos_ruta_doc_certificadonosancion, $ipos_ruta_doc_syllabus, $ipos_ruta_doc_homologacion, $ipos_mensaje1, $ipos_mensaje2);
                    $exito=1;
                }else{ // caso contrario crear
                    $resul = $model->insertarDataInscripcionposgrado($per_id, $data['unidad'], $data['programa'], $modalidad, $año, $per_dni, $tipo_financiamiento, $data);
                    // creación de contacto
                   }
                    //consultar persona contacto
                    $insc_personacont = new PersonaContacto();
                    $exist_personacon = $insc_personacont->consultaPersonaContacto($per_id);
                    // si existe modificar
                    if ($exist_personacon['contacto_id'] > 0) {
                       $modi_personacon = $insc_personacont->modificarPersonacontacto($per_id, $tpar_id, $pcon_nombre, null, $pcon_celular, null);
                       //$exito=1;
                    }
                    // sino crear
                    else{
                        $crea_personacon = $insc_personacont->crearPersonaContacto($per_id, $tpar_id, $pcon_nombre, null, $pcon_celular, null);
                        //if($crea_personacon){
                        //$exito=1;
                        //}
                  }
                    // creación de datos formacion profesional
                    $modestinstruccion = new EstudianteInstruccion();
                    $resexisteinstruccion = $modestinstruccion->consultarEstInstruccion($per_id);
                    if ($resexistecontacto['existe_instruccion'] == 0) {
                        //Creación de persona de contacto
                        $resp_instruccion = $modestinstruccion->insertarEstudianteInstruccion($per_id, $titulo_ter, $universidad_tercer, $grado_tercer, $titulo_cuarto, $universidad_cuarto, $grado_cuarto);
                    } else {
                        $resp_instruccion = $modestinstruccion->modificarEstudianteinstruccion($per_id, $titulo_ter, $universidad_tercer, $grado_tercer, $titulo_cuarto, $universidad_cuarto, $grado_cuarto);
                    }
                    // creación de datos laborales del aspirante o estudiante
                    $mod_infolaboral = new InformacionLaboral();
                    $resexisteinfo = $mod_infolaboral->consultarInfoLaboral($per_id);
                    if ($resexisteinfo['existe_infolaboral'] == 0) {
                        //Creación de persona de contacto
                        $resp_infolaboral = $mod_infolaboral->insertarInfoLaboral($per_id, $empresa, $cargo, $telefono_emp, $pais_emp, $prov_emp, $ciu_emp, $parroquia, $direccion_emp, $añoingreso_emp, $correo_emp, $cat_ocupacional);
                    } else {
                        $resp_infolaboral = $mod_infolaboral->modificarInfoLaboral($per_id, $empresa, $cargo, $telefono_emp, $pais_emp, $prov_emp, $ciu_emp, $parroquia, $direccion_emp, $añoingreso_emp, $correo_emp, $cat_ocupacional);
                    }
                    // info Idiomas
                    //Idioma Ingles
                    $mod_idiomas = new EstudianteIdiomas();
                    $idioma = $idioma1;
                    if($idioma == 1 && $idioma1 > 0){
                        $resp_existe_idioma = $mod_idiomas->consultarInfoIdiomasEst($per_id, 1);
                        if ($resp_existe_idioma['existe_idioma'] == 0) {
                            $info_idioma = $mod_idiomas->insertarInfoIdiomaEst($per_id, $idioma1, $nivel1, $noidioma);
                        } else {
                            $info_idioma = $mod_idiomas->modificarInfoIdiomaEst($per_id, $idioma1, $nivel1, $noidioma);
                        }
                    }
                    $idiomas = $idioma2;
                    if($idiomas == 2 && $idioma2 > 0){
                        $resp_existe_idioma = $mod_idiomas->consultarInfoIdiomasEst($per_id, 2);
                        if ($resp_existe_idioma['existe_idioma'] == 0) {
                            $info_idioma = $mod_idiomas->insertarInfoIdiomaEst($per_id, $idioma2, $nivel2, $noidioma);
                        } else {
                            $info_idioma = $mod_idiomas->modificarInfoIdiomaEst($per_id, $idioma2, $nivel2, $noidioma);
                        }
                    }
                    if($idiomas == 3 && $idioma2 > 0){
                        $resp_existe_idioma = $mod_idiomas->consultarInfoIdiomasEst($per_id, 3);
                        if ($resp_existe_idioma['existe_idioma'] == 0) {
                            $info_idioma = $mod_idiomas->insertarInfoIdiomaEst($per_id, $idioma2, $otronivel, $otroidioma);
                        } else {
                            $info_idioma = $mod_idiomas->modificarInfoIdiomaEst($per_id, $idioma2, $otronivel, $otroidioma);
                        }
                    }

                    // info discapacidad
                    $mod_infodiscapacidad = new InfoDiscapacidadEst();
                    $resp_existe_infodisc = $mod_infodiscapacidad->consultarInfoDiscapacidadest($per_id);
                    if ($resp_existe_infodisc['existe_infodiscapacidad'] == 0 && $discapacidad == 1) {
                        $info_discapacidad = $mod_infodiscapacidad->insertarInfoDiscapacidad($per_id, $tipo_discap, $porcentaje_discap);
                    } else {
                        if ($discapacidad == 1) {
                            $info_discapacidad = $mod_infodiscapacidad->modificarInfoDiscapacidad($per_id, $tipo_discap, $porcentaje_discap);
                        }
                    }

                    // info Docencia
                    $mod_infodocencia = new InfoDocenciaEstudiante();
                    $resp_docencia = $mod_infodocencia->consultarInfoDocenciaEstudiante($per_id);
                    if ($resp_docencia['existe_infodocente'] == 0 && $docencias == 1) {
                        $info_docencia = $mod_infodocencia->insertarInfoDocenciaEst($per_id, $año_docencia, $area_docencia);
                    } else {
                        if ($docencias == 1) {
                            $info_docencia = $mod_infodocencia->modificarInfoDocenciaEst($per_id, $año_docencia, $area_docencia);
                        }
                    }

                    // info Investigacion
                    $mod_infoinvestigacion = new InfoEstudianteInvestigacion();
                    $resp_investigacion = $mod_infoinvestigacion->consultarInfoEstudianteInvestigacion($per_id);
                    if ($resp_existe_infodisc['existe_infodiscapacidad'] == 0 && $investiga == 1) {
                        $info_investigacion = $mod_infoinvestigacion->insertarInfoEstInvestigacion($per_id, $articulos, $area_investigacion);
                    } else {
                        if ($investiga == 1) {
                            $info_investigacion = $mod_infoinvestigacion->modificarInfoEstInvestigacion($per_id, $articulos, $area_investigacion);
                        }
                    }
                    //if($exito){
                        //Envio de correo a admisones
                        $mod_unidad = new UnidadAcademica();
                        $unidad_nombre = $mod_unidad->consultarNombreunidad($unidad);
                        $nombre_completo = $primer_nombre .' '.$segundo_nombre. ' '. $primer_apellido .' '.$segundo_apellido;
                        Utilities::putMessageLogFile('cc xXx.. ' .$nombre_completo );
                        Utilities::putMessageLogFile('ttt xXx.. ' .$unidad_nombre["nombre_unidad"] );
                        Utilities::putMessageLogFile('sss xXx.. ' .$per_dni );
                        $tituloMensaje = Yii::t("interesado", "UTEG - Inscripción Posgrado");
                        $asunto = Yii::t("interesado", "UTEG - Inscripción Posgrado");
                        $bodyadmision = Utilities::getMailMessage("Requestregistration", array("[[nombres]]" => $nombre_completo, "[[dni]]" => $per_dni, "[[unidad]]" => $unidad_nombre["nombre_unidad"]), Yii::$app->language);
                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["admisionespri"] => "Jefe"], $asunto, $bodyadmision);
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "Tu informacion se ha guardado"),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    /*}else{
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "Tu información no se ha guardado."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);
                    }*/
              } else{

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

    public function actionTerminoposgrado() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/terminospos.php';
        return $this->render('terminoposgrado', [
        ]);
    }
}
