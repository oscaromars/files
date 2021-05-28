<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
$token = SearchAutocomplete::getToken();

financiero::registerTranslations();
?>

<div class="row">
    <form class="form-horizontal">
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_bodega" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("bodega", "Cellar") ?></label>
                <div class="col-sm-4 col-xs-4 col-md-4 col-lg-4">                    
                    <?= Html::dropDownList("cmb_bodega", 0, $bodega, ["class" => "form-control", "id" => "cmb_bodega"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="autocomplete-articulo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("bodega", "Article Code") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?=
                        SearchAutocomplete::widget([
                            'containerId' => 'articulo',
                            'token' => $token,
                            'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/movimientoitem/index',
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
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="frm_fecha" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("bodega", "Date from") ?></label>
                <div class="col-sm-4 col-xs-4 col-md-4 col-lg-4">
                    <?=
                    DatePicker::widget([
                        'name' => 'dtp_fec_ini',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "dtp_fec_ini", "data-type" => "fecha", "placeholder" => financiero::t("bodega", "Date from"),"disabled"=>"disabled"],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?> 
                </div>
                <label for="frm_fecha" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("bodega", "Date Until") ?></label>
                <div class="col-sm-4 col-xs-4 col-md-4 col-lg-4">
                    <?=
                    DatePicker::widget([
                        'name' => 'dtp_fec_fin',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "dtp_fec_fin", "data-type" => "fecha", "placeholder" => financiero::t("bodega", "Date Until")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?> 
                </div>
            </div>
            
        </div>   
        
        <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="txt_search" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("accion", "Search") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_search" placeholder="<?= financiero::t("bodega", "Search by Items") ?>">
                </div>
            </div>
        </div> -->
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_buscarData" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>
    
  
    </form>
</div>
<br />
<h3 class="page-header"><?= financiero::t("bodega", "ITEMS BALANCE") ?></h3>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="frm_ingreso" class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("bodega", "ENTRY") ?></label>
            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="0" id="frm_ingreso" data-type="alfa" disabled="disabled"  >
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="frm_egreso" class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("bodega", "EGRESS") ?></label>
            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="0" id="frm_egreso" data-type="alfa" disabled="disabled"  >
            </div>
        </div>           
    </div>
    <div class="col-md-4">
         <div class="form-group">
            <label for="frm_saldo" class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("bodega", "BALANCE") ?></label>
            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="0" id="frm_saldo" data-type="alfa" disabled="disabled"  >
            </div>
        </div>
    </div>

</div>

<?=
    $this->render('index-grid', ['model' => $model,]);
?>