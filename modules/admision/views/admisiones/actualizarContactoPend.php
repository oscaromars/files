<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use \app\models\PersonaPreins;
?>
<?= Html::hiddenInput('txth_idpt', $model['id'], ['id' => 'txth_idpt']); ?>
<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= Yii::t("formulario", "Detail Contact") ?></span><br/>    
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Data General Contact") ?></span></h4>                                  
            </div>
        </div>            
    </div> 
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_name" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $model['nombre'] ?>" id="txt_name" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                </div>
            </div>
        </div>          
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_lname" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $model['apellido'] ?>"  id="txt_lname" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                </div>
            </div>
        </div>          
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_email" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_correo1"><?= Yii::t("formulario", "Email") ?> </label>        
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <div class="input-group">
                        <input type="text" class="form-control PBvalidation" value="<?= $model['correo'] ?>"  id="txt_email" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                    </div>
                </div>
            </div>           
        </div>       
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_cellphone" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_phone1"><?= Yii::t("formulario", "Phone") ?> </label>        
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <div class="input-group">
                        <input type="text" class="form-control " value="<?= $model['celular'] ?>"  id="txt_sphone" data-type="celular_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                    </div>
                </div>
            </div>           
        </div>                  
    </div>   
    <?= Html::hiddenInput('txt_status', '', ['id' => 'txt_status']); ?>
    <?= Html::hiddenInput('txt_observation', '', ['id' => 'txt_observation']); ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div> 
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div> 
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div> 
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div> 
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div> 
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
           <a id="btn_grabarContactTemporal" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?> </a>                                   
        </div>
    </div> 
</form>