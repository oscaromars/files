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
            <label for="frm_min" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Minimal") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= number_format($model->EXI_MIN, 0, '.', ',') ?>" id="frm_min" data-type="number" />
            </div>
        </div>
        <div class="form-group">
            <label for="frm_max" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Maximum") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= number_format($model->EXI_MAX, 0, '.', ',') ?>" id="frm_max" data-type="number" />
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_compr" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Engaged") ?> </label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_COM, 0, '.', ',') ?>" id="frm_compr" data-type="all" />
            </div>
        </div>
        <div class="form-group">
            <label for="frm_exitot" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Total") ?> </label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_TOT, 0, '.', ',') ?>" id="frm_exitot" data-type="all" />
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Amount") ?></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Cost") ?></label>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Initial") ?> </label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->I_I_UNI, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->I_I_COS, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Amount") ?></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Cost") ?></label>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "January") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M01, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M01, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "February") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M02, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M02, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "March") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M03, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M03, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "April") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M04, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M04, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Inventory Date") ?></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"></label>
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"></label>
        </div>
        <div class="form-group">
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_invi',
                        'value' => isset($model->I_F_UNI)?(date(Yii::$app->params["dateByDefault"], strtotime($model->I_F_UNI))):date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidations", "id" => "frm_invi", "data-type" => "fecha", "disabled" => "disabled", "placeholder" => financiero::t("articulo", "Creation Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Amount") ?></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Cost") ?></label>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "May") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M05, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M05, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "June") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M06, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M06, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "July") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M07, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M07, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "August") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M08, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M08, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">&nbsp;</label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label">&nbsp;</label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label">&nbsp;</label>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">&nbsp;</label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"><input type="text" class="form-control" style="visibility: hidden;"/></div>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label">&nbsp;</label>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Amount") ?></label>
            <label class="col-xs-5 col-sm-5 col-md-5 col-lg-5 control-label"><?= financiero::t("articulo", "Cost") ?></label>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "September") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M09, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M09, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "October") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M10, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M10, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "November") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M11, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M11, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "December") ?></label>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->EXI_M12, 0, '.', ',') ?>" data-type="all" />
                    <span class="input-group-addon"><?= financiero::t("articulo", "Units") ?></span>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidations" disabled="disabled" value="<?= number_format($model->P_C_M12, Yii::$app->params['numDecimals'], '.', ',') ?>" data-type="all" />
                </div>
            </div>
        </div>
    </div>
</div>