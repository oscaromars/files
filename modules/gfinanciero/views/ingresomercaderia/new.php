<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;
use yii\data\ArrayDataProvider;

financiero::registerTranslations();

$token = SearchAutocomplete::getToken();
?>

<form class="form-horizontal">
    <h3 class="page-header"><?= financiero::t("ingresomercaderia", "Entry") ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_tipo" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Entry Type") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo", "0", $arr_tipo, ["class" => "form-control", "id" => "cmb_tipo",]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_fingreso" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Entry Date") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?=
                        DatePicker::widget([
                            'name' => 'frm_fingreso',
                            'value' => date(Yii::$app->params["dateByDefault"]),
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => ["class" => "form-control PBvalidation", "id" => "frm_fingreso", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => financiero::t("articulo", "Expires")],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => Yii::$app->params["dateByDatePicker"],
                            ]]
                        );
                        ?>  
                </div>
            </div>
        </div>
    </div>
    <h3 class="page-header"><?= financiero::t("bodega", "Cellar") ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="autocomplete-bodorig" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Cellar Origin") ?> </label>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'bodorig',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/ingresomercaderia/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putBodegaOrgData',
                            //'defaultValue' => '',
                            'htmlOptions' => ['class' => 'PBvalidations'],
                        ]);
                    ?>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_bodorigsec" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Cellar Origin Sec.")?>" />
                </div>
            </div>
            <div class="form-group">
                <label for="autocomplete-bodorigl" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">&nbsp;</label>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_bodorignom" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Cellar Origin") ?>" />
                </div>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_bodorigdir" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Cellar Origin Address") ?>" />
                </div>
            </div>
        </div>
       
    </div>
    <h3 class="page-header"><?= financiero::t("ingresomercaderia", "Reference") ?></h3>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="autocomplete-proveedor" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("ingresomercaderia", "Provider") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'proveedor',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/ingresomercaderia/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putProveedorData',
                            //'defaultValue' => '',
                            'htmlOptions' => ['class' => 'PBvalidation'],
                        ]);
                    ?>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_proveedorname" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Provider Name") ?>"/>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="autocomplete-articulo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Article") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'articulo',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/ingresomercaderia/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putArticuloData',
                            //'defaultValue' => '',
                            'htmlOptions' => ['class' => 'PBvalidations'],
                        ]);
                    ?>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_articulodesc" data-type="all" disabled="disabled" placeholder="<?= financiero::t("articulo", "Article Name") ?>" />
                </div>
            </div>
        </div>
           
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="frm_itemcant" class="col-xs-1 col-sm-1 col-md-1 col-lg-1 control-label"><?= financiero::t("ingresomercaderia", "Amount") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <input type="text" class="form-control PBvalidation" value="0" id="frm_itemcant" data-type="all" placeholder="<?= financiero::t("ingresomercaderia", "Amount") ?>" />
                </div>
                <label for="frm_p_lista" class="col-xs-1 col-sm-1 col-md-1 col-lg-1  control-label"><?= financiero::t("ingresomercaderia", "List Price") ?></label>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <input type="text" class="form-control PBvalidations" value="0.00" id="frm_p_lista" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "List Price") ?>" />
                </div>
                <label for="frm_p_costo" class="col-xs-1 col-sm-1 col-md-1 col-lg-1  control-label"><?= financiero::t("ingresomercaderia", "Price Cost") ?></label>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <input type="text" class="form-control PBvalidations" value="0.00" id="frm_p_costo" data-type="all"  placeholder="<?= financiero::t("ingresomercaderia", "Price Cost") ?>" />
                </div>
                <label for="frm_t_costo" class="col-xs-1 col-sm-1 col-md-1 col-lg-1 control-label"><?= financiero::t("ingresomercaderia", "Total") ?></label>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_t_costo" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Total") ?>" />
                </div>
            </div>
        </div>  
    </div>
        
    <div class="row">
        <div class="col-md-6">
            <h2 class="text-center hidden">
                <span class="label label-success">
                       <?= financiero::t('ingresomercaderia', 'Liquidated') ?>
                </span>
            </h2>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="col-sm-1 col-md-1 col-xs-1 col-lg-1"></div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                    <a id="btn_updItem" href="javascript:" style="display: none;" class="btn btn-primary btn-block pull-right"> <?= Yii::t("accion", "Update") ?></a>
                </div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                    <a id="btn_canItem" href="javascript:" style="display: none;" class="btn btn-warning btn-block pull-right"> <?= financiero::t("ingresomercaderia", "Cancel") ?></a>
                </div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                    <a id="btn_addItem" href="javascript:" style="display: block;" class="btn btn-primary btn-block pull-right"> <?= Yii::t("formulario", "Add") ?></a>
                </div>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <a id="btn_termItem" href="javascript:" class="btn btn-success btn-block pull-right"> <?= financiero::t("ingresomercaderia", "Items Terms.") ?></a>
                </div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                    <a id="btn_salir" href="javascript:" class="btn btn-danger btn-block pull-right"> <?= financiero::t("ingresomercaderia", "Cancel Process") ?></a>
                </div>
            </div>
        </div>
    </div>
    <?=
    $this->render('new-grid', [
        'model' => new ArrayDataProvider(array()),
    ]);
    ?>
    <br />
    <?=
    $this->render('new-footer', [
        
    ]);
    ?>
</form>

<script type="text/javascript">
    // to avoid close browser
    /*window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        return e.returnValue = "Are you sure you want to exit?";
    });*/
</script>