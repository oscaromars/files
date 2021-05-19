<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\Etnia;
use app\models\Pais;
use app\models\Provincia;
use app\models\Canton;
use app\models\Utilities;
use app\models\TipoSangre;
use app\models\Persona;
use app\models\Interesado;
use app\models\TipoParentesco;
use app\models\PersonaContacto;
use yii\helpers\ArrayHelper;
use app\models\EstadoCivil;

/**
 * 
 *
 * @author Kleber Loayza
 */
class FichaController extends \app\components\CController {

    public function actionUpdate() {
        $mod_pais = new Pais();
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
        }
        $data = Yii::$app->request->get();
        $per_id = base64_decode($data['perid']);
        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();
        $modperinteresado = new Persona();
        $respPerinteresado = $modperinteresado->consultaPersonaId($per_id);
        $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        if(empty($respPerinteresado['pai_id_nacimiento'])){
           $respPerinteresado['pai_id_nacimiento'] = 1; 
        }
        $arr_prov_nac = Provincia::provinciaXPais($respPerinteresado['pai_id_nacimiento']);
        if(empty($respPerinteresado['pro_id_nacimiento'])){
           $respPerinteresado['pro_id_nacimiento'] = 1; 
        }
        $arr_ciu_nac = Canton::cantonXProvincia($respPerinteresado['pro_id_nacimiento']);
        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_dom = Provincia::provinciaXPais($respPerinteresado['pai_id_domicilio']);
        $arr_ciu_dom = Canton::cantonXProvincia($respPerinteresado['pro_id_domicilio']);
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
                    "arr_pais_nac" => ArrayHelper::map($arr_pais_nac, "id", "value"),
                    "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                    "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    "respPerinteresado" => $respPerinteresado,
                    "respotraetnia" => $respotraetnia,
        ]);
    }
	 function actionGuardarficha() {
            if (Yii::$app->request->isAjax) {
                $per_class = new Persona();
                $data = Yii::$app->request->post();
                $per_id = $data["persona_id"];
                $per_pri_nombre = $data["pnombre_persona"];
                $per_seg_nombre = $data["snombre_persona"];
                $per_pri_apellido = $data["papellido_persona"];
                $per_seg_apellido = $data["sapellido_persona"];
                $per_genero = $data["genero_persona"];
                $etn_id = $data["etnia_persona"];
                $eciv_id = $data["ecivil_persona"];
                $per_fecha_nacimiento = $data["fnacimiento_persona"];
                $per_nacionalidad = $data["pnacionalidad"];
                $pai_id_nacimiento = $data["pais_persona"];
                $pro_id_nacimiento = $data["provincia_persona"];
                $can_id_nacimiento = $data["canton_persona"];
                $per_correo = $data["correo_persona"];
                $per_celular = $data["celular_persona"];
                $tsan_id = $data["tsangre_persona"];
                $per_nac_ecuatoriano = $data["nacecuador"];
                $pai_id_domicilio = $data["paisd_domicilio"];
                $pro_id_domicilio = $data["provinciad_domicilio"];
                $can_id_domicilio = $data["cantond_domicilio"];
                $per_domicilio_telefono = $data["telefono_domicilio"];
                $per_domicilio_sector = $data["sector_domicilio"];
                $per_domicilio_cpri = $data["callep_domicilio"];
                $per_domicilio_csec = $data["calls_domicilio"];
                $per_domicilio_num = $data["numero_domicilio"];
                $per_domicilio_ref = $data["referencia_domicilio"];
                try {
                    $exito=$per_class->modificaPersona(
                            $per_id, $per_pri_nombre, $per_seg_nombre, $per_pri_apellido, 
                            $per_seg_apellido, $etn_id, $eciv_id, $per_genero, $pai_id_nacimiento, 
                            $pro_id_nacimiento, $can_id_nacimiento, $per_fecha_nacimiento, 
                            $per_celular, $per_correo, $tsan_id, $per_domicilio_sector, 
                            $per_domicilio_cpri, $per_domicilio_csec, $per_domicilio_num, 
                            $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, 
                            $pro_id_domicilio, $can_id_domicilio, $per_nac_ecuatoriano, 
                            $per_nacionalidad, null);
                    if ($exito) {
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada."),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                        echo \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                        return;
                    } else {
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        echo \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                        return;
                    }
                } catch (Exception $ex) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al grabar.".$ex),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    echo \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    return;
                }
            }
        }
}
