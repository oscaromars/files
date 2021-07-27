<?php

namespace app\controllers;

use Yii;
use app\models\Utilities;
use yii\base\Exception;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\PromocionPrograma;
use app\modules\academico\models\EstudioAcademico;
use app\models\Pais;
use app\models\Provincia;
use app\models\Idioma;
use app\models\NivelIdioma;
use app\models\Canton;
use app\models\TipoDiscapacidad;
use app\models\InscripcionPosgrado;
use app\modules\admision\models\MetodoIngreso;
use app\modules\admision\models\ConvenioEmpresa;
use app\models\TipoInstitucionAca;
use app\models\NivelInstruccion;
use app\models\Persona;
use app\models\TipoParentesco;
use app\models\PersonaContacto;
use yii\helpers\ArrayHelper;
use app\models\EstadoCivil;

class InscripcionposgradoController extends \yii\web\Controller {

    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }
    public function actionIndex() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $per_id = Yii::$app->session->get("PB_perid");
        $mod_persona = Persona::findIdentity($per_id);

        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $mod_programa = new EstudioAcademico();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
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

        $arr_nacionalidad = Pais::find()->select("pai_id AS id, pai_nacionalidad AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_provincia = Provincia::provinciaXPais($arr_nacionalidad[0]["id"]);
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
        
        return $this->render('index', [
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
            'arr_programa' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_programa), 'id', 'name'),
            'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_modalidad), 'id', 'name'),
            "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
            "tipos_dni2" => array("CED" => Yii::t("formulario", "DNI Document1"), "PASS" => Yii::t("formulario", "Passport1")),
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
            "arr_nivelidioma" => ArrayHelper::map($arr_nivelidioma, "id", "value"),
            "arr_tip_discap" => ArrayHelper::map($arr_tip_discap, "id", "value"),
            "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
            "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
            "arr_convenio_empresa" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Ninguna")]], $arr_convempresa), "id", "name"),
            "resp_datos" => $resp_datos,
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
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/" . $data["name_file"] . "_per_" . $per_id . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
            }

            
            $con = \Yii::$app->db;
            $transaction = $con->beginTransaction();
            $con1 = \Yii::$app->db_captacion;
            $transaction1 = $con1->beginTransaction();
            $timeSt = time();
            try {

                $unidad = $data['unidad'];
                $programa = $data['programa'];
                $modalidad = $data['modalidad'];
                $año = $data['año'];
                $tipo_financiamiento = $data['tipo_financiamiento'];
                $ming_id = $data['ming_id'];

                $per_id = Yii::$app->session->get("PB_perid");
                $per_dni = $data['cedula'];
                $inscriposgrado_id = $data["ipos_id"];
                if (isset($data["ipos_ruta_doc_foto"]) && $data["ipos_ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_foto_per_" . $per_id . "." . $typeFile;
                    $foto_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $foto_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_dni"]) && $data["ipos_ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_dni_per_" . $per_id . "." . $typeFile;
                    $dni_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $dni_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_certvota"]) && $data["ipos_ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_certvota_per_" . $per_id . "." . $typeFile;
                    $certvota_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $certvota_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_titulo"]) && $data["ipos_ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_titulo_per_" . $per_id . "." . $typeFile;
                    $titulo_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $titulo_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_comprobante"]) && $data["ipos_ruta_doc_comprobante"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_comprobante"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $comprobantepago_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_comprobante_per_" . $per_id . "." . $typeFile;
                    $comprobantepago_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $comprobantepago_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_comprobante"] = $comprobantepago_archivo;
                    if ($comprobantepago_archivo === false)
                        throw new Exception('Error doc Comprobante de pago de matrícula no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_record1"]) && $data["ipos_ruta_doc_record1"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_record1"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $record1_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_record_per_" . $per_id . "." . $typeFile;
                    $record1_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $record1_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_record1"] = $record1_archivo;
                    if ($record1_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                } 
                if (isset($data["ipos_ruta_doc_senescyt"]) && $data["ipos_ruta_doc_senescyt"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_senescyt"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $senescyt_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_senescyt_per_" . $per_id . "." . $typeFile;
                    $senescyt_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $senescyt_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_senescyt"] = $senescyt_archivo;
                    if ($senescyt_archivo === false)
                        throw new Exception('Error doc Senescyt no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_hojavida"]) && $data["ipos_ruta_doc_hojavida"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_hojavida"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $hojavida_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_hojavida_per_" . $per_id . "." . $typeFile;
                    $hojavida_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $hojavida_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_hojavida"] = $hojavida_archivo;
                    if ($hojavida_archivo === false)
                        throw new Exception('Error doc Hoja de Vida no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_cartarecomendacion"]) && $data["ipos_ruta_doc_cartarecomendacion"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_cartarecomendacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $carta_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_cartarecomendacion_per_" . $per_id . "." . $typeFile;
                    $carta_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $carta_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_cartarecomendacion"] = $carta_archivo;
                    if ($carta_archivo === false)
                        throw new Exception('Error doc Carta de Recomendación no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_certificadolaboral"]) && $data["ipos_ruta_doc_certificadolaboral"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_certificadolaboral"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certlaboral_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_certlaboral_per_" . $per_id . "." . $typeFile;
                    $certlaboral_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $certlaboral_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_certificadolaboral"] = $certlaboral_archivo;
                    if ($certlaboral_archivo === false)
                        throw new Exception('Error doc Certificado Laboral no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_certificadoingles"]) && $data["ipos_ruta_doc_certificadoingles"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_certificadoingles"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certingles_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_certingles_per_" . $per_id . "." . $typeFile;
                    $certingles_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $certingles_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_certificadoingles"] = $certingles_archivo;
                    if ($certingles_archivo === false)
                        throw new Exception('Error doc Certificado Ingles A2 no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_recordacademico"]) && $data["ipos_ruta_doc_recordacademico"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_recordacademico"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $recordacad_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_recordacad_per_" . $per_id . "." . $typeFile;
                    $recordacad_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $recordacad_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_recordacademico"] = $recordacad_archivo;
                    if ($recordacad_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_certnosancion"]) && $data["ipos_ruta_doc_certnosancion"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_certnosancion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certnosancion_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_certificado_per_" . $per_id . "." . $typeFile;
                    $certnosancion_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $certnosancion_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_certnosancion"] = $certnosancion_archivo;
                    if ($certnosancion_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                } 
                if (isset($data["ipos_ruta_doc_syllabus"]) && $data["ipos_ruta_doc_syllabus"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_syllabus"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $syllabus_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_syllabus_per_" . $per_id . "." . $typeFile;
                    $syllabus_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $syllabus_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_syllabus"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["ipos_ruta_doc_homologacion"]) && $data["ipos_ruta_doc_homologacion"] != "") {
                    $arrIm = explode(".", basename($data["ipos_ruta_doc_homologacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $homologacion_archivoOld = Yii::$app->params["documentFolder"] . "inscripcionposgrado/" . $per_id . "/doc_homologacion_per_" . $per_id . "." . $typeFile;
                    $homologacion_archivo = InscripcionPosgrado::addLabelTimeDocumentos($inscriposgrado_id, $homologacion_archivoOld, $timeSt);
                    $data["ipos_ruta_doc_homologacion"] = $homologacion_archivo;
                    if ($homologacion_archivo === false)
                        throw new Exception('Error doc Especie valorada por homologación no renombrado.');
                }

                //FORM 1 datos personal   
            $per_dni = $data['cedula'];         
            $primer_nombre = $data["primer_nombre"];
            $segundo_nombre = $data["segundo_nombre"];
            $primer_apellido = $data["primer_apellido"];
            $segundo_apellido = $data["segundo_apellido"];
            $can_id_nacimiento = $data["cuidad_nac"];
            $per_fecha_nacimiento = $data["fecha_nac"];
            $per_nacionalidad = $data["nacionalidad"]; 
            $eciv_id = $data["estado_civil"];
            $pai_id_domicilio = $data["pais"];
            $pro_id_domicilio = $data["provincia"];
            $can_id_domicilio = $data["canton"];

            //FORM 1 datos de Contacto
            $per_domicilio_ref = $data["dir_domicilio"];
            $per_celular = $data["celular"];
            $per_domicilio_telefono = $data["telefono"];
            $per_correo = $data["correo"];

            //FORM 1 datos en caso de emergencias
            $pcon_nombre = $data["cont_emergencia"];
            $tpar_id = $data["parentesco"];
            $pcon_celular = $data["tel_emergencia"];

            //Form2 Datos formacion profesional
            $titulo_ter = $data["titulo_tercer"];
            $universidad_tercer = $data["universidad_tercer"];
            $grado_tercer = $data["grado_tercer"];

            $titulo_cuarto = $data["titulo_cuarto"];
            $universidad_cuarto = $data["universidad_cuarto"];
            $grado_cuarto = $data["grado_cuarto"];

            //Form2 Datos laboral
            $empresa = $data["empresa"];
            $cargo = $data["cargo"];
            $telefono_emp = $data["telefono_emp"];
            $prov_emp = $data["prov_emp"];
            $ciu_emp = $data["ciu_emp"];
            $parroquia = $data["parroquia"];
            $direccion_emp = $data["direccion_emp"];
            $añoingreso_emp = $data["añoingreso_emp"];
            $correo_emp = $data["correo_emp"];
            $cat_ocupacional = $data["cat_ocupacional"];

            //Form2 Datos idiomas
            $idioma1 = $data["idioma1"];
            $nivel1 = $data["nivel1"];

            $idioma2 = $data["idioma2"];
            $nivel2 = $data["nivel2"];

            //Form2 Datos adicionales
            $tipo_discap = $data["tipo_discap"];
            $porcentaje_discap = $data["porcentaje_discap"];

            $año_docencia = $data["año_docencia"];
            $area_docencia = $data["area_docencia"];

            $articulos = $data["articulos"];
            $area_investigacion = $data["area_investigacion"];

            //Form2 Datos financiamiento
            $tipo_financiamiento = $data["tipo_financiamiento"];
            

            $insc_persona = new Persona();
                \app\models\Utilities::putMessageLogFile(' personauuuuuuuuuuuuuuuuuuuuuu:  '.$per_dni);
                $resp_persona = $insc_persona->ConsultaRegistroExiste( 0,$per_dni, $per_dni);
                if ($resp_persona['existen'] == 0) {
                    //Nuevo Registro
                    \app\models\Utilities::putMessageLogFile(' persona:  '.$resp_inscripcion);
                    //if($resp_inscripcion == 0){ 
                        \app\models\Utilities::putMessageLogFile('datos a enviar:  '.$data);
                        \app\models\Utilities::putMessageLogFile('resultado de la inseercion:  '.$resul);
                    $regPersona = $mod_persona->insertarPersonaInscripcionposgrado($per_dni, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $can_id_nacimiento, $per_fecha_nacimiento, $per_nacionalidad, $eciv_id, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_domicilio_ref, $per_celular, $per_domicilio_telefono, $per_correo);  
                } else{

                    $resul = array();
                    $error++;
                    $error_message .= Yii::t("formulario", "The person already exists");    

                    $message = array(
                        "wtmessage" => Yii::t("formulario",  $error_message), //$error_message
                        "title" => Yii::t('jslang', 'Bad Request'),
                    );
                    $resul["status"] = FALSE;
                    $resul["error"] = null;
                    $resul["message"] = $message;
                    $resul["data"] = null;
                    $resul["dataext"] = null;
                

                    //return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Error'), $error_message);
                    return Utilities::ajaxResponse('ERROR_EXIST', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);   
                    //$resul = $model->actualizarInscripcionposgrado($data);
                    if ($resp_persona['existen'] == 1) {
                    // actualizacion de Persona
                    $respPersona = $mod_persona->modificaPersonaInscripcioposgrado($per_dni, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $can_id_nacimiento, $per_fecha_nacimiento, $per_nacionalidad, $eciv_id, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_domicilio_ref, $per_celular, $per_domicilio_telefono, $per_correo);
                    }
                }
                
                // creación de contacto
                $modpersonacontacto = new PersonaContacto();
                //Si existe registro en persona contacto se modifican los datos.
                $resexistecontacto = $modpersonacontacto->consultaPersonaContacto($per_id);
                if ($resexistecontacto) {
                    if ($pcon_nombre != $pcon_apellido) {
                        $contacto = $pcon_nombre . " " . $pcon_apellido;
                    } else {
                        $contacto = $pcon_nombre;
                    }
                    $resp_modcontacto = $modpersonacontacto->modificarPersonacontacto($per_id, $tpar_id, $contacto, $pcon_telefono, $pcon_celular, $pcon_direccion);
                } else {
                    if ($sincontacto != 1) {
                        //Creación de persona de contacto.
                        $modpersonacontacto->crearPersonaContacto($per_id, $tpar_id, $pcon_nombre . " " . $pcon_apellido, $pcon_telefono, $pcon_celular, $pcon_direccion);
                    }
                }
                $model = new InscripcionPosgrado();
                \app\models\Utilities::putMessageLogFile('consultarrrrr personasssss:  '.$per_dni);
                $resp_inscripcion = $model->consultarDatosInscripcionposgrado($per_dni);
                \app\models\Utilities::putMessageLogFile(' personaxxxxxxxxxxx:  '.$resp_inscripcion['existe_inscripcionposgrado']);
                if ($resp_inscripcion['existe_inscripcionposgrado'] == 0){ 
                    $resul = $model->insertarDataInscripcionposgrado($unidad, $programa, $modalidad, $año, $per_dni, $tipo_financiamiento, $data);
                    if ($resul) {
                            $exito=1;
                        }
                    if($exito){ 
                    \app\models\Utilities::putMessageLogFile('resultado es ok');
                        //$_SESSION['persona_id'] =  $resul['per_id'];
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "The information have been saved"),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);                                        
                    }             
                    
                    //} 
                    else {
                        \app\models\Utilities::putMessageLogFile('resultado es NOok');
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "The information have not been saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);
                    }
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
}
