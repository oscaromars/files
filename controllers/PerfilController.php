<?php

namespace app\controllers;

use Yii;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\Profesor;
use app\models\Etnia;
use app\models\Pais;
use app\models\Provincia;
use app\models\Canton;
use app\models\Utilities;
use app\models\TipoSangre;
use app\models\Persona;
use app\models\EstadoCivil;
use app\models\TipoParentesco;
use app\models\PersonaContacto;
use app\models\PersonaCorreoInstitucional;
use app\models\ContactoGeneral;
use app\models\TipoContactoGeneral;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\base\Security;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;

/**
 * 
 *
 * @author Diana Lopez
 */
class PerfilController extends \app\components\CController {

    protected $widthImg = "141";
    protected $heightImg = "193";

    public function actionUpdate() {
        $mod_areapais = new Pais();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais en informacion personal
                //$mod_areapais = new Pais();
                $area = $mod_areapais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
        }
        $per_id = Yii::$app->session->get("PB_perid");
        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();
        //Búsqueda de los datos de persona logueada
        $modpersona = new Persona();
        $respPersona = $modpersona->consultaPersonaId($per_id);

        if ($respPersona['per_id']) {
            $modpercorinstitucional = new PersonaCorreoInstitucional();
            $respPerCorInstitucional = $modpercorinstitucional->consultarCorreoInstitucional($per_id);
            $modContGeneral = new ContactoGeneral();
            $respContGeneral = $modContGeneral->consultaContactoGeneral($respPersona['per_id']);
            $data = Yii::$app->request->get();
        }

        $pais_id = 57; //Ecuador
        $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value, pai_codigo_fono AS code")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_nac = Provincia::provinciaXPais($respPersona['pai_id_nacimiento']);
        $arr_ciu_nac = Canton::cantonXProvincia($respPersona['pro_id_nacimiento']);

        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value, pai_codigo_fono AS code")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_dom = Provincia::provinciaXPais($respPersona['pai_id_domicilio']);
        $arr_ciu_dom = Canton::cantonXProvincia($respPersona['pro_id_domicilio']);

        $arr_tipparent = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
        $arr_civil = EstadoCivil::find()->select("eciv_id as id, eciv_nombre as value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();

        $area = $mod_areapais->consultarCodigoArea($respPersona['pai_id_domicilio']);
        $respotraetnia = $modpersona->consultarOtraetnia($per_id);

        if (empty($respPersona['per_foto'])) {
            $respPersona['per_foto'] = '/uploads/ficha/silueta_default.jpeg';
        }

        return $this->render('update', [
                    "arr_etnia" => ArrayHelper::map($arr_etnia, "id", "value"),
                    "arr_civil" => ArrayHelper::map($arr_civil, "id", "value"),
                    "tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),
                    /*                     * */
                    "arr_pais_nac" => $arr_pais_nac, //ArrayHelper::map($arr_pais_nac, "id", "value"),
                    "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                    "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                    /*                     * */
                    "arr_pais_dom" => $arr_pais_dom,
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    /*                     * */
                    "arr_tipparent" => ArrayHelper::map($arr_tipparent, "id", "value"),
                    "respPersona" => $respPersona, 
                    "area" => $area,
                    "respPerCorInstitucional" => $respPerCorInstitucional,
                    "respContGeneral" => $respContGeneral,
                    "respotraetnia" => $respotraetnia,
                    "widthImg" => $this->widthImg,
                    "heightImg" => $this->heightImg,
        ]);
    }

