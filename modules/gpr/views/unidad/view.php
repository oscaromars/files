<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();
/*
$classAll = "fa fa-square-o";
$valueAll = 0;
if(count($usuarios) == count($dataUsers)){
    $classAll = "fa fa-check-square";
    $valueAll = 1;
}*/
?>
<!--<div class="col-sm-8">-->
    <form class="form-horizontal">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", "Unity Name") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->ugpr_nombre ?>" id="frm_name" data-type="alfa" disabled="disabled" placeholder="<?= gpr::t("unidad", "Unity Name") ?>">
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", 'Unity Description') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->ugpr_descripcion ?>" id="frm_desc" data-type="alfa" disabled="disabled" placeholder="<?= gpr::t("unidad", "Unity Description") ?>">
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_cat" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("categoria", 'Category Name') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_cat", $cat_id, $arr_categoria, ["class" => "form-control", "id" => "cmb_cat", "disabled" => "disabled", ]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_ent" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("entidad", 'Entity Name') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_ent", $model->ent_id, $arr_entidad, ["class" => "form-control", "id" => "cmb_ent", "disabled" => "disabled", ]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_tunidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", 'Type of Unit') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_tunidad", $model->tuni_id, $arr_tipo_unidad, ["class" => "form-control", "id" => "cmb_tunidad", "disabled" => "disabled", ]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="frm_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", "Unity Status") ?></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_status" value="<?= $model->ugpr_estado ?>" data-type="number" placeholder="<?= gpr::t("unidad", "Unity Status") ?>">
                        <span id="spanModStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconModStatuss" class="<?= ($model->ugpr_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
                    </div>
                </div>
            </div>
        </div> 
    </form>
<!--</div>-->
<?php /*
<div class="col-sm-4">
    <form  role="form" class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-12">
                <h4><?= gpr::t("responsablesubunidad", "Responsible Subunit") ?></h4>
            </div>
        </div>
        <div class="form-group">
            <div class="box box-widget collapsed-boxf box-share">
                <div class="box-body">
                    <div id="external-events" class="share-contact">
                        <?php
                        foreach($dataUsers as $key => $value){
                            $classItem = "fa fa-square-o";
                            $valueItem = 0;
                            foreach($usuarios as $key2 => $value2){
                                if($value['id'] == $value2['usu_id']){
                                    $classItem = "fa fa-check-square";
                                    $valueItem = 1;
                                    break;
                                }
                            }
                            if($value['id'] < 1000){
                        ?>
                        <div class="input-group">
                            <input type="hidden" class="form-control" id="contc_<?= $value['id'] ?>" value="<?= $valueItem ?>" data-id="<?= $value['id'] ?>" />
                            <span class="input-group-addon chk-calOff"><i class="<?= $classItem ?>"></i></span>
                            <div class="form-control cal-titleOff"><?= $value['Apellidos'] . " " . $value['Nombres'] ?><span class='lblowner'></span></div>
                        </div>
                        <?php }} ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
*/ 
?>
<input type="hidden" id="frm_id" value="<?= $model->ugpr_id ?>">