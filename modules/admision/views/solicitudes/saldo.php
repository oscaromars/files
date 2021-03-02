<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;

academico::registerTranslations();
financiero::registerTranslations();
admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_sins_id', base64_encode($sins_id), ['id' => 'txth_sins_id']); ?>
<?= Html::hiddenInput('txth_per_id', base64_encode($per_id), ['id' => 'txth_per_id']); ?>
<?= Html::hiddenInput('txth_int_id', base64_encode($int_id), ['id' => 'txth_int_id']); ?>
<?= Html::hiddenInput('txth_rsin_id', base64_encode($personaData["rsin_id"]), ['id' => 'txth_rsin_id']); ?>
<?= Html::hiddenInput('txth_emp_id', base64_encode($emp_id), ['id' => 'txth_emp_id']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= admision::t("Solicitudes", "Generate enrollment balance") ?></span></h3>
    </div>        
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_numsolicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_solicitud"><?= admision::t("Solicitudes", "Request #") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["num_solicitud"] ?>" id="txt_numsolicitud" disabled="true">                 
                </div>
            </div>
        </div>
    </div>   
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombres" class="col-sm-4 control-label" id="lbl_nombres"><?= Yii::t("formulario", "Names") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["per_nombres"] ?>" id="txt_nombres" disabled="true">                 
                </div>
            </div>
        </div>   

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_apellidos" class="col-sm-4 control-label" id="lbl_apellidos"><?= Yii::t("formulario", "Last Names") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["per_apellidos"] ?>" id="txt_apellidos" disabled="true">                 
                </div>
            </div>
        </div> 
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_correo" class="col-sm-4 control-label" id="lbl_nombres"><?= Yii::t("formulario", "Email") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["per_correo"] ?>" id="txt_nombres" disabled="true">                 
                </div>
            </div>
        </div>   

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-4 control-label" id="lbl_apellidos"><?= Yii::t("formulario", "CellPhone") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["per_celular"] ?>" id="txt_apellidos" disabled="true">                 
                </div>
            </div>
        </div> 
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nivelint" class="col-sm-4 control-label" id="lbl_unidad"><?= academico::t("Academico", "Academic unit") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["uaca_nombre"] ?>" id="txt_nivelint" disabled="true">                 
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_carrera" class="col-sm-4 control-label" id="lbl_carrera"><?= academico::t("Academico", "Career/Program/Course") ?></label> 
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="<?= $personaData["carrera"] ?>" id="txt_carrera" disabled="true">                 
                </div>
            </div>
        </div>    
    </div>      
    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h4><b><span id="lbl_solicitud"><?= admision::t("Solicitudes", "Generate enrollment balance") ?></span></b></h4>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_item" class="col-sm-4 control-label" id="lbl_unidad"><?= financiero::t("Pagos", "Item") ?></label> 
                <div class="col-sm-8 ">
                    <?= Html::dropDownList("cmb_item", 0, array_merge([Yii::t("formulario", "Select")], $arr_item), ["class" => "form-control", "id" => "cmb_item"]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_precio" class="col-sm-4 control-label" id="lbl_carrera"><?= financiero::t("Pagos", "Price") ?></label> 
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control keyupmce" value="0" id="txt_precio" data-type="alfa" align="rigth" disabled="true" placeholder="<?= financiero::t("Pagos", "Price") ?>">
                </div>
            </div>
        </div>    
    </div>      

</form>
