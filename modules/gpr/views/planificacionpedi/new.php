<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("planificacionpedi", "Pedi Planning Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="" id="frm_name" data-type="all" placeholder="<?= gpr::t("planificacionpedi", "Pedi Planning Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("planificacionpedi", "Pedi Planning Description") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <textarea id="frm_desc" class="form-control PBvalidation" rows="5" data-type="all" placeholder="<?= gpr::t("planificacionpedi", "Pedi Planning Description") ?>"></textarea>        
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_ent" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("entidad", 'Entity') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_ent", 0, $arr_entidad, ["class" => "form-control", "id" => "cmb_ent"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fini" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("planificacionpedi", "Pedi Planning Initial Date") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fini',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fini", "data-type" => "fecha", "placeholder" => gpr::t("planificacionpedi", "Pedi Planning Initial Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fend" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("planificacionpedi", "Pedi Planning End Date") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fend',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fend", "data-type" => "fecha", "placeholder" => gpr::t("planificacionpedi", "Pedi Planning Initial Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fupd" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("planificacionpedi", "Pedi Planning Last Update Date") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidations" value="<?= date(Yii::$app->params["dateByDefault"]) ?>" id="frm_fupd" data-type="alfa" disabled="disabled" placeholder="<?= gpr::t("planificacionpedi", "Pedi Planning Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("planificacionpedi", "Pedi Planning Status") ?></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_status" value="0" data-type="number" placeholder="<?= gpr::t("planificacionpedi", "Pedi Planning Status") ?>">
                    <span id="spanAccStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAccStatus" class="glyphicon glyphicon-unchecked"></i></span>
                </div>
            </div>
        </div>
    </div> 
</form>