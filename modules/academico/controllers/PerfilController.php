<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\Persona;
use app\models\Empresa;
use app\models\EmpresaPersona;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use app\models\Provincia;
use app\models\Pais;
use app\models\Grupo;
use app\models\Rol;
use app\models\GrupRol;
use app\models\Canton;
use app\modules\academico\models\Profesor;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use yii\base\Exception;
use app\models\EstadoCivil;
use app\models\Etnia;
use app\models\TipoSangre;
use app\models\TipoPassword;
use app\modules\academico\models\Estudiante;

/**New */
use app\modules\academico\models\Perfil;

class PerfilController extends \app\components\CController {

    public function actionIndex() {

        $_SESSION['JSLANG']['Passwords are differents, please enter passwords again.'] = Yii::t("perfil",'Passwords are differents, please enter passwords again.');
        $model = new Provincia();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["pai_id"])) {
                $arr_pro = $model->provinciabyPais($data["pai_id"]);
                
                list($firstpro) = $arr_pro;

                $arr_can  = Canton::find()
                    ->select(["can_id as id", "can_nombre as name"])            
                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                    "pro_id" => $firstpro['id']])->asArray()->all();

                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', ['arr_pro'=>$arr_pro, 'arr_can'=>$arr_can]);
            }
            if (isset($data["pro_id"])) {
                $arr_can  = Canton::find()
                    ->select(["can_id as id", "can_nombre as name"])
                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                    "pro_id" => $data["pro_id"]])->asArray()->all();

                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $arr_can);
            }
            if (isset($data["type_doc"])) {
                $dni = Persona::getDNIbyTipoDoc($data["per_id"], $data["type_doc"]);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $dni);
            }
        }

        $country_id = 1;
        $pro_id = Provincia::findOne(["pai_id" => $country_id, "pro_estado" => 1, "pro_estado_logico" => 1]);
        $per_id = Yii::$app->session->get("PB_perid");
        $usu_id = Yii::$app->session->get("PB_iduser");
        $user = Usuario::findIdentity($usu_id);
        if ($per_id < 1000) {
            $per_id = base64_decode(Yii::$app->request->get('per_id', 0));
            if($per_id != 0){
                $user = Usuario::findOne(['per_id' => $per_id]);
                $usu_id = $user->usu_id;
            }else   $per_id = Yii::$app->session->get("PB_perid");
        }
        $persona = Persona::findIdentity($per_id);
        $otra_etnia = $persona->consultarOtraetnia($per_id);
        $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
        $arr_pro = Provincia::findAll(["pai_id" => ((isset($persona->pai_id_nacimiento) && $persona->pai_id_nacimiento != "")?($persona->pai_id_nacimiento):$country_id), "pro_estado" => 1, "pro_estado_logico" => 1]);
        $arr_can = Canton::findAll(["pro_id" => ((isset($persona->pro_id_nacimiento) && $persona->pro_id_nacimiento != "")?($persona->pro_id_nacimiento):$pro_id->pro_id), "can_estado" => 1, "can_estado_logico" => 1]);
        $arr_estado_civil = EstadoCivil::find()->select("eciv_id as id, eciv_nombre as value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $arr_tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();

        return $this->render('index', [
                    'user' => $user,
                    'persona' => $persona,
                    "otra_etnia" => $otra_etnia['oetn_nombre'],
                    "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
                    "arr_etnia" => ArrayHelper::map($arr_etnia, "id", "value"),
                    "arr_tipos_sangre" => ArrayHelper::map($arr_tipos_sangre, "id", "value"),
                    "arr_tipo_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport"), "RUC" => "RUC"),
                    'arr_genero' => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                    'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                    'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                    /* 'arr_parr' => (empty(ArrayHelper::map($arr_parr, "par_id", "par_nombre"))) ? array(Yii::t("pais", "-- Select Parish --")) : (ArrayHelper::map($arr_parr, "par_id", "par_nombre")), */
        ]);            
    }   

    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {

                $per_id = $data["per_id"];
                $per_pri_nombre = $data["first_nombre"];
                $per_seg_nombre = $data["second_name"];
                $per_pri_apellido = $data["first_apellido"];
                $per_seg_apellido = $data["second_lastname"];
                $dni = $data["dni"];
                $per_correo = $data["mail"];
                $type_doc = $data["type_doc"];
                $per_genero = $data["gender"];
                $otra_etnia = $data["other_ethnicity"];
                $per_nacionalidad = $data["nationality"];
                $pro_id_nacimiento = $data["pro_id"];
                $eciv_id = $data["eciv_id"];
                $per_celular = $data["phone"];
                $per_nac_ecuatoriano = $data["nac_ecu"];
                $etn_id = $data["etn_id"];
                $per_fecha_nacimiento = $data["birth_date"];
                $pai_id_nacimiento = $data["pai_id"];
                $can_id = $data["can_id"];
                $tsan_id = $data["tsan_id"];
                $password_new = $data["password"];

                $user = Usuario::findIdentity(Yii::$app->session->get("PB_iduser"));

                if ($user->validatePassword($password_new)) {
                    /**Enter to the same password, then error */
                    $message = array(
                        "wtmessage" => "La antigua contraseña debe ser diferente a su contraseña actual",
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                } else {
                    $tpass = TipoPassword::findIdentity(1); // get Simple Password Type
                    $minPass = 8;

                    $regx = str_replace("VAR", $minPass, $tpass->tpas_validacion);
                    if (!preg_match($regx, $password_new)) {
                        $message = array(
                            "wtmessage" => "La contraseña no cumple con el nivel de seguridad minimo 8 caracteres y de " . $tpass->tpas_observacion,
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }

                    $user->generateAuthKey(); // generacion de hash
                    $user->setPassword($password_new);
                    $user->usu_upreg = '1';
                    if ($user->save()) {
                        $persona_model = Persona::findOne($per_id);
                        $persona_model->per_pri_nombre = $per_pri_nombre;
                        $persona_model->per_seg_nombre = $per_seg_nombre;
                        $persona_model->per_pri_apellido = $per_pri_apellido;
                        $persona_model->per_seg_apellido = $per_seg_apellido;
                        if ($type_doc == "CED") {
                            $persona_model->per_cedula = $dni;
                        } else if ($type_doc == "PASS") {
                            $persona_model->per_pasaporte = $dni;
                        } else if ($type_doc == "RUC") {
                            $persona_model->per_ruc = $dni;
                        }                        
                        $persona_model->per_correo = $per_correo;
                        $persona_model->per_genero = $per_genero;
                        $persona_model->per_nacionalidad = $per_nacionalidad;
                        $persona_model->pro_id_nacimiento = $pro_id_nacimiento;
                        $persona_model->eciv_id = $eciv_id;
                        $persona_model->per_celular = $per_celular;
                        $persona_model->per_nac_ecuatoriano = strval($per_nac_ecuatoriano);
                        $persona_model->etn_id = $etn_id;
                        $persona_model->per_fecha_nacimiento = $per_fecha_nacimiento;
                        $persona_model->pai_id_nacimiento = $pai_id_nacimiento;
                        $persona_model->can_id_nacimiento = $can_id;
                        $persona_model->tsan_id = $tsan_id;

                        /**Other ethnicity */
                        $persona_model->modificarOtraEtnia($per_id, $otra_etnia, "1");

                        if ($persona_model->save()) {
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                        }
                    }                    
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $ex);
            }
        }
    }

    public function actionAdmin() {

        $_SESSION['JSLANG']['Passwords are differents, please enter passwords again.'] = Yii::t("perfil",'Passwords are differents, please enter passwords again.');
        $model = new Provincia();
        
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            
            if (isset($data["persona"])) {
                $per_id = $data["per_id"];
                //$arr_persona = Persona::findOne($per_id)->asArray();
                $arr_persona = Persona::find()->where(["per_id" => $per_id])->asArray()->all();

                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', ['arr_persona'=> $arr_persona[0],'per_id'=>$per_id ]);
            }

            if (isset($data["pai_id"])) {
                $arr_pro = $model->provinciaXPais($data["pai_id"]); 
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', ['arr_pro'=>$arr_pro]);
            }

            if (isset($data["pro_id"])) {
                \app\models\Utilities::putMessageLogFile('ingresa');
                $arr_can  = Canton::find()
                    ->select(["can_id as id", "can_nombre as name"])
                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                    "pro_id" => $data["pro_id"]])->asArray()->all();

                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false',  ['arr_can'=> $arr_can]);
            }

            if (isset($data["type_doc"])) {
                $dni = Persona::getDNIbyTipoDoc($data["per_id"], $data["type_doc"]);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $dni);
            }
        }//if ajax

        $country_id       = 17;
        $pro_id           = Provincia::findOne(["pai_id" => $country_id, "pro_estado" => 1, "pro_estado_logico" => 1]);
        $per_id           = Yii::$app->session->get("PB_perid");
        $usu_id           = Yii::$app->session->get("PB_iduser");
        $user             = Usuario::findIdentity($usu_id);
        $persona          = Persona::findIdentity($per_id);
        $otra_etnia       = $persona->consultarOtraetnia($per_id);
        $arr_estudiantes  = Estudiante::getEstudiantes();
        $arr_pais         = Pais::find()->select("pai_id, pai_nombre")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->orderBy(['pai_nombre' => SORT_ASC])->asArray()->all();//Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
        $arr_pro          = Provincia::findAll(["pai_id" => ((isset($persona->pai_id_nacimiento) && $persona->pai_id_nacimiento != "")?($persona->pai_id_nacimiento):$country_id), "pro_estado" => 1, "pro_estado_logico" => 1]);
        $arr_can          = Canton::findAll(["pro_id" => ((isset($persona->pro_id_nacimiento) && $persona->pro_id_nacimiento != "")?($persona->pro_id_nacimiento):$pro_id->pro_id), "can_estado" => 1, "can_estado_logico" => 1]);
        $arr_estado_civil = EstadoCivil::find()->select("eciv_id as id, eciv_nombre as value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
        $arr_etnia        = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $arr_tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();

        return $this->render('admin', [
                    'user'             => $user,
                    'persona'          => $persona,
                    "otra_etnia"       => $otra_etnia['oetn_nombre'],
                    "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
                    "arr_etnia"        => ArrayHelper::map($arr_etnia, "id", "value"),
                    "arr_tipos_sangre" => ArrayHelper::map($arr_tipos_sangre, "id", "value"),
                    "arr_tipo_dni"     => array("CED" => Yii::t("formulario", "SSN"), "PASS" => Yii::t("formulario", "Passport")),
                    'arr_genero'       => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                    'arr_estudiantes'  => ArrayHelper::map(array_merge([["id" => "0", "nombre" => Yii::t("formulario", "Select")]], $arr_estudiantes), "id", "nombre"),
                    'arr_pais'         => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                    'arr_pro'          => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                    'arr_can'          => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                    /* 'arr_parr' => (empty(ArrayHelper::map($arr_parr, "par_id", "par_nombre"))) ? array(Yii::t("pais", "-- Select Parish --")) : (ArrayHelper::map($arr_parr, "par_id", "par_nombre")), */
        ]);            
    }  

    public function actionUpdateadmin() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {

                $per_id       = $data["per_id"];
                $per_correo   = $data["newemail"];
                $password_new = $data["password"];

                if (!empty($password_new)) {
                    //$userio = Usuario::findIdentity(Yii::$app->session->get("PB_iduser"));
                    $usuario = Usuario::find()->where(['per_id' => $per_id])->asArray()->one();
                    $usu_id  = $usuario['usu_id'];
                    $user = Usuario::findIdentity($usu_id);

                    if ($user->validatePassword($password_new)) {
                        // Introduzca la misma contraseña, entonces el error
                        $message = array(
                            "wtmessage" => "La antigua contraseña debe ser diferente a su contraseña actual",
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    } else {
                        $tpass = TipoPassword::findIdentity(1); // obtener el tipo de contraseña simple
                        $minPass = 8;

                        $regx = str_replace("VAR", $minPass, $tpass->tpas_validacion);
                        if (!preg_match($regx, $password_new)) {
                            $message = array(
                                "wtmessage" => "La contraseña no cumple con el nivel de seguridad minimo 8 caracteres y de " . $tpass->tpas_observacion,
                                "title" => Yii::t('jslang', 'Error'),
                            );
                            return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                        }                    
                        $user->generateAuthKey(); // generacion de hash
                        $user->setPassword($password_new);
                        $user->usu_upreg = '1';
                        if(trim($per_correo)!='')
                            $user->usu_user = $per_correo;
                        $user->save();
                    }
                }

                $persona_model = Persona::findOne($per_id);
                $correo        = $persona_model->per_correo;

                $persona_model->per_pri_nombre   = $data["first_nombre"];
                $persona_model->per_seg_nombre   = $data["second_name"];;
                $persona_model->per_pri_apellido = $data["first_apellido"];
                $persona_model->per_seg_apellido = $data["second_lastname"];

                if ($data["type_doc"] == "CED") {
                    $persona_model->per_cedula    = $data["dni"];
                } else if ($data["type_doc"] == "PASS") {
                    $persona_model->per_pasaporte = $data["dni"];
                } else if ($data["type_doc"] == "RUC") {
                    $persona_model->per_ruc = $data["dni"];
                }

                $persona_model->per_genero           = $data["gender"];
                $persona_model->per_nacionalidad     = $data["nationality"];
                $persona_model->pro_id_nacimiento    = $data["pro_id"];
                $persona_model->eciv_id              = $data["eciv_id"];
                $persona_model->per_celular          = $data["phone"];
                $persona_model->per_nac_ecuatoriano  = strval($data["nac_ecu"]);
                $persona_model->etn_id               = $data["etn_id"];
                $persona_model->per_fecha_nacimiento = $data["birth_date"];
                $persona_model->pai_id_nacimiento    = $data["pai_id"];
                $persona_model->can_id_nacimiento    = $data["can_id"];
                $persona_model->tsan_id              = $data["tsan_id"];
                $persona_model->per_domicilio_cpri   = $data["domicilio"];

                if(trim($per_correo)!='')
                    $persona_model->per_correo = $per_correo;             

                if ($persona_model->save()) {

                    if( $correo!= $persona_model->per_correo ||
                       !empty($password_new) ){


                        $link    = Url::base(true); 
                        $usuario = $persona_model->per_pri_nombre . " " . $persona_model->per_pri_apellido;
                        $body    = Utilities::getMailMessage("credentialsnew", 
                                        array("[[usuario]]"  => $usuario,
                                              "[[username]]" => $persona_model->per_correo, 
                                              "[[clave]]"    => $password_new, 
                                              "[[link]]"     => $link), Yii::$app->language);
                        
                        $tituloMensaje = Yii::t("interesado", "Registro en línea UTEG");
                        /*
                        \app\models\Utilities::putMessageLogFile('usuario: '. $usuario);
                        \app\models\Utilities::putMessageLogFile('persona_model->per_correo: '. $persona_model->per_correo);
                        \app\models\Utilities::putMessageLogFile('password_new: '. $password_new);
                        \app\models\Utilities::putMessageLogFile('correo: '. $correo);
                        \app\models\Utilities::putMessageLogFile('link: '. $link);
                        */
                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["contactoEmail"], [$correo => "$usuario"], $asunto, $body);
                    }

                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }
                   // }                    
                //}
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $ex);
            }
        }
    }//function actionUpdateadmin

}
