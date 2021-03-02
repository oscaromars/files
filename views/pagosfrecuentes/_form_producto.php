<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\financiero\Module as financiero;

financiero::registerTranslations();
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Product Selection") ?></span></h3>
</div> 
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">            
            <label for="cmb_unidad_solicitud" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "Academic unit") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?= Html::dropDownList("cmb_unidad_solicitud", 0, $arr_ninteres, ["class" => "form-control", "id" => "cmb_unidad_solicitud"]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">            
            <label for="cmb_modalidad_solicitud" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "Mode") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?= Html::dropDownList("cmb_modalidad_solicitud", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad_solicitud"]) ?>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;" id="divmetodocan">
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">            
            <label for="cmb_metodo_solicitud" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label keyupmce"><?= Yii::t("formulario", "Income Method") ?><span class="text-danger">*</span></label>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?= Html::dropDownList("cmb_metodo_solicitud", 0, $arr_metodos, ["class" => "form-control", "id" => "cmb_metodo_solicitud"]) ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divItem" style="display: block">        
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="cmb_item" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label keyupmce"><?= financiero::t("Pagos", "Item") ?></label>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?= Html::dropDownList("cmb_item", 1, $arr_item, ["class" => "form-control", "id" => "cmb_item"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="display:none">
        <div class="form-group">
            <label for="txt_cantidad_item" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Amount") ?></label>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <input type="text" class="form-control keyupmce" value="" id="txt_cantidad_item" data-type="alfa" align="rigth" disabled="true" placeholder="<?= financiero::t("Pagos", "Amount") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_precio_item" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Price") ?></label>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <input type="text" class="form-control keyupmce" value="<?php echo $txt_precio ?>" id="txt_precio_item" data-type="alfa" align="rigth" disabled="true" placeholder="<?= financiero::t("Pagos", "Price") ?>">
            </div>
        </div>
    </div>
</div>
<br/>
<br/>
<div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6"></div>
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class='col-md-7 col-xs-7 col-lg-7 col-sm-7'></div>
        <div class='col-md-3 col-xs-3 col-lg-3 col-sm-3'>         
            <p> <a id="btn_AgregarItem" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Add") ?></a></p>
        </div>
    </div>        
</div> 
<br/>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Productos Seleccionados") ?></span></h3>
</div>
<div id = "dataListItem"></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso2back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
    <div class="col-md-2">
        <a id="paso2next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>