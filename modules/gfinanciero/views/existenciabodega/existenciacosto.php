<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\gfinanciero\Module as financiero;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;

$token = SearchAutocomplete::getToken();

financiero::registerTranslations();
?>

<form class="form-horizontal">

    <h3 class="page-header">
        <?= financiero::t("bodega", "Existence Cost") ?>
    </h3>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">
            <label for="cmb_bodega" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("bodega", "Cellar") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_bodega", "0", $bodega, ["class" => "form-control", "id" => "cmb_bodega"]) ?>
            </div>            
            
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="autocomplete-articulo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("bodega", "Article Code") ?> </label>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <?=
                SearchAutocomplete::widget([
                    'containerId' => 'articulo',
                    'token' => $token,
                    'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/existenciabodega/index',
                    'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                    'callback' => 'putArticulo',
                    'defaultValue' => "",
                        //'htmlOptions' => ['class' => 'PBvalidation'],
                ]);
                ?>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <input type="text" class="form-control PBvalidations" value="" id="txt_des_com" data-type="all" disabled="disabled" />
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">
            <label for="cmb_linea" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("lineaarticulo", "Line Name") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_linea", "0", $linea_art, ["class" => "form-control", "id" => "cmb_linea"]) ?>
            </div>            
            <label for="cmb_marca" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("marcaarticulo", "Mark Name") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_marca", "0", $marca_art, ["class" => "form-control", "id" => "cmb_marca"]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">
            <label for="cmb_tipo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("tipoarticulo", "Type Name") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_tipo", "0", $tipo_art, ["class" => "form-control", "id" => "cmb_tipo"]) ?>
            </div>
            <label for="cmb_tpro" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("tipoitem", "Product Type") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_tpro", "0", $tprod_art, ["class" => "form-control", "id" => "cmb_tpro"]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">
            <a id="btn_reporte" href="javascript:" class="btn btn-primary btn-block"> <?= financiero::t("bodega", "Print") ?></a>
        </div>
    </div>




</form>
