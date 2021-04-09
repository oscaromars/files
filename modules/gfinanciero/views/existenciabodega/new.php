<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
$token = SearchAutocomplete::getToken();

financiero::registerTranslations();

?>

<form class="form-horizontal">

    <h3 class="page-header">
        <?= financiero::t("bodega", "Cellar") ?>
    </h3>
    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="autocomplete-bodega" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("bodega", "Cellar") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'bodega',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/existenciabodega/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putBodega',
                        'defaultValue' => "",
                        'htmlOptions' => ['class' => 'PBvalidation'],
                    ]);
                ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <input type="text" class="form-control PBvalidations" value="<?= $bodega->NOM_BOD ?>" id="txt_nom_bod" data-type="all" disabled="disabled" />
            </div>
        </div>
    </div>    
    
    
    <h3 class="page-header">
        <?= financiero::t("bodega", "Articles") ?>
    </h3>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="autocomplete-articulo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("bodega", "Article Code") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'articulo',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/existenciabodega/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putArticulo',
                        'defaultValue' => "",
                        'htmlOptions' => ['class' => 'PBvalidation'],
                    ]);
                ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <input type="text" class="form-control PBvalidations" value="<?= $bodega->NOM_BOD ?>" id="txt_des_com" data-type="all" disabled="disabled" />
            </div>
        </div>
    </div>    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">        
        <div class="form-group">           
            <label for="lbl_ubi_fis" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("bodega", "Physical location") ?></label>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidation" value="" id="txt_ubi_fis" data-type="all"  placeholder="<?= financiero::t("bodega", "Physical location") ?>">
            </div>
            <label for="txt_p_costo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("bodega", "Price") ?></label>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="input-group">                    
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidation" value="" id="txt_p_costo" data-type="all"  placeholder="<?= financiero::t("bodega", "Price") ?>">
                </div>
            </div>
        </div>
    </div>   
</form>