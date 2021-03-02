<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>
<!--<div class="col-sm-8">-->
    <form class="form-horizontal">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", "Unity Name") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_name" data-type="all" placeholder="<?= gpr::t("unidad", "Unity Name") ?>">
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", 'Unity Description') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_desc" data-type="all" placeholder="<?= gpr::t("unidad", "Unity Description") ?>">
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_cat" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("categoria", 'Category Name') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_cat", 0, $arr_categoria, ["class" => "form-control", "id" => "cmb_cat"]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_ent" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("entidad", 'Entity Name') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_ent", 0, $arr_entidad, ["class" => "form-control", "id" => "cmb_ent"]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_tunidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", 'Type of Unit') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_tunidad", 0, $arr_tipo_unidad, ["class" => "form-control", "id" => "cmb_tunidad"]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="frm_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", "Unity Status") ?></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_status" value="0" data-type="number" placeholder="<?= gpr::t("unidad", "Unity Status") ?>">
                        <span id="spanAccStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAccStatus" class="glyphicon glyphicon-unchecked"></i></span>
                    </div>
                </div>
            </div>
        </div> 
    </form>
<!--</div>-->
<?php /*
<div class="col-sm-4">
    <form  class="form-horizontal">
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
                            if($value['id'] < 1000){
                        ?>
                        <div class="input-group">
                            <input type="hidden" class="form-control" id="contc_<?= $value['id'] ?>" value="0" data-id="<?= $value['id'] ?>" />
                            <span class="input-group-addon chk-cal"><i class="fa fa-square-o"></i></span>
                            <div class="form-control cal-title"><?= $value['Apellidos'] . " " . $value['Nombres'] ?><span class='lblowner'></span></div>
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