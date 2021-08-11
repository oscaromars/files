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
use app\models\EstudianteInstruccion;
use app\models\InformacionLaboral;
use app\models\InfoDiscapacidadEst;
use app\models\EstudianteIdiomas;
use app\models\InfoDocenciaEstudiante;
use app\models\InfoEstudianteInvestigacion;
use app\modules\admision\models\MetodoIngreso;
use app\modules\admision\models\ConvenioEmpresa;
use app\models\TipoInstitucionAca;
use app\models\NivelInstruccion;
use app\models\Persona;
use app\models\Empresa;
use app\models\EmpresaPersona;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use app\models\Grupo;
use app\models\Rol;
use app\models\GrupRol;
use app\models\TipoParentesco;
use app\models\PersonaContacto;
use yii\helpers\ArrayHelper;
use app\models\EstadoCivil;
use yii\data\ArrayDataProvider;

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

        $arr_ciudad_nac= Canton::find()->select("can_id AS id, can_nombre AS value")->where(["can_estado_logico" => "1", "can_estado" => "1"])->asArray()->all();
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
                $mod_persona = new Persona();
                $resp_persona = $mod_persona->consultarUltimoPer_id();
                $persona = $resp_persona["ultimo"];
                $per_id = intval( $persona );
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

                $mod_persona = new Persona();
                $resp_persona = $mod_persona->consultarUltimoPer_id();
                $persona = $resp_persona["ultimo"];
                $per_id = intval( $persona );

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
            $pai_id_domicilio = $data["nacionalidad"];
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

            $noidioma = '';
            $otroidioma = $data["otroidioma"];
            $otronivel = $data["otronivel"];

            //Form2 Datos adicionales
            $discapacidad = $data["discapacidad"];
            $tipo_discap = $data["tipo_discap"];
            $porcentaje_discap = $data["porcentaje_discap"];

            $docencias = $data["docencias"];
            $año_docencia = $data["año_docencia"];
            $area_docencia = $data["area_docencia"];

            $investiga = $data["investiga"];
            $articulos = $data["articulos"];
            $area_investigacion = $data["area_investigacion"];

            //Form2 Datos financiamiento
            $tipo_financiamiento = $data["tipo_financiamiento"];

            //archivos cargados
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


                    // creación de contacto
                    $modpersonacontacto = new PersonaContacto();
                    $mod_persona = new Persona();
                    $resp_persona = $mod_persona->consultPer_id();
                    $persona = $resp_persona["ultimo"];
                    \app\models\Utilities::putMessageLogFile('traer el per_id:  '.$per_id);
                    $per_id = intval( $persona );

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
                    if ($resexisteinfo['existe_instruccion'] == 0) {
                        //Creación de persona de contacto
                        $resp_infolaboral = $mod_infolaboral->insertarInfoLaboral($per_id, $empresa, $cargo, $telefono_emp, $prov_emp, $ciu_emp, $parroquia, $direccion_emp, $añoingreso_emp, $correo_emp, $cat_ocupacional);
                    } else {   
                        $resp_infolaboral = $mod_infolaboral->modificarInfoLaboral($per_id, $empresa, $cargo, $telefono_emp, $prov_emp, $ciu_emp, $parroquia, $direccion_emp, $añoingreso_emp, $correo_emp, $cat_ocupacional);
                    }

                    // info Idiomas 
                    //Idioma Ingles  
                    $mod_idiomas = new EstudianteIdiomas();  
                    $idioma = $idioma1;   
                    if($idioma == 1){
                        $resp_existe_idioma = $mod_idiomas->consultarInfoIdiomasEst($per_id, 1);
                        if ($resp_existe_idioma['existe_idioma'] == 0) {
                            $info_idioma = $mod_idiomas->insertarInfoIdiomaEst($per_id, $idioma1, $nivel1, $noidioma);
                        } else {
                            $info_idioma = $mod_idiomas->modificarInfoDiscapacidad($per_id, $idioma1, $nivel1, $noidioma);
                        }
                    }   
                    $idiomas = $idioma2; 
                    if($idiomas == 2){
                        $resp_existe_idioma = $mod_idiomas->consultarInfoIdiomasEst($per_id, 2);
                        if ($resp_existe_idioma['existe_idioma'] == 0) {
                            $info_idioma = $mod_idiomas->insertarInfoIdiomaEst($per_id, $idioma2, $nivel2, $noidioma);
                        } else {
                            $info_idioma = $mod_idiomas->modificarInfoDiscapacidad($per_id, $idioma2, $nivel2, $noidioma);
                        }
                    }   
                    if($idiomas == 3){
                        $resp_existe_idioma = $mod_idiomas->consultarInfoIdiomasEst($per_id, 3);
                        if ($resp_existe_idioma['existe_idioma'] == 0) {
                            $info_idioma = $mod_idiomas->insertarInfoIdiomaEst($per_id, $idioma2, $otronivel, $otroidioma);
                        } else {
                            $info_idioma = $mod_idiomas->modificarInfoDiscapacidad($per_id, $idioma2, $otronivel, $otroidioma);
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
                
                $mod_persona = new Persona();
                $model = new InscripcionPosgrado();
                $resp_persona = $mod_persona->consultPer_id();
                $persona = $resp_persona["ultimo"];
                $per_id = intval( $persona );
                
                \app\models\Utilities::putMessageLogFile('consultarrrrr personasssss:  '.$per_id);
                $resp_inscripcion = $model->consultarDatosInscripcionposgrado($per_id);
                \app\models\Utilities::putMessageLogFile(' personaxxxxxxxxxxx:  '.$resp_inscripcion['existe_inscripcionposgrado']);
                if ($resp_inscripcion['existe_inscripcionposgrado'] == 0){ 
                    $resul = $model->insertarDataInscripcionposgrado($per_id, $unidad, $programa, $modalidad, $año, $per_dni, $tipo_financiamiento, $ipos_ruta_doc_foto, $ipos_ruta_doc_dni, $ipos_ruta_doc_certvota, $ipos_ruta_doc_titulo, $ipos_ruta_doc_comprobantepago, $ipos_ruta_doc_recordacademico, $ipos_ruta_doc_senescyt, $ipos_ruta_doc_hojadevida, $ipos_ruta_doc_cartarecomendacion, $ipos_ruta_doc_certificadolaboral, $ipos_ruta_doc_certificadoingles, $ipos_ruta_doc_otrorecord, $ipos_ruta_doc_certificadonosancion, $ipos_ruta_doc_syllabus, $ipos_ruta_doc_homologacion, $ipos_mensaje1, $ipos_mensaje2);
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

    public function actionAspiranteposgrado() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $model_posgrado = new InscripcionPosgrado();
        $unidad_model = new UnidadAcademica();
        $programa_model = new EstudioAcademico();
        $moda_model = new Modalidad();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"]      = $data['search'];
            $arrSearch["año"]     = $data['año'];  
            $arrSearch["unidad"]      = $data['unidad'];
            $arrSearch["programa"]      = $data['programa'];
            $arrSearch["modalidad"]   = $data['modalidad'];
            $model = $model_posgrado->consultaRegistroAdmisionposgrado($arrSearch, 1);
            return $this->render('_aspiranteposgradogrid', [
                "model" => $model,
            ]);

        }else {
            $model = $model_posgrado->consultaRegistroAdmisionposgrado(null, 1);
        }

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
        }
        $arr_unidad = $unidad_model->consultarUnidadAcademicasEmpresa(1);
        $arr_programa = $programa_model->consultarCarreraxunidad(2);
        $arr_modalidad = $programa_model->consultarmodalidadxcarrera($arr_programa[0]["id"]);
        return $this->render('aspiranteposgrado_index', [
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
            'arr_programa' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_programa), 'id', 'name'),
            'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_modalidad), 'id', 'name'),
            "model" => $model,
        ]);
    }

    public function actionDownload($route, $type) {
        $grupo = new Grupo();
        if (Yii::$app->session->get('PB_isuser')) {
            $route = str_replace("../", "", $route);
            if (preg_match("/^" . $this->folder_cv . "\//", $route)) {
                $url_image = Yii::$app->basePath . "/uploads/" . $route;
                $arrIm = explode(".", $url_image);
                $typeImage = $arrIm[count($arrIm) - 1];
                if (file_exists($url_image)) {
                    if (strtolower($typeImage) == "pdf") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/pdf");
                        if ($type == "view") {
                            header('Content-Disposition: inline; filename="cv_' . time() . '.pdf";');
                        } else {
                            header('Content-Disposition: attachment; filename="cv_' . time() . '.pdf";');
                        }
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_image));
                        readfile($url_image);
                        //return file_get_contents($url_image);
                    }
                }
            }
        }
        exit();
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {

            $id = $data['id']; // per_id
            $per_cedula = $data['cedula'];

            $persona_model = Persona::findOne($id);
            $contacto_model = PersonaContacto::findOne($id);
            $usuario_model = Usuario::findOne(["per_id" => $id, "usu_estado" => '1', "usu_estado_logico" => '1']);
            $empresa_persona_model = EmpresaPersona::findOne(["per_id" => $id, "eper_estado" => '1', "eper_estado_logico" => '1']);

            /* Validacion de acceso a vistas por usuario */
            $user_ingresa = Yii::$app->session->get("PB_iduser");
            $user_usermane = Yii::$app->session->get("PB_username");
            $user_perId = Yii::$app->session->get("PB_perid");
            $grupo_model = new Grupo();
            $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
            if ($id != $user_perId) {
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['inscripciongrado/aspirantegrado']);
            }

            /**
             * Inf. Personal
             */
            $arr_ciudad_nac = Canton::findAll(["pro_id" => $persona_model->can_id_nacimiento, "can_estado" => 1, "can_estado_logico" => 1]);
            $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            $arr_pro = Provincia::findAll(["pai_id" => $persona_model->pai_id_domicilio, "pro_estado" => 1, "pro_estado_logico" => 1]);
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);

            $ViewFormTab1 = $this->renderPartial('ViewFormTab1', [
                'arr_ciudad_nac' => (empty(ArrayHelper::map($arr_ciudad_nac, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_ciudad_nac, "can_id", "can_nombre")),
                "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
                'persona_model' => $persona_model,
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. Profesional
             */
            
            $instruccion_model = EstudianteInstruccion::findOne(['per_id' => $persona_model->per_id]);
            $laboral_model = InformacionLaboral::findOne(['per_id' => $persona_model->per_id]);
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            $arr_pro = Provincia::findAll(["pai_id" => $laboral_model->ilab_prov_emp, "pro_estado" => 1, "pro_estado_logico" => 1]);
            $arr_can = Canton::findAll(["pro_id" => $laboral_model->ilab_ciu_emp, "can_estado" => 1, "can_estado_logico" => 1]);
            

            $ViewFormTab2 = $this->renderPartial('ViewFormTab2', [
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                "arr_categoria" => array("1" => Yii::t("formulario", "Pública"), "2" => Yii::t("formulario", "Privada")),
                'persona_model' => $persona_model,
                'instruccion_model' => $instruccion_model,
                'laboral_model' => $laboral_model,
            ]);

            /**
             * Inf. Idiomas
             */
            $idiomas = new EstudianteIdiomas();
            $idioma_model = EstudianteIdiomas::findOne(['per_id' => $persona_model->per_id]);
            $model = $idioma_model->getAllestudianteidiomasGrid($idioma_model->per_id);

            $ViewFormTab3 = $this->renderPartial('ViewFormTab3', [
                'idioma_model' => $idioma_model,
                'model' => $model,

            ]);
            /**
             * Inf. Adicional
             */

            $discapacidad_model = InfoDiscapacidadEst::findOne(['per_id' => $persona_model->per_id]);
            $docencia_model = InfoDocenciaEstudiante::findOne(['per_id' => $persona_model->per_id]);
            $investigaciones_model = InfoEstudianteInvestigacion::findOne(['per_id' => $persona_model->per_id]);
            $ipos_model = InscripcionPosgrado::findOne(['per_id' => $persona_model->per_id]);
            $arr_tip_discap = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();

            $ViewFormTab5 = $this->renderPartial('ViewFormTab5', [
                "arr_tip_discap" => ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "-- Select --")]], $arr_tip_discap), "id", "value"),
                'discapacidad_model' => $discapacidad_model,
                'docencia_model' => $docencia_model,
                'investigaciones_model' => $investigaciones_model,
                'ipos_model' => $ipos_model,

            ]);  
            /**
             * Documentación
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
            $mod_insposgrado = new InscripcionPosgrado();
            $documentos = $mod_insposgrado->ObtenerdocumentosInscripcionPosgrado($persona_model->per_id);
            $ViewFormTab6 = $this->renderPartial('ViewFormTab6', [
                "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                "arch1" => $documentos['ipos_ruta_doc_foto'],
                "arch2" => $documentos['ipos_ruta_doc_dni'],
                "arch3" => $documentos['ipos_ruta_doc_certvota'],
                "arch4" => $documentos['ipos_ruta_doc_titulo'],
                "arch5" => $documentos['ipos_ruta_doc_comprobantepago'],
                "arch6" => $documentos['ipos_ruta_doc_recordacademico'],
                "arch7" => $documentos['ipos_ruta_doc_senescyt'],
                "arch8" => $documentos['ipos_ruta_doc_hojadevida'],
                "arch9" => $documentos['ipos_ruta_doc_cartarecomendacion'],
                "arch10" => $documentos['ipos_ruta_doc_certificadolaboral'],
                "arch11" => $documentos['ipos_ruta_doc_certificadoingles'],
                "arch12" => $documentos['ipos_ruta_doc_otrorecord'],
                "arch13" => $documentos['ipos_ruta_doc_certificadonosancion'],
                "arch14" => $documentos['ipos_ruta_doc_syllabus'],
                "arch15" => $documentos['ipos_ruta_doc_homologacion'],
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,
                'documentos' => $documentos,

            ]);          



            $items = [
                [
                    'label' => Yii::t('inscripciongrado', 'Info. Datos Personales'),
                    'content' => $ViewFormTab1,
                    'active' => true
                ],
                [
                    'label' => Yii::t('inscripciongrado', 'Info. Datos Profesionales'),
                    'content' => $ViewFormTab2,
                ],
                [
                    'label' => Yii::t('inscripciongrado', 'Info. Datos de Idiomas'),
                    'content' => $ViewFormTab3,
                ],
                [
                    'label' => Yii::t('inscripciongrado', 'Info. Datos Adicionales'),
                    'content' => $ViewFormTab5,
                ],
                [
                    'label' => Yii::t('inscripciongrado', 'Documentación'),
                    'content' => $ViewFormTab6,
                ],
            ];
            return $this->render('view', ['items' => $items, 'persona_model' => $persona_model, 'contacto_model' => $contacto_model]);
        }
        return $this->redirect(['inscripciongrado/aspirantegrado']);
    }

    public function actionEdit() {

        if (Yii::$app->request->isAjax) {
            
            $data = Yii::$app->request->post();
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            
            //$per_id = 54;
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                $mod_persona = new Persona();
                $resp_persona = $mod_persona->consultarUltimoPer_id();
                $persona = $resp_persona["ultimo"];
                $per_id = intval( $persona );
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
        }
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];

            if (Yii::$app->request->isAjax) {
                if (isset($data["pai_id"])) {
                    $model = new Provincia();
                    $arr_pro = $model->provinciabyPais($data["pai_id"]);

                    list($firstpro) = $arr_pro;

                    $arr_can = Canton::find()
                                    ->select(["can_id as id", "can_nombre as name"])
                                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                                        "pro_id" => $firstpro['id']])->asArray()->all();

                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', ['arr_pro' => $arr_pro, 'arr_can' => $arr_can]);
                } else if (isset($data["pro_id"])) {
                    $arr_can = Canton::find()
                                    ->select(["can_id as id", "can_nombre as name"])
                                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                                        "pro_id" => $data["pro_id"]])->asArray()->all();

                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $arr_can);
                }
            }

            $persona_model = Persona::findOne($id);
            Utilities::putMessageLogFile('id:' . $id);
            $usuario_model = Usuario::findOne(["per_id" => $id, "usu_estado" => '1', "usu_estado_logico" => '1']);
            $empresa_persona_model = EmpresaPersona::findOne(["per_id" => $id, "eper_estado" => '1', "eper_estado_logico" => '1']);
            $email = (isset($persona_model->per_correo) && $persona_model->per_correo != "") ? ($persona_model->per_correo) : ($usuario_model->usu_user);

            /* Validacion de acceso a vistas por usuario */
            $user_ingresa = Yii::$app->session->get("PB_iduser");
            $user_usermane = Yii::$app->session->get("PB_username");
            $user_perId = Yii::$app->session->get("PB_perid");
            $grupo_model = new Grupo();
            $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
            if ($id != $user_perId) {
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['profesor/index']);
            }

            /**
             * Inf. Personal
             */
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);
            $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            $arr_pro = Provincia::findAll(["pai_id" => $persona_model->pai_id_domicilio, "pro_estado" => 1, "pro_estado_logico" => 1]);
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);

            $EditFormTab1 = $this->renderPartial('EditFormTab1', [
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
                'persona_model' => $persona_model,
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
            ]);

            /**
             * Inf. Profesional
             */
            
            $instruccion_model = EstudianteInstruccion::findOne(['per_id' => $persona_model->per_id]);
            $laboral_model = InformacionLaboral::findOne(['per_id' => $persona_model->per_id]);
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            $arr_pro = Provincia::findAll(["pai_id" => $laboral_model->ilab_prov_emp, "pro_estado" => 1, "pro_estado_logico" => 1]);
            $arr_can = Canton::findAll(["pro_id" => $laboral_model->ilab_ciu_emp, "can_estado" => 1, "can_estado_logico" => 1]);
            

            $EditFormTab2 = $this->renderPartial('EditFormTab2', [
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                "arr_categoria" => array("1" => Yii::t("formulario", "Pública"), "2" => Yii::t("formulario", "Privada")),
                'persona_model' => $persona_model,
                'instruccion_model' => $instruccion_model,
                'laboral_model' => $laboral_model,
            ]);

            /**
             * Inf. Idiomas
             */

            $arr_idioma= Idioma::find()->select("idi_id AS id, idi_nombre AS value")->where(["idi_estado_logico" => "1", "idi_estado" => "1"])->asArray()->all();
            $arr_nivelidioma= NivelIdioma::find()->select("nidi_id AS id, nidi_descripcion AS value")->where(["nidi_estado_logico" => "1", "nidi_estado" => "1"])->asArray()->all();
            $idiomas = new EstudianteIdiomas();
            $idioma_model = EstudianteIdiomas::findOne(['per_id' => $persona_model->per_id]);
            $model = $idioma_model->getAllestudianteidiomasGrid($idioma_model->per_id);

            $EditFormTab3 = $this->renderPartial('EditFormTab3', [
                "arr_idioma" => ArrayHelper::map($arr_idioma, "id", "value"),
                "arr_nivelidioma" => ArrayHelper::map($arr_nivelidioma, "id", "value"),
                'idioma_model' => $idioma_model,
                'model' => $model,

            ]);
            /**
             * Inf. Adicional
             */

            $discapacidad_model = InfoDiscapacidadEst::findOne(['per_id' => $persona_model->per_id]);
            $arr_tip_discap = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();
            $docencia_model = InfoDocenciaEstudiante::findOne(['per_id' => $persona_model->per_id]);
            $investigaciones_model = InfoEstudianteInvestigacion::findOne(['per_id' => $persona_model->per_id]);
            $ipos_model = InscripcionPosgrado::findOne(['per_id' => $persona_model->per_id]);

            $EditFormTab5 = $this->renderPartial('EditFormTab5', [
                'discapacidad_model' => $discapacidad_model,
                "arr_tip_discap" => ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "-- Select --")]], $arr_tip_discap), "id", "value"), //ArrayHelper::map($arr_tip_discap, "id", "value"),
                'docencia_model' => $docencia_model,
                'investigaciones_model' => $investigaciones_model,
                'ipos_model' => $ipos_model,

            ]);  
            /**
             * Documentación
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

            $mod_insposgrado = new InscripcionPosgrado();
            $documentos = $mod_insposgrado->ObtenerdocumentosInscripcionPosgrado(['per_id' => $persona_model->per_id]);

            $EditFormTab6 = $this->renderPartial('EditFormTab6', [
                "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                "arch1" => $documentos['ipos_ruta_doc_foto'],
                "arch2" => $documentos['ipos_ruta_doc_dni'],
                "arch3" => $documentos['ipos_ruta_doc_certvota'],
                "arch4" => $documentos['ipos_ruta_doc_titulo'],
                "arch5" => $documentos['ipos_ruta_doc_comprobantepago'],
                "arch6" => $documentos['ipos_ruta_doc_recordacademico'],
                "arch7" => $documentos['ipos_ruta_doc_senescyt'],
                "arch8" => $documentos['ipos_ruta_doc_hojadevida'],
                "arch9" => $documentos['ipos_ruta_doc_cartarecomendacion'],
                "arch10" => $documentos['ipos_ruta_doc_certificadolaboral'],
                "arch11" => $documentos['ipos_ruta_doc_certificadoingles'],
                "arch12" => $documentos['ipos_ruta_doc_otrorecord'],
                "arch13" => $documentos['ipos_ruta_doc_certificadonosancion'],
                "arch14" => $documentos['ipos_ruta_doc_syllabus'],
                "arch15" => $documentos['ipos_ruta_doc_homologacion'],
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,
                'documentos' => $documentos,

            ]);


            $items = [
                [
                    'label' => Yii::t('formulario', 'Info. Datos Personales'),
                    'content' => $EditFormTab1,
                    'active' => true
                ],
                [
                    'label' => Yii::t('formulario', 'Info. Datos Profesionales'),
                    'content' => $EditFormTab2,
                ],
                [
                    'label' => Yii::t('formulario', 'Info. Datos Idiomas'),
                    'content' => $EditFormTab3,
                ],
                /*[
                    'label' => Yii::t('formulario', 'Info. Datos Discapacidad'),
                    'content' => $EditFormTab4,
                ],*/
                [
                    'label' => Yii::t('formulario', 'Info. Datos Adicionales'),
                    'content' => $EditFormTab5,
                ],
                [
                    'label' => Yii::t('formulario', 'Documentación'),
                    'content' => $EditFormTab6,
                ],
            ];

            return $this->render('edit', [
                        'items' => $items, 
                        'persona_model' => $persona_model, 
                        'contacto_model' => $contacto_model,
            ]);
        }
        return $this->redirect(['inscripciongrado/aspirantegrado']);
    }
    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $per_id = $data["per_id"];

                /* Validacion de acceso a vistas por usuario */
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $user_usermane = Yii::$app->session->get("PB_username");
                $user_perId = Yii::$app->session->get("PB_perid");
                $grupo_model = new Grupo();
                $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
                if ($per_id != $user_perId) {
                    // if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos)  && !in_array(['id' => '15'], $arr_grupos))

                    if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                        return $this->redirect(['profesor/index']);
                }

                /**
                 * Inf. Basica
                 */

                $cedula = $data["cedula"];
                $pasaporte = $data["pasaporte"];
                $pri_nombre = $data["pri_nombre"];
                $seg_nombre = $data["seg_nombre"];
                $pri_apellido = $data["pri_apellido"];
                $seg_apellido = $data["seg_apellido"];
                $can_id_nacimiento = $data["can_id"];
                $fecha_nacimiento = $data["fecha_nacimiento"];
                $correo = strtolower($data["correo"]);
                $nacionalidad = $data["nacionalidad"];
                $celular = $data["celular"];
                $phone = $data["phone"];
                $dedicacion = $data["dedicacion"];
                $pro_num_contrato = $data["pro_num_contrato"];
                $foto = $data['foto'];

                /**
                 * Inf. Domicilio
                 */
                $pai_id_domicilio = $data["pai_id"];
                $pro_id_domicilio = $data["pro_id"];
                $can_id_domicilio = $data["can_id"];
                $sector = ucwords($data["sector"]);
                $calle_pri = ucwords($data["calle_pri"]);
                $calle_sec = ucwords($data["calle_sec"]);
                $numeracion = ucwords($data["numeracion"]);
                $referencia = ucwords($data["referencia"]);

                /**
                 * Inf. Cuenta
                 */
                $usuario = strtolower($data["usuario"]);
                $clave = $data["clave"];
                $gru_id = $data["gru_id"];
                $rol_id = $data["rol_id"];
                $emp_id = $data["emp_id"];

                $persona_model = Persona::findOne($per_id);
                $persona_model->per_pri_nombre = $pri_nombre;
                $persona_model->per_seg_nombre = $seg_nombre;
                $persona_model->per_pri_apellido = $pri_apellido;
                $persona_model->per_seg_apellido = $seg_apellido;
                $persona_model->per_cedula = $cedula;
                if ($ruc != "") {
                    $persona_model->per_ruc = $ruc;
                }
                if ($pasaporte != "") {
                    $persona_model->per_pasaporte = $pasaporte;
                }
                $persona_model->per_correo = $correo;
                $persona_model->per_nacionalidad = $nacionalidad;
                $persona_model->per_celular = $celular;
                $persona_model->per_domicilio_telefono = $phone;
                $persona_model->per_fecha_nacimiento = $fecha_nacimiento;
                $persona_model->pai_id_domicilio = $pai_id_domicilio;
                $persona_model->pro_id_domicilio = $pro_id_domicilio;
                $persona_model->can_id_domicilio = $can_id_domicilio;
                $persona_model->per_domicilio_sector = $sector;
                $persona_model->per_domicilio_cpri = $calle_pri;
                $persona_model->per_domicilio_csec = $calle_sec;
                $persona_model->per_domicilio_num = $numeracion;
                $persona_model->per_domicilio_ref = $referencia;
                $arr_file = explode($foto, '.jpg');
                if (isset($arr_file[0]) && $arr_file[0] != "") {
                    $oldFile = $this->folder_cv . '/' . $foto;
                    $persona_model->per_foto = $this->folder_cv . '/' . $per_id . "_" . $foto;
                    $urlBase = Yii::$app->basePath . Yii::$app->params["documentFolder"];
                    rename($urlBase . $oldFile, $urlBase . $persona_model->per_foto);
                }

                /**
                 * Inf. Session Storages
                 */
                $arr_instuccion = (isset($data["grid_instruccion_list"]) && $data["grid_instruccion_list"] != "") ? $data["grid_instruccion_list"] : NULL;
                $arr_docencia = (isset($data["grid_docencia_list"]) && $data["grid_docencia_list"] != "") ? $data["grid_docencia_list"] : NULL;
                $arr_experiencia = (isset($data["grid_experiencia_list"]) && $data["grid_experiencia_list"] != "") ? $data["grid_experiencia_list"] : NULL;
                $arr_idioma = (isset($data["grid_idioma_list"]) && $data["grid_idioma_list"] != "") ? $data["grid_idioma_list"] : NULL;
                $arr_investigacion = (isset($data["grid_investigacion_list"]) && $data["grid_investigacion_list"] != "") ? $data["grid_investigacion_list"] : NULL;
                $arr_evento = (isset($data["grid_evento_list"]) && $data["grid_evento_list"] != "") ? $data["grid_evento_list"] : NULL;
                $arr_conferencia = (isset($data["grid_conferencia_list"]) && $data["grid_conferencia_list"] != "") ? $data["grid_conferencia_list"] : NULL;
                $arr_publicacion = (isset($data["grid_publicacion_list"]) && $data["grid_publicacion_list"] != "") ? $data["grid_publicacion_list"] : NULL;
                $arr_coordinacion = (isset($data["grid_coordinacion_list"]) && $data["grid_coordinacion_list"] != "") ? $data["grid_coordinacion_list"] : NULL;
                $arr_evaluacion = (isset($data["grid_evaluacion_list"]) && $data["grid_evaluacion_list"] != "") ? $data["grid_evaluacion_list"] : NULL;
                $arr_referencia = (isset($data["grid_referencia_list"]) && $data["grid_referencia_list"] != "") ? $data["grid_referencia_list"] : NULL;

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );

                if ($persona_model->save()) {
                    $usuario_model = Usuario::findOne(["per_id" => $per_id]);

                    /** Se agregan Informacion de Expediente * */
                    $profesor_model = Profesor::findOne(["per_id" => $per_id]);
                    $profesor_model->ddoc_id = $dedicacion;
                    $profesor_model->pro_num_contrato = $pro_num_contrato;
                    $profesor_model->save();
                    //ProfesorInstruccion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_instuccion)) {
                        foreach ($arr_instuccion as $key0 => $value0) {
                            if ($value0[6] == "N") {
                                $instruccion_model = new ProfesorInstruccion();
                                $instruccion_model->nins_id = $value0[1];
                                $instruccion_model->pins_institucion = ucwords($value0[2]);
                                $instruccion_model->pins_especializacion = ucwords($value0[3]);
                                $instruccion_model->pins_titulo = ucwords($value0[4]);
                                $instruccion_model->pins_senescyt = strtoupper($value0[5]);
                                $instruccion_model->pro_id = $profesor_model->pro_id;
                                $instruccion_model->pins_estado = '1';
                                $instruccion_model->pins_estado_logico = '1';
                                $instruccion_model->pins_usuario_ingreso = $user_ingresa;
                                $instruccion_model->save();
                            }
                        }
                    }
                    //ProfesorExpDoc::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_docencia)) {
                        foreach ($arr_docencia as $key1 => $value1) {
                            if ($value1[6] == "N") {
                                $docencia_model = new ProfesorExpDoc();
                                $docencia_model->ins_id = $value1[1];
                                $docencia_model->pedo_fecha_inicio = $value1[2];
                                $docencia_model->pedo_fecha_fin = $value1[3];
                                $docencia_model->pedo_denominacion = ucwords($value1[4]);
                                $docencia_model->pedo_asignaturas = ucwords($value1[5]);
                                $docencia_model->pro_id = $profesor_model->pro_id;
                                $docencia_model->pedo_estado = '1';
                                $docencia_model->pedo_estado_logico = '1';
                                $docencia_model->pedo_usuario_ingreso = $user_ingresa;
                                $docencia_model->save();
                            }
                        }
                    }
                    //ProfesorExpProf::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_experiencia)) {
                        foreach ($arr_experiencia as $key2 => $value2) {
                            if ($value2[6] == "N") {
                                $experiencia_model = new ProfesorExpProf();
                                $experiencia_model->pepr_organizacion = ucwords($value2[1]);
                                $experiencia_model->pepr_fecha_inicio = $value2[2];
                                $experiencia_model->pepr_fecha_fin = $value2[3];
                                $experiencia_model->pepr_denominacion = ucwords($value2[4]);
                                $experiencia_model->pepr_funciones = ucwords($value2[5]);
                                $experiencia_model->pro_id = $profesor_model->pro_id;
                                $experiencia_model->pepr_estado = '1';
                                $experiencia_model->pepr_estado_logico = '1';
                                $experiencia_model->pepr_usuario_ingreso = $user_ingresa;
                                $experiencia_model->save();
                            }
                        }
                    }
                    //ProfesorIdiomas::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_idioma)) {
                        foreach ($arr_idioma as $key3 => $value3) {
                            if ($value3[6] == "N") {
                                $idiomas_model = new ProfesorIdiomas();
                                $idiomas_model->idi_id = $value3[1];
                                $idiomas_model->pidi_nivel_escrito = ucfirst($value3[2]);
                                $idiomas_model->pidi_nivel_oral = ucfirst($value3[3]);
                                $idiomas_model->pidi_certificado = ucfirst($value3[4]);
                                $idiomas_model->pidi_institucion = ucwords($value3[5]);
                                $idiomas_model->pro_id = $profesor_model->pro_id;
                                $idiomas_model->pidi_estado = '1';
                                $idiomas_model->pidi_estado_logico = '1';
                                $idiomas_model->pidi_usuario_ingreso = $user_ingresa;
                                $idiomas_model->save();
                            }
                        }
                    }
                    //ProfesorInvestigacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_investigacion)) {
                        foreach ($arr_investigacion as $key4 => $value4) {
                            if ($value4[7] == "N") {
                                $investigacion_model = new ProfesorInvestigacion();
                                $investigacion_model->pinv_proyecto = ucwords($value4[1]);
                                $investigacion_model->pinv_ambito = ucwords($value4[2]);
                                $investigacion_model->pinv_responsabilidad = ucwords($value4[3]);
                                $investigacion_model->pinv_entidad = ucwords($value4[4]);
                                $investigacion_model->pinv_anio = strtolower($value4[5]);
                                $investigacion_model->pinv_duracion = strtolower($value4[6]);
                                $investigacion_model->pro_id = $profesor_model->pro_id;
                                $investigacion_model->pinv_estado = '1';
                                $investigacion_model->pinv_estado_logico = '1';
                                $investigacion_model->pinv_usuario_ingreso = $user_ingresa;
                                $investigacion_model->save();
                            }
                        }
                    }
                    //ProfesorCapacitacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_evento)) {
                        foreach ($arr_evento as $key5 => $value5) {
                            if ($value5[6] == "N") {
                                $capacitacion_model = new ProfesorCapacitacion();
                                $capacitacion_model->pcap_tipo = strtolower($value5[4]);
                                $capacitacion_model->pcap_evento = ucwords($value5[1]);
                                $capacitacion_model->pcap_institucion = ucwords($value5[2]);
                                $capacitacion_model->pcap_anio = strtolower($value5[3]);
                                $capacitacion_model->pcap_duracion = strtolower($value5[5]);
                                $capacitacion_model->pro_id = $profesor_model->pro_id;
                                $capacitacion_model->pcap_estado = '1';
                                $capacitacion_model->pcap_estado_logico = '1';
                                $capacitacion_model->pcap_usuario_ingreso = $user_ingresa;
                                $capacitacion_model->save();
                            }
                        }
                    }
                    //ProfesorConferencia::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_conferencia)) {
                        foreach ($arr_conferencia as $key6 => $value6) {
                            if ($value6[5] == "N") {
                                $capacitacion_model = new ProfesorConferencia();
                                $capacitacion_model->pcon_evento = ucwords($value6[1]);
                                $capacitacion_model->pcon_institucion = ucwords($value6[2]);
                                $capacitacion_model->pcon_anio = strtolower($value6[3]);
                                $capacitacion_model->pcon_ponencia = ucwords($value6[4]);
                                $capacitacion_model->pro_id = $profesor_model->pro_id;
                                $capacitacion_model->pcon_estado = '1';
                                $capacitacion_model->pcon_estado_logico = '1';
                                $capacitacion_model->pcon_usuario_ingreso = $user_ingresa;
                                $capacitacion_model->save();
                            }
                        }
                    }
                    //ProfesorCoordinacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_coordinacion)) {
                        foreach ($arr_coordinacion as $key7 => $value7) {
                            if ($value7[6] == "N") {
                                $coordinacion_model = new ProfesorCoordinacion();
                                $coordinacion_model->pcoo_alumno = ucwords($value7[1]);
                                $coordinacion_model->pcoo_programa = ucwords($value7[2]);
                                $coordinacion_model->pcoo_academico = ucwords($value7[3]);
                                $coordinacion_model->ins_id = ($value7[4]);
                                $coordinacion_model->pcoo_anio = strtolower($value7[5]);
                                $coordinacion_model->pro_id = $profesor_model->pro_id;
                                $coordinacion_model->pcoo_estado = '1';
                                $coordinacion_model->pcoo_estado_logico = '1';
                                $coordinacion_model->pcoo_usuario_ingreso = $user_ingresa;
                                $coordinacion_model->save();
                            }
                        }
                    }
                    //ProfesorEvaluacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_evaluacion)) {
                        foreach ($arr_evaluacion as $key8 => $value8) {
                            if ($value8[4] == "N") {
                                $evaluacion_model = new ProfesorEvaluacion();
                                $evaluacion_model->peva_periodo = strtolower($value8[1]);
                                $evaluacion_model->peva_institucion = ucwords($value8[2]);
                                $evaluacion_model->peva_evaluacion = ucwords($value8[3]);
                                $evaluacion_model->pro_id = $profesor_model->pro_id;
                                $evaluacion_model->peva_estado = '1';
                                $evaluacion_model->peva_estado_logico = '1';
                                $evaluacion_model->peva_usuario_ingreso = $user_ingresa;
                                $evaluacion_model->save();
                            }
                        }
                    }
                    //ProfesorPublicacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_publicacion)) {
                        foreach ($arr_publicacion as $key9 => $value9) {
                            if ($value9[6] == "N") {
                                $publicacion_model = new ProfesorPublicacion();
                                $publicacion_model->tpub_id = $value9[1];
                                $publicacion_model->ppub_titulo = ucwords($value9[2]);
                                $publicacion_model->ppub_editorial = ucwords($value9[3]);
                                $publicacion_model->ppub_isbn = strtoupper($value9[4]);
                                $publicacion_model->ppub_autoria = ucwords($value9[5]);
                                $publicacion_model->pro_id = $profesor_model->pro_id;
                                $publicacion_model->ppub_estado = '1';
                                $publicacion_model->ppub_estado_logico = '1';
                                $publicacion_model->ppub_usuario_ingreso = $user_ingresa;
                                $publicacion_model->save();
                            }
                        }
                    }
                    //ProfesorReferencia::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_referencia)) {
                        foreach ($arr_referencia as $key10 => $value10) {
                            if ($value10[5] == "N") {
                                $referencia_model = new ProfesorReferencia();
                                $referencia_model->pref_contacto = ucwords($value10[1]);
                                $referencia_model->pref_relacion_cargo = ucwords($value10[2]);
                                $referencia_model->pref_organizacion = ucwords($value10[3]);
                                $referencia_model->pref_numero = strtolower($value10[4]);
                                $referencia_model->pro_id = $profesor_model->pro_id;
                                $referencia_model->pref_estado = '1';
                                $referencia_model->pref_estado_logico = '1';
                                $referencia_model->pref_usuario_ingreso = $user_ingresa;
                                $referencia_model->save();
                            }
                        }
                    }
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no creado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.' . $ex->getMessage()),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }
}
