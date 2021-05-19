<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as aspirante;

academico::registerTranslations();
financiero::registerTranslations();
aspirante::registerTranslations();
?>
<form class="form-horizontal">
    <?= Html::hiddenInput('txth_ruc_lb', Yii::t("formulario", "RUC"), ['id' => 'txth_ruc_lb']); ?>
    <?= Html::hiddenInput('txth_ced_lb', Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1"), ['id' => 'txth_ced_lb']); ?>
    <?= Html::hiddenInput('txth_pas_lb', Yii::t("formulario", "Passport"), ['id' => 'txth_pas_lb']); ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Invoices") ?></span></h3>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= financiero::t("Pagos", "Billing Data") ?></span></h4> 
            </div>
        </div>    
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombres_fac" data-keydown="true" data-type="alfa" placeholder="<?= Yii::t("formulario", "Names") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellidos_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_apellidos_fac" data-keydown="true" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Names") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_dir_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Address") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_dir_fac" data-type="alfanumerico" placeholder="<?= Yii::t("formulario", "Address") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_tel_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Phone") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="" id="txt_tel_fac" data-keydown="true" data-type="alfa" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                    </div>
                </div>
            </div> 
        </div>    
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="opt_tipo_DNI" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_opciones"><?= Yii::t("formulario", "Type DNI") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">  
                        <label><input type="radio" name="opt_tipo_DNI"  value="1" checked>&nbsp;&nbsp;<b><?= Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?></b></label><br/>
                        <label><input type="radio" name="opt_tipo_DNI"  value="2" ><b>&nbsp;&nbsp;<?= Yii::t("formulario", "RUC") ?></b></label><br/>                                              
                        <label><input type="radio" name="opt_tipo_DNI"  value="3" ><b>&nbsp;&nbsp;<?= Yii::t("formulario", "Passport") ?></b></label>                                              
                    </div>  
                </div>
            </div>
            <!-- <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_dni_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_dni"><? Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_dni_fac" data-type="number" data-lengthMax="10" data-lengthMin="10" placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>    -->  
            <div class='col-md-6 col-sm-6 col-xs-6 col-lg-6' id="DivcedulaFac">  
                <div class="form-group">
                    <label for="txt_dni_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_dni"><?= Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" maxlength="10" class="form-control" value="" id="txt_dni_fac" data-type="cedula" data-keydown="true" placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="display: none;" id="DivpasaporteFac">
                <div class="form-group">
                    <label for="txt_pasaporte_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Passport") ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" maxlength="15" class="form-control keyupmce" id="txt_pasaporte_fac" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Passport") ?>">
                    </div>
                </div>
            </div>        
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="display: none;" id="DivRucFac">
                <div class="form-group">
                    <label for="txt_ruc_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "RUC") ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" maxlength="13" class="form-control keyupmce" id="txt_ruc_fac" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "RUC") ?>">
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_correo_factura" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Email") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation" id="txt_correo_factura" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                    </div>
                </div>
            </div>    
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_total_factura" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Payment Total") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <label id="lbl_total_factura" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-2">
            <a id="paso3back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
        </div>
        <div class="col-md-8"></div>    
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
        <div class="col-md-2">
            <a id="paso3next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Pay") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
        </div>
        <a id="btn_pago_p" href="javascript:" class="btn btn-primary btn-block pbpopup"></a>
    </div>    
</form>