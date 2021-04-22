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
<div class="alert alert-warning alert-dismissible">
    <h4><i class="icon fa fa-warning"></i> <b><?= financiero::t("egresomercaderia", "Alert") ?>!</b></h4>
    <b><?= financiero::t("egresomercaderia", "If you want to cancel the Egress, you must click on the Cancel Process button.") ?></b>
</div>
<form class="form-horizontal">
    <h3 class="page-header"><?= financiero::t("egresomercaderia", "Egress") ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_tipo" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("egresomercaderia", "Egress Type") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo", "0", $arr_tipo, ["class" => "form-control", "id" => "cmb_tipo",]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_fegreso" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("egresomercaderia", "Egress Date") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?=
                        DatePicker::widget([
                            'name' => 'frm_fegreso',
                            'value' => date(Yii::$app->params["dateByDefault"]),
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => ["class" => "form-control PBvalidation", "id" => "frm_fegreso", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => financiero::t("articulo", "Expires")],
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
                <label for="autocomplete-bodorig" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("egresomercaderia", "Cellar Origin") ?> </label>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'bodorig',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/egresomercaderia/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putBodegaOrgData',
                            //'defaultValue' => '',
                            'htmlOptions' => ['class' => 'PBvalidations'],
                        ]);
                    ?>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_bodorigsec" data-type="all" disabled="disabled" placeholder="<?= financiero::t("egresomercaderia", "Cellar Origin Sec.")?>" />
                </div>
            </div>
            <div class="form-group">
                <label for="autocomplete-bodorigl" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">&nbsp;</label>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_bodorignom" data-type="all" disabled="disabled" placeholder="<?= financiero::t("egresomercaderia", "Cellar Origin") ?>" />
                </div>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_bodorigdir" data-type="all" disabled="disabled" placeholder="<?= financiero::t("egresomercaderia", "Cellar Origin Address") ?>" />
                </div>
            </div>
        </div>
        <div class="col-md-6 boddst" style="display: none;">
            <div class="form-group">
                <label for="autocomplete-boddest" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("egresomercaderia", "Cellar Destiny") ?> </label>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'boddest',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/egresomercaderia/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putBodegaDstData',
                            //'defaultValue' => '',
                            'htmlOptions' => ['class' => 'PBvalidations'],
                        ]);
                    ?>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_boddestsec" data-type="all" disabled="disabled" placeholder="<?= financiero::t("egresomercaderia", "Cellar Destiny Sec.") ?>" />
                </div>
            </div>
            <div class="form-group">
                <label for="autocomplete-boddestl" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">&nbsp;</label>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_boddestnom" data-type="all" disabled="disabled" placeholder="<?= financiero::t("egresomercaderia", "Cellar Destiny") ?>" />
                </div>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_boddestdir" data-type="all" disabled="disabled" placeholder="<?= financiero::t("egresomercaderia", "Cellar Destiny Address") ?>" />
                </div>
            </div>
        </div>
    </div>
    <h3 class="page-header"><?= financiero::t("egresomercaderia", "Reference") ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="autocomplete-cliente" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("egresomercaderia", "Client") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'cliente',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/egresomercaderia/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putClienteData',
                            //'defaultValue' => '',
                            'htmlOptions' => ['class' => 'PBvalidation'],
                        ]);
                    ?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_clientename" data-type="all" disabled="disabled" placeholder="<?= financiero::t("cliente", "Client Name") ?>"/>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="autocomplete-articulo" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("articulo", "Article") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'articulo',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/egresomercaderia/index',
                            'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                            'callback' => 'putArticuloData',
                            //'defaultValue' => '',
                            'htmlOptions' => ['class' => 'PBvalidations'],
                        ]);
                    ?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_articulodesc" data-type="all" disabled="disabled" placeholder="<?= financiero::t("articulo", "Article Name") ?>" />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_itemcant" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("egresomercaderia", "Amount") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <input type="text" class="form-control PBvalidation" value="0.00" id="frm_itemcant" data-type="all" placeholder="<?= financiero::t("egresomercaderia", "Amount") ?>" />
                </div>
                <label for="frm_itemcompr" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("egresomercaderia", "Reserved Amount") ?></label>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_itemcompr" data-type="all" disabled="disabled" placeholder="<?= financiero::t("egresomercaderia", "Reserved Amount") ?>" />
                </div>
                <label for="frm_itemdispo" class="col-xs-1 col-sm-1 col-md-1 col-lg-1 control-label"><?= financiero::t("egresomercaderia", "Available Amount") ?></label>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <input type="text" class="form-control PBvalidations" value="" id="frm_itemdispo" data-type="all" disabled="disabled" placeholder="<?= financiero::t("egresomercaderia", "Available Amount") ?>" />
                </div>
            </div>
        </div>     
    </div>
    <div class="row">
        <div class="col-md-6"><h2 class="text-center hidden"><span class="label label-success"><?= financiero::t('egresomercaderia', 'Liquidated') ?></span></h2></div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="col-sm-1 col-md-1 col-xs-1 col-lg-1"></div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                    <a id="btn_updItem" href="javascript:" style="display: none;" class="btn btn-primary btn-block pull-right"> <?= Yii::t("accion", "Update") ?></a>
                </div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                    <a id="btn_canItem" href="javascript:" style="display: none;" class="btn btn-warning btn-block pull-right"> <?= financiero::t("egresomercaderia", "Cancel") ?></a>
                </div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                    <a id="btn_addItem" href="javascript:" style="display: block;" class="btn btn-primary btn-block pull-right"> <?= Yii::t("formulario", "Add") ?></a>
                </div>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <a id="btn_termItem" href="javascript:" class="btn btn-success btn-block pull-right"> <?= financiero::t("egresomercaderia", "Items Terms.") ?></a>
                </div>
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">
                    <a id="btn_salir" href="javascript:" class="btn btn-danger btn-block pull-right"> <?= financiero::t("egresomercaderia", "Cancel Process") ?></a>
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