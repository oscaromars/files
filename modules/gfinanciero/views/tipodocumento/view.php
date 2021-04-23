<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();
?>

<form class="form-horizontal">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_tipo_est" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Point Code") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo_est", $model->COD_PTO, $arr_establecimiento, ["class" => "form-control", "disabled" => "disabled", "id" => "cmb_tipo_est",]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_tipo_emi" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Box Code") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo_emi", $model->COD_CAJ, $arr_emision, ["class" => "form-control", "disabled" => "disabled", "id" => "cmb_tipo_emi",]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_codigo" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Code") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" disabled="disabled" value="<?= $model->TIP_NOF ?>" id="frm_codigo" data-type="all" data-lengthMin="2" data-lengthMax="2" placeholder="<?= financiero::t("tipodocumento", "Code") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_numdocumento" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Document Number") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" disabled="disabled" value="<?= $model->NUM_NOF ?>" id="frm_numdocumento" data-type="all" disabled="disabled" placeholder="<?= financiero::t("tipodocumento", "Document Number") ?>">  
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_nombredocumento" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Document Name") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->NOM_NOF ?>" disabled="disabled" id="frm_nombredocumento" data-type="all"  placeholder="<?= financiero::t("tipodocumento", "Document Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_fechadocumento" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Document Date") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?=
                    DatePicker::widget([
                        'name' => 'frm_fechadocumento',
                        'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->FEC_NOF)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fechadocumento", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => financiero::t("tipodocumento", "Document Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="autocomplete-ctaiva" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Acc. Iva") ?> </label>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <input type="text" class="form-control PBvalidations" value="<?= $model->CTA_IVA ?>" disabled="disabled" id="autocomplete-ctaiva" data-type="all" disabled="disabled" />
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="text" class="form-control PBvalidations" value="<?= $ctaiva ?>" disabled="disabled" id="frm_ctaivadesc" data-type="all" disabled="disabled" />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_iva" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Vat %") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->POR_IVA ?>" disabled="disabled" id="frm_iva" data-type="all" placeholder="<?= financiero::t("tipodocumento", "Vat %") ?>">
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_tipo_trans" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Type Group") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo_trans", $tip_trans, $arr_tipo_trans, ["class" => "form-control", "disabled" => "disabled", "id" => "cmb_tipo_trans",]) ?> 
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_doc" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "DOC") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->TIP_DOC ?>" disabled="disabled" id="frm_doc" data-type="all" data-lengthMin="1" data-lengthMax="2" placeholder="<?= financiero::t("tipodocumento", "DOC") ?>">
                </div>
            </div>
        </div>
        
    </div>

    <div class ="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_tipo_edoc" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Edoc. Tipo") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo_edoc", ($modelTipEdoc->IdDirectorio != "")?($modelTipEdoc->IdDirectorio):"0", $arr_tipo_edoc, ["class" => "form-control", "disabled" => "disabled", "id" => "cmb_tipo_edoc",]) ?> 
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group chkstatus <?=$modelTipEdoc->IdDirectorio ?>" <?= ($modelTipEdoc->IdDirectorio != "")?"":'style="display: none;"'; ?> >
                <label for="frm_status" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Enable Edoc") ?></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_status" value="<?= $model->EDOC_EST ?>" data-type="number" placeholder="<?= financiero::t("tipodocumento", "Enable Edoc") ?>">
                        <span id="spanAccStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAccStatus" class="glyphicon glyphicon-<?= ($model->EDOC_EST == 1)?"check":"unchecked" ?>"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_cantitems" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Quantity Items") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->C_ITEMS ?>" id="frm_cantitems" data-type="number" disabled="disabled" placeholder="<?= financiero::t("tipodocumento", "Quantity Items") ?>">  
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_numdocumento" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("tipodocumento", "Sequence") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo_sec", $model->SEC_AUT, $arr_tipo_sec, ["class" => "form-control", "disabled" => "disabled", "id" => "cmb_tipo_sec",]) ?> 
                </div>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_codpto" value="<?= $model->COD_PTO ?>">
<input type="hidden" id="frm_codcaj" value="<?= $model->COD_CAJ ?>">
<input type="hidden" id="frm_tipnof" value="<?= $model->TIP_NOF ?>">
