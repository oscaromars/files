<?php

/* 
 * The PenBlu framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by PenBlu Software (http://www.penblu.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions 
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of PenBlu Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PenBlu is based on code by 
 * Yii Software LLC (http://www.yiisoft.com) Copyright © 2008
 *
 */

namespace app\modules\gfinanciero\controllers;

use Yii;
use app\components\CController;
use app\models\Canton;
use app\models\ConfiguracionSeguridad;
use app\models\EmpresaPersona;
use app\models\EstadoCivil;
use app\models\Etnia;
use app\models\ExportFile;
use app\models\Grupo;
use app\models\GrupRol;
use app\models\Pais;
use app\models\Persona;
use app\models\Provincia;
use app\models\TipoPassword;
use app\models\TipoSangre;
use app\models\UsuaGrolEper;
use app\models\Usuario;
use app\modules\gfinanciero\models\Empleado;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\models\Banco;
use app\modules\gfinanciero\models\Cargo;
use app\modules\gfinanciero\models\Catalogo;
use app\modules\gfinanciero\models\Departamentos;
use app\modules\gfinanciero\models\Discapacidad;
use app\modules\gfinanciero\models\EmpleadoCargo;
use app\modules\gfinanciero\models\GEmpleado;
use app\modules\gfinanciero\models\SubDepartamento;
use app\modules\gfinanciero\models\TipoContrato;
use app\modules\gfinanciero\models\TipoContribuyente;
use app\modules\gfinanciero\models\TipoEmpleado;
use app\modules\gfinanciero\Module as financiero;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;

financiero::registerTranslations();

class EmpleadoController extends CController {

