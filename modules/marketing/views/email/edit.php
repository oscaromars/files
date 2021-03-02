<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\marketing\Module;
use app\modules\admision\Module as admision;
admision::registerTranslations();

?>
<?= Html::hiddenInput('txth_list_id', base64_encode($list_id), ['id' => 'txth_list_id']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formnewlista">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= Module::t("marketing", "Edit List") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_empresa" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label"><?= Yii::t("formulario", "Company") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <?= Html::dropDownList("cmb_empresa", $respuesta["emp_id"], $arr_empresa, ["class" => "form-control can_combo", "id" => "cmb_empresa"]) ?>
                </div>
            </div>
        </div>        
    </div>
    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h4><span id="lbl_solicitud"><?= Module::t("marketing", "Datos de Lista") ?></span></h4>
    </div>
    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_carrera_programa" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label"><?= admision::t("crm", "Career/Program/Course") ?> <span class="text-danger">*</span> </label> 
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <?= Html::dropDownList("cmb_carrera_programa", $respuesta["codigo_estudio"], $arr_carrera, ["class" => "form-control can_combo", "id" => "cmb_carrera_programa"]) ?>
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_correo_contacto" class="col-sm-4 control-label" id="lbl_correo_contacto"><?= Module::t("marketing", "Contact Email") ?><span class="text-danger">*</span></label> 
                <div class="col-sm-8 ">
                    <?= Html::dropDownList("cmb_correo_empresa", $respuesta["ecor_id"], $arr_correo, ["class" => "form-control can_combo", "id" => "cmb_correo_empresa"]) ?>
                </div>
            </div>
        </div>                  
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">        
        <!--<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombre_contacto" class="col-sm-4 control-label" id="lbl_nombre_contacto"><?= Module::t("marketing", "Name Contact") ?><span class="text-danger">*</span></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombre_contacto" data-type="alfa" placeholder="<?= Module::t("marketing", "Name Contact") ?>" >                 
                </div>
            </div>
        </div> -->
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_pais" class="col-sm-4 control-label" id="lbl_pais"><?= Yii::t("formulario", "Country") ?><span class="text-danger">*</span></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $respuesta["lis_pais"] ?>" id="txt_pais" data-type="alfa" placeholder="<?= Yii::t("formulario", "Country") ?>" disabled="true">        
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_provincia" class="col-sm-4 control-label" id="lbl_provincia"><?= Yii::t("formulario", "State") ?><span class="text-danger">*</span></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $respuesta["lis_provincia"] ?>" id="txt_provincia" data-type="alfa" placeholder="<?= Yii::t("formulario", "State") ?>" disabled="true" >        
                </div>
            </div>
        </div>
    </div>    

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">                
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_ciudad" class="col-sm-4 control-label" id="lbl_ciudad"><?= Yii::t("formulario", "City") ?><span class="text-danger">*</span></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $respuesta["lis_ciudad"] ?>" id="txt_ciudad" data-type="alfa" placeholder="<?= Yii::t("formulario", "City") ?>" disabled="true" >        
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_direccion1" class="col-sm-4 control-label" id="lbl_direccion1"><?= Module::t("marketing", "Address1") ?><span class="text-danger">*</span></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $respuesta["lis_direccion1_empresa"] ?>" id="txt_direccion1" data-type="alfanumerico" placeholder="<?= Module::t("marketing", "Address1") ?>" disabled="true">                 
                </div>
            </div>
        </div>        
    </div>    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">                
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_direccion2" class="col-sm-4 control-label" id="lbl_direccion2"><?= Module::t("marketing", "Address2") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $respuesta["lis_direccion2_empresa"] ?>" id="txt_direccion2" data-type="alfanumerico" placeholder="<?= Module::t("marketing", "Address2") ?>" disabled="true">                 
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_telefono" class="col-sm-4 control-label" id="lbl_telefono"><?= Yii::t("formulario", "Phone") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control " value="<?= $respuesta["lis_telefono_empresa"] ?>" id="txt_telefono" data-type="telefono_sin" placeholder="<?= Yii::t("formulario", "Phone") ?>" disabled="true">                 
                </div>
            </div>
        </div>   
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">                     
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_codigo_postal" class="col-sm-4 control-label" id="lbl_codigo_postal"><?= Yii::t("formulario", "Postal Code") ?><span class="text-danger">*</span></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $respuesta["lis_codigo_postal"] ?>" id="txt_codigo_postal" data-type="alfanumerico" placeholder="<?= Yii::t("formulario", "Postal Code") ?>" disabled="true">                 
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_asunto" class="col-sm-4 control-label" id="lbl_asunto"><?= Module::t("marketing", "Subject") ?><span class="text-danger">*</span></label> 
                <div class="col-sm-8 ">
                    <textarea  class="form-control PBvalidation keyupmce" id="txt_asunto" rows="3" ><?= $respuesta["lis_asunto"] ?></textarea>                                     
                </div>
            </div>
        </div>      
    </div>         
    
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <!--<a id="btn_actualizar" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Send") ?></a>-->
        </div>
    </div>
</form>
