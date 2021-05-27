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
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\gfinanciero\components\CMsgException;
use app\modules\gfinanciero\models\Articulo;
use app\modules\gfinanciero\models\LineaArticulo;
use app\modules\gfinanciero\models\MarcaArticulo;
use app\modules\gfinanciero\models\TipoArticulo;
use app\modules\gfinanciero\models\TipoItem;
use app\modules\gfinanciero\Module as financiero;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;

financiero::registerTranslations();

class ListaprecioController extends CController {

    /**
     * Index Action. List the items from a Model
     *
     * @return void
     */
    public function actionIndex() {
        $data = Yii::$app->request->post();
        if($data['ls_query_id'] == "autocomplete-articulo"){
            $query = $data['ls_query'];
            $currentPage = $data['ls_current_page'];
            $perPage = $data['ls_items_per_page'];
            $arr_data = Articulo::getDataColumnsQueryWidget("I", true, true);
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
        $arr_precio = [
            '0' => financiero::t('listaprecio', '-- All Prices --'), 
            'pv1' => financiero::t('listaprecio', 'SP1'), 
            'pv2' => financiero::t('listaprecio', 'SP2'), 
            'pv3' => financiero::t('listaprecio', 'SP3'), 
            'pv4' => financiero::t('listaprecio', 'SP4'), ];
        $arr_stock = [
            '0' => financiero::t('listaprecio', '-- All --'),
            '1' => '= 0',
            '2' => '> 0',
        ];
        $arr_marca = MarcaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_MAR' => SORT_ASC])->all();
        $arr_marca = ['0' => financiero::t('marcaarticulo', '-- All Marks --')] + ArrayHelper::map($arr_marca, "COD_MAR", "NOM_MAR");
        $arr_linea = LineaArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_LIN' => SORT_ASC])->all();
        $arr_linea = ['0' => financiero::t('lineaarticulo', '-- All Lines --')] + ArrayHelper::map($arr_linea, "COD_LIN", "NOM_LIN");
        $arr_tipo = TipoArticulo::find()->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['NOM_TIP' => SORT_ASC])->all();
        $arr_tipo = ['0' => financiero::t('tipoarticulo', '-- All Types --')] + ArrayHelper::map($arr_tipo, "COD_TIP", "NOM_TIP");
        $_SESSION['JSLANG']['Export'] = financiero::t('listaprecio', "Export");
        $_SESSION['JSLANG']['Select the type format to Export.'] = financiero::t('listaprecio', "Select the type format to Export.");
        $_SESSION['JSLANG']['PDF'] = financiero::t('listaprecio', "PDF");
        $_SESSION['JSLANG']['EXCEL'] = financiero::t('listaprecio', "EXCEL");
        $_SESSION['JSLANG']['Report Price List'] = financiero::t("listaprecio", "Report Price List");

        return $this->render('index',[
            'arr_marca' => $arr_marca,
            'arr_linea' => $arr_linea,
            'arr_tipo' => $arr_tipo,
            'arr_precio' => $arr_precio,
            'arr_stock' => $arr_stock,
        ]);
    }

    /**
     *  Action. Allow to view the page to export
     *
     * @return void
     */
    public function actionPrintlista() {
        ini_set('memory_limit', '256M');
        $this->view->title = financiero::t("listaprecio", "Report Price List");
        $arrHeader = array(
            financiero::t("listaprecio", "Code"),
            financiero::t("listaprecio", "Description"),
            financiero::t("lineaarticulo", "Line"),
            financiero::t("tipoarticulo", "Type"),
            financiero::t("marcaarticulo", "Mark"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["articulo"] = (isset($data['articulo']) && $data['articulo'] != "")?$data['articulo']:NULL;
            $arrSearch["linea"]    = (isset($data['linea']) && $data['linea'] != 0)?$data['linea']:NULL;
            $arrSearch["marca"]    = (isset($data['marca']) && $data['marca'] != 0)?$data['marca']:NULL;
            $arrSearch["tipo"]     = (isset($data['tipo']) && $data['tipo'] != 0)?$data['tipo']:NULL;
            $arrSearch["precio"]   = $data['precio'];
            $arrSearch["stock"]    = (isset($data['stock']) && $data['stock'] != 0)?$data['stock']:NULL;
        }
        if($arrSearch["precio"] === "0"){
            $arrHeader[] = financiero::t("listaprecio", "SP1");
            $arrHeader[] = financiero::t("listaprecio", "SP2");
            $arrHeader[] = financiero::t("listaprecio", "SP3");
            $arrHeader[] = financiero::t("listaprecio", "SP4");
        }else{
            $arrHeader[] = financiero::t("listaprecio", "Sale Price");
        }
        $model = new Articulo();
        $arrData = $model->getPricesListItems($arrSearch["articulo"], $arrSearch["linea"], $arrSearch["tipo"], $arrSearch["marca"], $arrSearch["precio"], $arrSearch["stock"]);
        
        return $this->render('exportpdf', [
            'arr_head' => $arrHeader,
            'arr_body' => $arrData
        ]);
    }

    /**
     * Expexcel Action. Allow to download a Excel document from index page.
     *
     * @return void
     */
    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G");
        $arrHeader = array(
            financiero::t("listaprecio", "Code"),
            financiero::t("listaprecio", "Description"),
            financiero::t("lineaarticulo", "Line"),
            financiero::t("tipoarticulo", "Type"),
            financiero::t("marcaarticulo", "Mark"),
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["articulo"] = (isset($data['articulo']) && $data['articulo'] != "")?$data['articulo']:NULL;
            $arrSearch["linea"]    = (isset($data['linea']) && $data['linea'] != 0)?$data['linea']:NULL;
            $arrSearch["marca"]    = (isset($data['marca']) && $data['marca'] != 0)?$data['marca']:NULL;
            $arrSearch["tipo"]     = (isset($data['tipo']) && $data['tipo'] != 0)?$data['tipo']:NULL;
            $arrSearch["precio"]   = $data['precio'];
            $arrSearch["stock"]    = (isset($data['stock']) && $data['stock'] != 0)?$data['stock']:NULL;
        }
        if($arrSearch["precio"] === "0"){
            $colPosition[] = "H";
            $colPosition[] = "I";
            $colPosition[] = "J";
            $colPosition[] = "K";
            $arrHeader[] = financiero::t("listaprecio", "SP1");
            $arrHeader[] = financiero::t("listaprecio", "SP2");
            $arrHeader[] = financiero::t("listaprecio", "SP3");
            $arrHeader[] = financiero::t("listaprecio", "SP4");
        }else{
            $colPosition[] = "H";
            $arrHeader[] = financiero::t("listaprecio", "Sale Price");
        }
        $model = new Articulo();
        $arrData = $model->getPricesListItems($arrSearch["articulo"], $arrSearch["linea"], $arrSearch["tipo"], $arrSearch["marca"], $arrSearch["precio"], $arrSearch["stock"]);
        $nameReport = financiero::t("listaprecio", "Report Price List");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }
}
