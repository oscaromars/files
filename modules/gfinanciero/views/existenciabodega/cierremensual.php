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
                        //'htmlOptions' => ['class' => 'PBvalidation'],
                    ]);
                ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <input type="text" class="form-control PBvalidations" value="" id="txt_des_com" data-type="all" disabled="disabled" />
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fecha" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Date Update") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fecha',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha", "data-type" => "fecha",  "placeholder" => financiero::t("bodega", "Date Update")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_exitotal" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="checkbox" id="chk_exiTot" value=""> <label for="chk_exiTot" class="control-label"><?= financiero::t("bodega", "Total Existence Update") ?></label>&nbsp;&nbsp;&nbsp;
                
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">
                <a id="btn_procesar" href="javascript:" class="btn btn-primary btn-block"> <?= financiero::t("bodega", "Process") ?></a>
            </div>
    </div>
    
   
   
</form>
