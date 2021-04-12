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
                <label for="autocomplete-bodega" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("bodega", "Cellar") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'bodega',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/tomafisica/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putBodegaData',
                            'defaultValue' => $bodega_id,
                            'htmlOptions' => ['class' => 'PBvalidations'],
                        ]);
                    ?>
                </div>
                <label for="cmb_tipo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("bodega", "Name") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control PBvalidations" value="<?= $bodega_name ?>" id="frm_bodegadesc" data-type="all" disabled="disabled" placeholder="<?= financiero::t("articulo", "Article Name") ?>" />
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_stock" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("listaprecio", "Stock") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_stock", $numex_value, $arr_stock, ["class" => "form-control", "id" => "cmb_stock"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">
                <a id="btn_buscarData" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("accion", "Search") ?></a>
            </div>
        </div>
    </form>
</div>
<br />
<br />
<div class='row'>
    <div>
        <div class="col-md-6 col-xs-6 col-lg-6 col-sm-6"></div>
        <div class="col-md-6 col-xs-6 col-lg-6 col-sm-6">
            <div class="form-group">
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <a id="btn_setTemAct" href="javascript:" class="btn btn-danger btn-block pull-right"> <?= financiero::t("tomafisica", "Update Temporal Stock") ?></a>
                </div>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <a id="btn_printInvVal" href="javascript:" class="btn btn-info btn-block pull-right"> <?= financiero::t("tomafisica", "Valued Inventory Print") ?></a>
                </div>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <a id="btn_printInven" href="javascript:" class="btn btn-success btn-block pull-right"> <?= financiero::t("tomafisica", "Inventory Print") ?></a>
                </div>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <a id="btn_printStock" href="javascript:" class="btn btn-warning btn-block pull-right"> <?= financiero::t("tomafisica", "Physical Stock Print") ?></a>
                </div>
            </div>
        </div>  
    </div>
</div>
<?=
    $this->render('index-grid', [ 
        'model' => $model,
    ]);
?>