<?php

namespace app\controllers;

use Yii;
use app\models\Utilities;
use yii\base\Exception;
use app\models\Etnia;
use app\models\Pais;
use app\models\Provincia;
//use app\models\Idiomas;
use app\models\Canton;
use app\models\TipoSangre;
use app\models\TipoDiscapacidad;
use app\models\TipoInstitucionAca;
use app\models\NivelInstruccion;
use app\models\Persona;
use app\models\Interesado;
use app\models\TipoParentesco;
use app\models\PersonaContacto;
use yii\helpers\ArrayHelper;
use app\models\EstadoCivil;

class FichasocioeconomicaController extends \yii\web\Controller {

    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }
    public function actionIndex() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $per_id = Yii::$app->session->get("PB_perid");
        $mod_persona = Persona::findIdentity($per_id);

        $mod_pais = new Pais(); 
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
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
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais en informacion personal                
                $area = $mod_pais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
        }
        /*if (base64_decode($_GET['ids']) != '') {// tomar el de parametro)
            $per_id = base64_decode($_GET['ids']);
        } else {
            $per_id = Yii::$app->session->get("PB_perid");
        }*/
  
        $per_genero = Persona::find()->select("per_id AS id, per_genero AS value")->where(["per_estado_logico" => "1", "per_estado" => "1"])->asArray()->all();
        $arr_nacionalidad = Pais::find()->select("pai_id AS id, pai_nacionalidad AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_pais = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_pais_reside = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $arr_pais_col = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_col = Provincia::provinciaXPais($arr_pais_col[0]["id"]);
        $arr_ciu_col = Canton::cantonXProvincia($arr_prov_col[0]["id"]);
        $arr_tip_discap = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();
        //$arr_idiomas = Idiomas::find()->select("idi_id AS id, idi_nombre AS value")->where(["idi_estado_logico" => "1", "idi_estado" => "1"])->asArray()->all();




        /*$tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();

        //Búsqueda de los datos de persona logueada 
        $modperinteresado = new Persona();
        
        $respPerinteresado = $modperinteresado->consultaPersonaId($per_id);
        $pais_id = 57; //Ecuador   
        //algo como linea 415 para que aparezca ls provincias y canton si tienen en la BASE
        $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value, pai_codigo_fono AS code")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_nac = Provincia::provinciaXPais($respPerinteresado['pai_id_domicilio']);
        $arr_ciu_nac = Canton::cantonXProvincia($arr_prov_nac[0]["id"]);

        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_dom = Provincia::provinciaXPais($respPerinteresado['pai_id_domicilio']);
        $arr_ciu_dom = Canton::cantonXProvincia($arr_prov_dom[0]["id"]);

        $arr_pais_med = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_med = Provincia::provinciaXPais($arr_pais_col[0]["id"]);
        $arr_ciu_med = Canton::cantonXProvincia($arr_prov_col[0]["id"]);
        $arr_pais_ter = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_ter = Provincia::provinciaXPais($pais_id);
        $arr_ciu_ter = Canton::cantonXProvincia($arr_prov_ter[0]["id"]);

        $arr_pais_cuat = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_cuat = Provincia::provinciaXPais($pais_id);
        $arr_ciu_cuat = Canton::cantonXProvincia($arr_prov_cuat[0]["id"]);

        $arr_tip_discap = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();
        $arr_tip_discap_fam = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();

        //$arr_tip_instaca_med = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        //$arr_tip_instaca_ter = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        //$arr_tip_instaca_cuat = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        //$arr_ninstruc_mad = NivelInstruccion::find()->select("nins_id AS id, nins_nombre AS value")->where(["nins_estado_logico" => "1", "nins_estado" => "1"])->asArray()->all();
        //$arr_ninstruc_pad = NivelInstruccion::find()->select("nins_id AS id, nins_nombre AS value")->where(["nins_estado_logico" => "1", "nins_estado" => "1"])->asArray()->all();

        $arr_tipparent_dis = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $arr_tipparent_enf = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

        $area = $mod_pais->consultarCodigoArea($respPerinteresado['pai_id_domicilio']);*/
        return $this->render('index', [
                    "arr_nacionalidad" => ArrayHelper::map($arr_nacionalidad, "id", "value"),
                    "genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    "arr_pais" => ArrayHelper::map($arr_pais, "id", "value"),
                    "arr_pais_reside" => ArrayHelper::map($arr_pais_reside, "id", "value"),
                    "arr_etnia" => ArrayHelper::map($arr_etnia, "id", "value"),
                    "tipos_institucion" => array("Pública" => Yii::t("formulario", "Pública"), "Privada" => Yii::t("formulario", "Privada")),
                    "arr_pais_col" => ArrayHelper::map($arr_pais_col, "id", "value"),
                    "arr_prov_col" => ArrayHelper::map($arr_prov_col, "id", "value"),
                    "arr_ciu_col" => ArrayHelper::map($arr_ciu_col, "id", "value"),
                    "graduacion" => array("si" => Yii::t("formulario", "Si"), "no" => Yii::t("formulario", "No")),
                    "tipo_financiamiento" => array("1" => Yii::t("formulario", "Crédito Directo"), "2" => Yii::t("formulario", "Crédito Bancario"), "3" => Yii::t("formulario", "Pago al Contado")),
                    "arr_tip_discap" => ArrayHelper::map($arr_tip_discap, "id", "value"),
                    //"arr_idiomas" => ArrayHelper::map($arr_idiomas, "id", "value"),



                    /*"arr_civil" => array("1" => Yii::t("general", "Single"),
                        "2" => Yii::t("general", "Married"),
                        "3" => Yii::t("general", "Widower"),
                        "4" => Yii::t("general", "Divorced"),
                        "5" => Yii::t("general", "Civil Union")),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    
                    "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),*/
                    /*                     * */
                    /*"arr_pais_nac" => $arr_pais_nac, //ArrayHelper::map($arr_pais_nac, "id", "value"),
                    "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                    "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                    
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    
                    "arr_pais_med" => ArrayHelper::map($arr_pais_med, "id", "value"),
                    "arr_prov_med" => ArrayHelper::map($arr_prov_med, "id", "value"),
                    "arr_ciu_med" => ArrayHelper::map($arr_ciu_med, "id", "value"),
                    
                    "arr_pais_ter" => ArrayHelper::map($arr_pais_ter, "id", "value"),
                    "arr_prov_ter" => ArrayHelper::map($arr_prov_ter, "id", "value"),
                    "arr_ciu_ter" => ArrayHelper::map($arr_ciu_ter, "id", "value"),
                    
                    "arr_pais_cuat" => ArrayHelper::map($arr_pais_cuat, "id", "value"),
                    "arr_prov_cuat" => ArrayHelper::map($arr_prov_cuat, "id", "value"),
                    "arr_ciu_cuat" => ArrayHelper::map($arr_ciu_cuat, "id", "value"),
                    
                    "arr_tip_discap" => ArrayHelper::map($arr_tip_discap, "id", "value"),
                    "arr_tip_discap_fam" => ArrayHelper::map($arr_tip_discap_fam, "id", "value"),
                    
                    //"arr_tip_instaca_med" => ArrayHelper::map($arr_tip_instaca_med, "id", "value"),
                    //"arr_tip_instaca_ter" => ArrayHelper::map($arr_tip_instaca_ter, "id", "value"),
                    //"arr_tip_instaca_cuat" => ArrayHelper::map($arr_tip_instaca_cuat, "id", "value"),
                    
                    //"arr_ninstruc_mad" => ArrayHelper::map($arr_ninstruc_mad, "id", "value"),
                    //"arr_ninstruc_pad" => ArrayHelper::map($arr_ninstruc_pad, "id", "value"),
                    
                    "arr_tipparent_dis" => ArrayHelper::map($arr_tipparent_dis, "id", "value"),
                    "arr_tipparent_enf" => ArrayHelper::map($arr_tipparent_enf, "id", "value"),
                    "respPerinteresado" => $respPerinteresado,
                    "area" => $area,*/
        ]);
    }

    public function actionGuardarfichasocioeconomica() {
        $modperinteresado = new Persona();
        $user_ingresa = Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            /*$modInteresado = new Interesado();
            $data = Yii::$app->request->post();
            if ($_SESSION['persona_ingresa'] != '') {// tomar el de parametro)
                $per_id = $_SESSION['persona_ingresa'];
            } else {
                unset($_SESSION['persona_ingresa']);
                $per_id = Yii::$app->session->get("PB_perid");
            }
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
                $dirFileEnd = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/" . $data["name_file"] . "_per_" . $per_id . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
            }*/

            //FORM 1 datos personal            
            $primer_nombre = $data["primer_nombre"];
            $segundo_nombre = $data["segundo_nombre"];
            $primer_apellido = $data["primer_apellido"];
            $segundo_apellido = $data["segundo_apellido"];
            $genero_persona = $data["genero_persona"];
            $nacionalidad = $data["nacionalidad"];
            $pais = $data["pais"]; 
            $pais_reside = $data["pais_reside"];
            $etnia_persona = $data["etnia_persona"];
            $actitudes = $data["actitudes"];
            $formacion_madre = $data["formacion_madre"];
            $formacion_padre = $data["formacion_padre"];
            $miembros_hogar = $data["miembros_hogar"];
            $discapacidad = $data["discapacidad"];
            $tipo_discapacidad = $data["tipo_discapacidad"];
            $tsan_id = $data["tsangre_persona"];
            $porcentaje_dicapacidad = $data["porcentaje_dicapacidad"];

            //FORM 2 Informacion de Contacto
            $nombre_colegio = $data["nombre_colegio"];
            $tipo_colegio = $data["tipo_colegio"];
            $pais_colegio = $data["pais_colegio"];
            $prov_colegio = $data["prov_colegio"];
            $ciu_colegio = $data["ciu_colegio"];
            $especializacion = $data["especializacion"];
            $bachillerato = $data["bachillerato"];
            $homologacion = $data["homologacion"];
            $universidad = $data["universidad"];
            $carrera = $data["carrera"];
            $graduado = $data["graduado"];
            $fecha_grado = $data["fecha_grado"];
            $año_estudio = $data["año_estudio"];
            $aprendizajes = $data["aprendizajes"];

            //FORM 3 Datos Domicilio
            $tipo_financiamiento = $data["tipo_financiamiento"];
            $insttucion_credito = $data["insttucion_credito"];
            /*$can_id_domicilio = $data["cantond_domicilio"];
            $per_domicilio_telefono = $data["telefono_domicilio"];
            $per_domicilio_sector = $data["sector_domicilio"];
            $per_domicilio_cpri = $data["callep_domicilio"];
            $per_domicilio_csec = $data["calls_domicilio"];
            $per_domicilio_num = $data["numero_domicilio"];
            $per_domicilio_ref = $data["referencia_domicilio"];*/

            //FORM 4 Datos Academicos - Estudios Nivel Medio 
            $idioma1 = $data["idioma1"];
            $nivel_escrito1 = $data["nivel_escrito1"];
            $nivel_leido1 = $data["nivel_leido1"];
            $nivel_hablado1 = $data["nivel_hablado1"];

            $idioma2 = $data["idioma2"];
            $nivel_escrito2 = $data["nivel_escrito2"];
            $nivel_leido2 = $data["nivel_leido2"];
            $nivel_hablado2 = $data["nivel_hablado2"];

            $idioma3 = $data["idioma3"];
            $nivel_escrito3 = $data["nivel_escrito3"];
            $nivel_leido3 = $data["nivel_leido3"];
            $nivel_hablado3 = $data["nivel_hablado3"];

            $idioma4 = $data["idioma4"];
            $nivel_escrito4 = $data["nivel_escrito4"];
            $nivel_leido4 = $data["nivel_leido4"];
            $nivel_hablado4 = $data["nivel_hablado4"];

            /*$pai_id_cua = $data["pais_cuarto"];
            $pro_id_cua = $data["prov_cuarto"];
            $ciu_id_cua = $data["ciu_cuarto"];
            $tit_cua = ucwords(strtolower($data["tit_cuarto"]));
            $grado_cua = $data["grado_cuarto"];*/

            //FORM 5 Datos Familiares
            $empresa = $data["empresa"];
            $cargo = $data["cargo"];
            $cat_ocupacional = $data["cat_ocupacional"];
            $direc_empresa = $data["direc_empresa"];
            $telefono_empresa = $data["telefono_empresa"];
            $correo_empresa = $data["correo_empresa"];
            $fecha_ingreso = $data["fecha_ingreso"];

            //FORM 6 Datos Adicionales
            $tipo_beca = $data["tipo_beca"]; //rd
            $motivo_beca = $data["motivo_beca"];
            $monto_recibido = $data["monto_recibido"];
            $porcentajes_beca = $data["porcentajes_beca"];
            /*$discapacidad_fam = $data["discapacidad_fam"];
            $enfermedad_fam = $data["enfermedad_fam"];*/

            /*$idis_archivo = "";
            if (isset($data["archivoi"]) && $data["archivoi"] != "") {
                $arrIm = explode(".", basename($data["archivoi"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $idis_archivo = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/enf_discapacidad_per_" . $per_id . "." . $typeFile;
            }

            $enf_catastrofica = $data["enfermedc"]; //rd
            $ienf_tipoenfermedad = "";
            $ienf_archivo = "";
            if (isset($data["archivoc"]) && $data["archivoc"] != "") {
                $arrIm = explode(".", basename($data["archivoc"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $ienf_archivo = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/enf_catastrofica_per_" . $per_id . "." . $typeFile;
            }

            $disc_severa = $data["discapacidadsev"]; //rd
            $tip_disc_severa = $data["tipof"];
            $porc_disc_severa = $data["porcentajef"];
            $paren_disc_severa = $data["parentescof"];
            $disc_archivo = "";
            if (isset($data["archivof"]) && $data["archivof"] != "") {
                $arrIm = explode(".", basename($data["archivof"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $disc_archivo = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/fam_discapacidad_per_" . $per_id . "." . $typeFile;
            }

            $enf_catas_carga = $data["enfermedcf"]; //rd
            $enf_paren_carga = $data["enfermedcp"];
            $ifen_archivo = "";
            if (isset($data["archivocf"]) && $data["archivocf"] != "") {
                $arrIm = explode(".", basename($data["archivocf"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $ifen_archivo = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/fam_catastrofica_per_" . $per_id . "." . $typeFile;
            }*/
            $tip_enf_fam = $tip_disc_severa; // se debe cambiar
            $con = \Yii::$app->db;
            $transaction = $con->beginTransaction();
            $con1 = \Yii::$app->db_captacion;
            $transaction1 = $con1->beginTransaction();
            try {
                // obtener id de preinteresado
                $arr_interesado = $modInteresado->consultaDatosPreInteresado($per_id);
                if (count($arr_interesado) > 0 && isset($arr_interesado)) {
                    // actualizacion de Persona
                    $respPersona = $modperinteresado->modificaPersona($per_id, $per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $etn_id, $eciv_id, $per_genero, $pai_id_nacimiento, $pro_id_nacimiento, $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $tsan_id, $per_domicilio_sector, $per_domicilio_cpri, $per_domicilio_csec, $per_domicilio_num, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nac_ecuatoriano, $per_nacionalidad, '');
                    // creación de contacto
                    $modpersonacontacto = new PersonaContacto();

                    // si es diferente de vacío guardar otra etnia.                    
                    if (!empty($etniaotra) and $etn_id == 6) {
                        //Se verifica si existe, se modifican los datos.
                        $respconsOtraEtnia = $modperinteresado->consultarOtraetnia($per_id);
                        if ($respconsOtraEtnia) {
                            $respOtraEtnia = $modperinteresado->modificarOtraEtnia($per_id, $etniaotra, '1');
                        } else {
                            //Creación
                            $respOtraEtnia = $modperinteresado->crearOtraEtnia($per_id, $etniaotra);
                        }
                    } else {
                        //Se verifica que si existe como otra etnia se inactiva el registro.
                        $respconsOtraEtnia = $modperinteresado->consultarOtraetnia($per_id);
                        if ($respconsOtraEtnia) {
                            $respOtraEtnia = $modperinteresado->modificarOtraEtnia($per_id, $etniaotra, '0');
                        }
                    }
                    // validar que si esta vacío no llame a guardar
                    if (empty($pcon_nombre) && empty($pcon_apellido) && empty($pcon_telefono) && empty($pcon_celular) && $tpar_id == 1 && empty($pcon_direccion)) {
                        $sincontacto = 1;
                    }

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
                    //Busca id de interesado.
                    $resp_existeinteresado = $modInteresado->consultaDatosinteresado($per_id);
                    if ($resp_existeinteresado) {
                        $int_id = $resp_existeinteresado["int_id"];
                    } else {
                        // creación de Interesado
                        $respInsertInteresado = $modInteresado->crearInteresado($arr_interesado['id'], $user_ingresa);
                        $int_id = Yii::$app->db_captacion->getLastInsertID();
                        // modificar estado pre interesado
                        $respModPreInteresado = $modInteresado->modificaPreInteresado($arr_interesado['id']);
                    }
                    $msg = "info académicos";
                    //Verificar si tiene datos académicos.
                    $resp_existeacadmedio = $modInteresado->consultaInfoacademico($per_id, 1);
                    if ($resp_existeacadmedio) {
                        //modificación de estudios medios.
                        $respInfoAcademicoMedio = $modInteresado->modificarInfoAcaInteresado($int_id, $pai_id_med, $pro_id_med, $ciu_id_med, $tipinti_med, 1, $instituto_med, $tit_med, $grado_med);
                    } else {
                        // info académica Medio
                        $respInfoAcademicoMedio = $modInteresado->crearInfoAcaInteresado($int_id, $pai_id_med, $pro_id_med, $ciu_id_med, $tipinti_med, 1, $instituto_med, $tit_med, $grado_med);
                    }
                    // info académica Tercer
                    //si no tiene informacion no guardar
                    if (empty($instituto_ter) && empty($tit_ter) && empty($grado_ter)) {
                        $sintercernivel = 1;
                    }
                    if ($sintercernivel != 1) {
                        $resp_existeacadternivel = $modInteresado->consultaInfoacademico($per_id, 2);
                        if ($resp_existeacadternivel) {
                            //modificación de info de tercer nivel.
                            $respInfoAcademicoTercer = $modInteresado->modificarInfoAcaInteresado($int_id, $pai_id_ter, $pro_id_ter, $ciu_id_ter, $tipinti_ter, 2, $instituto_ter, $tit_ter, $grado_ter);
                        } else {
                            $respInfoAcademicoTercer = $modInteresado->crearInfoAcaInteresado($int_id, $pai_id_ter, $pro_id_ter, $ciu_id_ter, $tipinti_ter, 2, $instituto_ter, $tit_ter, $grado_ter);
                        }
                    }
                    // info academica Cuarto
                    //si no tiene información no guardar
                    if (empty($instituto_cua) && empty($tit_cua) && empty($grado_cua)) {
                        $sincuartonivel = 1;
                    }
                    if ($sincuartonivel != 1) {
                        $resp_existeacadcuarnivel = $modInteresado->consultaInfoacademico($per_id, 3);
                        if ($resp_existeacadcuarnivel) {
                            $respInfoAcademicoCuarto = $modInteresado->modificarInfoAcaInteresado($int_id, $pai_id_cua, $pro_id_cua, $ciu_id_cua, $tipinti_cua, 3, $instituto_cua, $tit_cua, $grado_cua);
                        } else {
                            $respInfoAcademicoCuarto = $modInteresado->crearInfoAcaInteresado($int_id, $pai_id_cua, $pro_id_cua, $ciu_id_cua, $tipinti_cua, 3, $instituto_cua, $tit_cua, $grado_cua);
                        }
                    }
                    // info familiar
                    // validar que si esta vacío no llame a guardar.
                    $msg = "info familiar";
                    if (empty($miembros_salario) && empty($miembros_hogar) && (($instr_madre == 1) or empty($instr_madre)) && (($instr_padre == 1) or empty($instr_padre))) {
                        $sinmiembros = 1;
                    }

                    $resp_existe_infofamilia = $modInteresado->consultaInfofamilia($per_id);
                    if ($sinmiembros != 1) {
                        if ($resp_existe_infofamilia) {
                            $info_familiar = $modInteresado->modificarInfoFamInteresado($int_id, $instr_padre, $instr_madre, $miembros_hogar, $miembros_salario);
                        } else {
                            $info_familiar = $modInteresado->crearInfoFamInteresado($int_id, $instr_padre, $instr_madre, $miembros_hogar, $miembros_salario);
                        }
                    }
                    $msg = "info discapacidad";
                    // info discapacidad                     
                    $resp_existe_infodisc = $modInteresado->consultaInfodiscapacidad($per_id);
                    if (!empty($discapacidad)) {
                        if ($discapacidad == 1) {
                            if ($resp_existe_infodisc) {
                                $info_discapacidad = $modInteresado->modificarInfoDisInteresado($int_id, $tip_discapacidad, $discapacidad, $porc_discapacidad, $idis_archivo);
                            } else {
                                $info_discapacidad = $modInteresado->crearInfoDisInteresado($int_id, $tip_discapacidad, $discapacidad, $porc_discapacidad, $idis_archivo);
                            }
                        } else {
                            if ($resp_existe_infodisc) {  //Cuando ha existido registro en tabla info_discapacidad, se inactiva el registro.
                                $info_discapacidad = $modInteresado->inactivarInfoDisInteresado($int_id);
                            }
                        }
                    }
                    $msg = "info enfermedad catastrófica";
                    // info enfermedad catastrófica
                    $resp_existeinfoenfer = $modInteresado->consultaInfoenfermedad($per_id);
                    if (!empty($enf_catastrofica)) {
                        if ($enfermedad == 1) {
                            if ($resp_existeinfoenfer) {
                                $info_enfer_catas = $modInteresado->modificarInfoEnfInteresado($int_id, $enf_catastrofica, $ienf_tipoenfermedad, $ienf_archivo);
                            } else {
                                $info_enfer_catas = $modInteresado->crearInfoEnfInteresado($int_id, $enf_catastrofica, $ienf_tipoenfermedad, $ienf_archivo);
                            }
                        } else {
                            if ($resp_existeinfoenfer) {
                                $info_enfer_catas = $modInteresado->inactivarInfoEnfInteresado($int_id);
                            }
                        }
                    }
                    $msg = "info discapacidad familiar";
                    // info enfermedad familiar discapacidad
                    $resp_existeconsinfofamdis = $modInteresado->consultaInfofamailiadisc($per_id);
                    if (!empty($disc_severa)) {
                        if ($discapacidad_fam == 1) {
                            if ($resp_existeconsinfofamdis) {
                                $info_enfer_fam_disc = $modInteresado->modificarInfoEnfFamiliadisc($int_id, $paren_disc_severa, $tip_disc_severa, $disc_severa, $porc_disc_severa, $disc_archivo);
                            } else {
                                $info_enfer_fam_disc = $modInteresado->crearInfoEnfFamiliadisc($int_id, $paren_disc_severa, $tip_disc_severa, $disc_severa, $porc_disc_severa, $disc_archivo);
                            }
                        } else {
                            if ($resp_existeconsinfofamdis) {
                                $info_enfer_fam_disc = $modInteresado->inactivarInfoEnfFamiliadisc($int_id);
                            }
                        }
                    }
                    $msg = "info enfermedad familiar";
                    // info familia enfermedad
                    $resp_existeenffam = $modInteresado->consultaInfoenfermedadfam($per_id);
                    if (!empty($enf_catas_carga)) {
                        if ($enfermedad_fam == 1) {
                            if ($resp_existeenffam) {
                                $info_enfer_familiar = $modInteresado->modificarInfoEnfFamilia($int_id, $enf_paren_carga, $tip_enf_fam, $enf_catas_carga, $ifen_archivo);
                            } else {
                                $info_enfer_familiar = $modInteresado->crearInfoEnfFamilia($int_id, $enf_paren_carga, $tip_enf_fam, $enf_catas_carga, $ifen_archivo);
                            }
                        } else {
                            if ($resp_existeenffam) {
                                $info_enfer_familiar = $modInteresado->inactivarInfoEnfFamilia($int_id);
                            }
                        }
                    }
                    //Se asigna menú de interesado (cambio de rol)
                    $msg = "asignación de rol";
                    $resp_consrol = $modInteresado->consultaGruporolinteresado($per_id);
                    if ($resp_consrol["grol_id"] == 11) {
                        //Ya tiene asignado el rol de interesado.
                        $exito = 1;
                        $mensaje = "La infomación ha sido actualizada.";
                    } else {
                        $resprol = $modInteresado->modificaGruporol($per_id, 11);
                        if ($resprol) {
                            $exito = 1;
                        }
                    }
                }
                if ($exito) {
                    $transaction->commit();
                    $transaction1->commit();
                    if (empty($mensaje)) {
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. Proceda con el ingreso de su solicitud."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                    } else {
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", $mensaje),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                    }
                    return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    return;
                } else {
                    $transaction->rollback();
                    $transaction1->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $msg),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    return;
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $transaction1->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $msg),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                return;
            }
        }
    }

    public function actionView() {
        $mod_pais = new Pais();
        // $usu_id = Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
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
        }
        $per_id = Yii::$app->session->get("PB_perid");
        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();

        // Consulta datos de informacion academica  
        $modInteresado = new Interesado();
        $respacademicomedio = $modInteresado->consultaInfoacademico($per_id, 1);
        $respacademicotercer = $modInteresado->consultaInfoacademico($per_id, 2);
        $respacademicocuarto = $modInteresado->consultaInfoacademico($per_id, 3);

        //Búsqueda de los datos de persona logueada
        $modperinteresado = new Persona();
        $respPerinteresado = $modperinteresado->consultaPersonaId($per_id);
        $pais_id = 57; //Ecuador 
        //algo como esto para que aparezca en create
        if (empty($respacademicotercer)) {
            $respacademicotercer['pais'] = $pais_id;
            $respacademicotercer['provincia'] = 1;  //Azuay
            //Camilo Ponce Enríquez  id ==> 1
        }

        if (empty($respacademicocuarto)) {
            $respacademicocuarto['pais'] = $pais_id;
            $respacademicocuarto['provincia'] = 1;  //Azuay
            //Camilo Ponce Enríquez  id ==> 1
        }

        $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_nac = Provincia::provinciaXPais($respPerinteresado['pai_id_nacimiento']);
        $arr_ciu_nac = Canton::cantonXProvincia($respPerinteresado['pro_id_nacimiento']);

        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_dom = Provincia::provinciaXPais($respPerinteresado['pai_id_domicilio']);
        $arr_ciu_dom = Canton::cantonXProvincia($respPerinteresado['pro_id_domicilio']);

        $arr_pais_med = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_med = Provincia::provinciaXPais($respacademicomedio['pais']);
        $arr_ciu_med = Canton::cantonXProvincia($respacademicomedio['provincia']);

        $arr_pais_ter = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_ter = Provincia::provinciaXPais($respacademicotercer['pais']);
        $arr_ciu_ter = Canton::cantonXProvincia($respacademicotercer['provincia']);

        $arr_pais_cuat = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_cuat = Provincia::provinciaXPais($respacademicocuarto['pais']);
        $arr_ciu_cuat = Canton::cantonXProvincia($respacademicocuarto['provincia']);

        $arr_tip_discap = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();
        $arr_tip_discap_fam = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();

        $arr_tip_instaca_med = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_tip_instaca_ter = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_tip_instaca_cuat = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_ninstruc_mad = NivelInstruccion::find()->select("nins_id AS id, nins_nombre AS value")->where(["nins_estado_logico" => "1", "nins_estado" => "1"])->asArray()->all();
        $arr_ninstruc_pad = NivelInstruccion::find()->select("nins_id AS id, nins_nombre AS value")->where(["nins_estado_logico" => "1", "nins_estado" => "1"])->asArray()->all();

        $arr_tipparent_dis = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $arr_tipparent_enf = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

        // Consulta datos de contacto
        $modpersonacontacto = new PersonaContacto();
        $respcontacto = $modpersonacontacto->consultaPersonaContacto($per_id);
        // Consulta datos de informacion de familia        
        $respfamilia = $modInteresado->consultaInfofamilia($per_id);
        // Consulta datos de informacion adicional
        $respinfodiscapacidad = $modInteresado->consultaInfodiscapacidad($per_id);
        $respinfoenfermedad = $modInteresado->consultaInfoenfermedad($per_id);
        $respinfodiscapacidadf = $modInteresado->consultaInfofamailiadisc($per_id);
        $respinfoenfermedadf = $modInteresado->consultaInfoenfermedadfam($per_id);
        // Consultar otra etnia
        $respotraetnia = $modperinteresado->consultarOtraetnia($per_id);
        //$respinfoadicional = $modInteresado->consultaInfoadicional($per_id);

        $area = $mod_pais->consultarCodigoArea($respPerinteresado['pai_id_nacimiento']);
        $area_dom = $mod_pais->consultarCodigoArea($respPerinteresado['pai_id_domicilio']);
        return $this->render('view', [
                    "arr_etnia" => ArrayHelper::map($arr_etnia, "id", "value"),
                    "arr_civil" => array("1" => Yii::t("general", "Single"),
                        "2" => Yii::t("general", "Married"),
                        "3" => Yii::t("general", "Viudo"),
                        "4" => Yii::t("general", "Divorciado"),
                        "5" => Yii::t("general", "Unión de hecho")),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),
                    /*                     * */
                    "arr_pais_nac" => ArrayHelper::map($arr_pais_nac, "id", "value"),
                    "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                    "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                    /*                     * */
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    /*                     * */
                    "arr_pais_med" => ArrayHelper::map($arr_pais_med, "id", "value"),
                    "arr_prov_med" => ArrayHelper::map($arr_prov_med, "id", "value"),
                    "arr_ciu_med" => ArrayHelper::map($arr_ciu_med, "id", "value"),
                    /*                     * */
                    "arr_pais_ter" => ArrayHelper::map($arr_pais_ter, "id", "value"),
                    "arr_prov_ter" => ArrayHelper::map($arr_prov_ter, "id", "value"),
                    "arr_ciu_ter" => ArrayHelper::map($arr_ciu_ter, "id", "value"),
                    /*                     * */
                    "arr_pais_cuat" => ArrayHelper::map($arr_pais_cuat, "id", "value"),
                    "arr_prov_cuat" => ArrayHelper::map($arr_prov_cuat, "id", "value"),
                    "arr_ciu_cuat" => ArrayHelper::map($arr_ciu_cuat, "id", "value"),
                    /*                     * */
                    "arr_tip_discap" => ArrayHelper::map($arr_tip_discap, "id", "value"),
                    "arr_tip_discap_fam" => ArrayHelper::map($arr_tip_discap_fam, "id", "value"),
                    /*                     * */
                    "arr_tip_instaca_med" => ArrayHelper::map($arr_tip_instaca_med, "id", "value"),
                    "arr_tip_instaca_ter" => ArrayHelper::map($arr_tip_instaca_ter, "id", "value"),
                    "arr_tip_instaca_cuat" => ArrayHelper::map($arr_tip_instaca_cuat, "id", "value"),
                    /*                     * */
                    "arr_ninstruc_mad" => ArrayHelper::map($arr_ninstruc_mad, "id", "value"),
                    "arr_ninstruc_pad" => ArrayHelper::map($arr_ninstruc_pad, "id", "value"),
                    /*                     * */
                    "arr_tipparent_dis" => ArrayHelper::map($arr_tipparent_dis, "id", "value"),
                    "arr_tipparent_enf" => ArrayHelper::map($arr_tipparent_enf, "id", "value"),
                    "respPerinteresado" => $respPerinteresado,
                    "respcontacto" => $respcontacto,
                    "respfamilia" => $respfamilia,
                    "respacademicomedio" => $respacademicomedio,
                    "respacademicotercer" => $respacademicotercer,
                    "respacademicocuarto" => $respacademicocuarto,
                    //"respinfoadicional" => $respinfoadicional,
                    "respinfodiscapacidad" => $respinfodiscapacidad,
                    "respinfoenfermedad" => $respinfoenfermedad,
                    "respinfodiscapacidadf" => $respinfodiscapacidadf,
                    "respinfoenfermedadf" => $respinfoenfermedadf,
                    "respotraetnia" => $respotraetnia,
                    "area" => $area,
                    "area_dom" => $area_dom,
        ]);
    }

    public function actionVerfichaxinteresado() {
        $mod_pais = new Pais();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
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
        }
        $per_id = base64_decode($_GET['ids']); // tomar el de parametro
        // Consulta datos de informacion academica  
        $modInteresado = new Interesado();
        $respacademicomedio = $modInteresado->consultaInfoacademico($per_id, 1);
        $respacademicotercer = $modInteresado->consultaInfoacademico($per_id, 2);
        $respacademicocuarto = $modInteresado->consultaInfoacademico($per_id, 3);

        //Búsqueda de los datos de persona logueada
        $modperinteresado = new Persona();
        $respPerinteresado = $modperinteresado->consultaPersonaId($per_id);

        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();

        $pais_id = 57; //Ecuador    

        if (empty($respacademicotercer)) {
            $respacademicotercer['pais'] = $pais_id;
            $respacademicotercer['provincia'] = 1;  //Azuay
            //Camilo Ponce Enríquez  id ==> 1
        }

        if (empty($respacademicocuarto)) {
            $respacademicocuarto['pais'] = $pais_id;
            $respacademicocuarto['provincia'] = 1;  //Azuay
            //Camilo Ponce Enríquez  id ==> 1
        }
        $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_nac = Provincia::provinciaXPais($respPerinteresado['pai_id_nacimiento']);
        $arr_ciu_nac = Canton::cantonXProvincia($respPerinteresado['pro_id_nacimiento']);

        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_dom = Provincia::provinciaXPais($respPerinteresado['pai_id_domicilio']);
        $arr_ciu_dom = Canton::cantonXProvincia($respPerinteresado['pro_id_domicilio']);

        $arr_pais_med = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_med = Provincia::provinciaXPais($respacademicomedio['pais']);
        $arr_ciu_med = Canton::cantonXProvincia($respacademicomedio['provincia']);

        $arr_pais_ter = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_ter = Provincia::provinciaXPais($respacademicotercer['pais']);
        $arr_ciu_ter = Canton::cantonXProvincia($respacademicotercer['provincia']);

        $arr_pais_cuat = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_cuat = Provincia::provinciaXPais($respacademicocuarto['pais']);
        $arr_ciu_cuat = Canton::cantonXProvincia($respacademicocuarto['provincia']);

        $arr_tip_discap = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();
        $arr_tip_discap_fam = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();

        $arr_tip_instaca_med = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_tip_instaca_ter = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_tip_instaca_cuat = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_ninstruc_mad = NivelInstruccion::find()->select("nins_id AS id, nins_nombre AS value")->where(["nins_estado_logico" => "1", "nins_estado" => "1"])->asArray()->all();
        $arr_ninstruc_pad = NivelInstruccion::find()->select("nins_id AS id, nins_nombre AS value")->where(["nins_estado_logico" => "1", "nins_estado" => "1"])->asArray()->all();

        $arr_tipparent_dis = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $arr_tipparent_enf = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();


        // Consulta datos de contacto
        $modpersonacontacto = new PersonaContacto();
        $respcontacto = $modpersonacontacto->consultaPersonaContacto($per_id);

        // Consulta datos de informacion de familia      
        $respfamilia = $modInteresado->consultaInfofamilia($per_id);

        // Consulta datos de informacion adicional         
        $respinfodiscapacidad = $modInteresado->consultaInfodiscapacidad($per_id);
        $respinfoenfermedad = $modInteresado->consultaInfoenfermedad($per_id);
        $respinfodiscapacidadf = $modInteresado->consultaInfofamailiadisc($per_id);
        $respinfoenfermedadf = $modInteresado->consultaInfoenfermedadfam($per_id);
        // Consultar otra etnia
        $respotraetnia = $modperinteresado->consultarOtraetnia($per_id);
        $area = $mod_pais->consultarCodigoArea($respPerinteresado['pai_id_nacimiento']);
        $area_dom = $mod_pais->consultarCodigoArea($respPerinteresado['pai_id_domicilio']);
        return $this->render('verfichaxinteresado', [
                    "arr_etnia" => ArrayHelper::map($arr_etnia, "id", "value"),
                    "arr_civil" => array("1" => Yii::t("general", "Single"),
                        "2" => Yii::t("general", "Married"),
                        "3" => Yii::t("general", "Widower"),
                        "4" => Yii::t("general", "Divorced"),
                        "5" => Yii::t("general", "Civil Union")),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),
                    /*                     * */
                    "arr_pais_nac" => ArrayHelper::map($arr_pais_nac, "id", "value"),
                    "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                    "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                    /*                     * */
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    /*                     * */
                    "arr_pais_med" => ArrayHelper::map($arr_pais_med, "id", "value"),
                    "arr_prov_med" => ArrayHelper::map($arr_prov_med, "id", "value"),
                    "arr_ciu_med" => ArrayHelper::map($arr_ciu_med, "id", "value"),
                    /*                     * */
                    "arr_pais_ter" => ArrayHelper::map($arr_pais_ter, "id", "value"),
                    "arr_prov_ter" => ArrayHelper::map($arr_prov_ter, "id", "value"),
                    "arr_ciu_ter" => ArrayHelper::map($arr_ciu_ter, "id", "value"),
                    /*                     * */
                    "arr_pais_cuat" => ArrayHelper::map($arr_pais_cuat, "id", "value"),
                    "arr_prov_cuat" => ArrayHelper::map($arr_prov_cuat, "id", "value"),
                    "arr_ciu_cuat" => ArrayHelper::map($arr_ciu_cuat, "id", "value"),
                    /*                     * */
                    "arr_tip_discap" => ArrayHelper::map($arr_tip_discap, "id", "value"),
                    "arr_tip_discap_fam" => ArrayHelper::map($arr_tip_discap_fam, "id", "value"),
                    /*                     * */
                    "arr_tip_instaca_med" => ArrayHelper::map($arr_tip_instaca_med, "id", "value"),
                    "arr_tip_instaca_ter" => ArrayHelper::map($arr_tip_instaca_ter, "id", "value"),
                    "arr_tip_instaca_cuat" => ArrayHelper::map($arr_tip_instaca_cuat, "id", "value"),
                    /*                     * */
                    "arr_ninstruc_mad" => ArrayHelper::map($arr_ninstruc_mad, "id", "value"),
                    "arr_ninstruc_pad" => ArrayHelper::map($arr_ninstruc_pad, "id", "value"),
                    /*                     * */
                    "arr_tipparent_dis" => ArrayHelper::map($arr_tipparent_dis, "id", "value"),
                    "arr_tipparent_enf" => ArrayHelper::map($arr_tipparent_enf, "id", "value"),
                    "respPerinteresado" => $respPerinteresado,
                    "respcontacto" => $respcontacto,
                    "respfamilia" => $respfamilia,
                    "respacademicomedio" => $respacademicomedio,
                    "respacademicotercer" => $respacademicotercer,
                    "respacademicocuarto" => $respacademicocuarto,
                    "respinfodiscapacidad" => $respinfodiscapacidad,
                    "respinfoenfermedad" => $respinfoenfermedad,
                    "respinfodiscapacidadf" => $respinfodiscapacidadf,
                    "respinfoenfermedadf" => $respinfoenfermedadf,
                    "respotraetnia" => $respotraetnia,
                    "area" => $area,
                    "area_dom" => $area_dom,
        ]);
    }

    public function actionUpdate() {
        $mod_pais = new Pais();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
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
        }
        $per_id = Yii::$app->session->get("PB_perid");
        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();

        // Consulta datos de informacion academica  
        $modInteresado = new Interesado();
        $respacademicomedio = $modInteresado->consultaInfoacademico($per_id, 1);
        $respacademicotercer = $modInteresado->consultaInfoacademico($per_id, 2);
        $respacademicocuarto = $modInteresado->consultaInfoacademico($per_id, 3);

        //Búsqueda de los datos de persona logueada
        $modperinteresado = new Persona();
        $respPerinteresado = $modperinteresado->consultaPersonaId($per_id);
        $pais_id = 57; //Ecuador 
        //algo como esto para que aparezca en create
        if (empty($respacademicotercer)) {
            $respacademicotercer['pais'] = $pais_id;
            $respacademicotercer['provincia'] = 1;  //Azuay            
        }

        if (empty($respacademicocuarto)) {
            $respacademicocuarto['pais'] = $pais_id;
            $respacademicocuarto['provincia'] = 1;  //Azuay           
        }

        $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_nac = Provincia::provinciaXPais($respPerinteresado['pai_id_nacimiento']);
        $arr_ciu_nac = Canton::cantonXProvincia($respPerinteresado['pro_id_nacimiento']);

        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_dom = Provincia::provinciaXPais($respPerinteresado['pai_id_domicilio']);
        $arr_ciu_dom = Canton::cantonXProvincia($respPerinteresado['pro_id_domicilio']);

        $arr_pais_med = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_med = Provincia::provinciaXPais($respacademicomedio['pais']);
        $arr_ciu_med = Canton::cantonXProvincia($respacademicomedio['provincia']);

        $arr_pais_ter = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_ter = Provincia::provinciaXPais($respacademicotercer['pais']);
        $arr_ciu_ter = Canton::cantonXProvincia($respacademicotercer['provincia']);

        $arr_pais_cuat = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_cuat = Provincia::provinciaXPais($respacademicocuarto['pais']);
        $arr_ciu_cuat = Canton::cantonXProvincia($respacademicocuarto['provincia']);

        $arr_tip_discap = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();
        $arr_tip_discap_fam = TipoDiscapacidad::find()->select("tdis_id AS id, tdis_nombre AS value")->where(["tdis_estado_logico" => "1", "tdis_estado" => "1"])->asArray()->all();

        $arr_tip_instaca_med = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_tip_instaca_ter = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_tip_instaca_cuat = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_ninstruc_mad = NivelInstruccion::find()->select("nins_id AS id, nins_nombre AS value")->where(["nins_estado_logico" => "1", "nins_estado" => "1"])->asArray()->all();
        $arr_ninstruc_pad = NivelInstruccion::find()->select("nins_id AS id, nins_nombre AS value")->where(["nins_estado_logico" => "1", "nins_estado" => "1"])->asArray()->all();

        $arr_tipparent_dis = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $arr_tipparent_enf = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

        // Consulta datos de contacto
        $modpersonacontacto = new PersonaContacto();
        $respcontacto = $modpersonacontacto->consultaPersonaContacto($per_id);
        // Consulta datos de informacion de familia        
        $respfamilia = $modInteresado->consultaInfofamilia($per_id);
        // Consulta datos de informacion adicional
        $respinfodiscapacidad = $modInteresado->consultaInfodiscapacidad($per_id);
        $respinfoenfermedad = $modInteresado->consultaInfoenfermedad($per_id);
        $respinfodiscapacidadf = $modInteresado->consultaInfofamailiadisc($per_id);
        $respinfoenfermedadf = $modInteresado->consultaInfoenfermedadfam($per_id);
        // Consultar otra etnia
        $respotraetnia = $modperinteresado->consultarOtraetnia($per_id);
        $area = $mod_pais->consultarCodigoArea($respPerinteresado['pai_id_nacimiento']);
        $area_dom = $mod_pais->consultarCodigoArea($respPerinteresado['pai_id_domicilio']);
        $arr_civil = EstadoCivil::find()->select("eciv_id as id, eciv_nombre as value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();

        return $this->render('update', [
                    "arr_etnia" => ArrayHelper::map($arr_etnia, "id", "value"),
                    "arr_civil" => ArrayHelper::map($arr_civil, "id", "value"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),
                    /*                     * */
                    "arr_pais_nac" => ArrayHelper::map($arr_pais_nac, "id", "value"),
                    "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                    "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                    /*                     * */
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    /*                     * */
                    "arr_pais_med" => ArrayHelper::map($arr_pais_med, "id", "value"),
                    "arr_prov_med" => ArrayHelper::map($arr_prov_med, "id", "value"),
                    "arr_ciu_med" => ArrayHelper::map($arr_ciu_med, "id", "value"),
                    /*                     * */
                    "arr_pais_ter" => ArrayHelper::map($arr_pais_ter, "id", "value"),
                    "arr_prov_ter" => ArrayHelper::map($arr_prov_ter, "id", "value"),
                    "arr_ciu_ter" => ArrayHelper::map($arr_ciu_ter, "id", "value"),
                    /*                     * */
                    "arr_pais_cuat" => ArrayHelper::map($arr_pais_cuat, "id", "value"),
                    "arr_prov_cuat" => ArrayHelper::map($arr_prov_cuat, "id", "value"),
                    "arr_ciu_cuat" => ArrayHelper::map($arr_ciu_cuat, "id", "value"),
                    /*                     * */
                    "arr_tip_discap" => ArrayHelper::map($arr_tip_discap, "id", "value"),
                    "arr_tip_discap_fam" => ArrayHelper::map($arr_tip_discap_fam, "id", "value"),
                    /*                     * */
                    "arr_tip_instaca_med" => ArrayHelper::map($arr_tip_instaca_med, "id", "value"),
                    "arr_tip_instaca_ter" => ArrayHelper::map($arr_tip_instaca_ter, "id", "value"),
                    "arr_tip_instaca_cuat" => ArrayHelper::map($arr_tip_instaca_cuat, "id", "value"),
                    /*                     * */
                    "arr_ninstruc_mad" => ArrayHelper::map($arr_ninstruc_mad, "id", "value"),
                    "arr_ninstruc_pad" => ArrayHelper::map($arr_ninstruc_pad, "id", "value"),
                    /*                     * */
                    "arr_tipparent_dis" => ArrayHelper::map($arr_tipparent_dis, "id", "value"),
                    "arr_tipparent_enf" => ArrayHelper::map($arr_tipparent_enf, "id", "value"),
                    "respPerinteresado" => $respPerinteresado,
                    "respcontacto" => $respcontacto,
                    "respfamilia" => $respfamilia,
                    "respacademicomedio" => $respacademicomedio,
                    "respacademicotercer" => $respacademicotercer,
                    "respacademicocuarto" => $respacademicocuarto,
                    "respinfodiscapacidad" => $respinfodiscapacidad,
                    "respinfoenfermedad" => $respinfoenfermedad,
                    "respinfodiscapacidadf" => $respinfodiscapacidadf,
                    "respinfoenfermedadf" => $respinfoenfermedadf,
                    "respotraetnia" => $respotraetnia,
                    "area" => $area,
                    "area_dom" => $area_dom,
        ]);
    }

    public function actionCreateins() {
        $mod_pais = new Pais();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
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
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais en informacion personal                
                $area = $mod_pais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
        }
        if (base64_decode($_GET['ids']) != '') {// tomar el de parametro)
            $per_id = base64_decode($_GET['ids']);
        } else {
            $per_id = Yii::$app->session->get("PB_perid");
        }
        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();

        //Búsqueda de los datos de persona logueada
        $modperinteresado = new Persona();
        $respPerinteresado = $modperinteresado->consultaPersonaId($per_id);
        $pais_id = 57; //Ecuador   
        //algo como linea 415 para que aparezca ls provincias y canton si tienen en la BASE
        $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value, pai_codigo_fono AS code")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_nac = Provincia::provinciaXPais($respPerinteresado['pai_id_domicilio']);
        $arr_ciu_nac = Canton::cantonXProvincia($arr_prov_nac[0]["id"]);

        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_dom = Provincia::provinciaXPais($respPerinteresado['pai_id_domicilio']);
        $arr_ciu_dom = Canton::cantonXProvincia($arr_prov_dom[0]["id"]);

        $arr_pais_med = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_med = Provincia::provinciaXPais($pais_id);
        $arr_ciu_med = Canton::cantonXProvincia($arr_prov_med[0]["id"]);

        $arr_pais_ter = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_ter = Provincia::provinciaXPais($pais_id);
        $arr_ciu_ter = Canton::cantonXProvincia($arr_prov_ter[0]["id"]);

        $arr_pais_cuat = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_cuat = Provincia::provinciaXPais($pais_id);
        $arr_ciu_cuat = Canton::cantonXProvincia($arr_prov_cuat[0]["id"]);

        $arr_tip_instaca_med = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_tip_instaca_ter = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();
        $arr_tip_instaca_cuat = TipoInstitucionAca::find()->select("tiac_id AS id, tiac_nombre AS value")->where(["tiac_estado_logico" => "1", "tiac_estado" => "1"])->asArray()->all();

        $arr_tipparent_dis = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $arr_tipparent_enf = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

        $area = $mod_pais->consultarCodigoArea($respPerinteresado['pai_id_domicilio']);

        return $this->render('createins', [
                    "arr_etnia" => ArrayHelper::map($arr_etnia, "id", "value"),
                    "arr_civil" => array("1" => Yii::t("general", "Single"),
                        "2" => Yii::t("general", "Married"),
                        "3" => Yii::t("general", "Widower"),
                        "4" => Yii::t("general", "Divorced"),
                        "5" => Yii::t("general", "Civil Union")),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),
                    /*                     * */
                    "arr_pais_nac" => $arr_pais_nac, //ArrayHelper::map($arr_pais_nac, "id", "value"),
                    "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                    "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                    /*                     * */
                    "arr_pais_dom" => /* $arr_pais_dom, */ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    /*                     * */
                    "arr_pais_med" => ArrayHelper::map($arr_pais_med, "id", "value"),
                    "arr_prov_med" => ArrayHelper::map($arr_prov_med, "id", "value"),
                    "arr_ciu_med" => ArrayHelper::map($arr_ciu_med, "id", "value"),
                    /*                     * */
                    "arr_pais_ter" => ArrayHelper::map($arr_pais_ter, "id", "value"),
                    "arr_prov_ter" => ArrayHelper::map($arr_prov_ter, "id", "value"),
                    "arr_ciu_ter" => ArrayHelper::map($arr_ciu_ter, "id", "value"),
                    /*                     * */
                    "arr_pais_cuat" => ArrayHelper::map($arr_pais_cuat, "id", "value"),
                    "arr_prov_cuat" => ArrayHelper::map($arr_prov_cuat, "id", "value"),
                    "arr_ciu_cuat" => ArrayHelper::map($arr_ciu_cuat, "id", "value"),
                    /*                     * */
                    "arr_tip_instaca_med" => ArrayHelper::map($arr_tip_instaca_med, "id", "value"),
                    "arr_tip_instaca_ter" => ArrayHelper::map($arr_tip_instaca_ter, "id", "value"),
                    "arr_tip_instaca_cuat" => ArrayHelper::map($arr_tip_instaca_cuat, "id", "value"),
                    /*                     * */
                    "arr_tipparent_dis" => ArrayHelper::map($arr_tipparent_dis, "id", "value"),
                    "arr_tipparent_enf" => ArrayHelper::map($arr_tipparent_enf, "id", "value"),
                    "respPerinteresado" => $respPerinteresado,
                    "area" => $area,
        ]);
    }

}
