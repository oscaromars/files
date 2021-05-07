<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

financiero::registerTranslations();
academico::registerTranslations();

?>
<?= Html::hiddenInput('txth_uedu_id', $_GET['uedu_id'], ['id' => 'txth_uedu_id']); ?>
<!-- <div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "New User") ?></span><br/>    
</div>-->
<br><br><br><br>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_usuarionew" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", 'Users') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_usuarionew" data-type="all" data-keydown="true" placeholder="<?= Yii::t("formulario", 'Users') ?>"> 
            </div>           
            <label for="txt_nombrenew" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=  Yii::t("formulario", 'Names') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombrenew" data-type="all" data-keydown="true" placeholder="<?= Yii::t("formulario", 'Names') ?>">                    
            </div>  
        </div>
    </div>   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_apellidonew" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", 'Last Names') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_apellidonew" data-type="all" data-keydown="true" placeholder="<?= Yii::t("formulario", 'Last Names') ?>"> 
            </div>           
            <label for="lbl_cedulanew" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "DNI 1") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control keyupmce" value="" id="txt_cedulanew" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "DNI 1") ?>">                    
            </div>  
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_matriculanew" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Enrollment") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control  keyupmce" value="" id="txt_matriculanew" data-type="all" data-keydown="true" placeholder="<?= Yii::t("formulario", "Enrollment") ?>"> 
            </div>           
            <label for="lbl_correonew" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Email") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control keyupmce" value="" id="txt_correonew" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">                    
            </div>  
        </div>
    </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_newusuario" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?></a>
        </div>
    
</div>
</br>
</form>