    protected $widthImg = "141";
    protected $heightImg = "193";
    protected $perDefFoto = '/uploads/ficha/silueta_default.jpeg';
    protected $fichaDir = 'ficha/';
    protected $limitSizeFile = "8000000"; // 8 MB
    protected $extensionAvailable = "jpeg,png,jpg"; // pdf, jpeg, png
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new Empleado();
        $data = Yii::$app->request->post();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getsubdep"])) {
                $dep_id = $data['dep_id'];
                $subDeps = SubDepartamento::find()->select("sdep_id AS id, sdep_nombre AS name")->where(["sdep_estado_logico" => "1", "sdep_estado" => "1", 'dep_id' => $dep_id,])->asArray()->all();
                $message = array("subdeps" => $subDeps);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data['getDataEmpleado'])){
                $code = $data['id'];
                $arrEmpleado = Empleado::getPersonaByCode($code);
                $message = array("empleado" => $arrEmpleado);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data['getSalario'])){
                $id = $data['id'];
                $modelCargo = Cargo::findOne(['carg_id' => $id]);
                $message = array("salario" => (isset($modelCargo->carg_sueldo))?($modelCargo->carg_sueldo):0);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data['getRoles'])){
                $gru_id = $data['id'];
                $grupo_model = Grupo::findOne($gru_id);
                $model_confSeg = ConfiguracionSeguridad::findOne($grupo_model->cseg_id);
                $model_tPass = TipoPassword::findOne($model_confSeg->tpas_id);
                $arr_roles = GrupRol::find()
                    ->joinWith('rol r', false, 'INNER JOIN')
                    ->select('r.rol_id as id, r.rol_nombre as name')
                    ->where(['gru_id' => $gru_id, "grol_estado" => 1, "grol_estado_logico" => 1, "r.rol_estado" => 1, "r.rol_estado_logico" => 1])
                    ->asArray()
                    ->all();
                $message = array(
                    "roles" => $arr_roles, 
                    'seg_desc' => $model_tPass->tpas_observacion,//$model_confSeg->cseg_descripcion, 
                    'long_pass' => $model_confSeg->cseg_long_pass, 
                    'reg_pass' => $model_tPass->tpas_validacion,
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        if($data['ls_query_id'] == "autocomplete-empleado"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Empleado::getDataPersonaColumnsQueryWidget();
            return SearchAutocomplete::renderView($query, 
                $arr_data['con'], 
                $arr_data['cols'], 
                $arr_data['aliasCols'],
                $arr_data['colVisible'],
                $arr_data['table'], 
                $arr_data['where'], 
                $arr_data['order'], 
                $arr_data['limitPages'], 
                $currentPage, 
                $perPage);
        }
        if($data['ls_query_id'] == "autocomplete-cuenta"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Catalogo::getDataColumnsQueryWidget();
            return SearchAutocomplete::renderView($query, 
                $arr_data['con'], 
                $arr_data['cols'], 
                $arr_data['aliasCols'],
                $arr_data['colVisible'],
                $arr_data['table'], 
                $arr_data['where'], 
                $arr_data['order'], 
                $arr_data['limitPages'], 
                $currentPage, 
                $perPage);
        }
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                "model" => $model->getAllItemsGrid($data["search"], true),
            ]);
        }
        /*if (Yii::$app->request->isAjax) { }*/
        return $this->render('index', [
            'model' => $model->getAllItemsGrid(NULL, true),
        ]);
    }
    
    /**
     * View Action. Allow view the information about item from Index Action
     *
     * @return void
     */
    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $model = Empleado::findOne(['empl_codigo' => $id,]);
            $modelPer = Persona::findOne(['per_id' => $model->per_id]);
            $modelGemp = GEmpleado::findOne(['COD_EMP' => $id,]);
            $modelUser = Usuario::findOne(['per_id' => $model->per_id, 'usu_estado' => '1', 'usu_estado_logico' => '1', ]);
            $model_EmpPer = EmpresaPersona::findOne(['per_id' => $model->per_id, 'emp_id' => $emp_id, 'eper_estado' => '1', 'eper_estado_logico' => '1',]);
            $model_usuaGrolEper = UsuaGrolEper::findOne(['eper_id' => $model_EmpPer->eper_id, 'usu_id' => $modelUser->usu_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1',]);
            $model_Grol = GrupRol::findOne(['grol_id' => $model_usuaGrolEper->grol_id]);
            $modelEmpleadoCargo = EmpleadoCargo::findOne(['empl_codigo' => $id, 'ecarg_estado' => '1', 'ecarg_estado_logico' => '1',]);
            $pai_nac_id = $modelPer->pai_id_nacimiento;
            $pai_dom_id = $modelPer->pai_id_domicilio;
            $pro_nac_id = $modelPer->pro_id_nacimiento;
            $pro_dom_id = $modelPer->pro_id_domicilio;
            $can_nac_id = $modelPer->can_id_nacimiento;
            $can_dom_id = $modelPer->can_id_domicilio;
            $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
            $arr_prov_nac = Provincia::provinciaXPais($pai_nac_id);
            $arr_ciu_nac = Canton::cantonXProvincia($pro_nac_id);
            $arr_prov_dom = Provincia::provinciaXPais($pai_dom_id);
            $arr_ciu_dom = Canton::cantonXProvincia($pro_dom_id);
            $arr_civil = EstadoCivil::find()->select("eciv_id as id, eciv_nombre as value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
            $arr_cargo = Cargo::find()->select("carg_id AS id, carg_nombre AS value, carg_sueldo AS salario")->where(["carg_estado_logico" => "1", "carg_estado" => "1", ])->asArray()->all();
            $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();
            $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
            $arr_banco = Banco::find()->select("IDS_BAN AS id, NOM_BAN AS value")->where(["EST_LOG" => "1", "EST_DEL" => "1"])->asArray()->all();
            $inventario_arr = Catalogo::findOne(['COD_CTA' => $model->empl_cuenta_contable, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $sdep_id = $model->sdep_id;
            $modelSubDepar = SubDepartamento::findOne(['sdep_id' => $sdep_id]);
            $arr_departamento = Departamentos::find()->select("dep_id AS id, dep_nombre AS value")->where(["dep_estado_logico" => "1", "dep_estado" => "1"])->asArray()->all();
            $dep_id = $modelSubDepar->dep_id;
            $arr_subdepartamento = SubDepartamento::find()->select("sdep_id AS id, sdep_nombre AS value")->where(["sdep_estado_logico" => "1", "sdep_estado" => "1", 'dep_id' => $dep_id,])->asArray()->all();
            $arr_tipoContrato = TipoContrato::find()->select("tipc_id AS id, tipc_nombre AS value")->where(["tipc_estado_logico" => "1", "tipc_estado" => "1",])->asArray()->all();
            $arr_tipoContribuyente = TipoContribuyente::find()->select("COD_CON AS id, NOM_CON AS value")->where(["EST_LOG" => "1", "EST_DEL" => "1",])->asArray()->all();
            $arr_discapacidad = Discapacidad::find()->select("dis_id AS id, dis_nombre AS value")->where(["dis_estado" => "1", "dis_estado_logico" => "1",])->asArray()->all();
            $arr_grupo = Grupo::find()->select("gru_id AS id, gru_nombre AS value")->where(["gru_estado" => "1", "gru_estado_logico" => "1",])->asArray()->all();
            $arr_t_empleado =  TipoEmpleado::find()->select("tipe_id AS id, tipe_nombre AS value")->where(["tipe_estado" => "1", "tipe_estado_logico" => "1",])->asArray()->all();
            $gru_id = $model_Grol->gru_id;
            $model_confSeg = ConfiguracionSeguridad::findOne($gru_id);
            $model_tPass = TipoPassword::findOne($model_confSeg->tpas_id);
            $arr_roles = GrupRol::find()
                        ->joinWith('rol r', false, 'INNER JOIN')
                        ->select('r.rol_id as id, r.rol_nombre as value')
                        ->where(['gru_id' => $gru_id, "grol_estado" => 1, "grol_estado_logico" => 1, "r.rol_estado" => 1, "r.rol_estado_logico" => 1])
                        ->asArray()
                        ->all();

            return $this->render('view', [
                //'new_id' => $new_id,
                'model' => $model,
                'modelGemp' => $modelGemp,
                'modelUser' => $modelUser,
                'modelPer' => $modelPer,
                'userGroup' => $model_Grol->gru_id,
                'userRol' => $model_Grol->rol_id,
                'dep_id' => $dep_id,
                'sdep_id' => $sdep_id,

                "arr_genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                "arr_civil" => ArrayHelper::map($arr_civil, "id", "value"),
                'arr_cargo' => ArrayHelper::map($arr_cargo, "id", "value"),
                'cargo' => $modelEmpleadoCargo->carg_id,
                'salario' => number_format($modelEmpleadoCargo->ecarg_sueldo, 2, '.', ','),
                "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),
                "pai_nac_id" => $pai_nac_id,
                "pro_nac_id" => $pro_nac_id,
                "can_nac_id" => $can_nac_id,
                "pai_dom_id" => $pai_dom_id,
                "pro_dom_id" => $pro_dom_id,
                "can_dom_id" => $can_dom_id,
                "arr_pais_nac" => ArrayHelper::map($arr_pais_nac, "id", "value"),
                "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                "arr_pais_dom" => ArrayHelper::map($arr_pais_nac, "id", "value"),
                "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),

                'etnica' => ArrayHelper::map($arr_etnia, "id", "value"),
                'arr_t_empleado' => ArrayHelper::map($arr_t_empleado, "id", "value"),
                "widthImg" => $this->widthImg,
                "heightImg" => $this->heightImg,
                'arr_banco' => ArrayHelper::map($arr_banco, "id", "value"),
                'tipo_pago' => Empleado::getMetodosPagos(),
                'cuenta_code' => $inventario_arr->COD_CTA,
                'cuenta_name' => $inventario_arr->NOM_CTA,
                'arr_departamento' => ArrayHelper::map($arr_departamento, "id", "value"),
                'arr_subdepartamento' => ArrayHelper::map($arr_subdepartamento, "id", "value"),
                'per_foto' => (isset($modelPer->per_foto) && $modelPer->per_foto != '')?$modelPer->per_foto:$this->perDefFoto,
                'arr_tipoContrato' => ArrayHelper::map($arr_tipoContrato, "id", "value"),
                'arr_tipoContribuyente' => ArrayHelper::map($arr_tipoContribuyente, "id", "value"),
                'arr_discapacidad' => ArrayHelper::map($arr_discapacidad, "id", "value"),
                'limitSizeFile' => $this->limitSizeFile,
                'arr_grupo' => ArrayHelper::map($arr_grupo, "id", "value"),
                'arr_rol' => ArrayHelper::map($arr_roles, "id", "value"),
                'reg_pass' => $model_tPass->tpas_validacion,
                'long_pass' => $model_confSeg->cseg_long_pass,
                'desc_pass' => $model_tPass->tpas_observacion, //$model_confSeg->cseg_descripcion,
            ]); 
        }else if(isset($data['download'])){
            if (Yii::$app->session->get('PB_isuser')) {
                $tipo = $data['tipo'];
                $code = $data['code'];
                $model = Empleado::findOne(['empl_codigo' => $code, 'empl_estado' => '1', 'empl_estado_logico' => '1']);
                $file = "";
                if($model){
                    $filename = str_replace(" ", "", $model->empl_nombre) . "_" . $model->empl_codigo . "_";
                    switch($tipo){
                        case "ced":
                            $file = $model->empl_ruta_cedula;
                            $filename .= "ced";
                            break;
                        case "con":
                            $file = $model->empl_ruta_contrato;
                            $filename .= "con";
                            break;
                        case "avi":
                            $file = $model->empl_ruta_aviso_entrada;
                            $filename .= "avi";
                            break;
                    }
                    $arrFile = explode(".", basename($file));
                    $typeFile = strtolower($arrFile[count($arrFile) - 1]);

                    $route = str_replace("../", "", $file);
                    $url_file = Yii::$app->basePath . $route;
                    if (file_exists($url_file)) {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: " . Utilities::mimeContentType(basename($file)));
                        header('Content-Disposition: attachment; filename="' . $filename . "." . $typeFile .'";');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_file));
                        readfile($url_file);
                    }
                }
            }
            exit();
        }
        return $this->redirect('index');
    }
    
    /**
     * Edit Action. Allow edit the information from View Action.
     *
     * @return void
     */
    public function actionEdit() {
        $data = Yii::$app->request->get();
        $_SESSION['JSLANG']['Please attach a File Name in format {format}.'] = financiero::t('empleado', 'Please attach a File Name in format {format}.');
        $_SESSION['JSLANG']['Password are differents. Please enter passwords again.'] = financiero::t('empleado', 'Password are differents. Please enter passwords again.');
        $_SESSION['JSLANG']["Password does not meet the Security Group Criteria."] = financiero::t('empleado', "Password does not meet the Security Group Criteria.");
        $_SESSION['JSLANG']["Photo"] = Yii::t('formulario', "Photo");
        if (isset($data['id'])) {
            $id = $data['id'];
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $model = Empleado::findOne(['empl_codigo' => $id,]);
            $modelPer = Persona::findOne(['per_id' => $model->per_id]);
            $modelGemp = GEmpleado::findOne(['COD_EMP' => $id,]);
            $modelUser = Usuario::findOne(['per_id' => $model->per_id, 'usu_estado' => '1', 'usu_estado_logico' => '1', ]);
            $model_EmpPer = EmpresaPersona::findOne(['per_id' => $model->per_id, 'emp_id' => $emp_id, 'eper_estado' => '1', 'eper_estado_logico' => '1',]);
            $model_usuaGrolEper = UsuaGrolEper::findOne(['eper_id' => $model_EmpPer->eper_id, 'usu_id' => $modelUser->usu_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1',]);
            $model_Grol = GrupRol::findOne(['grol_id' => $model_usuaGrolEper->grol_id]);
            $modelEmpleadoCargo = EmpleadoCargo::findOne(['empl_codigo' => $id, 'ecarg_estado' => '1', 'ecarg_estado_logico' => '1',]);
            $pai_nac_id = $modelPer->pai_id_nacimiento;
            $pai_dom_id = $modelPer->pai_id_domicilio;
            $pro_nac_id = $modelPer->pro_id_nacimiento;
            $pro_dom_id = $modelPer->pro_id_domicilio;
            $can_nac_id = $modelPer->can_id_nacimiento;
            $can_dom_id = $modelPer->can_id_domicilio;
            $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
            $arr_prov_nac = Provincia::provinciaXPais($pai_nac_id);
            $arr_ciu_nac = Canton::cantonXProvincia($pro_nac_id);
            $arr_prov_dom = Provincia::provinciaXPais($pai_dom_id);
            $arr_ciu_dom = Canton::cantonXProvincia($pro_dom_id);
            $arr_civil = EstadoCivil::find()->select("eciv_id as id, eciv_nombre as value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
            $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();
            $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
            $arr_banco = Banco::find()->select("IDS_BAN AS id, NOM_BAN AS value")->where(["EST_LOG" => "1", "EST_DEL" => "1"])->asArray()->all();
            $arr_cargo = Cargo::find()->select("carg_id AS id, carg_nombre AS value, carg_sueldo AS salario")->where(["carg_estado_logico" => "1", "carg_estado" => "1", ])->asArray()->all();
            $inventario_arr = Catalogo::findOne(['COD_CTA' => $model->empl_cuenta_contable, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $sdep_id = $model->sdep_id;
            $modelSubDepar = SubDepartamento::findOne(['sdep_id' => $sdep_id]);
            $arr_departamento = Departamentos::find()->select("dep_id AS id, dep_nombre AS value")->where(["dep_estado_logico" => "1", "dep_estado" => "1"])->asArray()->all();
            $dep_id = $modelSubDepar->dep_id;
            $arr_subdepartamento = SubDepartamento::find()->select("sdep_id AS id, sdep_nombre AS value")->where(["sdep_estado_logico" => "1", "sdep_estado" => "1", 'dep_id' => $dep_id,])->asArray()->all();
            $arr_tipoContrato = TipoContrato::find()->select("tipc_id AS id, tipc_nombre AS value")->where(["tipc_estado_logico" => "1", "tipc_estado" => "1",])->asArray()->all();
            $arr_tipoContribuyente = TipoContribuyente::find()->select("COD_CON AS id, NOM_CON AS value")->where(["EST_LOG" => "1", "EST_DEL" => "1",])->asArray()->all();
            $arr_discapacidad = Discapacidad::find()->select("dis_id AS id, dis_nombre AS value")->where(["dis_estado" => "1", "dis_estado_logico" => "1",])->asArray()->all();
            $arr_grupo = Grupo::find()->select("gru_id AS id, gru_nombre AS value")->where(["gru_estado" => "1", "gru_estado_logico" => "1",])->asArray()->all();
            $arr_t_empleado =  TipoEmpleado::find()->select("tipe_id AS id, tipe_nombre AS value")->where(["tipe_estado" => "1", "tipe_estado_logico" => "1",])->asArray()->all();
            $gru_id = $model_Grol->gru_id;
            $model_confSeg = ConfiguracionSeguridad::findOne($gru_id);
            $model_tPass = TipoPassword::findOne($model_confSeg->tpas_id);
            $arr_roles = GrupRol::find()
                        ->joinWith('rol r', false, 'INNER JOIN')
                        ->select('r.rol_id as id, r.rol_nombre as value')
                        ->where(['gru_id' => $gru_id, "grol_estado" => 1, "grol_estado_logico" => 1, "r.rol_estado" => 1, "r.rol_estado_logico" => 1])
                        ->asArray()
                        ->all();

            return $this->render('edit', [
                //'new_id' => $new_id,
                'model' => $model,
                'modelGemp' => $modelGemp,
                'modelUser' => $modelUser,
                'modelPer' => $modelPer,
                'userGroup' => $model_Grol->gru_id,
                'userRol' => $model_Grol->rol_id,
                'dep_id' => $dep_id,
                'sdep_id' => $sdep_id,

                "arr_genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
                "arr_civil" => ArrayHelper::map($arr_civil, "id", "value"),
                'arr_cargo' => ArrayHelper::map($arr_cargo, "id", "value"),
                'cargo' => $modelEmpleadoCargo->carg_id,
                'salario' => number_format($modelEmpleadoCargo->ecarg_sueldo, 2, '.', ','),
                "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),
                "pai_nac_id" => $pai_nac_id,
                "pro_nac_id" => $pro_nac_id,
                "can_nac_id" => $can_nac_id,
                "pai_dom_id" => $pai_dom_id,
                "pro_dom_id" => $pro_dom_id,
                "can_dom_id" => $can_dom_id,
                "arr_pais_nac" => ArrayHelper::map($arr_pais_nac, "id", "value"),
                "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
                "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                "arr_pais_dom" => ArrayHelper::map($arr_pais_nac, "id", "value"),
                "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),

                'etnica' => ArrayHelper::map($arr_etnia, "id", "value"),
                'arr_t_empleado' => ArrayHelper::map($arr_t_empleado, "id", "value"),
                "widthImg" => $this->widthImg,
                "heightImg" => $this->heightImg,
                'arr_banco' => ArrayHelper::map($arr_banco, "id", "value"),
                'tipo_pago' => Empleado::getMetodosPagos(),
                'cuenta_code' => $inventario_arr->COD_CTA,
                'cuenta_name' => $inventario_arr->NOM_CTA,
                'arr_departamento' => ArrayHelper::map($arr_departamento, "id", "value"),
                'arr_subdepartamento' => ArrayHelper::map($arr_subdepartamento, "id", "value"),
                'per_foto' => (isset($modelPer->per_foto) && $modelPer->per_foto != '')?$modelPer->per_foto:$this->perDefFoto,
                'arr_tipoContrato' => ArrayHelper::map($arr_tipoContrato, "id", "value"),
                'arr_tipoContribuyente' => ArrayHelper::map($arr_tipoContribuyente, "id", "value"),
                'arr_discapacidad' => ArrayHelper::map($arr_discapacidad, "id", "value"),
                'limitSizeFile' => $this->limitSizeFile,
                'arr_grupo' => ArrayHelper::map($arr_grupo, "id", "value"),
                'arr_rol' => ArrayHelper::map($arr_roles, "id", "value"),
                'reg_pass' => $model_tPass->tpas_validacion,
                'long_pass' => $model_confSeg->cseg_long_pass,
                'desc_pass' => $model_tPass->tpas_observacion, //$model_confSeg->cseg_descripcion,
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
        $_SESSION['JSLANG']['Please attach a File Name in format {format}.'] = financiero::t('empleado', 'Please attach a File Name in format {format}.');
        $_SESSION['JSLANG']['Password are differents. Please enter passwords again.'] = financiero::t('empleado', 'Password are differents. Please enter passwords again.');
        $_SESSION['JSLANG']["Password does not meet the Security Group Criteria."] = financiero::t('empleado', "Password does not meet the Security Group Criteria.");
        $_SESSION['JSLANG']["Photo"] = Yii::t('formulario', "Photo");
        $pais_id = 1; //Ecuador
        $arr_pais_nac = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $arr_prov_nac = Provincia::provinciaXPais($pais_id);
        $arr_ciu_nac = Canton::cantonXProvincia($arr_prov_nac[0]['id']);
        $arr_civil = EstadoCivil::find()->select("eciv_id as id, eciv_nombre as value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();
        $tipos_sangre = TipoSangre::find()->select("tsan_id AS id, tsan_nombre AS value")->where(["tsan_estado_logico" => "1", "tsan_estado" => "1"])->asArray()->all();
        $arr_etnia = Etnia::find()->select("etn_id AS id, etn_nombre AS value")->where(["etn_estado_logico" => "1", "etn_estado" => "1"])->asArray()->all();
        $arr_banco = Banco::find()->select("IDS_BAN AS id, NOM_BAN AS value")->where(["EST_LOG" => "1", "EST_DEL" => "1"])->asArray()->all();
        $inventario_arr = Catalogo::findOne(['COD_CTA' => '101010101', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $arr_departamento = Departamentos::find()->select("dep_id AS id, dep_nombre AS value")->where(["dep_estado_logico" => "1", "dep_estado" => "1"])->asArray()->all();
        $dep_id = $arr_departamento[0]['id'];
        $arr_subdepartamento = SubDepartamento::find()->select("sdep_id AS id, sdep_nombre AS value")->where(["sdep_estado_logico" => "1", "sdep_estado" => "1", 'dep_id' => $dep_id,])->asArray()->all();
        $arr_cargo = Cargo::find()->select("carg_id AS id, carg_nombre AS value, carg_sueldo AS salario")->where(["carg_estado_logico" => "1", "carg_estado" => "1", ])->asArray()->all();
        $arr_tipoContrato = TipoContrato::find()->select("tipc_id AS id, tipc_nombre AS value")->where(["tipc_estado_logico" => "1", "tipc_estado" => "1",])->asArray()->all();
        $arr_tipoContribuyente = TipoContribuyente::find()->select("COD_CON AS id, NOM_CON AS value")->where(["EST_LOG" => "1", "EST_DEL" => "1",])->asArray()->all();
        $arr_discapacidad = Discapacidad::find()->select("dis_id AS id, dis_nombre AS value")->where(["dis_estado" => "1", "dis_estado_logico" => "1",])->asArray()->all();
        $arr_grupo = Grupo::find()->select("gru_id AS id, gru_nombre AS value")->where(["gru_estado" => "1", "gru_estado_logico" => "1",])->asArray()->all();
        $arr_t_empleado =  TipoEmpleado::find()->select("tipe_id AS id, tipe_nombre AS value")->where(["tipe_estado" => "1", "tipe_estado_logico" => "1",])->asArray()->all();
        $gru_id = $arr_grupo[0]['id'];
        $model_confSeg = ConfiguracionSeguridad::findOne($gru_id);
        $model_tPass = TipoPassword::findOne($model_confSeg->tpas_id);
        $arr_roles = GrupRol::find()
                    ->joinWith('rol r', false, 'INNER JOIN')
                    ->select('r.rol_id as id, r.rol_nombre as value')
                    ->where(['gru_id' => $gru_id, "grol_estado" => 1, "grol_estado_logico" => 1, "r.rol_estado" => 1, "r.rol_estado_logico" => 1])
                    ->asArray()
                    ->all();

        return $this->render('new', [
            //'new_id' => $new_id,
            "arr_genero" => array("M" => Yii::t("formulario", "Male"), "F" => Yii::t("formulario", "Female")),
            "arr_civil" => ArrayHelper::map($arr_civil, "id", "value"),
            'arr_cargo' => ArrayHelper::map($arr_cargo, "id", "value"),
            'salario' => number_format($arr_cargo[0]['salario'], 2, '.', ','),
            "tipos_sangre" => ArrayHelper::map($tipos_sangre, "id", "value"),
            "arr_pais_nac" => ArrayHelper::map($arr_pais_nac, "id", "value"),
            "pai_id" => $pais_id,
            "arr_prov_nac" => ArrayHelper::map($arr_prov_nac, "id", "value"),
            "arr_ciu_nac" => ArrayHelper::map($arr_ciu_nac, "id", "value"),
            'etnica' => ArrayHelper::map($arr_etnia, "id", "value"),
            'arr_t_empleado' => ArrayHelper::map($arr_t_empleado, "id", "value"),
            "widthImg" => $this->widthImg,
            "heightImg" => $this->heightImg,
            'arr_banco' => ArrayHelper::map($arr_banco, "id", "value"),
            'tipo_pago' => Empleado::getMetodosPagos(),
            'cuenta_code' => $inventario_arr->COD_CTA,
            'cuenta_name' => $inventario_arr->NOM_CTA,
            'arr_departamento' => ArrayHelper::map($arr_departamento, "id", "value"),
            'arr_subdepartamento' => ArrayHelper::map($arr_subdepartamento, "id", "value"),
            'per_foto' => $this->perDefFoto,
            'arr_tipoContrato' => ArrayHelper::map($arr_tipoContrato, "id", "value"),
            'arr_tipoContribuyente' => ArrayHelper::map($arr_tipoContribuyente, "id", "value"),
            'arr_discapacidad' => ArrayHelper::map($arr_discapacidad, "id", "value"),
            'limitSizeFile' => $this->limitSizeFile,
            'arr_grupo' => ArrayHelper::map($arr_grupo, "id", "value"),
            'arr_rol' => ArrayHelper::map($arr_roles, "id", "value"),
            'reg_pass' => $model_tPass->tpas_validacion,
            'long_pass' => $model_confSeg->cseg_long_pass,
            'desc_pass' => $model_tPass->tpas_observacion, //$model_confSeg->cseg_descripcion,
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
            $trans2 = Yii::$app->db->beginTransaction();
            $trans  = Yii::$app->db_gfinanciero->beginTransaction();
            $fechaActual = date('Y-m-d H:i:s');
            try {
                //// body logic begin
                $id = $data["id"]; // ID de la persona si se agrega desde el modelo ya existe
                $pri_nombre = $data["pri_nombre"];
                $seg_nombre = $data["seg_nombre"];
                $pri_apellido = $data["pri_apellido"];
                $seg_apellido = $data["seg_apellido"];
                $ecivil = $data["ecivil"];
                $genero = $data["genero"];
                $correo = $data["correo"];
                $celular = $data["celular"];
                $telefono = $data["telefono"];
                $telefonoc = $data["telefonoc"];
                $extension = $data["extension"];
                $tipo_sangre = $data["tipo_sangre"];
                $raza_etnica = $data["raza_etnica"];
                $f_nacimiento = $data["f_nacimiento"];
                $cedula = $data["cedula"];
                $ruc = $data["ruc"];
                $pasaporte = $data["pasaporte"];
                $nacionalidad = $data["nacionalidad"];
                $pai_nac = $data["pai_nac"];
                $pro_nac = $data["pro_nac"];
                $ciu_nac = $data["ciu_nac"];
                $ecua = $data["ecua"]; // boolean si es ecuatoriano
                $pai_dom = $data["pai_dom"];
                $pro_dom = $data["pro_dom"];
                $ciu_dom = $data["ciu_dom"];
                $tel2_dom = $data["tel2_dom"];
                $sector_dom = $data["sector_dom"];
                $callepri_dom = $data["callepri_dom"];
                $callesec_dom = $data["callesec_dom"];
                $numeracion_dom = $data["numeracion_dom"];
                $referencia_dom = $data["referencia_dom"];
                $banco = $data["banco"];
                $cc_banco = $data["cc_banco"];
                $tpago = $data["tpago"];
                $cta_contable = $data["cta_contable"];
                $f_ingreso = $data["f_ingreso"];
                $cod_vend = $data["cod_vend"];
                $per_foto = $data["per_foto"];
                $ced_foto = $data["ced_foto"];
                $contr_pdf = $data["contr_pdf"];
                $entr_pdf = $data["entr_pdf"];
                $departamento = $data["departamento"];
                $subdepartamento = $data["subdepartamento"];
                $tipContrato = $data["tipContrato"];
                $cargo = $data['cargo'];
                $salario = $data['salario'];
                $f_ssocial = $data["f_ssocial"];
                $freserva = $data["freserva"];
                $dtercero = $data["dtercero"];
                $dcuarto = $data["dcuarto"];
                $sobretiempo = $data["sobretiempo"];
                $t_empleado = $data['t_empleado'];
                $tipContribuyente = $data["tipContribuyente"];
                $cargas = $data["cargas"];
                $discapacidad = $data["discapacidad"];
                $tip_disc = $data["tip_disc"];
                $per_disc = $data["per_disc"];
                $user = $data["username"];
                $clave = $data["clave"];
                $clave_repeat = $data["clave_repeat"];
                $grupo = $data["grupo"];
                $rol = $data["rol"];
                $observacion = $data["observacion"];

                // verificando codigo de vendedor
                $mod_gEmp = GEmpleado::findOne(['COD_VEN' => $cod_vend, 'EST_LOG' => '1', 'EST_DEL' => '1']);
                if($mod_gEmp){
                    $arr_errs['error'] = financiero::t('empleado', 'Employee Code already Exists.');
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // llenado de tabla Persona
                $arrDataPer['per_pri_nombre'] = $pri_nombre;
                $arrDataPer['per_seg_nombre'] = $seg_nombre;
                $arrDataPer['per_pri_apellido'] = $pri_apellido;
                $arrDataPer['per_seg_apellido'] = $seg_apellido;
                $arrDataPer['eciv_id'] = $ecivil;
                $arrDataPer['per_genero'] = $genero;
                $arrDataPer['per_correo'] = $correo;
                $arrDataPer['per_celular'] = $celular;
                $arrDataPer['per_domicilio_telefono'] = $telefono;
                $arrDataPer['per_trabajo_telefono'] = $telefonoc;
                $arrDataPer['per_trabajo_ext'] = $extension;
                $arrDataPer['tsan_id'] = $tipo_sangre;
                $arrDataPer['etn_id'] = $raza_etnica;
                $arrDataPer['per_fecha_nacimiento'] = $f_nacimiento;
                $arrDataPer['per_cedula'] = $cedula;
                $arrDataPer['per_ruc'] = $ruc;
                $arrDataPer['per_pasaporte'] = $pasaporte;
                $arrDataPer['per_nacionalidad'] = $nacionalidad;
                $arrDataPer['pai_id_nacimiento'] = $pai_nac;
                $arrDataPer['pro_id_nacimiento'] = $pro_dom;
                $arrDataPer['can_id_nacimiento'] = $ciu_nac;
                $arrDataPer['per_nac_ecuatoriano'] = $ecua;
                $arrDataPer['pai_id_domicilio'] = $pai_dom;
                $arrDataPer['pro_id_domicilio'] = $pro_dom;
                $arrDataPer['can_id_domicilio'] = $ciu_dom;
                $arrDataPer['per_domicilio_celular2'] = $tel2_dom;
                $arrDataPer['per_domicilio_sector'] = $sector_dom;
                $arrDataPer['per_domicilio_cpri'] = $callepri_dom;
                $arrDataPer['per_domicilio_csec'] = $callesec_dom;
                $arrDataPer['per_domicilio_num'] = $numeracion_dom;
                $arrDataPer['per_domicilio_ref'] = $referencia_dom;
                $arrDataPer['per_fecha_creacion'] = $fechaActual;
                $nper_id = $id;
                // Se Obtiene el GROL
                $modelGrol = GrupRol::findOne(['gru_id' => $grupo, 'rol_id' => $rol, 'grol_estado' => '1', 'grol_estado_logico' => '1', ]);
                if($id != 0){ // ya existe la persona                    
                    // llenado de tabla Persona
                    $arrDataPer['per_fecha_modificacion'] = $fechaActual;
                    $result = Yii::$app->db->createCommand()->update(Yii::$app->db->dbname . '.persona', $arrDataPer, ['per_id' => $nper_id])->execute();

                    // se verifica si existe empresa_persona
                    $model_EmpPer = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $nper_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);
                    if(!$model_EmpPer){
                        $model_EmpPer = new EmpresaPersona();
                        $model_EmpPer->eper_fecha_creacion = $fechaActual;
                        $model_EmpPer->eper_estado = '1';
                        $model_EmpPer->eper_estado_logico = '1';
                    }else{
                        $model_EmpPer->eper_fecha_modificacion = $fechaActual;
                    }
                    $model_EmpPer->emp_id = $emp_id;
                    $model_EmpPer->per_id = $nper_id;
                    if(!$model_EmpPer->save()){
                        $arr_errs = $model_EmpPer->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }

                    // Verificar si ya tiene usuario Creado
                    $model_usuario = Usuario::findOne(['per_id' => $nper_id, 'usu_user' => $user, 'usu_estado' => '1', 'usu_estado_logico' => '1']);
                    $nusr_id = 0;
                    if($model_usuario){
                        $model_usuario->generateAuthKey();
                        $model_usuario->setPassword($clave);
                        $arrDataUsr['usu_fecha_modificacion'] = $fechaActual;
                        if($clave != ""){
                            $arrDataUsr['usu_sha'] = $model_usuario->usu_sha;
                            $arrDataUsr['usu_password'] = $model_usuario->usu_password;
                        }
                        $result = Yii::$app->db->createCommand()->update(Yii::$app->db->dbname . '.usuario', $arrDataUsr, ['usu_id' => $model_usuario->usu_id])->execute();
                        $nusr_id = $model_usuario->usu_id;
                    }else{
                        $model_usuario = new Usuario();
                        $model_usuario->generateAuthKey();
                        $model_usuario->setPassword($clave);
                        $arrDataUsr['per_id'] = $nper_id;
                        $arrDataUsr['usu_user'] = $user;
                        $arrDataUsr['usu_estado'] = '1';
                        $arrDataUsr['usu_fecha_creacion'] = $fechaActual;
                        $arrDataUsr['usu_estado_logico'] = '1';
                        $arrDataUsr['usu_sha'] = $model_usuario->usu_sha;
                        $arrDataUsr['usu_password'] = $model_usuario->usu_password;
                        $result = Yii::$app->db->createCommand()->insert(Yii::$app->db->dbname . '.usuario', $arrDataUsr)->execute();
                        $nusr_id = Yii::$app->db->getLastInsertID(Yii::$app->db->dbname . '.usuario');
                        if($nusr_id <= 0){
                            $arr_errs['error'] = financiero::t('empleado', "Error to Create Employee. Try Again.");
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                    
                    // Se crea si no existe registros en usua_grol_eper
                    $model_usuaGrolEper = UsuaGrolEper::findOne(['eper_id' => $model_EmpPer->eper_id, 'usu_id' => $nusr_id, 
                                    'grol_id' => $modelGrol->grol_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1']);
                    if(!$model_usuaGrolEper){
                        $model_usuaGrolEper = new UsuaGrolEper();
                        $model_usuaGrolEper->eper_id = $model_EmpPer->eper_id;
                        $model_usuaGrolEper->usu_id = $nusr_id;
                        $model_usuaGrolEper->grol_id = $modelGrol->grol_id;
                        $model_usuaGrolEper->ugep_estado = '1';
                        $model_usuaGrolEper->ugep_fecha_creacion = $fechaActual;
                        $model_usuaGrolEper->ugep_estado_logico = '1';
                    }else{
                        $model_usuaGrolEper->ugep_fecha_modificacion = $fechaActual;
                    }
                    if(!$model_usuaGrolEper->save()){
                        $arr_errs = $model_usuaGrolEper->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }

                }else{
                    // llenado de tabla Persona
                    $arrDataPer['per_fecha_creacion'] = $fechaActual;
                    $arrDataPer['per_estado'] = '1';
                    $arrDataPer['per_estado_logico'] = '1';
                    $result = Yii::$app->db->createCommand()->insert(Yii::$app->db->dbname . '.persona', $arrDataPer)->execute();
                    $nper_id = Yii::$app->db->getLastInsertID(Yii::$app->db->dbname . '.persona');
                    if($nper_id <= 0){
                        $arr_errs['error'] = financiero::t('empleado', "Error to Create Employee. Try Again.");
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }

                    // Se crea registro en empresa_persona
                    $model_EmpPer = new EmpresaPersona();
                    $model_EmpPer->emp_id = $emp_id;
                    $model_EmpPer->per_id = $nper_id;
                    $model_EmpPer->eper_estado = '1';
                    $model_EmpPer->eper_fecha_creacion = $fechaActual;
                    $model_EmpPer->eper_estado_logico = '1';
                    if(!$model_EmpPer->save()){
                        $arr_errs = $model_EmpPer->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }

                    // Se crea Usuario con Clave Aleatoria si la clave es vacia
                    $model_usuario = new Usuario();
                    $model_usuario->generateAuthKey();
                    $model_usuario->setPassword($clave);
                    $arrDataUsr['per_id'] = $nper_id;
                    $arrDataUsr['usu_user'] = $user;
                    $arrDataUsr['usu_estado'] = '1';
                    $arrDataUsr['usu_fecha_creacion'] = $fechaActual;
                    $arrDataUsr['usu_estado_logico'] = '1';
                    $arrDataUsr['usu_sha'] = $model_usuario->usu_sha;
                    $arrDataUsr['usu_password'] = $model_usuario->usu_password;
                    $result = Yii::$app->db->createCommand()->insert(Yii::$app->db->dbname . '.usuario', $arrDataUsr)->execute();
                    $nusr_id = Yii::$app->db->getLastInsertID(Yii::$app->db->dbname . '.usuario');
                    if($nusr_id <= 0){
                        $arr_errs['error'] = financiero::t('empleado', "Error to Create Employee. Try Again.");
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }

                    // Se crea registro en usua_grol_eper
                    $model_usuaGrolEper = new UsuaGrolEper();
                    $model_usuaGrolEper->eper_id = $model_EmpPer->eper_id;
                    $model_usuaGrolEper->usu_id = $nusr_id;
                    $model_usuaGrolEper->grol_id = $modelGrol->grol_id;
                    $model_usuaGrolEper->ugep_estado = '1';
                    $model_usuaGrolEper->ugep_fecha_creacion = $fechaActual;
                    $model_usuaGrolEper->ugep_estado_logico = '1';
                    if(!$model_usuaGrolEper->save()){
                        $arr_errs = $model_usuaGrolEper->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }

                // Se crea GEmpleado
                $model_Gempleado =  new GEmpleado();
                $model_Gempleado->COD_EMP = ($cedula == "")?(($ruc == "")?(substr($pasaporte, 0, 10)):substr($ruc, 0, 10)):substr($cedula, 0, 10);
                $model_Gempleado->CED_RUC = ($cedula == "")?(($ruc == "")?$pasaporte:$ruc):$cedula;
                $model_Gempleado->NOM_EMP = $pri_nombre . " " . $seg_nombre . " " . $pri_apellido . " " . $seg_apellido;
                $model_Gempleado->FEC_EMP = $fechaActual;
                $model_Gempleado->FEC_NAC = $f_nacimiento;
                $model_Gempleado->COD_VEN = $cod_vend;
                $model_Gempleado->IDS_DEP = '1';
                $model_Gempleado->USU_ACC = NULL;
                $model_Gempleado->COD_PAI = "$pai_dom";
                $model_Gempleado->COD_CIU = "$ciu_dom";
                $model_Gempleado->DIR_EMP = $sector_dom . ", " . $callepri_dom . ", " . $callesec_dom . ", " . $numeracion_dom;
                $model_Gempleado->TEL_N01 = $celular;
                $model_Gempleado->TEL_N02 = $telefono;
                $model_Gempleado->NUM_FAX = $telefonoc;
                $model_Gempleado->EXT_EMP = $extension;
                $model_Gempleado->CORRE_E = $correo;
                $model_Gempleado->TIP_EMP = "01";
                $model_Gempleado->COD_CTA = $cta_contable;
                $model_Gempleado->OBSER01 = $observacion;
                $model_Gempleado->EST_CVAR = '0';
                $model_Gempleado->EST_PER = '1';
                $model_Gempleado->FEC_SIS = date('Y-m-d');
                $model_Gempleado->HOR_SIS = date('H:i:s');
                $model_Gempleado->USUARIO = $username;
                $model_Gempleado->EQUIPO = Utilities::getClientRealIP();
                $model_Gempleado->EST_LOG = '1';
                $model_Gempleado->EST_DEL = '1';
                if(!$model_Gempleado->save()){
                    $arr_errs = $model_Gempleado->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Se crea Empleado
                $model_empleado = new Empleado();
                $model_empleado->empl_codigo = ($cedula == "")?(($ruc == "")?(substr($pasaporte, 0, 10)):substr($ruc, 0, 10)):substr($cedula, 0, 10);
                $model_empleado->sdep_id = $subdepartamento;
                $model_empleado->per_id = $nper_id;
                $model_empleado->tcon_id = "$tipContribuyente";
                $model_empleado->dis_id = ($discapacidad == "1")?$tip_disc:NULL;
                $model_empleado->tipe_id = $t_empleado;
                $model_empleado->tipc_id = $tipContrato;
                $model_empleado->empl_cod_vendedor = $cod_vend;
                $model_empleado->empl_cedula_ruc = ($cedula == "")?(($ruc == "")?$pasaporte:$ruc):$cedula;
                $model_empleado->empl_nombre = $pri_nombre . " " . $seg_nombre;
                $model_empleado->empl_apellido = $pri_apellido . " " . $seg_apellido;
                $model_empleado->empl_fecha_nacimiento = $f_nacimiento;
                $model_empleado->empl_direccion = $sector_dom . ", " . $callepri_dom . ", " . $callesec_dom . ", " . $numeracion_dom;
                $model_empleado->empl_telefono = $telefono;
                $model_empleado->empl_telefono_movil = $celular;
                $model_empleado->empl_carga_familiar = $cargas;
                $model_empleado->empl_genero = $genero;
                $model_empleado->empl_ids_ban = $banco;
                $model_empleado->empl_metodo_pago = $tpago;
                $model_empleado->empl_cuenta_bancaria = $cc_banco;
                $model_empleado->empl_cuenta_contable = $cta_contable;
                $model_empleado->empl_fecha_ingreso = $f_ingreso;
                $model_empleado->empl_fecha_salida = NULL;
                $model_empleado->empl_fecha_seguro_social = $f_ssocial;
                $model_empleado->empl_estado_civil = $ecivil;
                $model_empleado->empl_cuenta_catalogo = NULL;
                $model_empleado->empl_fondo_reserva = $freserva;
                $model_empleado->empl_decimo_tercero = $dtercero;
                $model_empleado->empl_decimo_cuarto = $dcuarto;
                $model_empleado->empl_paga_sobretiempo = $sobretiempo;
                //$model_empleado->empl_ruta_foto = $per_foto;
                //$model_empleado->empl_ruta_cedula = $ced_foto;
                //$model_empleado->empl_ruta_contrato = $contr_pdf;
                //$model_empleado->empl_ruta_aviso_entrada = $entr_pdf;
                $model_empleado->empl_email_notificacion = $correo;
                $model_empleado->empl_porcentaje_discapacidad = ($discapacidad == "1")?$per_disc:"";
                $model_empleado->empl_usuario_ingreso = $usu_id;
                $model_empleado->empl_fecha_creacion = $fechaActual;
                $model_empleado->empl_estado = '1';
                $model_empleado->empl_estado_logico = '1';
                if(!$model_empleado->save()){
                    $arr_errs = $model_empleado->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Se crea Empleado_Cargo
                $modelEmpleadoCargo = new EmpleadoCargo();
                $modelEmpleadoCargo->empl_codigo = $model_empleado->empl_codigo;
                $modelEmpleadoCargo->carg_id = $cargo;
                $modelEmpleadoCargo->sdep_id = $subdepartamento;
                $modelEmpleadoCargo->ecarg_sueldo = $salario;
                $modelEmpleadoCargo->ecarg_fecha_inicio = $fechaActual;
                $modelEmpleadoCargo->ecarg_observacion = "";
                $modelEmpleadoCargo->ecarg_usuario_ingreso = $usu_id;
                $modelEmpleadoCargo->ecarg_fecha_creacion = $fechaActual;
                $modelEmpleadoCargo->ecarg_estado = '1';
                $modelEmpleadoCargo->ecarg_estado_logico = '1';
                if(!$modelEmpleadoCargo->save()){
                    $arr_errs = $modelEmpleadoCargo->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                //Recibe Parametros Foto
                $per_fotoF = $_FILES['per_foto'];
                if (!isset($per_fotoF) || count($per_fotoF) == 0) {
                    $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($per_fotoF['name'])]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                $arrImPerFoto = explode(".", basename($per_fotoF['name']));
                $typeFileFoto = strtolower($arrImPerFoto[count($arrImPerFoto) - 1]);
                if(!in_array($typeFileFoto, explode(',',$this->extensionAvailable))){
                    $arr_errs['error'] = Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($per_fotoF['name']), 'extensions' => $this->extensionAvailable]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                if($per_fotoF['size'] > $this->limitSizeFile){
                    $arr_errs['error'] = Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($per_fotoF['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                //Recibe Parametros Cedula
                $ced_fotoF = $_FILES['ced_foto'];
                if (!isset($ced_fotoF) || count($ced_fotoF) == 0) {
                    $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($ced_fotoF['name'])]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                $arrImCedFoto = explode(".", basename($ced_fotoF['name']));
                $typeFileCed = strtolower($arrImCedFoto[count($arrImCedFoto) - 1]);
                if(!in_array($typeFileCed, explode(',',$this->extensionAvailable))){
                    $arr_errs['error'] = Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($ced_fotoF['name']), 'extensions' => $this->extensionAvailable]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                if($ced_fotoF['size'] > $this->limitSizeFile){
                    $arr_errs['error'] = Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($ced_fotoF['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                //Recibe Parametros Contrato
                $cont_pdf = $_FILES['contr_pdf'];
                if (!isset($cont_pdf) || count($cont_pdf) == 0) {
                    $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($cont_pdf['name'])]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                $arrImContPdf = explode(".", basename($cont_pdf['name']));
                $typeFileCont = strtolower($arrImContPdf[count($arrImContPdf) - 1]);
                if($typeFileCont != 'pdf'){
                    $arr_errs['error'] = Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($cont_pdf['name']), 'extensions' => $this->extensionAvailable]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                if($cont_pdf['size'] > $this->limitSizeFile){
                    $arr_errs['error'] = Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($cont_pdf['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                //Recibe Parametros Aviso Entrada
                $avi_entPdf = $_FILES['entr_pdf'];
                if (!isset($avi_entPdf) || count($avi_entPdf) == 0) {
                    $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($avi_entPdf['name'])]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                $arrImAviPdf = explode(".", basename($avi_entPdf['name']));
                $typeFileAvi = strtolower($arrImAviPdf[count($arrImAviPdf) - 1]);
                if($typeFileAvi != 'pdf'){
                    $arr_errs['error'] = Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($avi_entPdf['name']), 'extensions' => $this->extensionAvailable]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }
                if($avi_entPdf['size'] > $this->limitSizeFile){
                    $arr_errs['error'] = Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($avi_entPdf['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Procesando Foto Perfil
                $dirCurrent = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_foto_per_" . $nper_id . "." . $typeFileFoto;
                $dirFileEnd = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_foto_per_" . $nper_id . ".jpeg";
                $status = false;
                if(strtolower($typeFileFoto) == 'jpg' || strtolower($typeFileFoto) == 'jpeg'){
                    $status = Utilities::moveUploadFile($per_fotoF['tmp_name'], $dirFileEnd);
                }else{
                    $status = Utilities::moveUploadFile($per_fotoF['tmp_name'], $dirCurrent);
                    if($status){
                        $status = Utilities::changeIMGtoJPG($dirCurrent, $dirFileEnd);
                    }
                }
                if(!$status){
                    $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($per_fotoF['name'])]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }else{
                    /*$model_persona->per_foto = $dirFileEnd;
                    if(!$model_persona->save()){
                        $arr_errs = $model_persona->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }*/
                    $result = Yii::$app->db->createCommand()->update(Yii::$app->db->dbname . '.persona', ['per_foto' => $dirFileEnd], ['per_id' => $nper_id])->execute();
                    $model_empleado->empl_ruta_foto = $dirFileEnd;
                    if(!$model_empleado->save()){
                        $arr_errs = $model_empleado->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }

                // Procesando Foto Cedula
                $dirCurrentCed = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_ced_per_" . $nper_id . "." . $typeFileCed;
                $dirFileEndCed = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_ced_per_" . $nper_id . ".jpeg";
                $status = false;
                if(strtolower($typeFileCed) == 'jpg' || strtolower($typeFileCed) == 'jpeg'){
                    $status = Utilities::moveUploadFile($ced_fotoF['tmp_name'], $dirFileEndCed);
                }else{
                    $status = Utilities::moveUploadFile($ced_fotoF['tmp_name'], $dirCurrentCed);
                    if($status){
                        $status = Utilities::changeIMGtoJPG($dirCurrentCed, $dirFileEndCed);
                    }
                }
                if(!$status){
                    $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($ced_fotoF['name'])]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }else{
                    $model_empleado->empl_ruta_cedula = $dirFileEndCed;
                    if(!$model_empleado->save()){
                        $arr_errs = $model_empleado->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }

                // Procesando Pdf Contrato
                $dirCurrentCon = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_contrato_per_" . $nper_id . "." . $typeFileCont;
                $status = Utilities::moveUploadFile($cont_pdf['tmp_name'], $dirCurrentCon);
                if(!$status){
                    $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($cont_pdf['name'])]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }else{
                    $model_empleado->empl_ruta_contrato = $dirCurrentCon;
                    if(!$model_empleado->save()){
                        $arr_errs = $model_empleado->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }

                // Procesando Pdf Aviso de Entrada
                $dirCurrentAvi = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_entrada_per_" . $nper_id . "." . $typeFileAvi;
                $status = Utilities::moveUploadFile($avi_entPdf['tmp_name'], $dirCurrentAvi);
                if(!$status){
                    $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($avi_entPdf['name'])]);
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }else{
                    $model_empleado->empl_ruta_aviso_entrada = $dirCurrentAvi;
                    if(!$model_empleado->save()){
                        $arr_errs = $model_empleado->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }

                //// body logic End
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                $trans2->commit();
                $trans->commit();
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            } catch (\Exception $ex) {
                $trans2->rollback();
                $trans->rollback();
                $exMsg = new CMsgException($ex);
                $msg = (($exMsg->getMsgLang()) != '')?($exMsg->getMsgLang()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                // set innodb_lock_wait_timeout=100; -- IF PROBLEM SQL TIMEOUT
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
            $trans2 = Yii::$app->db->beginTransaction();
            $trans  = Yii::$app->db_gfinanciero->beginTransaction();
            $fechaActual = date('Y-m-d H:i:s');
            try {
                //// body logic begin
                $id = $data["id"]; // ID de la persona 
                $code = $data['code'];
                $pri_nombre = $data["pri_nombre"];
                $seg_nombre = $data["seg_nombre"];
                $pri_apellido = $data["pri_apellido"];
                $seg_apellido = $data["seg_apellido"];
                $ecivil = $data["ecivil"];
                $genero = $data["genero"];
                $correo = $data["correo"];
                $celular = $data["celular"];
                $telefono = $data["telefono"];
                $telefonoc = $data["telefonoc"];
                $extension = $data["extension"];
                $tipo_sangre = $data["tipo_sangre"];
                $raza_etnica = $data["raza_etnica"];
                $f_nacimiento = $data["f_nacimiento"];
                $cedula = $data["cedula"];
                $ruc = $data["ruc"];
                $pasaporte = $data["pasaporte"];
                $nacionalidad = $data["nacionalidad"];
                $pai_nac = $data["pai_nac"];
                $pro_nac = $data["pro_nac"];
                $ciu_nac = $data["ciu_nac"];
                $ecua = $data["ecua"]; // boolean si es ecuatoriano
                $pai_dom = $data["pai_dom"];
                $pro_dom = $data["pro_dom"];
                $ciu_dom = $data["ciu_dom"];
                $tel2_dom = $data["tel2_dom"];
                $sector_dom = $data["sector_dom"];
                $callepri_dom = $data["callepri_dom"];
                $callesec_dom = $data["callesec_dom"];
                $numeracion_dom = $data["numeracion_dom"];
                $referencia_dom = $data["referencia_dom"];
                $banco = $data["banco"];
                $cc_banco = $data["cc_banco"];
                $tpago = $data["tpago"];
                $cta_contable = $data["cta_contable"];
                $f_ingreso = $data["f_ingreso"];
                $cod_vend = $data["cod_vend"];
                $per_foto = $data["per_foto"];
                $ced_foto = $data["ced_foto"];
                $contr_pdf = $data["contr_pdf"];
                $entr_pdf = $data["entr_pdf"];
                $departamento = $data["departamento"];
                $subdepartamento = $data["subdepartamento"];
                $tipContrato = $data["tipContrato"];
                $cargo = $data['cargo'];
                $salario = $data['salario'];
                $f_ssocial = $data["f_ssocial"];
                $freserva = $data["freserva"];
                $dtercero = $data["dtercero"];
                $dcuarto = $data["dcuarto"];
                $sobretiempo = $data["sobretiempo"];
                $t_empleado = $data['t_empleado'];
                $tipContribuyente = $data["tipContribuyente"];
                $cargas = $data["cargas"];
                $discapacidad = $data["discapacidad"];
                $tip_disc = $data["tip_disc"];
                $per_disc = $data["per_disc"];
                $user = $data["username"];
                $clave = $data["clave"];
                $clave_repeat = $data["clave_repeat"];
                $grupo = $data["grupo"];
                $rol = $data["rol"];
                $observacion = $data["observacion"];

                // llenado de tabla Persona
                $arrDataPer['per_pri_nombre'] = $pri_nombre;
                $arrDataPer['per_seg_nombre'] = $seg_nombre;
                $arrDataPer['per_pri_apellido'] = $pri_apellido;
                $arrDataPer['per_seg_apellido'] = $seg_apellido;
                $arrDataPer['eciv_id'] = $ecivil;
                $arrDataPer['per_genero'] = $genero;
                $arrDataPer['per_correo'] = $correo;
                $arrDataPer['per_celular'] = $celular;
                $arrDataPer['per_domicilio_telefono'] = $telefono;
                $arrDataPer['per_trabajo_telefono'] = $telefonoc;
                $arrDataPer['per_trabajo_ext'] = $extension;
                $arrDataPer['tsan_id'] = $tipo_sangre;
                $arrDataPer['etn_id'] = $raza_etnica;
                $arrDataPer['per_fecha_nacimiento'] = $f_nacimiento;
                $arrDataPer['per_cedula'] = $cedula;
                $arrDataPer['per_ruc'] = $ruc;
                $arrDataPer['per_pasaporte'] = $pasaporte;
                $arrDataPer['per_nacionalidad'] = $nacionalidad;
                $arrDataPer['pai_id_nacimiento'] = $pai_nac;
                $arrDataPer['pro_id_nacimiento'] = $pro_dom;
                $arrDataPer['can_id_nacimiento'] = $ciu_nac;
                $arrDataPer['per_nac_ecuatoriano'] = $ecua;
                $arrDataPer['pai_id_domicilio'] = $pai_dom;
                $arrDataPer['pro_id_domicilio'] = $pro_dom;
                $arrDataPer['can_id_domicilio'] = $ciu_dom;
                $arrDataPer['per_domicilio_celular2'] = $tel2_dom;
                $arrDataPer['per_domicilio_sector'] = $sector_dom;
                $arrDataPer['per_domicilio_cpri'] = $callepri_dom;
                $arrDataPer['per_domicilio_csec'] = $callesec_dom;
                $arrDataPer['per_domicilio_num'] = $numeracion_dom;
                $arrDataPer['per_domicilio_ref'] = $referencia_dom;
                $arrDataPer['per_fecha_modificacion'] = $fechaActual;
                $nper_id = $id;
                // Se Obtiene el GROL
                $modelGrol = GrupRol::findOne(['gru_id' => $grupo, 'rol_id' => $rol, 'grol_estado' => '1', 'grol_estado_logico' => '1', ]);

                // actualizacion de tabla Persona
                $result = Yii::$app->db->createCommand()->update(Yii::$app->db->dbname . '.persona', $arrDataPer, ['per_id' => $nper_id])->execute();

                // se obtiene empresa_persona
                $model_EmpPer = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $nper_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);

                // Se actualiza usuario
                $model_usuario = Usuario::findOne(['per_id' => $nper_id, 'usu_user' => $user, 'usu_estado' => '1', 'usu_estado_logico' => '1']);
                $nusr_id = $model_usuario->usu_id;
                if($clave != ""){
                    $model_usuario->generateAuthKey();
                    $model_usuario->setPassword($clave);
                    $arrDataUsr['usu_fecha_modificacion'] = $fechaActual;
                    $result = Yii::$app->db->createCommand()->update(Yii::$app->db->dbname . '.usuario', $arrDataUsr, ['usu_id' => $model_usuario->usu_id])->execute();
                }

                // Se desactiva todos los usuaGrolEper anteriores
                $arrModelUsuaGrolEper =  UsuaGrolEper::findAll(['eper_id' => $model_EmpPer->eper_id, 'usu_id' => $nusr_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1']);
                foreach($arrModelUsuaGrolEper as $key2 => $item2){
                    $item2->ugep_estado = '0';
                    $item2->ugep_estado_logico = '0';
                    $item2->ugep_fecha_modificacion = $fechaActual;
                    if(!$item2->save()){
                        $arr_errs = $item2->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }
                // Se crea registros en usua_grol_eper
                $model_usuaGrolEper = new UsuaGrolEper();
                $model_usuaGrolEper->eper_id = $model_EmpPer->eper_id;
                $model_usuaGrolEper->usu_id = $nusr_id;
                $model_usuaGrolEper->grol_id = $modelGrol->grol_id;
                $model_usuaGrolEper->ugep_estado = '1';
                $model_usuaGrolEper->ugep_fecha_creacion = $fechaActual;
                $model_usuaGrolEper->ugep_estado_logico = '1';
                if(!$model_usuaGrolEper->save()){
                    $arr_errs = $model_usuaGrolEper->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Se actualiza GEmpleado
                $model_Gempleado =  GEmpleado::findOne(['COD_EMP' => $code, 'EST_LOG' => '1', 'EST_DEL' => '1']);
                //$model_Gempleado->COD_EMP = ($cedula == "")?(($ruc == "")?(substr($pasaporte, 0, 10)):substr($ruc, 0, 10)):substr($cedula, 0, 10);
                $model_Gempleado->CED_RUC = ($cedula == "")?(($ruc == "")?$pasaporte:$ruc):$cedula;
                $model_Gempleado->NOM_EMP = $pri_nombre . " " . $seg_nombre . " " . $pri_apellido . " " . $seg_apellido;
                $model_Gempleado->FEC_EMP = $fechaActual;
                $model_Gempleado->FEC_NAC = $f_nacimiento;
                $model_Gempleado->COD_VEN = $cod_vend;
                $model_Gempleado->COD_PAI = "$pai_dom";
                $model_Gempleado->COD_CIU = "$ciu_dom";
                $model_Gempleado->DIR_EMP = $sector_dom . ", " . $callepri_dom . ", " . $callesec_dom . ", " . $numeracion_dom;
                $model_Gempleado->TEL_N01 = $celular;
                $model_Gempleado->TEL_N02 = $telefono;
                $model_Gempleado->NUM_FAX = $telefonoc;
                $model_Gempleado->EXT_EMP = $extension;
                $model_Gempleado->CORRE_E = $correo;
                $model_Gempleado->TIP_EMP = "01";
                $model_Gempleado->COD_CTA = $cta_contable;
                $model_Gempleado->OBSER01 = $observacion;
                if(!$model_Gempleado->save()){
                    $arr_errs = $model_Gempleado->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Se actualiza Empleado
                $model_empleado = Empleado::findOne(['empl_codigo' => $code, 'empl_estado' => '1', 'empl_estado_logico' => '1', ]);
                //$model_empleado->empl_codigo = ($cedula == "")?(($ruc == "")?(substr($pasaporte, 0, 10)):substr($ruc, 0, 10)):substr($cedula, 0, 10);
                $model_empleado->sdep_id = $subdepartamento;
                //$model_empleado->per_id = $nper_id;
                $model_empleado->tcon_id = "$tipContribuyente";
                $model_empleado->dis_id = ($discapacidad == "1")?$tip_disc:NULL;
                $model_empleado->tipe_id = $t_empleado;
                $model_empleado->tipc_id = $tipContrato;
                $model_empleado->empl_cod_vendedor = $cod_vend;
                $model_empleado->empl_cedula_ruc = ($cedula == "")?(($ruc == "")?$pasaporte:$ruc):$cedula;
                $model_empleado->empl_nombre = $pri_nombre . " " . $seg_nombre;
                $model_empleado->empl_apellido = $pri_apellido . " " . $seg_apellido;
                $model_empleado->empl_fecha_nacimiento = $f_nacimiento;
                $model_empleado->empl_direccion = $sector_dom . ", " . $callepri_dom . ", " . $callesec_dom . ", " . $numeracion_dom;
                $model_empleado->empl_telefono = $telefono;
                $model_empleado->empl_telefono_movil = $celular;
                $model_empleado->empl_carga_familiar = $cargas;
                $model_empleado->empl_genero = $genero;
                $model_empleado->empl_ids_ban = $banco;
                $model_empleado->empl_metodo_pago = $tpago;
                $model_empleado->empl_cuenta_bancaria = $cc_banco;
                $model_empleado->empl_cuenta_contable = $cta_contable;
                $model_empleado->empl_fecha_ingreso = $f_ingreso;
                $model_empleado->empl_fecha_salida = NULL;
                $model_empleado->empl_fecha_seguro_social = $f_ssocial;
                $model_empleado->empl_estado_civil = $ecivil;
                $model_empleado->empl_cuenta_catalogo = NULL;
                $model_empleado->empl_fondo_reserva = $freserva;
                $model_empleado->empl_decimo_tercero = $dtercero;
                $model_empleado->empl_decimo_cuarto = $dcuarto;
                $model_empleado->empl_paga_sobretiempo = $sobretiempo;
                //$model_empleado->empl_ruta_foto = $per_foto;
                //$model_empleado->empl_ruta_cedula = $ced_foto;
                //$model_empleado->empl_ruta_contrato = $contr_pdf;
                //$model_empleado->empl_ruta_aviso_entrada = $entr_pdf;
                $model_empleado->empl_email_notificacion = $correo;
                $model_empleado->empl_porcentaje_discapacidad = ($discapacidad == "1")?$per_disc:"";
                $model_empleado->empl_usuario_ingreso = $usu_id;
                $model_empleado->empl_fecha_creacion = $fechaActual;
                $model_empleado->empl_estado = '1';
                $model_empleado->empl_estado_logico = '1';
                if(!$model_empleado->save()){
                    $arr_errs = $model_empleado->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Se desactiva todos los Empleado_Cargo anteriores
                $arrModelEmpleadoCargo =  EmpleadoCargo::findAll(['empl_codigo' => $model_empleado->empl_codigo, 'ecarg_estado' => '1', 'ecarg_estado_logico' => '1',]);
                foreach($arrModelEmpleadoCargo as $key3 => $item3){
                    $item3->ecarg_usuario_modifica = $usu_id;
                    $item3->ecarg_fecha_modificacion = $fechaActual;
                    $item3->ecarg_fecha_fin = $fechaActual;
                    $item3->ecarg_estado = '0';
                    $item3->ecarg_estado_logico = '0';
                    if(!$item3->save()){
                        $arr_errs = $item3->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                }

                // Se actualiza Empleado_Cargo
                $modelEmpleadoCargo = new EmpleadoCargo();
                $modelEmpleadoCargo->empl_codigo = $model_empleado->empl_codigo;
                $modelEmpleadoCargo->carg_id = $cargo;
                $modelEmpleadoCargo->sdep_id = $subdepartamento;
                $modelEmpleadoCargo->ecarg_sueldo = $salario;
                $modelEmpleadoCargo->ecarg_fecha_inicio = $fechaActual;
                $modelEmpleadoCargo->ecarg_observacion = "";
                $modelEmpleadoCargo->ecarg_usuario_ingreso = $usu_id;
                $modelEmpleadoCargo->ecarg_fecha_creacion = $fechaActual;
                $modelEmpleadoCargo->ecarg_estado = '1';
                $modelEmpleadoCargo->ecarg_estado_logico = '1';
                if(!$modelEmpleadoCargo->save()){
                    $arr_errs = $modelEmpleadoCargo->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                //Recibe Parametros Foto
                $per_fotoF = $_FILES['per_foto'];
                if (isset($per_fotoF) && count($per_fotoF) > 0) {
                    $arrImPerFoto = explode(".", basename($per_fotoF['name']));
                    $typeFileFoto = strtolower($arrImPerFoto[count($arrImPerFoto) - 1]);
                    if(!in_array($typeFileFoto, explode(',',$this->extensionAvailable))){
                        $arr_errs['error'] = Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($per_fotoF['name']), 'extensions' => $this->extensionAvailable]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    if($per_fotoF['size'] > $this->limitSizeFile){
                        $arr_errs['error'] = Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($per_fotoF['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    // Procesando Foto Perfil
                    $dirCurrent = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_foto_per_" . $nper_id . "." . $typeFileFoto;
                    $dirFileEnd = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_foto_per_" . $nper_id . ".jpeg";
                    $status = false;
                    if(strtolower($typeFileFoto) == 'jpg' || strtolower($typeFileFoto) == 'jpeg'){
                        $status = Utilities::moveUploadFile($per_fotoF['tmp_name'], $dirFileEnd);
                    }else{
                        $status = Utilities::moveUploadFile($per_fotoF['tmp_name'], $dirCurrent);
                        if($status){
                            $status = Utilities::changeIMGtoJPG($dirCurrent, $dirFileEnd);
                        }
                    }
                    if(!$status){
                        $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($per_fotoF['name'])]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }else{
                        $result = Yii::$app->db->createCommand()->update(Yii::$app->db->dbname . '.persona', ['per_foto' => $dirFileEnd], ['per_id' => $nper_id])->execute();
                        $model_empleado->empl_ruta_foto = $dirFileEnd;
                        if(!$model_empleado->save()){
                            $arr_errs = $model_empleado->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                }

                //Recibe Parametros Cedula
                $ced_fotoF = $_FILES['ced_foto'];
                if (isset($ced_fotoF) && count($ced_fotoF) > 0) {
                    $arrImCedFoto = explode(".", basename($ced_fotoF['name']));
                    $typeFileCed = strtolower($arrImCedFoto[count($arrImCedFoto) - 1]);
                    if(!in_array($typeFileCed, explode(',',$this->extensionAvailable))){
                        $arr_errs['error'] = Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($ced_fotoF['name']), 'extensions' => $this->extensionAvailable]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    if($ced_fotoF['size'] > $this->limitSizeFile){
                        $arr_errs['error'] = Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($ced_fotoF['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    // Procesando Foto Cedula
                    $dirCurrentCed = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_ced_per_" . $nper_id . "." . $typeFileCed;
                    $dirFileEndCed = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_ced_per_" . $nper_id . ".jpeg";
                    $status = false;
                    if(strtolower($typeFileCed) == 'jpg' || strtolower($typeFileCed) == 'jpeg'){
                        $status = Utilities::moveUploadFile($ced_fotoF['tmp_name'], $dirFileEndCed);
                    }else{
                        $status = Utilities::moveUploadFile($ced_fotoF['tmp_name'], $dirCurrentCed);
                        if($status){
                            $status = Utilities::changeIMGtoJPG($dirCurrentCed, $dirFileEndCed);
                        }
                    }
                    if(!$status){
                        $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($ced_fotoF['name'])]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }else{
                        $model_empleado->empl_ruta_cedula = $dirFileEndCed;
                        if(!$model_empleado->save()){
                            $arr_errs = $model_empleado->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                }
                
                //Recibe Parametros Contrato
                $cont_pdf = $_FILES['contr_pdf'];
                if (isset($cont_pdf) && count($cont_pdf) > 0) {
                    $arrImContPdf = explode(".", basename($cont_pdf['name']));
                    $typeFileCont = strtolower($arrImContPdf[count($arrImContPdf) - 1]);
                    if($typeFileCont != 'pdf'){
                        $arr_errs['error'] = Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($cont_pdf['name']), 'extensions' => $this->extensionAvailable]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    if($cont_pdf['size'] > $this->limitSizeFile){
                        $arr_errs['error'] = Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($cont_pdf['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    // Procesando Pdf Contrato
                    $dirCurrentCon = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_contrato_per_" . $nper_id . "." . $typeFileCont;
                    $status = Utilities::moveUploadFile($cont_pdf['tmp_name'], $dirCurrentCon);
                    if(!$status){
                        $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($cont_pdf['name'])]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }else{
                        $model_empleado->empl_ruta_contrato = $dirCurrentCon;
                        if(!$model_empleado->save()){
                            $arr_errs = $model_empleado->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                }
                
                //Recibe Parametros Aviso Entrada
                $avi_entPdf = $_FILES['entr_pdf'];
                if (isset($avi_entPdf) && count($avi_entPdf) > 0) {
                    $arrImAviPdf = explode(".", basename($avi_entPdf['name']));
                    $typeFileAvi = strtolower($arrImAviPdf[count($arrImAviPdf) - 1]);
                    if($typeFileAvi != 'pdf'){
                        $arr_errs['error'] = Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($avi_entPdf['name']), 'extensions' => $this->extensionAvailable]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    if($avi_entPdf['size'] > $this->limitSizeFile){
                        $arr_errs['error'] = Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($avi_entPdf['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    // Procesando Pdf Aviso de Entrada
                    $dirCurrentAvi = Yii::$app->params["documentFolder"] . $this->fichaDir . $nper_id . "/doc_entrada_per_" . $nper_id . "." . $typeFileAvi;
                    $status = Utilities::moveUploadFile($avi_entPdf['tmp_name'], $dirCurrentAvi);
                    if(!$status){
                        $arr_errs['error'] = Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($avi_entPdf['name'])]);
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }else{
                        $model_empleado->empl_ruta_aviso_entrada = $dirCurrentAvi;
                        if(!$model_empleado->save()){
                            $arr_errs = $model_empleado->getFirstErrors();
                            throw new \Exception(json_encode($arr_errs), '9999999');
                        }
                    }
                }

                //// body logic End
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                $trans2->commit();
                $trans->commit();
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            } catch (\Exception $ex) {
                $trans2->rollback();
                $trans->rollback();
                $exMsg = new CMsgException($ex);
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
            $fechaActual = date('Y-m-d H:i:s');
            $trans2 = Yii::$app->db->beginTransaction();
            $trans  = Yii::$app->db_gfinanciero->beginTransaction();
            try {
                //// body logic begin
                $id  = $data["id"];
                // Eliminacion Logica Empleado
                $model = Empleado::findOne(['empl_codigo' => $id,]);
                $nper_id = $model->per_id;
                $model->empl_fecha_modificacion = $fechaActual;
                $model->empl_usuario_modifica = $usu_id;
                $model->empl_estado = '0';
                $model->empl_estado_logico = '0';
                if (!$model->save()) {
                    $arr_errs = $model->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Eliminacion Logica GEmpleado
                $modelG = GEmpleado::findOne(['COD_EMP' => $id]);
                $modelG->EST_LOG = "0";
                $modelG->EST_DEL = "0";
                if (!$modelG->save()) {
                    $arr_errs = $modelG->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Eliminacion Logica de Persona
                $result = Yii::$app->db->createCommand()->update(Yii::$app->db->dbname . '.persona', 
                            ['per_estado' => '0', 'per_estado_logico' => '0', 'per_fecha_modificacion' => $fechaActual], ['per_id' => $nper_id])->execute();

                // Eliminacion Logica de Empresa_persona
                $model_EmpPer = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $nper_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);
                $model_EmpPer->eper_estado = '0';
                $model_EmpPer->eper_estado_logico = '0';
                $model_EmpPer->eper_fecha_modificacion = $fechaActual;
                if (!$model_EmpPer->save()) {
                    $arr_errs = $model_EmpPer->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                // Eliminacion Logica de usua_grol_eper
                $model_usuaGrolEper = UsuaGrolEper::findAll(['eper_id' => $model_EmpPer->eper_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1']);
                foreach($model_usuaGrolEper as $key => $item){
                    $item->ugep_estado = '0';
                    $item->ugep_estado_logico = '0';
                    $item->ugep_fecha_modificacion = $fechaActual;
                    if (!$item->save()) {
                        $arr_errs = $item->getFirstErrors();
                        throw new \Exception(json_encode($arr_errs), '9999999');
                    }
                    // Eliminacion Logica de Usuario
                    $nusu_id = $item->usu_id;
                    $modelUsu = Usuario::findOne(['usu_id' => $nusu_id,]);
                    $result = Yii::$app->db->createCommand()->update(Yii::$app->db->dbname . '.usuario', 
                            ['usu_estado' => '0', 'usu_estado_logico' => '0', 'usu_fecha_modificacion' => $fechaActual], ['usu_id' => $nusu_id])->execute();
                }

                // Se Elimina Empleado_Cargo
                $modelEmpleadoCargo = EmpleadoCargo::findOne(['empl_codigo' => $model->empl_codigo, 'ecarg_estado' => '1', 'ecarg_estado_logico' => '1',]);
                $modelEmpleadoCargo->ecarg_usuario_modifica = $usu_id;
                $modelEmpleadoCargo->ecarg_fecha_modificacion = $fechaActual;
                $modelEmpleadoCargo->ecarg_fecha_fin = $fechaActual;
                $modelEmpleadoCargo->ecarg_estado = '0';
                $modelEmpleadoCargo->ecarg_estado_logico = '0';
                if(!$modelEmpleadoCargo->save()){
                    $arr_errs = $modelEmpleadoCargo->getFirstErrors();
                    throw new \Exception(json_encode($arr_errs), '9999999');
                }

                //// body logic End

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                $trans2->commit();
                $trans->commit();
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            } catch (\Exception $ex) {
                $exMsg = new CMsgException($ex);
                $trans2->rollback();
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
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
        $arrHeader = array(
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Sex"),
            Yii::t("formulario", "Marital Status"),
            financiero::t("empleado", "Date of Admission"),
            financiero::t("empleado", "SubDepartment"),
            financiero::t("empleado", "Department"),
            Yii::t("formulario", "Discapacity"),
            financiero::t("empleado", "Discapacity Percentage"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new Empleado();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, false, true);
        }
        $nameReport = financiero::t("empleado", "Report Employee Items");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    /**
     * Exppdf Action. Allow to download a Pdf document from index page.
     *
     * @return void
     */
    public function actionExppdf() {
        ini_set('memory_limit', Yii::$app->params['memorylimit']);
        $report = new ExportFile();
        $this->view->title = financiero::t("empleado", "Report Employee Items");  // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Sex"),
            Yii::t("formulario", "Marital Status"),
            financiero::t("empleado", "Date of Admission"),
            financiero::t("empleado", "SubDepartment"),
            financiero::t("empleado", "Department"),
            Yii::t("formulario", "Discapacity"),
            financiero::t("empleado", "Discapacity Percentage"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
        }
        $arrData = array();
        $model = new Empleado();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, false, true);
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