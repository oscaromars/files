<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\gfinanciero\Module as financiero;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;

financiero::registerTranslations();
$token = SearchAutocomplete::getToken();
?>

<div class="row">
    <form class="form-horizontal">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="autocomplete-articulo" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("articulo", "Article") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'articulo',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/listaprecio/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putArticuloData',
                            //'defaultValue' => '',
                            'htmlOptions' => ['class' => 'PBvalidations'],
                        ]);
                    ?>
                </div>
                <label for="cmb_tipo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("articulo", "Article Name") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_articulodesc" data-type="all" disabled="disabled" placeholder="<?= financiero::t("articulo", "Article Name") ?>" />
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_linea" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("lineaarticulo", "Line") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_linea", "0", $arr_linea, ["class" => "form-control", "id" => "cmb_linea"]) ?>
                </div>
                <label for="cmb_tipo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("tipoarticulo", "Type") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tipo", "0", $arr_tipo, ["class" => "form-control", "id" => "cmb_tipo"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_marca" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("marcaarticulo", "Mark") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_marca", "0", $arr_marca, ["class" => "form-control", "id" => "cmb_marca"]) ?>
                </div>
                <label for="cmb_precio" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("listaprecio", "Price") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_precio", "0", $arr_precio, ["class" => "form-control", "id" => "cmb_precio"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_stock" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("listaprecio", "Stock") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_stock", "0", $arr_stock, ["class" => "form-control", "id" => "cmb_stock"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">
                <a id="btn_buscarData" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("accion", "Print") ?></a>
            </div>
        </div>
    </form>
</div>
<br />
<br />