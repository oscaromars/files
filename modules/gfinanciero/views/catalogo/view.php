<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_id" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("catalogo", "Account") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->COD_CTA ?>" id="frm_id" data-type="number" disabled="disabled" placeholder="<?= financiero::t("catalogo", "Account") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("catalogo", "Name Account") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->NOM_CTA ?>" id="frm_name" data-type="alfa" disabled="disabled" placeholder="<?= financiero::t("catalogo", "Name Account") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("catalogo", "Account Type") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?=
                        Html::dropDownList(
                                "cmb_tipcuenta", $model->TIP_CTA, array_merge([financiero::t("catalogo", "-- Select --")], $arrtipCuenta), ["class" => "form-control", "id" => "cmb_tipcuenta", "disabled"=>"disabled"]
                        )
                 ?>
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("catalogo", "Record Type") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?=
                        Html::dropDownList(
                                "cmb_tipregistro", $model->TIP_REG, array_merge([financiero::t("catalogo", "-- Select --")], $arrtipRegistro), ["class" => "form-control", "id" => "cmb_tipregistro","disabled"=>"disabled"]
                        )
                 ?>
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fecha" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("lineaarticulo", "Creation Date") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fecha',
                        'value' => $model->FEC_SIS,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha", "data-type" => "fecha", "disabled" => "disabled", "placeholder" => financiero::t("catalogo", "Creation Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
    </div> 
</form>
<input type="hidden" id="frm_id" value="<?= $model->COD_CTA ?>">