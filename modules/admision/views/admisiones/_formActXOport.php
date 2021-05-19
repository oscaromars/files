<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$con_nombre = "";
if ($datageneral["tper_id"] == 1) {
    $con_nombre = $datageneral["pges_pri_apellido"] . ' ' . $datageneral["pges_pri_nombre"];
} else {
    $con_nombre = $datageneral["pges_contacto_empresa"];
}
?>
<?= Html::hiddenInput('txth_pgid', $_GET["pgid"], ['id' => 'txth_pgid']); ?>
<div class='row'>   
    <div class='col-md-12'>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ">
            <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Activity log") ?></span></h3>
            <br>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombres" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label" id="lbl_nombres"><?= Yii::t("formulario", "Names") ?></label> 
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $con_nombre ?>" disabled id="txt_nombres" data-type="alfa" placeholder="<?= Yii::t("formulario", "Names") ?>">

                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_pais" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label" id="lbl_pais"><?= Yii::t("formulario", "Country") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $datageneral["pai_nombre"] ?>" disabled id="txt_pais" data-type="alfa" placeholder="<?= Yii::t("formulario", "Country") ?>">                
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_correo" class="col-sm-2 col-md-2 col-xs-2 col-lg-2   control-label" id="lbl_correo"><?= Yii::t("formulario", "Email") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $datageneral["pges_correo"] ?>" disabled id="txt_correo" data-type="alfa" placeholder="<?= Yii::t("formulario", "Email") ?>">                
                </div>
            </div>
        </div>   
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label" id="lbl_celular"><?= Yii::t("formulario", "CellPhone") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">      
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $datageneral["pges_celular"] ?>" disabled id="txt_celular" data-type="alfa" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">                                
                </div>
            </div><br>
        </div> 
        <?php if (base64_decode($_GET['est']) < '3'|| base64_decode($_GET['est']) == '5') { ?>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class='col-md-4 col-xs-4 col-lg-4 col-sm-4'>         
                        <p> <a id="btn_Neopera" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "New Activity") ?></a></p>
                    </div>
                </div>        
            </div> 
        <?php } ?>
    </div>
</div>

