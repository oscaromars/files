<?php

use yii\helpers\Html;
?>
<div class="row">    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-12">
            <div class="form-group">
                <label for="txt_buscarDataPersonat" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
                <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_buscarDataPersonat" placeholder="<?= Yii::t("formulario", "Search by Contact Names") ?>">
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_buscarContactot" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>
