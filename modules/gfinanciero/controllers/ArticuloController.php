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
use app\models\ExportFile;
use app\modules\gfinanciero\models\Articulo;
use app\modules\gfinanciero\models\MarcaArticulo;
use yii\helpers\ArrayHelper;
use app\components\CException;
use app\models\Canton;
use app\models\Pais;
use app\models\Provincia;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\models\Catalogo;
use app\modules\gfinanciero\models\Divisa;
use app\modules\gfinanciero\models\Proveedor;
use app\modules\gfinanciero\models\LineaArticulo;
use app\modules\gfinanciero\models\Localidad;
use app\modules\gfinanciero\models\TipoArticulo;
use app\modules\gfinanciero\models\TipoItem;
use app\modules\gfinanciero\models\UnidadMedida;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;
use phpDocumentor\Reflection\Location;

financiero::registerTranslations();

class ArticuloController extends CController {
 
    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $model = new Articulo();

        $data = Yii::$app->request->post();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getPais"])) {
                $pais_id = $data['pai_id'];
                $provincias = Localidad::getAllStatesByCountry($pais_id);
                $arr_provincias = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a State --')]], $provincias);
                $pro_id = current($provincias);
                $ciudades = Localidad::getAllCitiesByState($pro_id);
                $arr_ciudades = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a City --')]], $ciudades);
                $message = array("provincias" => $arr_provincias, "ciudades" => $arr_ciudades);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getProvincia"])) {
                $pro_id = $data['pro_id'];
                $ciudades = Localidad::getAllCitiesByState($pro_id);
                $arr_ciudades = array_merge(['0' => ['id' => '0', 'name' => financiero::t('localidad', '-- Select a City --')]], $ciudades);
                $message = array("ciudades" => $arr_ciudades);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        if($data['ls_query_id'] == "autocomplete-linea"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = LineaArticulo::getDataColumnsQueryWidget();
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
        if($data['ls_query_id'] == "autocomplete-divisa"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Divisa::getDataColumnsQueryWidget();
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
        if($data['ls_query_id'] == "autocomplete-proveedor"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Proveedor::getDataColumnsQueryWidget();
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
        if($data['ls_query_id'] == "autocomplete-inventario"){
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
        if($data['ls_query_id'] == "autocomplete-venta"){
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
        if($data['ls_query_id'] == "autocomplete-cventa"){
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
        if($data['ls_query_id'] == "autocomplete-iventa"){
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
        if($data['ls_query_id'] == "autocomplete-medida"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = UnidadMedida::getDataColumnsQueryWidget();
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
        if($data['ls_query_id'] == "autocomplete-tipo"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = TipoArticulo::getDataColumnsQueryWidget();
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
        if($data['ls_query_id'] == "autocomplete-marca"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = MarcaArticulo::getDataColumnsQueryWidget();
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
        $arr_marca = MarcaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_MAR' => SORT_ASC])->all();
        $arr_marca = ['0' => financiero::t('marcaarticulo', '-- All Marks --')] + ArrayHelper::map($arr_marca, "COD_MAR", "NOM_MAR");
        $arr_linea = LineaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_LIN' => SORT_ASC])->all();
        $arr_linea = ['0' => financiero::t('lineaarticulo', '-- All Lines --')] + ArrayHelper::map($arr_linea, "COD_LIN", "NOM_LIN");
        $arr_tipo = TipoArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_TIP' => SORT_ASC])->all();
        $arr_tipo = ['0' => financiero::t('tipoarticulo', '-- All Types --')] + ArrayHelper::map($arr_tipo, "COD_TIP", "NOM_TIP");
        $arr_tpro = TipoItem::find()->where(['TITE_ESTADO' => '1', 'TITE_ESTADO_LOGICO' => '1'])->orderBy(['TITE_NOMBRE' => SORT_ASC])->all();
        $arr_tpro = ['0' => financiero::t('tipoitem', '-- All Type Items --')] + ArrayHelper::map($arr_tpro, "TITE_PREFIX", "TITE_NOMBRE");
        if (isset($data["PBgetFilter"])) {
            return $this->render('index', [
                "model" => $model->getAllItemsGrid($data["search"], $data["linea"], $data["tipo"], $data["marca"], $data["tpro"], true),
                'arr_marca' => $arr_marca,
                'arr_linea' => $arr_linea,
                'arr_tipo' => $arr_tipo,
                'arr_tpro' => $arr_tpro,
            ]);
        }
        /*if (Yii::$app->request->isAjax) { }*/
        return $this->render('index', [
            'model' => $model->getAllItemsGrid(NULL, NULL, NULL, NULL, NULL, true),
            'arr_marca' => $arr_marca,
            'arr_linea' => $arr_linea,
            'arr_tipo' => $arr_tipo,
            'arr_tpro' => $arr_tpro,
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
            $model = Articulo::findOne(['COD_ART' => $id,]);
            $_SESSION['JSLANG']['Please select only Movement Accounts.'] = financiero::t('catalogo',"Please select only Movement Accounts.");
            $_SESSION['JSLANG']['Prices and percentages must not be zero.'] = financiero::t('articulo',"Prices and percentages must not be zero.");
            $_SESSION['JSLANG']['Reference Price is not correct. It must be greater than Average Price.'] = financiero::t('articulo',"Reference Price is not correct. It must be greater than Average Price.");
            $_SESSION['JSLANG']['Reference Price must be greater than zero.'] = financiero::t('articulo',"Reference Price must be greater than zero.");
            $_SESSION['JSLANG']['Prices and percentages must be greater than zero.'] = financiero::t('articulo',"Prices and percentages must be greater than zero.");
            $_SESSION['JSLANG']['Please select a Country.'] = financiero::t('localidad',"Please select a Country.");
            $_SESSION['JSLANG']['Please select a State.'] = financiero::t('localidad',"Please select a State.");
            $_SESSION['JSLANG']['Please select a City.'] = financiero::t('localidad',"Please select a City.");
            $_SESSION['JSLANG']['Please select a Product Type.'] = financiero::t('tipoitem',"Please select a Product Type.");

            $arr_marca = MarcaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_MAR' => SORT_ASC])->all();
            $arr_marca = ['0' => financiero::t('marcaarticulo', '-- Select an Article Name --')] + ArrayHelper::map($arr_marca, "COD_MAR", "NOM_MAR");
            $arr_linea = LineaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_LIN' => SORT_ASC])->all();
            $arr_linea = ['0' => financiero::t('lineaarticulo', '-- Select a Line Name --')] + ArrayHelper::map($arr_linea, "COD_LIN", "NOM_LIN");
            $arr_tipo = TipoItem::find()->where(['TITE_ESTADO' => '1', 'TITE_ESTADO_LOGICO' => '1'])->orderBy(['TITE_NOMBRE' => SORT_ASC])->all();
            $arr_tipo = ['0' => financiero::t('tipoitem', '-- Select a Type Item Name --')] + ArrayHelper::map($arr_tipo, "TITE_PREFIX", "TITE_NOMBRE");
            $ciudad_def = $model->COD_PAI;
            $can_data = Canton::findOne(['can_id' => $ciudad_def, 'can_estado' => '1', 'can_estado_logico' => '1']);
            $pro_data = Provincia::findOne(['pro_id' => $can_data->pro_id, 'pro_estado' => '1', 'pro_estado_logico' => '1']);
            //$pai_data = Pais::findOne(['pai_id' => $pro_data->pai_id, 'pai_estado' => '1', 'pai_estado_logico' => '1']);
            $pais_def = $pro_data->pai_id;
            $provincia_def = $pro_data->pro_id;
            
            $arr_pais = Localidad::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'C_I_OCG' => '01',])->orderBy(['NOM_OCG' => SORT_ASC])->all();
            $arr_pais = ['0' => financiero::t('localidad', '-- Select a Country --')] + ArrayHelper::map($arr_pais, "COD_OCG", "NOM_OCG");
            $arr_provincia = Localidad::getAllStatesByCity($ciudad_def);
            $arr_provincia = ['0' => financiero::t('localidad', '-- Select a State --')] + ArrayHelper::map($arr_provincia, "id", "name");
            $arr_ciudad = Localidad::getAllCitiesByState($provincia_def);
            $arr_ciudad = ['0' => financiero::t('localidad', '-- Select a City --')] + ArrayHelper::map($arr_ciudad, "id", "name");
            
            $inventario_arr = Catalogo::findOne(['COD_CTA' => $model->AUX_N01, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $venta_arr = Catalogo::findOne(['COD_CTA' => $model->AUX_N02, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $costo_arr = Catalogo::findOne(['COD_CTA' => $model->AUX_N03, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $invta_arr = Catalogo::findOne(['COD_CTA' => $model->AUX_N04, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $medida_arr = UnidadMedida::findOne(['COD_MED' => $model->COD_MED, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $linea_arr = LineaArticulo::findOne(['COD_LIN' => $model->COD_LIN, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $tipo_arr = TipoArticulo::findOne(['COD_TIP' => $model->COD_TIP, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $marca_arr = MarcaArticulo::findOne(['COD_MAR' => $model->COD_MAR, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $divisa_arr = Divisa::findOne(['COD_DIV' => $model->COD_DIV, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $proveedor_arr = Proveedor::findOne(['COD_PRO' => $model->COD_PRO, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            
            return $this->render('view', [
                'model' => $model,
                'arr_marca' => $arr_marca,
                'arr_linea' => $arr_linea,
                'arr_tipo' => $arr_tipo,
                'arr_pais' => $arr_pais,
                'arr_provincia' => $arr_provincia,
                'arr_ciudad' => $arr_ciudad,
                'inventario_code' => $inventario_arr->COD_CTA,
                'inventario_name' => $inventario_arr->NOM_CTA,
                'venta_code' => $venta_arr->COD_CTA,
                'venta_name' => $venta_arr->NOM_CTA,
                'costo_code' => $costo_arr->COD_CTA,
                'costo_name' => $costo_arr->NOM_CTA,
                'invta_code' => $invta_arr->COD_CTA,
                'invta_name' => $invta_arr->NOM_CTA,
                'medida_code' => $medida_arr->COD_MED,
                'medida_name' => $medida_arr->NOM_MED,
                'linea_code' => $linea_arr->COD_LIN,
                'linea_name' => $linea_arr->NOM_LIN,
                'tipo_code' => $tipo_arr->COD_TIP,
                'tipo_name' => $tipo_arr->NOM_TIP,
                'marca_code' => $marca_arr->COD_MAR,
                'marca_name' => $marca_arr->NOM_MAR,
                'divisa_code' => $divisa_arr->COD_DIV,
                'divisa_name' => $divisa_arr->NOM_DIV,
                'proveedor_code' => $proveedor_arr->COD_PRO,
                'proveedor_name' => $proveedor_arr->NOM_PRO,
                'pais_def' => $pais_def,
                'provincia_def' => $provincia_def,
                'ciudad_def' => $ciudad_def,
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
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];
            $model = Articulo::findOne(['COD_ART' => $id,]);
            $_SESSION['JSLANG']['Please select only Movement Accounts.'] = financiero::t('catalogo',"Please select only Movement Accounts.");
            $_SESSION['JSLANG']['Prices and percentages must not be zero.'] = financiero::t('articulo',"Prices and percentages must not be zero.");
            $_SESSION['JSLANG']['Reference Price is not correct. It must be greater than Average Price.'] = financiero::t('articulo',"Reference Price is not correct. It must be greater than Average Price.");
            $_SESSION['JSLANG']['Reference Price must be greater than zero.'] = financiero::t('articulo',"Reference Price must be greater than zero.");
            $_SESSION['JSLANG']['Prices and percentages must be greater than zero.'] = financiero::t('articulo',"Prices and percentages must be greater than zero.");
            $_SESSION['JSLANG']['Please select a Country.'] = financiero::t('localidad',"Please select a Country.");
            $_SESSION['JSLANG']['Please select a State.'] = financiero::t('localidad',"Please select a State.");
            $_SESSION['JSLANG']['Please select a City.'] = financiero::t('localidad',"Please select a City.");
            $_SESSION['JSLANG']['Please select a Product Type.'] = financiero::t('tipoitem',"Please select a Product Type.");

            $arr_marca = MarcaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_MAR' => SORT_ASC])->all();
            $arr_marca = ['0' => financiero::t('marcaarticulo', '-- Select an Article Name --')] + ArrayHelper::map($arr_marca, "COD_MAR", "NOM_MAR");
            $arr_linea = LineaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_LIN' => SORT_ASC])->all();
            $arr_linea = ['0' => financiero::t('lineaarticulo', '-- Select a Line Name --')] + ArrayHelper::map($arr_linea, "COD_LIN", "NOM_LIN");
            $arr_tipo = TipoItem::find()->where(['TITE_ESTADO' => '1', 'TITE_ESTADO_LOGICO' => '1'])->orderBy(['TITE_NOMBRE' => SORT_ASC])->all();
            $arr_tipo = ['0' => financiero::t('tipoitem', '-- Select a Type Item Name --')] + ArrayHelper::map($arr_tipo, "TITE_PREFIX", "TITE_NOMBRE");
            $ciudad_def = $model->COD_PAI;
            $can_data = Canton::findOne(['can_id' => $ciudad_def, 'can_estado' => '1', 'can_estado_logico' => '1']);
            $pro_data = Provincia::findOne(['pro_id' => $can_data->pro_id, 'pro_estado' => '1', 'pro_estado_logico' => '1']);
            //$pai_data = Pais::findOne(['pai_id' => $pro_data->pai_id, 'pai_estado' => '1', 'pai_estado_logico' => '1']);
            $pais_def = $pro_data->pai_id;
            $provincia_def = $pro_data->pro_id;
            
            $arr_pais = Localidad::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'C_I_OCG' => '01',])->orderBy(['NOM_OCG' => SORT_ASC])->all();
            $arr_pais = ['0' => financiero::t('localidad', '-- Select a Country --')] + ArrayHelper::map($arr_pais, "COD_OCG", "NOM_OCG");
            $arr_provincia = Localidad::getAllStatesByCity($ciudad_def);
            $arr_provincia = ['0' => financiero::t('localidad', '-- Select a State --')] + ArrayHelper::map($arr_provincia, "id", "name");
            $arr_ciudad = Localidad::getAllCitiesByState($provincia_def);
            $arr_ciudad = ['0' => financiero::t('localidad', '-- Select a City --')] + ArrayHelper::map($arr_ciudad, "id", "name");
            
            $inventario_arr = Catalogo::findOne(['COD_CTA' => $model->AUX_N01, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $venta_arr = Catalogo::findOne(['COD_CTA' => $model->AUX_N02, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $costo_arr = Catalogo::findOne(['COD_CTA' => $model->AUX_N03, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $invta_arr = Catalogo::findOne(['COD_CTA' => $model->AUX_N04, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $medida_arr = UnidadMedida::findOne(['COD_MED' => $model->COD_MED, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $linea_arr = LineaArticulo::findOne(['COD_LIN' => $model->COD_LIN, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $tipo_arr = TipoArticulo::findOne(['COD_TIP' => $model->COD_TIP, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $marca_arr = MarcaArticulo::findOne(['COD_MAR' => $model->COD_MAR, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $divisa_arr = Divisa::findOne(['COD_DIV' => $model->COD_DIV, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            $proveedor_arr = Proveedor::findOne(['COD_PRO' => $model->COD_PRO, 'EST_LOG' => '1', 'EST_DEL' => '1']);
            
            return $this->render('edit', [
                'model' => $model,
                'arr_marca' => $arr_marca,
                'arr_linea' => $arr_linea,
                'arr_tipo' => $arr_tipo,
                'arr_pais' => $arr_pais,
                'arr_provincia' => $arr_provincia,
                'arr_ciudad' => $arr_ciudad,
                'inventario_code' => $inventario_arr->COD_CTA,
                'inventario_name' => $inventario_arr->NOM_CTA,
                'venta_code' => $venta_arr->COD_CTA,
                'venta_name' => $venta_arr->NOM_CTA,
                'costo_code' => $costo_arr->COD_CTA,
                'costo_name' => $costo_arr->NOM_CTA,
                'invta_code' => $invta_arr->COD_CTA,
                'invta_name' => $invta_arr->NOM_CTA,
                'medida_code' => $medida_arr->COD_MED,
                'medida_name' => $medida_arr->NOM_MED,
                'linea_code' => $linea_arr->COD_LIN,
                'linea_name' => $linea_arr->NOM_LIN,
                'tipo_code' => $tipo_arr->COD_TIP,
                'tipo_name' => $tipo_arr->NOM_TIP,
                'marca_code' => $marca_arr->COD_MAR,
                'marca_name' => $marca_arr->NOM_MAR,
                'divisa_code' => $divisa_arr->COD_DIV,
                'divisa_name' => $divisa_arr->NOM_DIV,
                'proveedor_code' => $proveedor_arr->COD_PRO,
                'proveedor_name' => $proveedor_arr->NOM_PRO,
                'pais_def' => $pais_def,
                'provincia_def' => $provincia_def,
                'ciudad_def' => $ciudad_def,
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
        $_SESSION['JSLANG']['Please select only Movement Accounts.'] = financiero::t('catalogo',"Please select only Movement Accounts.");
        $_SESSION['JSLANG']['Prices and percentages must not be zero.'] = financiero::t('articulo',"Prices and percentages must not be zero.");
        $_SESSION['JSLANG']['Reference Price is not correct. It must be greater than Average Price.'] = financiero::t('articulo',"Reference Price is not correct. It must be greater than Average Price.");
        $_SESSION['JSLANG']['Reference Price must be greater than zero.'] = financiero::t('articulo',"Reference Price must be greater than zero.");
        $_SESSION['JSLANG']['Prices and percentages must be greater than zero.'] = financiero::t('articulo',"Prices and percentages must be greater than zero.");
        $_SESSION['JSLANG']['Please select a Country.'] = financiero::t('localidad',"Please select a Country.");
        $_SESSION['JSLANG']['Please select a State.'] = financiero::t('localidad',"Please select a State.");
        $_SESSION['JSLANG']['Please select a City.'] = financiero::t('localidad',"Please select a City.");
        $_SESSION['JSLANG']['Please select a Product Type.'] = financiero::t('tipoitem',"Please select a Product Type.");
        
        $arr_marca = MarcaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_MAR' => SORT_ASC])->all();
        $arr_marca = ['0' => financiero::t('marcaarticulo', '-- Select an Article Name --')] + ArrayHelper::map($arr_marca, "COD_MAR", "NOM_MAR");
        $arr_linea = LineaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_LIN' => SORT_ASC])->all();
        $arr_linea = ['0' => financiero::t('lineaarticulo', '-- Select a Line Name --')] + ArrayHelper::map($arr_linea, "COD_LIN", "NOM_LIN");
        $arr_tipo = TipoItem::find()->where(['TITE_ESTADO' => '1', 'TITE_ESTADO_LOGICO' => '1'])->orderBy(['TITE_NOMBRE' => SORT_ASC])->all();
        $arr_tipo = ['0' => financiero::t('tipoitem', '-- Select a Type Item Name --')] + ArrayHelper::map($arr_tipo, "TITE_PREFIX", "TITE_NOMBRE");
        $pais_def = 1;
        $provincia_def = 10;
        $ciudad_def = 87;
        $arr_pais = Localidad::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1', 'C_I_OCG' => '01',])->orderBy(['NOM_OCG' => SORT_ASC])->all();
        $arr_pais = ['0' => financiero::t('localidad', '-- Select a Country --')] + ArrayHelper::map($arr_pais, "COD_OCG", "NOM_OCG");
        $arr_provincia = Localidad::getAllStatesByCountry($pais_def);
        $arr_provincia = ['0' => financiero::t('localidad', '-- Select a State --')] + ArrayHelper::map($arr_provincia, "id", "name");
        $arr_ciudad = Localidad::getAllCitiesByState($provincia_def);
        $arr_ciudad = ['0' => financiero::t('localidad', '-- Select a City --')] + ArrayHelper::map($arr_ciudad, "id", "name");
        
        $inventario_arr = Catalogo::findOne(['COD_CTA' => '101010101', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $venta_arr = Catalogo::findOne(['COD_CTA' => '410100101', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $costo_arr = Catalogo::findOne(['COD_CTA' => '410100101', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $invta_arr = Catalogo::findOne(['COD_CTA' => '410100101', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $medida_arr = UnidadMedida::findOne(['COD_MED' => '01', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $linea_arr = LineaArticulo::findOne(['COD_LIN' => '001', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $tipo_arr = TipoArticulo::findOne(['COD_TIP' => '001', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $marca_arr = MarcaArticulo::findOne(['COD_MAR' => '100', 'EST_LOG' => '1', 'EST_DEL' => '1']);
        $divisa_arr = Divisa::findOne(['COD_DIV' => '01', 'EST_LOG' => '1', 'EST_DEL' => '1']);

        return $this->render('new', [
            //'new_id' => $new_id,
            'arr_marca' => $arr_marca,
            'arr_linea' => $arr_linea,
            'arr_tipo' => $arr_tipo,
            'arr_pais' => $arr_pais,
            'arr_provincia' => $arr_provincia,
            'arr_ciudad' => $arr_ciudad,
            'inventario_code' => $inventario_arr->COD_CTA,
            'inventario_name' => $inventario_arr->NOM_CTA,
            'venta_code' => $venta_arr->COD_CTA,
            'venta_name' => $venta_arr->NOM_CTA,
            'costo_code' => $costo_arr->COD_CTA,
            'costo_name' => $costo_arr->NOM_CTA,
            'invta_code' => $invta_arr->COD_CTA,
            'invta_name' => $invta_arr->NOM_CTA,
            'medida_code' => $medida_arr->COD_MED,
            'medida_name' => $medida_arr->NOM_MED,
            'linea_code' => $linea_arr->COD_LIN,
            'linea_name' => $linea_arr->NOM_LIN,
            'tipo_code' => $tipo_arr->COD_TIP,
            'tipo_name' => $tipo_arr->NOM_TIP,
            'marca_code' => $marca_arr->COD_MAR,
            'marca_name' => $marca_arr->NOM_MAR,
            'divisa_code' => $divisa_arr->COD_DIV,
            'divisa_name' => $divisa_arr->NOM_DIV,
            'pais_def' => $pais_def,
            'provincia_def' => $provincia_def,
            'ciudad_def' => $ciudad_def,
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
                $alnombre = $data["alnombre"];
                $linea = $data["linea"];
                $tipo = $data["tipo"];
                $marca = $data["marca"];
                $pais = $data["pais"];
                $provincia = $data["provincia"];
                $ciudad = $data["ciudad"];
                $divisa = $data["divisa"];
                $ubicacion = $data["ubicacion"];
                $proveedor = $data["proveedor"];
                $inventario = $data["inventario"];
                $venta = $data["venta"];
                $cventa = $data["cventa"];
                $iventa = $data["iventa"];
                $medida = $data["medida"];
                $expira = $data["expira"];
                $tipopro = $data["tipopro"];
                $creditos = $data["creditos"];
                $descontinuado = $data["descontinuado"];
                $iva = $data["iva"];

                // Precios
                $precioref = $data["precioref"];
                $fecharef = $data["fecharef"];
                $pv1po = $data["pv1po"];
                $pv1pr = $data["pv1pr"];
                $pv1un = $data["pv1un"];
                $pv2po = $data["pv2po"];
                $pv2pr = $data["pv2pr"];
                $pv2un = $data["pv2un"];
                $pv3po = $data["pv3po"];
                $pv3pr = $data["pv3pr"];
                $pv3un = $data["pv3un"];
                $pv4po = $data["pv4po"];
                $pv4pr = $data["pv4pr"];
                $pv4un = $data["pv4un"];
                $descventa = $data["descventa"];

                // Existencias
                $exmin = $data["exmin"];
                $exmax = $data["exmax"];

                // Observaciones
                $observacion = $data["observacion"];

                $model = new Articulo();
                $model->COD_ART = $id;
                $model->DES_COM = $nombre;
                $model->DES_NAT = $alnombre;
                $model->F_C_ART = date('Y-m-d');
                //$model->F_A_INV = date('Y-m-d');
                $model->COD_LIN = $linea;
                $model->COD_TIP = $tipo;
                $model->COD_MAR = $marca;
                $model->COD_PAI = $ciudad;
                $model->COD_DIV = $divisa;
                $model->UBI_FIS = $ubicacion;
                $model->COD_PRO = $proveedor;
                $model->AUX_N01 = $inventario;
                $model->AUX_N02 = $venta;
                $model->AUX_N03 = $cventa;
                $model->AUX_N04 = $iventa;
                $model->COD_MED = $medida;
                $model->F_E_ART = $expira;
                $model->TIP_PRO = $tipopro;
                $model->NUM_CRE = $creditos;
                $model->I_M_DES = $descontinuado;
                $model->I_M_IVA = $iva;
                $model->P_COSTO = $precioref;
                $model->F_COS_N = $fecharef;
                $model->POR_N01 = $pv1po;
                $model->POR_N02 = $pv2po;
                $model->POR_N03 = $pv3po;
                $model->POR_N04 = $pv4po;
                $model->PAUX_03 = $pv1pr;
                $model->P_VENTA = $pv2pr;
                $model->PAUX_01 = $pv3pr;
                $model->PAUX_02 = $pv4pr;
                $model->CANT_01 = $pv1un;
                $model->CANT_02 = $pv2un;
                $model->CANT_03 = $pv3un;
                $model->CANT_04 = $pv4un;
                $model->POR_DES = $descventa;
                $model->EXI_MIN = $exmin;
                $model->EXI_MAX = $exmax;
                $model->CAR_ART = $observacion;

                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
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
                $alnombre = $data["alnombre"];
                $linea = $data["linea"];
                $tipo = $data["tipo"];
                $marca = $data["marca"];
                $pais = $data["pais"];
                $provincia = $data["provincia"];
                $ciudad = $data["ciudad"];
                $divisa = $data["divisa"];
                $ubicacion = $data["ubicacion"];
                $proveedor = $data["proveedor"];
                $inventario = $data["inventario"];
                $venta = $data["venta"];
                $cventa = $data["cventa"];
                $iventa = $data["iventa"];
                $medida = $data["medida"];
                $expira = $data["expira"];
                $tipopro = $data["tipopro"];
                $creditos = $data["creditos"];
                $descontinuado = $data["descontinuado"];
                $iva = $data["iva"];

                // Precios
                $precioref = $data["precioref"];
                $fecharef = $data["fecharef"];
                $pv1po = $data["pv1po"];
                $pv1pr = $data["pv1pr"];
                $pv1un = $data["pv1un"];
                $pv2po = $data["pv2po"];
                $pv2pr = $data["pv2pr"];
                $pv2un = $data["pv2un"];
                $pv3po = $data["pv3po"];
                $pv3pr = $data["pv3pr"];
                $pv3un = $data["pv3un"];
                $pv4po = $data["pv4po"];
                $pv4pr = $data["pv4pr"];
                $pv4un = $data["pv4un"];
                $descventa = $data["descventa"];

                // Existencias
                $exmin = $data["exmin"];
                $exmax = $data["exmax"];

                // Observaciones
                $observacion = $data["observacion"];

                $model = Articulo::findOne(['COD_ART' => $id,]);
                $model->DES_COM = $nombre;
                $model->DES_NAT = $alnombre;
                //$model->F_C_ART = date('Y-m-d');
                $model->F_A_INV = date('Y-m-d');
                $model->COD_LIN = $linea;
                $model->COD_TIP = $tipo;
                $model->COD_MAR = $marca;
                $model->COD_PAI = $ciudad;
                $model->COD_DIV = $divisa;
                $model->UBI_FIS = $ubicacion;
                $model->COD_PRO = $proveedor;
                $model->AUX_N01 = $inventario;
                $model->AUX_N02 = $venta;
                $model->AUX_N03 = $cventa;
                $model->AUX_N04 = $iventa;
                $model->COD_MED = $medida;
                $model->F_E_ART = $expira;
                $model->TIP_PRO = $tipopro;
                $model->NUM_CRE = $creditos;
                $model->I_M_DES = $descontinuado;
                $model->I_M_IVA = $iva;
                $model->P_COSTO = $precioref;
                $model->F_COS_N = date('Y-m-d');//$fecharef;
                $model->POR_N01 = $pv1po;
                $model->POR_N02 = $pv2po;
                $model->POR_N03 = $pv3po;
                $model->POR_N04 = $pv4po;
                $model->PAUX_03 = $pv1pr;
                $model->P_VENTA = $pv2pr;
                $model->PAUX_01 = $pv3pr;
                $model->PAUX_02 = $pv4pr;
                $model->CANT_01 = $pv1un;
                $model->CANT_02 = $pv2un;
                $model->CANT_03 = $pv3un;
                $model->CANT_04 = $pv4un;
                $model->POR_DES = $descventa;
                $model->EXI_MIN = $exmin;
                $model->EXI_MAX = $exmax;
                $model->CAR_ART = $observacion;

                $model->FEC_SIS = date('Y-m-d');
                $model->HOR_SIS = date('H:i:s');
                $model->USUARIO = $username;
                $model->EQUIPO = Utilities::getClientRealIP();
                //$model->EST_LOG = "1";
                //$model->EST_DEL = "1";
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
                $model = Articulo::findOne(['COD_ART' => $id,]);
                $model->F_A_INV = date('Y-m-d');
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
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrHeader = array(
            financiero::t("articulo", "Code"),
            financiero::t("articulo", "Article Name"),
            financiero::t("lineaarticulo", "Line Name"),
            financiero::t("tipoarticulo", "Type Name"),
            financiero::t("marcaarticulo", "Mark Name"),
            financiero::t("tipoitem", "Product Type"),
            financiero::t("articulo", "Price"),
            financiero::t("articulo", "Stock"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["linea"]  = $data['linea'];
            $arrSearch["tipo"]   = $data['tipo'];
            $arrSearch["marca"]  = $data['marca'];
            $arrSearch["tpro"]  = $data['tpro'];
        }
        $arrData = array();
        $model = new Articulo();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["linea"], $arrSearch["tipo"], $arrSearch["marca"], $arrSearch["tpro"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, NULL, NULL, false, true);
        }
        $nameReport = financiero::t("articulo", "Report Article Items");
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
        $this->view->title = financiero::t("articulo", "Report Article Items");  // Titulo del reporte
        $arrHeader = array(
            financiero::t("articulo", "Code"),
            financiero::t("articulo", "Article Name"),
            financiero::t("lineaarticulo", "Line Name"),
            financiero::t("tipoarticulo", "Type Name"),
            financiero::t("marcaarticulo", "Mark Name"),
            financiero::t("tipoitem", "Product Type"),
            financiero::t("articulo", "Price"),
            financiero::t("articulo", "Stock"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["linea"]  = $data['linea'];
            $arrSearch["tipo"]   = $data['tipo'];
            $arrSearch["marca"]  = $data['marca'];
            $arrSearch["tpro"]  = $data['tpro'];
        }
        $arrData = array();
        $model = new Articulo();
        if (count($arrSearch) > 0) {
            $arrData = $model->getAllItemsGrid($arrSearch["search"], $arrSearch["linea"], $arrSearch["tipo"], $arrSearch["marca"], $arrSearch["tpro"], false, true);
        } else {
            $arrData = $model->getAllItemsGrid(NULL, NULL, NULL, NULL, false, true);
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