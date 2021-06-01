<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<form class="form-horizontal">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_salario" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Minimum Salary") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" id="frm_salario" value="<?= number_format($model->crol_salario_minimo, 2, '.', ',') ?>" placeholder="<?= financiero::t("configuracionrol", "Minimum Salary") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_horas" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Working Hours") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" id="frm_horas" value="<?= $model->crol_horas_trabajo ?>" data-type="number" placeholder="<?= financiero::t("configuracionrol", "Working Hours") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class ="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_per_aporte" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Employer Contribution Percentage") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <div class="input-group">
                        <input type="text" class="form-control PBvalidation" id="frm_per_aporte" value="<?= $model->crol_porcentaje_aporte_patronal ?>" placeholder="<?= financiero::t("configuracionrol", "Contribution Biweekly") ?>">
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_aporte_mes" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Contribution Biweekly") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_aporte_mes" value="<?= $model->crol_aporte_mensual_quincena ?>" placeholder="<?= financiero::t("configuracionrol", "Contribution Biweekly") ?>">
                        <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->crol_aporte_mensual_quincena == '1')?"check":"unchecked" ?>"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class ="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_per_iess" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "IESS Percentage") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <div class="input-group">
                        <input type="text" class="form-control PBvalidation" id="frm_per_iess" value="<?= $model->crol_porcentaje_iess ?>" placeholder="<?= financiero::t("configuracionrol", "IESS Percentage") ?>">
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_iess_mes" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "IESS Contribution Biweekly") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_iess_mes" value="<?= $model->crol_iess_mensual_quincena ?>" placeholder="<?= financiero::t("configuracionrol", "IESS Contribution Biweekly") ?>">
                        <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->crol_iess_mensual_quincena == '1')?"check":"unchecked" ?>"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class ="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_transporte" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Transport") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                        <input type="text" class="form-control PBvalidation" id="frm_transporte" value="<?= number_format($model->crol_transporte, 2, '.', ',') ?>" placeholder="<?= financiero::t("configuracionrol", "Transport") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_transporte_mes" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Transport Biweekly") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_transporte_mes" value="<?= $model->crol_transp_mensual_quincena ?>" placeholder="<?= financiero::t("configuracionrol", "Transport Biweekly") ?>">
                        <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->crol_transp_mensual_quincena == '1')?"check":"unchecked" ?>"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class ="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_alimentacion" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Feeding") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                        <input type="text" class="form-control PBvalidation" id="frm_alimentacion" value="<?= number_format($model->crol_alimentacion, 2, '.', ',') ?>" placeholder="<?= financiero::t("configuracionrol", "Feeding") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_alimentacion_mes" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Feeding Biweekly") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_alimentacion_mes" value="<?= $model->crol_alimen_mensul_quincena ?>" placeholder="<?= financiero::t("configuracionrol", "Feeding Biweekly") ?>">
                        <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->crol_alimen_mensul_quincena == '1')?"check":"unchecked" ?>"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_beneficios" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("configuracionrol", "Pay Benefits") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_beneficios" value="<?= $model->crol_paga_benenficios ?>" placeholder="<?= financiero::t("configuracionrol", "Pay Benefits") ?>">
                        <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->crol_paga_benenficios == '1')?"check":"unchecked" ?>"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
</form>