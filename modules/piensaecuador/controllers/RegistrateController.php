<?php

namespace app\modules\piensaecuador\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Provincia;
use app\models\Canton;
use app\modules\academico\models\NivelInstruccion;
use app\modules\marketing\models\Interes;
use app\modules\piensaecuador\models\PersonaExterna;
use app\modules\piensaecuador\models\Ocupacion;
use yii\helpers\Url;


class RegistrateController extends \yii\web\Controller {
    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    public function actionIndex() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getocupaciones"])) {
                $ocupaciones = Ocupacion::find()->select("ocu_id AS id, ocu_nombre AS name")->where(["ocu_estado_logico" => "1", "ocu_estado" => "1"])->asArray()->all();
                $message = array("ocupaciones" => $ocupaciones);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }         
        }
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $pais_id = 1; //Ecuador
        $arr_prov = Provincia::provinciaXPais($pais_id);
        $arr_ciu = Canton::cantonXProvincia(1);
        $mod_nivel= new NivelInstruccion();
        $arr_nivel = $mod_nivel->consultarNivelInstruccion();
        $mod_interes = new Interes();
        $arr_interes = $mod_interes->consultarInteres();
        $mod_perext = new PersonaExterna();
        $arr_evento = $mod_perext->consultarEvento();  
        $mod_ocupaciones = new Ocupacion();
        $arr_ocupaciones = $mod_ocupaciones->consultarOcupaciones();
        $_SESSION['JSLANG']['Your information has not been saved. Please try again.'] = Yii::t('notificaciones', 'Your information has not been saved. Please try again.');
        return $this->render('index', [                    
                    "arr_provincia" => ArrayHelper::map($arr_prov, "id", "value"),
                    "arr_ciudad" => ArrayHelper::map($arr_ciu, "id", "value"),
                    "arr_genero" => array("1" => Yii::t("formulario", "Female"), "2" => Yii::t("formulario", "Male")),
                    "arr_nivel" => ArrayHelper::map($arr_nivel, "id", "value"),
                    "arr_evento" => ArrayHelper::map($arr_evento, "id", "value"), //$arr_evento
                    "arr_interes" => $arr_interes,
                    "arr_ocupaciones" => ArrayHelper::map($arr_ocupaciones, "id", "value"),
                    "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
        ]);
    }  

    public function actionUpdate() {
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        return $this->render('update', array());
    }  
    
    public function actionSave() {
        $mod_perext = new PersonaExterna();
        $con = \Yii::$app->db_externo;
        $ip = \app\models\Utilities::getClientRealIP(); // obtiene la ip de la máquina.   
        if (Yii::$app->request->isAjax) {            
            $data = Yii::$app->request->post();                                  
            $transaction = $con->beginTransaction();
            try {  
                $respPerexiste = $mod_perext->consultarXIdentificacion($data["identificacion"]);
                \app\models\Utilities::putMessageLogFile('existe:'.$respPerexiste["existe"]);   
                \app\models\Utilities::putMessageLogFile('tipo ident:'.$data["tipoidentifica"]);   
                if (empty($respPerexiste["existe"])) {                    
                    $dataRegistro = array(                        
                        'pext_tipoidentifica'  => $data["tipoidentifica"],
                        'pext_identificacion'  => $data["identificacion"],
                        'pext_nombres'  => ucwords(strtolower($data["nombres"])),
                        'pext_apellidos'  => ucwords(strtolower($data["apellidos"])), 
                        'pext_correo'  => strtolower($data["correo"]), 
                        'pext_celular'  => $data["celular"], 
                        'pext_telefono'  => $data["telefono"], 
                        'pext_genero'  => $data["genero"], 
                        'pext_fechanac'  => $data["fechanac"], 
                        'nins_id'  => $data["niv_interes"], 
                        'pro_id'  => $data["pro_id"], 
                        'can_id'  => $data["can_id"], 
                        'eve_id'  => $data["eve_id"], 
                        'ocu_id'  => $data["ocu_id"], 
                        'pext_ip_registro'  => $ip, 
                    );                                   
                    $respPersext = $mod_perext->insertPersonaExterna($con, $dataRegistro);
                    if ($respPersext) {
                        //Verifica que existan intereses marcados.
                        $arrIntereses = $data["intereses"]; 
                        if (empty($arrIntereses)){
                            $mensaje = "Seleccione unos de los intereses.";
                            $exito = 0;
                        } else {
                            for ($a=0;$a<count($arrIntereses);$a++){   
                                $intereses = 'S';
                                $dataRegIntereses = array(
                                    'int_id'  => $arrIntereses[$a]["interes_id"],
                                    'pext_id'  => $respPersext,                             
                                );                   
                                $resIntereses = $mod_perext->insertPersonaExternaInteres($con, $dataRegIntereses);
                                if (!($resIntereses)) {
                                    $exito = 0;
                                } 
                            }
                            //Si hubieron marcadas de intereses.
                            if (($intereses=='S') and ($exito!='0')) {
                                $exito = 1;
                            } else {                                
                                $exito = 0;
                            }
                        }
                    }
                } else {                   
                    $mensaje = "Ya se encuentra registrado.";                    
                    $exito = 0;                    
                }
                if ($exito==1) {
                    $transaction->commit();
                    $mensaje = "Se ha registrado exitosamente, un agente de Admisiones se contactará con usted.";
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", $mensaje),
                        "title" => Yii::t('jslang', 'Success'),                        
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {                    
                    $transaction->rollBack();   
                    $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar ".$mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );                    
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);                                                                              
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
                $message = array(
                    "wtmessage" => $ex->getMessage(), Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Error'),
                );                                
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }            
        }
    }
}

