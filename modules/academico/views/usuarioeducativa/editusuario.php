<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

financiero::registerTranslations();
academico::registerTranslations();
//print_r($arr_usuario);
?>
<?= Html::hiddenInput('txth_uedu_id', $_GET['uedu_id'], ['id' => 'txth_uedu_id']); ?>
<!-- <div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Edit User") ?></span><br/>    
</div>-->
<br><br><br><br>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_usuarioview" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", 'Users') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_usuario['uedu_usuario']; ?>" id="txt_usuarioview" data-type="all" data-keydown="true" placeholder="<?= Yii::t("formulario", 'Users') ?>"> 
            </div>           
            <label for="lbl_nombreview" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=  Yii::t("formulario", 'Names') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_usuario['uedu_nombres']; ?>" id="txt_nombreview" data-type="all" data-keydown="true" placeholder="<?= Yii::t("formulario", 'Names') ?>">                    
            </div>  
        </div>
    </div>   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_apellidoview" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", 'Last Names') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_usuario['uedu_apellidos']; ?>" id="txt_apellidoview" data-type="all" data-keydown="true" placeholder="<?= Yii::t("formulario", 'Last Names') ?>"> 
            </div>           
            <label for="lbl_cedulaview" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "DNI 1") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control keyupmce" value="<?php echo $arr_usuario['uedu_cedula']; ?>" id="txt_cedulaview" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "DNI 1") ?>">                    
            </div>  
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_matriculaview" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Enrollment") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control  keyupmce" value="<?php echo $arr_usuario['uedu_matricula']; ?>" id="txt_matriculaview" data-type="all" data-keydown="true" placeholder="<?= Yii::t("formulario", "Enrollment") ?>"> 
            </div>           
            <label for="lbl_correoview" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Email") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control keyupmce" value="<?php echo $arr_usuario['uedu_correo']; ?>" id="txt_correoview" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">                    
            </div>  
        </div>
    </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_editusuario" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Editar") ?></a>
        </div>
    
</div>
</br>
</form>