<?php

namespace app\modules\formulario\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Provincia;
use app\models\Canton;
use app\modules\formulario\models\PersonaFormulario;
use app\modules\academico\models\UnidadAcademica;
use app\modules\admision\models\Institucion;
use yii\helpers\Url;

class RegistrateController extends \yii\web\Controller {
    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    public function actionIndex() {
        $mod_unidad = new UnidadAcademica();
        $mod_PersForm = new PersonaFormulario();
        $mod_institucion = new Institucion();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }     
            if (isset($data["getcarrera"])) {
                $uaca_id = $data["uaca_id"];
                $carrera_programa = $mod_PersForm->consultarCarreraProgXUnidad($uaca_id);   
                $message = array("carr_prog" => $carrera_programa);
                echo Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }        
        }
        $this->layout = '@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/basic.php';
        $pais_id = 1; //Ecuador
        $arr_prov = Provincia::provinciaXPais($pais_id);
        $arr_ciu = Canton::cantonXProvincia(1);
        $arr_unidad_Uteg = $mod_unidad->consultarUnidadAcademicasxUteg();   
        $arr_carrera_prog = $mod_PersForm->consultarCarreraProgXUnidad(1);   
        $arr_institucion = $mod_institucion->consultarInstituciones(1);
        $_SESSION['JSLANG']['Your information has not been saved. Please try again.'] = Yii::t('notificaciones', 'Your information has not been saved. Please try again.');
        return $this->render('index', [                    
                    "arr_provincia" => ArrayHelper::map($arr_prov, "id", "value"),
                    "arr_ciudad" => ArrayHelper::map($arr_ciu, "id", "value"),                    
                    "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "arr_unidad" => ArrayHelper::map($arr_unidad_Uteg, "id", "name"),
                    "arr_carrera_prog" => ArrayHelper::map($arr_carrera_prog, "id", "name"),
                    "arr_institucion" => ArrayHelper::map($arr_institucion, "id", "name"),
        ]);
    }     
    
    public function actionSave() {
        $mod_perform = new PersonaFormulario();
        $con = \Yii::$app->db_externo;        
        if (Yii::$app->request->isAjax) {            
            $data = Yii::$app->request->post();                                  
            $transaction = $con->beginTransaction();
            try {  
                $respPerexiste = $mod_perform->consultarXIdentificacion($data["identificacion"]);
                \app\models\Utilities::putMessageLogFile('existe:'.$respPerexiste["existe"]);   
                \app\models\Utilities::putMessageLogFile('tipo ident:'.$data["tipoidentifica"]);   
                if (empty($respPerexiste["existe"])) {                    
                    $dataRegistro = array(                        
                        'pfor_tipoidentifica'  => $data["tipoidentifica"],
                        'pfor_identificacion'  => $data["identificacion"],
                        'pfor_nombres'  => ucwords(strtolower($data["nombres"])),
                        'pfor_apellidos'  => ucwords(strtolower($data["apellidos"])), 
                        'pfor_correo'  => strtolower($data["correo"]), 
                        'pfor_celular'  => $data["celular"], 
                        'pfor_telefono'  => $data["telefono"],                                                 
                        'pro_id'  => $data["pro_id"], 
                        'can_id'  => $data["can_id"], 
                        'pfor_institucion' => ucwords(strtolower($data["institucion"])), 
                        'uaca_id' => $data["unidad"], 
                        'eaca_id' => $data["carrera_programa"], 
                        'pfor_est_ant' => $data["estudio_anterior"],                         
                        'ins_id' => $data["institucion_acad"], 
                        'pfor_carrera_ant' => ucwords(strtolower($data["carrera_ant"])), 
                    );                                   
                    $respPersform = $mod_perform->insertPersonaFormulario($con, $dataRegistro);
                    if ($respPersform) {                        
                        $exito = 1;
                    } else {                                
                        $exito = 0;
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
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar. ".$mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );                    
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error. "), false, $message);                                                                              
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
                $message = array(
                    "wtmessage" => $ex->getMessage(), Yii::t("notificaciones", "Error al grabar. "),
                    "title" => Yii::t('jslang', 'Error'),
                );                                
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }            
        }
    }
}

