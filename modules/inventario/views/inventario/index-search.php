<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\inventario\Module as inventario;


?>
<div class="row">        
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">
            <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= inventario::t("inventario", "Search by Code and Custodian") ?>">
            </div>                        
        </div>
    </div>    
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">            
            <label for="cmb_tipo_bien" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= inventario::t("inventario", "Type Good") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_tipo_bien", 0, $arr_tipo_bien, ["class" => "form-control PBvalidation", "id" => "cmb_tipo_bien"]) ?>                                    
            </div>  
            <label for="cmb_categoria" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= inventario::t("inventario", "Category") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_categoria", 0, $arr_categoria, ["class" => "form-control PBvalidation", "id" => "cmb_categoria"]) ?>                                    
            </div>  
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">            
            <label for="cmb_departamento" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= inventario::t("inventario", "Department") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_departamento", 0, $arr_departamento, ["class" => "form-control PBvalidation", "id" => "cmb_departamento"]) ?>                                    
            </div>                 
            <label for="cmb_area" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= inventario::t("inventario", "Work area") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_area", 0, $arr_area, ["class" => "form-control PBvalidation", "id" => "cmb_area"]) ?>                                    
            </div>      
        </div>
    </div>
   
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarDataInv" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>

