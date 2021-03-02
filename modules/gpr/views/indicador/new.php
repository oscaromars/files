<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", "Indicator Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="" id="frm_name" data-type="all" placeholder="<?= gpr::t("indicador", "Indicator Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="frm_baseline" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", "Base Line Initial") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="" id="frm_baseline" data-type="all" placeholder="<?= gpr::t("indicador", "Base Line Initial") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_objoperativo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivooperativo", 'Operative Objective Name') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_objoperativo", 0, $arr_obj_operativo, ["class" => "form-control", "id" => "cmb_objoperativo"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_medida" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Unit of Measure') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_medida", 0, $arr_unidad_medida, ["class" => "form-control", "id" => "cmb_medida"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="frm_fuente" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", "Source File") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="" id="frm_fuente" data-type="all" placeholder="<?= gpr::t("indicador", "Source File") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="frm_calculo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", "Calculation Method") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="" id="frm_calculo" data-type="all" placeholder="<?= gpr::t("indicador", "Calculation Method") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_jerarquia" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Hierarchy') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_jerarquia", 0, $arr_jerarquia, ["class" => "form-control", "id" => "cmb_jerarquia"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_unidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", 'Unity Name') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_unidad", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_tconfiguracion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("tipoconfiguracion", 'Configuration Type Name') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_tconfiguracion", 0, $arr_tconf, ["class" => "form-control", "id" => "cmb_tconfiguracion"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_patron" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Indicator Pattern') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_patron", 0, $arr_patron, ["class" => "form-control", "id" => "cmb_patron"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Indicator Description') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <textarea class="form-control PBvalidation" value="" id="frm_desc" rows="5" data-type="all" placeholder="<?= gpr::t("indicador", "Indicator Description") ?>"></textarea>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12"><h3><?= gpr::t("indicador", 'Indicator Characteristics') ?></h3></div>
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_compartamiento" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Indicator Behavior') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_compartamiento", 0, $arr_comportamiento, ["class" => "form-control", "id" => "cmb_compartamiento"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_tmeta" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("meta", 'Advance Goal') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_tmeta", 2, $arr_tmeta, ["class" => "form-control", "id" => "cmb_tmeta", "disabled" => "disabled"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_frecuencia" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Indicator Frecuency') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_frecuencia", 0, $arr_frecuencia, ["class" => "form-control", "id" => "cmb_frecuencia"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="frm_fini" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("planificacionpoa", "Poa Planning Initial Date") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fini',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fini", "data-type" => "fecha", "placeholder" => gpr::t("indicador", "Indicator Initial Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12"><h3><?= gpr::t("indicador", 'Grouping of Indicators') ?></h3></div>
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_fraccional" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Fractional Indicator') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_fraccional", 0, $arr_fraccional, ["class" => "form-control", "id" => "cmb_fraccional"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_agrupacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Grouping of Indicators') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_agrupacion", 0, $arr_agrupacion, ["class" => "form-control", "id" => "cmb_agrupacion"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group" id="dvTagr" data-agrp="0" style="display: none;">
            <label for="cmb_tagrupacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("indicador", 'Type of Grouping') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_tagrupacion", 0, $arr_tagrupacion, ["class" => "form-control", "id" => "cmb_tagrupacion"]) ?>  
            </div>
        </div>
    </div> 
</form>