    /* Guardado del Primer Tab */
    public function actionGuardartab1() {
        //error_log("Entro a log");        
        $modpersona = new Persona(); //ExpedienteProfesor();
        $per_id = Yii::$app->session->get("PB_perid");

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            
            if ($data["upload_file"]) {
                $files = $_FILES[key($_FILES)];
                if (!isset($files) || count($files) == 0) {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                //Recibe Paramentros
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirCurrent = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/" . $data["name_file"] . "_per_" . $per_id . "." . $typeFile;
                $dirFileEnd = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/" . $data["name_file"] . "_per_" . $per_id . ".jpeg";
                $status = false;
                if(strtolower($typeFile) == 'jpg' || strtolower($typeFile) == 'jpeg'){
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                }else{
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirCurrent);
                    if($status){
                        $status = Utilities::changeIMGtoJPG($dirCurrent, $dirFileEnd);
                    }
                }
                if ($status) {
                    $modelpersona = Persona::findOne($per_id);
                    $modelpersona->per_foto = $dirFileEnd;
                    $modelpersona->save();
                    return true;
                } else {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
            }

            //FORM 1 datos personal
            $per_foto = $data["foto_persona"];
            $per_pri_nombre = $data["pnombre_persona"];
            $per_seg_nombre = $data["snombre_persona"];
            $per_pri_apellido = ucwords(mb_strtolower($data["papellido_persona"]));
            $per_seg_apellido = ucwords(mb_strtolower($data["sapellido_persona"]));
            $per_genero = $data["genero_persona"];
            $etn_id = $data["etnia_persona"];
            $etniaotra = ucwords(mb_strtolower($data["etnia_otra"])); // esta guarda en tabla otra_etnia
            $eciv_id = $data["ecivil_persona"];
            $per_fecha_nacimiento = $data["fnacimiento_persona"];
            $per_nacionalidad = $data["pnacionalidad"];
            $pai_id_nacimiento = $data["pais_persona"];
            $pro_id_nacimiento = $data["provincia_persona"];
            $can_id_nacimiento = $data["canton_persona"];
            $per_correo = ucwords(strtolower($data["correo_persona"]));
            $per_cor_institucional = ucwords(strtolower($data["correo_institucional"]));
            $per_telefono = $data["telefono_persona"];
            $per_celular = $data["celular_persona"];
            $tsan_id = $data["tsangre_persona"];
            $per_nac_ecuatoriano = $data["nacecuador"];

            //FORM 1 Informacion de Contacto
            $cgen_nombre = ucwords(mb_strtolower($data["nombre_contacto"]));
            $cgen_apellido = ucwords(mb_strtolower($data["apellido_contacto"]));
            $cgen_telefono = $data["telefono_contacto"];
            $cgen_celular = $data["celular_contacto"];
            $tpar_id = $data["parentesco_contacto"];
            $cgen_direccion = ucwords(mb_strtolower($data["direccion_contacto"]));

            //FORM 2 Datos Domicilio
            $pai_id_domicilio = $data["paisd_domicilio"];
            $pro_id_domicilio = $data["provinciad_domicilio"];
            $can_id_domicilio = $data["cantond_domicilio"];
            $per_domicilio_telefono = $data["telefono_domicilio"];
            $per_domicilio_sector = ucwords(mb_strtolower($data["sector_domicilio"]));
            $per_domicilio_cpri = ucwords(mb_strtolower($data["callep_domicilio"]));
            $per_domicilio_csec = ucwords(mb_strtolower($data["calls_domicilio"]));
            $per_domicilio_num = ucwords(mb_strtolower($data["numero_domicilio"]));
            $per_domicilio_ref = ucwords(mb_strtolower($data["referencia_domicilio"]));

            $foto_archivo = "";

            if (isset($data["foto_persona"]) && $data["foto_persona"] != "") {
                $arrIm = explode(".", basename($data["foto_persona"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $foto_archivo = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/doc_foto_per_" . $per_id . ".jpeg";
            }
            $con = \Yii::$app->db;
            $transaction = $con->beginTransaction();
            try {
                //Guardado con envio de Foto               
                $respPersona = $modpersona->modificaPersona($per_id, $per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $etn_id, $eciv_id, $per_genero, $pai_id_nacimiento, $pro_id_nacimiento, $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $tsan_id, $per_domicilio_sector, $per_domicilio_cpri, $per_domicilio_csec, $per_domicilio_num, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nac_ecuatoriano, $per_nacionalidad, $foto_archivo);
                if ($respPersona) {
                    // creacion de contacto                                    
                    $modcontactogeneral = new ContactoGeneral();
                    $modpercorinstitucional = new PersonaCorreoInstitucional();

                    // si es diferente de vacío guardar otra etnia.                    
                    if (!empty($etniaotra) and $etn_id == 6) {
                        //Se verifica si existe, se modifican los datos.
                        $respconsOtraEtnia = $modpersona->consultarOtraetnia($per_id);
                        if ($respconsOtraEtnia) {
                            $respOtraEtnia = $modpersona->modificarOtraEtnia($per_id, $etniaotra, '1');
                        } else {
                            //
                            $respOtraEtnia = $modpersona->crearOtraEtnia($per_id, $etniaotra);
                        }
                    } else {
                        //Se verifica que si existe como otra etnia se inactiva el registro.
                        $respconsOtraEtnia = $modpersona->consultarOtraetnia($per_id);
                        if ($respconsOtraEtnia) {
                            $respOtraEtnia = $modpersona->modificarOtraEtnia($per_id, $etniaotra, '0');
                        }
                    }
                    // actualizacion de Correo Profesor en caso de que se venga algun valor en esta variable                   
                    if (!empty($per_cor_institucional)) {
                        //Verificar si existe creado ya un correo institucional si no existe se lo crea
                        $verificarRegistroCorInstitucional = $modpercorinstitucional->consultarCorreoInstitucional($per_id);
                        if ($verificarRegistroCorInstitucional) {
                            //Existe Registro lo actualizamos
                            $respPerCorInstitucional = $modpercorinstitucional->modificaCorInstitucional($per_id, $per_cor_institucional);
                        } else {
                            //No Existe Registro lo creamos
                            $respPerCorInstitucional = $modpercorinstitucional->crearCorreoInstitucional($per_id, $per_cor_institucional);
                        }
                    }
                    // validar que si esta vacio no llame a guardar              
                    if (empty($cgen_nombre) && empty($cgen_apellido) && empty($cgen_telefono) && empty($cgen_celular) && $tpar_id != '' && empty($cgen_direccion)) {
                        $sincontacto = 1;
                    }

                    if ($sincontacto != 1) {
                        $verificarRegistroContactoGeneral = $modcontactogeneral->consultaContactoGeneral($per_id);
                        // $db_base = "db_asgard.persona";
                        if ($verificarRegistroContactoGeneral) {
                            $resp_contacto = $modcontactogeneral->modificaContactoGeneral($verificarRegistroContactoGeneral['contacto_id'], $per_id, $cgen_nombre, $cgen_apellido, $tpar_id, $cgen_direccion, $cgen_telefono, $cgen_celular);
                            if ($resp_contacto) {
                                $exito = 1;
                            }
                        } else {
                            $resp_contacto = $modcontactogeneral->crearContactoGeneral($per_id, $cgen_nombre, $cgen_apellido, $tpar_id, $cgen_direccion, $cgen_telefono, $cgen_celular);
                            if ($resp_contacto) {
                                $exito = 1;
                            }
                        }
                    } else {
                        $exito = 1;
                    }
                }

                if ($exito) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }

    }

    public function actionSetpicture(){
        $data = Yii::$app->request->get();
        $per_id = Yii::$app->session->get("PB_perid");
        $model_persona = Persona::findOne($per_id);
        $foto = Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/doc_foto_per_" . $per_id . ".jpeg";
        return $this->render('crop', [
            'per_foto' => $foto,
            "widthImg" => $this->widthImg,
            "heightImg" => $this->heightImg,
        ]);
    }

    public function actionSavepicture(){
        $per_id = Yii::$app->session->get("PB_perid");

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["crop_file"]) {
                try{
                    $src = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "ficha/" . $per_id . "/doc_foto_per_" . $per_id . ".jpeg";
                    $targ_w = $this->widthImg;
                    $targ_h = $this->heightImg;
                    $jpeg_quality = 90;

                    $img_r = imagecreatefromjpeg($src);
                    $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

                    imagecopyresampled($dst_r,$img_r,0,0,$data['x'],$data['y'],$targ_w,$targ_h,$data['w'],$data['h']);

                    imagejpeg($dst_r,$src,$jpeg_quality);
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                }catch(Exception $ex){
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al Procesar Imagen."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
                
            }
        }

    }

    public function actionCredencial(){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $idper  = Yii::$app->session->get('PB_perid', FALSE);
        $idemp  = Yii::$app->session->get('PB_idempresa', FALSE);
        
        if (Yii::$app->session->get('PB_isuser')) {
            $modelPersona = Persona::findOne($idper);
            $modelEstudiante = Estudiante::findOne(['per_id' => $idper, 'est_estado_logico' => '1', 'est_estado' => '1']);
            $modelDocente = Profesor::findOne(['per_id' => $idper, 'pro_estado_logico' => '1', 'pro_estado' => '1']);
            $isEstudiante = false;
            $isDocente = false;
            $images_dir = 'rounded_corners';
            $corner_radius = 10; // El radio de la esquina redondeada se establece en 20px por defecto
            $foto_archivo = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "ficha/" . $idper . "/doc_foto_per_" . $idper . ".jpeg";
            $bg_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-admin-front.jpeg";
            $bb_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-admin-back.jpeg";
            $marginPhoto = 14; // Para credenciales Administrativas
            $image_rounded = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/$images_dir/rounded_corner_".$corner_radius."px.b.png";
            $image_rounded2 = NULL;
            if($modelEstudiante){
                $marginPhoto = 14; // Para credenciales Estudiantes
                $image_rounded = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/$images_dir/rounded_corner_".$corner_radius."px.w.png";
                $image_rounded2 = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/$images_dir/rounded_corner_".$corner_radius."px.lb.png";
                $bg_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-estudiante-front.jpeg";
                $bb_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-estudiante-back.jpeg";
                $isEstudiante = true;
            }
            if($modelDocente && !$isEstudiante){
                $bg_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-docente-front.jpeg";
                $bb_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-docente-back.jpeg";
                $isDocente = true;
            }
            
            if(is_file($foto_archivo)){
                // mostrar los archivos
                //Header("Content-type: image/png");
                $size = [$this->widthImg,$this->heightImg];
                $im1 = imagecreatefromjpeg($bg_credencial); //image 325 x 523 
                $im2 = imagecreatefromjpeg($foto_archivo); //image 147 x 209 
                $image = ImageCreateTrueColor($size[0], $size[1]);
                imagecopyresampled($image,$im2,0,0,0,0,$size[0], $size[1], $size[0], $size[1]);
                
                $angle = 0; // The default angle is set to 0º
                $topleft = true; // La esquina superior izquierda se muestra por defecto
                $bottomleft = true; // La esquina inferior izquierda se muestra por defecto
                $bottomright = true; // La esquina inferior derecha se muestra por defecto
                $topright = true; // La esquina superior derecha se muestra por defecto
                $white = ImageColorAllocate($image,255,255,255);
                $black = ImageColorAllocate($image,0,0,0);

                $corner_source = imagecreatefrompng($image_rounded);
                $corner_width = imagesx($corner_source);  
                $corner_height = imagesy($corner_source);  
                $corner_resized = ImageCreateTrueColor($corner_radius, $corner_radius);
                ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $corner_radius, $corner_radius, $corner_width, $corner_height);
                $corner_width = imagesx($corner_resized);  
                $corner_height = imagesy($corner_resized);

                // Esquina inferior derecha
                if ($bottomright == true) {
                    $dest_x = $size[0] - $corner_width;  
                    $dest_y = $size[1] - $corner_height;  
                    $rotated = imagerotate($corner_resized, 180, 0);
                    imagecolortransparent($rotated, $black); 
                    imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
                }

                // Esquina inferior izquierda
                if ($bottomleft == true) {
                    $dest_x = 0;  
                    $dest_y = $size[1] - $corner_height; 
                    $rotated = imagerotate($corner_resized, 90, 0);
                    imagecolortransparent($rotated, $black); 
                    imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
                }

                if(isset($image_rounded2)){
                    $corner_source = imagecreatefrompng($image_rounded2);
                    ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $corner_radius, $corner_radius, $corner_width, $corner_height);
                    $corner_width = imagesx($corner_resized);  
                    $corner_height = imagesy($corner_resized);
                }

                // Esquina superior derecha
                if ($topright == true) {
                    $dest_x = $size[0] - $corner_width;  
                    $dest_y = 0;  
                    $rotated = imagerotate($corner_resized, 270, 0);
                    imagecolortransparent($rotated, $black); 
                    imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
                }

                // Esquina superior izquierda
                if ($topleft == true) {
                    $dest_x = 0;  
                    $dest_y = 0;  
                    imagecolortransparent($corner_resized, $black); 
                    imagecopymerge($image, $corner_resized, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
                } 

                // Rotar la imagen
                $image_rn = imagerotate($image, $angle, $white);

                // Texto
                $ttf_lightS = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Gotham-Light.otf";
                $ttf_light = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Gotham-Book.otf";
                $ttf_bold = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Gotham-Medium.ttf";
                $ttf_boldH = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Gotham-Bold.ttf";
                $ttf_arial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Arial.ttf";
                $ttf_arialB = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Arial-Bold.ttf";
                $colorB = imagecolorallocate($im1, 255, 255, 255);//#FFFFFF
                if($isEstudiante){
                    $colorB = imagecolorallocate($im1, 0, 84, 139);//#00548b
                }
                
                $colorW = imagecolorallocate($im1, 255, 255, 255);//#FFFFFF
                $font_size = 11;
                $angulo = 0;
                $posX = 12;
                $posY = 385;

                // Get image dimensions
                $width = imagesx($im1);
                $height = imagesy($im1);
                // Get center coordinates of image
                $centerX = $width / 2;
                $centerY = $height / 2;

                $nombre = $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido . " " . $modelPersona->per_seg_apellido; // limite 30 caracteres
                if(strlen($nombre) > 30){
                    $nombre = $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido; 
                }
                //$nombre = str_replace(array("Á","É", "Í", "Ó", "Ú"), array("A", "E", "I", "O", "U"), $nombre);
                $carrera = ""; // limite 30 caracteres
                $modalidad = ""; // limite 30 caracteres
                $cargo = ""; // limite 30 caracteres
                $matricula = "";
                $ttf_font = $ttf_boldH;
                $widthDifference = 7;
                if($isEstudiante){
                    $dataCarrera = $modelEstudiante->getInfoCarreraEstudiante($modelEstudiante->est_id, $idemp);
                    $ttf_font = $ttf_light;
                    $widthDifference = 0;
                    $carrera = (isset($dataCarrera['ResumenCarrera']) && $dataCarrera['ResumenCarrera'] != "")?$dataCarrera['ResumenCarrera']:$dataCarrera['Carrera']; // limite 30 caracteres
                    if(strlen($carrera) > 30){
                        $carrera = substr($carrera, 0, 31) . ".";
                    }
                    //$modalidad = $dataCarrera['Modalidad']; // limite 30 caracteres
                    $matricula = $modelEstudiante->est_matricula;
                }

                // Get size of text /// MUESTRA EL NOMBRE
                list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angulo, $ttf_font, strtoupper($nombre));
                // Determine offset of text
                $left_offset = ($right - $left) / 2;
                $top_offset = ($bottom - $top) / 2;
                // Generate coordinates
                $x = $centerX - $left_offset;
                $y = $centerY - $top_offset;
                // Add text to image
                //Imagen, tamaño, ángulo, x, y, color, fuente, texto
                imagefttext($im1, $font_size, $angulo, $x, $posY + $widthDifference, $colorB, $ttf_font, strtoupper($nombre));
                
                if(!$isEstudiante && !$isDocente){
                    $cargo = ""; ///********************************************************* */ LLENAR CARGO
                    // Get size of text
                    list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angulo, $ttf_font, strtoupper($cargo));
                    // Determine offset of text
                    $left_offset = ($right - $left) / 2;
                    $top_offset = ($bottom - $top) / 2;
                    // Generate coordinates
                    $x = $centerX - $left_offset;
                    $y = $centerY - $top_offset;
                    // Add text to image
                    imagefttext($im1, $font_size, $angle, $x, 397, $colorB, $ttf_font, strtoupper($cargo));
                }
                
                if($isEstudiante){
                    // Get size of text
                    list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angulo, $ttf_light, $carrera);
                    // Determine offset of text
                    $left_offset = ($right - $left) / 2;
                    $top_offset = ($bottom - $top) / 2;
                    // Generate coordinates
                    $x = $centerX - $left_offset;
                    $y = $centerY - $top_offset;
                    // Add text to image
                    imagefttext($im1, $font_size, $angulo, $x, 410, $colorB, $ttf_light, $carrera);
                
                
                    // Get size of text
                    list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angulo, $ttf_light, $modalidad);
                    // Determine offset of text
                    $left_offset = ($right - $left) / 2;
                    $top_offset = ($bottom - $top) / 2;
                    // Generate coordinates
                    $x = $centerX - $left_offset;
                    $y = $centerY - $top_offset;
                    // Add text to image
                    imagefttext($im1, $font_size, $angle, $x, 430, $colorB, $ttf_light, $modalidad);

                    // Periodo
                    $periodo = date("Y") . " - " . ( 1 + date("Y"));
                    // Get size of text
                    list($left, $bottom, $right, , , $top) = imageftbbox(19, $angulo, $ttf_light, $periodo);
                    // Determine offset of text
                    $left_offset = ($right - $left) / 2;
                    $top_offset = ($bottom - $top) / 2;
                    // Generate coordinates
                    $x = $centerX - $left_offset;
                    $y = $centerY - $top_offset;
                    //Imagen, tamaño, ángulo, x, y, color, fuente, texto
                    imagefttext($im1, 19, 0, 85, 498, $colorW, $ttf_bold, $periodo);
                }

                // Crear la imagen final
                //imagejpeg($image);
                imagecopy($im1, $image_rn, (imagesx($im1)/2)-(imagesx($image_rn)/2), (imagesy($im1)/2)-(imagesy($image_rn)/2)-$marginPhoto, 0, 0, imagesx($image_rn), imagesy($image_rn));
                
                $tempFile = Utilities::createTemporalFile("test");
                imagepng($im1, $tempFile);
                imagedestroy($im1);
                imagedestroy($image_rn);
                
                $front_file = file_get_contents($tempFile);
                $back_file = file_get_contents($bb_credencial);
                Utilities::removeTemporalFile($tempFile);
                return $this->render('credencial', [
                    'nombre_estudiante' => $nombre,
                    'carrera' => $carrera,
                    'modalidad' => $modalidad,
                    'matricula' => $matricula,
                    'img_front' => 'data:image/png;base64,' . base64_encode($front_file),
                    'img_back' => 'data:image/png;base64,' . base64_encode($back_file),
                    'isEstudiante' => $isEstudiante,
                    'isDocente' => $isDocente,
                    'cargo' => $cargo,
                ]);
            }else{
                Yii::$app->session->setFlash('error',"<h4>".Yii::t('notificaciones', 'Error')."</h4>".Yii::t("perfil","Please upload your picture to your Profile."));
                return $this->redirect('update');
            }
        }
    }
}
