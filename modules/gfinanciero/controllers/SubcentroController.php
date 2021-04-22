<?php

namespace app\modules\gfinanciero\controllers;

use Yii;
use app\components\CController;
use app\models\ExportFile;
use app\modules\gfinanciero\models\SubCentro;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\models\Centro;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

class SubcentroController extends CController {

    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new SubCentro();
        $data = Yii::$app->request->get();
        $arr_tipo = Centro::findAll(['EST_LOG' => '1', 'EST_DEL' => '1']);
        $arr_tipo = ['0' => financiero::t('centro', '-- All Centers --')] + ArrayHelper::map($arr_tipo, "COD_CEN", "NOM_CEN");
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                "model" => $model->getAllItemsGrid($data["search"], $data['centro'], true),
                'arr_tipo' => $arr_tipo,
            ]);
        }
        /*if (Yii::$app->request->isAjax) { }*/
        return $this->render('index', [
            'model' => $model->getAllItemsGrid(NULL, NULL, true),
            'arr_tipo' => $arr_tipo,
        ]);
    }

    /**
     * View Action. Allow view the information about item from Index Action
     *
     * @return void
     */
    public function actionView() {
        $_SESSION['JSLANG']['Please select a Center Name.'] = financiero::t('centro', 'Please select a Center Name.');
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = SubCentro::findOne(['COD_SCEN' => $id,]);
            $arr_tipo = Centro::findAll(['EST_LOG' => '1', 'EST_DEL' => '1']);
            $arr_tipo = ['0' => financiero::t('centro', '-- Select a Center Name --')] + ArrayHelper::map($arr_tipo, "COD_CEN", "NOM_CEN");
            return $this->render('view', [
                'model' => $model,
                'arr_tipo' => $arr_tipo,
            ]);
        }
        return $this->redirect('index');
    }

    /**
     * Edit Action. Allow edit the information from View Action.
     *
     * @return void
     */
    public function actionEdit() {
        $_SESSION['JSLANG']['Please select a Center Name.'] = financiero::t('centro', 'Please select a Center Name.');
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = SubCentro::findOne(['COD_SCEN' => $id,]);
            $arr_tipo = Centro::findAll(['EST_LOG' => '1', 'EST_DEL' => '1']);
            $arr_tipo = ['0' => financiero::t('centro', '-- Select a Center Name --')] + ArrayHelper::map($arr_tipo, "COD_CEN", "NOM_CEN");
            return $this->render('edit', [
                'model' => $model,
                'arr_tipo' => $arr_tipo,
            ]);
        }
        return $this->redirect('index');
    }

    /**
     * New Action. Allow show the form to create a new item or Object y Data Model.
     *
     * @return void
     */
    public function actionNew() {
        //$new_id = TipoArticulo::getNextIdItemRecord();
        $_SESSION['JSLANG']['Please select a Center Name.'] = financiero::t('centro', 'Please select a Center Name.');
        $arr_tipo = Centro::findAll(['EST_LOG' => '1', 'EST_DEL' => '1']);
        $arr_tipo = ['0' => financiero::t('centro', '-- Select a Center Name --')] + ArrayHelper::map($arr_tipo, "COD_CEN", "NOM_CEN");
        return $this->render('new', [
            //'new_id' => $new_id,
            'arr_tipo' => $arr_tipo,
        ]);
    }

    /**
     * Save Action. Allow save the information from new Form.
     *
     * @return void
     */
    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = Yii::$app->session->get("PB_perid");
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $username = Yii::$app->session->get("PB_username");
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id = $data["id"];
                $nombre = $data["nombre"];
                $centro = $data['centro'];

                $model = new SubCentro();
                $model->COD_SCEN = $id;
                $model->NOM_SCEN = $nombre;
                $model->COD_CEN = $centro;
                $model->FEC_CRE = date('Y-m-d');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = "1";
                $model->EST_DEL = "1";
                //// body logic End

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );

                if ($model->save()) {
                    $trans->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    $arr_errs = $model->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
            } catch (\Exception $ex) {
                $exMsg = new CMsgException($ex);
                $trans->rollback();
                $msg = (($exMsg->getMsgLang()) != '')?($exMsg->getMsgLang()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
        return $this->redirect('index');
    }

    /**
     * Update Action. Allow to Update information from Edit form.
     *
     * @return void
     */
    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = Yii::$app->session->get("PB_perid");
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $username = Yii::$app->session->get("PB_username");
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id = $data["id"];
                $nombre = $data["nombre"];
                $centro = $data['centro'];

                $model = SubCentro::findOne(['COD_SCEN' => $id]);
                //$model->COD_SCEN = $id;
                $model->NOM_SCEN = $nombre;
                $model->COD_CEN = $centro;
                $model->FEC_MOD = date('Y-m-d');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = "1";


                //// body logic End

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    $trans->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    $arr_errs = $model->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
            } catch (\Exception $ex) {
                $exMsg = new CMsgException($ex);
                $trans->rollback();
                $msg = (($exMsg->getMsgLang()) != '')?($exMsg->getMsgLang()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
        return $this->redirect('index');
    }

    /**
     * Delete Action. Allow delete an item from Index form.
     *
     * @return void
     */
    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = Yii::$app->session->get("PB_perid");
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $username = Yii::$app->session->get("PB_username");
            $error = true;
            $trans = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id = $data["id"];
                $model = SubCentro::findOne(['COD_SCEN' => $id]);
                $model->FEC_MOD = date('Y-m-d');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                $model->EST_LOG = '0';
                $model->EST_DEL = '0';
                //// body logic begin

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    $trans->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    $arr_errs = $model->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
            } catch (\Exception $ex) {
                $exMsg = new CMsgException($ex);
                $trans->rollback();
                $msg = (($exMsg->getMsgLang()) != '')?($exMsg->getMsgLang()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
        return $this->redirect('index');
    }

    /**
     * Expexcel Action. Allow to download a Excel document from index page.
     *
     * @return void
     */
    public function actionExpexcel() {
        ini_set('memory_limit', Yii::$app->params['memorylimit']);
      $content_type = Utilities::mimeContentType("xls");
      $nombarch = "Report-" . date("YmdHis") . ".xls";
      header("Content-Type: $content_type");
      header("Content-Disposition: attachment;filename=" . $nombarch);
      header('Cache-Control: max-age=0');
      $colPosition = array("C", "D", "E", "F");
      $arrHeader = array(
        financiero::t("subcentro", "Sub Center Code"),
        financiero::t("subcentro", "Sub Center Name"),
        financiero::t("centro", "Center Name"),
      );
      $data = Yii::$app->request->get();
      $arrSearch = array();
      if (count($data) > 0) {
          $arrSearch["search"] = $data['search'];
          $arrSearch["type"] = ($data['type'] !="0")?$data['type']:NULL;
      }
      $arrData = array();
      $model = new SubCentro();
      if (count($arrSearch) > 0) {
          $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["type"], false, true);
      } else {
          $arrData = $model->getAllItemsGrid(NULL, NULL, false, true);
      }
      $nameReport = financiero::t("subcentro", "Report Sub Center Items");
      Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
      exit;
    }

    /**
     * Exppdf Action. Allow to download a Pdf document from index page.
     *
     * @return void
     */
    public function actionExppdf() {
        //ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $report = new ExportFile();
        $this->view->title = financiero::t("subcentro", "Report Sub Center Items");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("subcentro", "Sub Center Code"),
            financiero::t("subcentro", "Sub Center Name"),
            financiero::t("centro", "Center Name"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["type"] = ($data['type'] != "0")?$data['type']:NULL;
        }
        $arrData = array();
        $model = new SubCentro();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["type"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, false, true);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
}
