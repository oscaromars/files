<?php

namespace app\modules\admision\controllers;

use Yii;
use app\modules\admision\models\Oportunidad;
use app\modules\admision\models\PersonaGestion;
use app\modules\admision\models\TipoOportunidadVenta;
use app\modules\admision\models\EstadoOportunidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\admision\models\PersonalAdmision;
use app\modules\academico\models\ModuloEstudio;
use app\models\Empresa;
use app\models\Utilities;
use yii\helpers\ArrayHelper;

class AgentesController extends \app\components\CController {

    public function actionReasignagente() {
        $opor_id = base64_decode($_GET["opor_id"]);
        $modoportunidad = new Oportunidad();
        $modagente = new PersonalAdmision();
        $respOportunidad = $modoportunidad->consultarOportunidadById($opor_id);
        $arra_contacto = $modoportunidad->ConsultarPersonaxGestion($respOportunidad["pges_id"]);
        $arra_agente = $modagente->consultarAgentereasigna($respOportunidad["padm_id"]);
        return $this->render('reasignagente', [
                    'arr_oportunidad' => $respOportunidad,
                    'arr_datosp' => $arra_contacto,
                    'arr_agente' => ArrayHelper::map($arra_agente, "id", "name"),
        ]);
    }

    public function actionView() {
        return $this->render('view', [
        ]);
    }

    public function actionEdit() {
        return $this->render('edit', [
        ]);
    }

    public function actionSavereasigna() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuario = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $nuevo_agente = $data["agente_nuevo"];
            $opor_id = $data["opor_id"];
            $con = \Yii::$app->db_crm;
            $transaction = $con->beginTransaction();
            try {
                $modagente = new PersonalAdmision();
                $respasigna = $modagente->actualizarAgenteOport($opor_id, $nuevo_agente, $usuario);
                if ($respasigna) {
                    $exito = 1;
                }
                if ($exito) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Se ha reasignado el agente."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al reasignar." . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionSave() {
        
    }

    public function actionUpdate() {
        
    }

}
