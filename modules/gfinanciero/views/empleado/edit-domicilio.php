<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>
<br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="cmb_pai_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Country") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_pai_dom", $pai_dom_id, $arr_pais_dom, ["class" => "form-control", "id" => "cmb_pai_dom", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_pro_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "State") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_pro_dom", $pro_dom_id, $arr_prov_dom, ["class" => "form-control", "id" => "cmb_pro_dom", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_ciu_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "City") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_ciu_dom", $can_dom_id, $arr_ciu_dom, ["class" => "form-control", "id" => "cmb_ciu_dom", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_tel2_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Phone") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_domicilio_celular2 ?>" id="frm_tel2_dom" data-type="all" placeholder="<?= Yii::t("formulario", "Phone") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_sector_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Sector") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_domicilio_sector ?>" id="frm_sector_dom" data-type="all" placeholder="<?= Yii::t("formulario", "Sector") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_callepri_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Main Street") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_domicilio_cpri ?>" id="frm_callepri_dom" data-type="all" placeholder="<?= Yii::t("formulario", "Main Street") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_callesec_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "High Street") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_domicilio_csec ?>" id="frm_callesec_dom" data-type="all" placeholder="<?= Yii::t("formulario", "High Street") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_numeracion_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Numeration") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_domicilio_num ?>" id="frm_numeracion_dom" data-type="all" placeholder="<?= Yii::t("formulario", "Numeration") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_referencia_dom" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Reference") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_domicilio_ref ?>" id="frm_referencia_dom" data-type="all" placeholder="<?= Yii::t("formulario", "Reference") ?>">
            </div>
        </div>
    </div>
</div>