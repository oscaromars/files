<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>
<br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Current Price") ?></label>
            <label for="frm_fref" class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Current Date") ?></label>
        </div>
        <div class="form-group">
            <label for="frm_pref" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Reference Price") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidation cval" value="0.0000" id="frm_pref" data-type="all" />
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fref',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fref", "data-type" => "fecha", "disabled" => "disabled", "placeholder" => financiero::t("articulo", "Creation Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
        <div class="form-group">
            <label for="frm_pv1po" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "P. V1") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation cper" value="13.04" id="frm_pv1po" data-refid="frm_pv1pr" data-type="all" />
                    <span class="input-group-addon">%</span>
                </div>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="input-group">
                    <label for="frm_pv1pr" style="display: none;"><?= financiero::t("articulo", "P. V1") ?> </label>
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidation cval" value="0.0000" id="frm_pv1pr" data-refid="frm_pv1po" data-type="all" />
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <label for="frm_pv1un" style="display: none;"><?= financiero::t("articulo", "P. V1") ?> </label>
                    <input type="text" class="form-control PBvalidation uval" value="0" id="frm_pv1un" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_pv2po" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "P. V2") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation cper" value="16.67" id="frm_pv2po" data-refid="frm_pv2pr" data-type="all" />
                    <span class="input-group-addon">%</span>
                </div>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="input-group">
                    <label for="frm_pv2pr" style="display: none;"><?= financiero::t("articulo", "P. V2") ?> </label>
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidation cval" value="0.0000" id="frm_pv2pr" data-refid="frm_pv2po" data-type="all" />
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <label for="frm_pv2un" style="display: none;"><?= financiero::t("articulo", "P. V2") ?> </label>
                    <input type="text" class="form-control PBvalidation uval" value="0" id="frm_pv2un" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_pv3po" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "P. V3") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation cper" value="20.00" id="frm_pv3po" data-refid="frm_pv3pr" data-type="all" />
                    <span class="input-group-addon">%</span>
                </div>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="input-group">
                    <label for="frm_pv3pr" style="display: none;"><?= financiero::t("articulo", "P. V3") ?> </label>
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidation cval" value="0.0000" id="frm_pv3pr" data-refid="frm_pv3po" data-type="all" />
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <label for="frm_pv3un" style="display: none;"><?= financiero::t("articulo", "P. V3") ?> </label>
                    <input type="text" class="form-control PBvalidation uval" value="0" id="frm_pv3un" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_pv4po" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "P. V4") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation cper" value="23.08" id="frm_pv4po" data-refid="frm_pv4pr" data-type="all" />
                    <span class="input-group-addon">%</span>
                </div>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="input-group">
                    <label for="frm_pv4pr" style="display: none;"><?= financiero::t("articulo", "P. V4") ?> </label>
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidation cval" value="0.0000" id="frm_pv4pr" data-refid="frm_pv4po" data-type="all" />
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <label for="frm_pv4un" style="display: none;"><?= financiero::t("articulo", "P. V4") ?> </label>
                    <input type="text" class="form-control PBvalidation uval" value="0" id="frm_pv4un" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_descven" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Sale Discount") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="0.00" id="frm_descven" data-type="all" />
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
    </div>
</div>