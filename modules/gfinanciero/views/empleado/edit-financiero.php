<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;
use kartik\date\DatePicker;

financiero::registerTranslations();

$token = SearchAutocomplete::getToken();
?>
<br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="cmb_banco" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Bank") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_banco", $model->empl_ids_ban, $arr_banco, ["class" => "form-control", "id" => "cmb_banco", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_cc_banco" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Bank Account Number") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $model->empl_cuenta_bancaria ?>" id="frm_cc_banco" data-type="all" placeholder="<?= financiero::t("empleado", "Bank Account Number") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_tpago" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Payment Method") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_tpago", $model->empl_metodo_pago, $tipo_pago, ["class" => "form-control", "id" => "cmb_tpago", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="autocomplete-cuenta" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Accounting Account") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'cuenta',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/empleado/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putAccountData',
                        'defaultValue' => $cuenta_code,
                        'htmlOptions' => ['class' => 'PBvalidation', ],
                    ]);
                ?>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <input type="text" class="form-control PBvalidations" value="<?= $cuenta_name ?>" disabled="disabled" id="frm_accdesc" data-type="all" disabled="disabled" />
            </div>
        </div>
        <div class="form-group">
            <label for="frm_ingreso" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Date of Admission") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <?=
                DatePicker::widget([
                    'name' => 'frm_ingreso',
                    'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->empl_fecha_ingreso)),
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation", "id" => "frm_ingreso", "data-type" => "fecha", "placeholder" => financiero::t("empleado", "Date of Admission"), ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
        <div class="form-group">
            <label for="frm_cod_vend" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Vendor Code") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $model->empl_cod_vendedor ?>" id="frm_cod_vend" data-type="all" data-lengthMax="3" placeholder="<?= financiero::t("empleado", "Vendor Code") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_ced_foto" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "DNI Picture") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <div class="file-upload-wrapper form-control" data-text="<?= Yii::t('accion', 'LoadFile') ?>" data-textbtn="<?= Yii::t('jslang', 'Upload') ?>">
                    <input type="file" class="PBvalidations file-upload-field" value="" name="frm_ced_foto"  id="frm_ced_foto" data-type="all" placeholder="<?= financiero::t("empleado", "DNI Picture") ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_contr_file" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Agreement PDF") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <div class="file-upload-wrapper form-control" data-text="<?= Yii::t('accion', 'LoadFile') ?>" data-textbtn="<?= Yii::t('jslang', 'Upload') ?>">
                    <input type="file" class="PBvalidations file-upload-field" value="" name="frm_contr_file"  id="frm_contr_file" data-type="all" placeholder="<?= financiero::t("empleado", "Agreement PDF") ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_entr_file" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Admission PDF") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <div class="file-upload-wrapper form-control" data-text="<?= Yii::t('accion', 'LoadFile') ?>" data-textbtn="<?= Yii::t('jslang', 'Upload') ?>">
                    <input type="file" class="PBvalidations file-upload-field" value="" name="frm_entr_file"  id="frm_entr_file" data-type="all" placeholder="<?= financiero::t("empleado", "Admission PDF") ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_departamento" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("departamento", "Department") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_departamento", $dep_id, $arr_departamento, ["class" => "form-control", "id" => "cmb_departamento", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_subdepartamento" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("departamento", "Sub Department") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_subdepartamento", $sdep_id, $arr_subdepartamento, ["class" => "form-control", "id" => "cmb_subdepartamento", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_tipContrato" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Agreement Type") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_tipContrato", $model->tipc_id, $arr_tipoContrato, ["class" => "form-control", "id" => "cmb_tipContrato", ]) ?>
            </div>
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="cmb_cargo" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("cargo", "Charge Name") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_cargo", $cargo, $arr_cargo, ["class" => "form-control", "id" => "cmb_cargo"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_salario" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("cargo", "Salary") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control PBvalidation" value="<?= $salario ?>" id="frm_salario" data-type="all" placeholder="<?= financiero::t("cargo", "Salary") ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_fecha_ssocial" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Social Security Date") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <?=
                DatePicker::widget([
                    'name' => 'frm_fecha_ssocial',
                    'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->empl_fecha_seguro_social)),
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha_ssocial", "data-type" => "fecha", "placeholder" => financiero::t("empleado", "Social Security Date"), ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
        <div class="form-group">
            <label for="frm_freserva" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Reserve Assets") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_freserva" value="<?= $model->empl_fondo_reserva ?>" placeholder="<?= financiero::t("empleado", "Reserve Assets") ?>">
                    <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->empl_fondo_reserva == "1")?"check":"unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_dtercero" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Thirteenth Salary") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_dtercero" value="<?= $model->empl_decimo_tercero ?>" placeholder="<?= financiero::t("empleado", "Thirteenth Salary") ?>">
                    <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->empl_decimo_tercero == "1")?"check":"unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_dcuarto" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Fourteenth Salary") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_dcuarto" value="<?= $model->empl_decimo_cuarto ?>" placeholder="<?= financiero::t("empleado", "Fourteenth Salary") ?>">
                    <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->empl_decimo_cuarto == "1")?"check":"unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_sobretiempo" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Overtime") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_sobretiempo" value="<?= $model->empl_paga_sobretiempo ?>" placeholder="<?= financiero::t("empleado", "Overtime") ?>">
                    <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-<?= ($model->empl_paga_sobretiempo == "1")?"check":"unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="cmb_t_empleado" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Employee Type") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_t_empleado", $model->tipe_id, $arr_t_empleado, ["class" => "form-control", "id" => "cmb_t_empleado", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_tipContribuyente" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Taxpayer Type") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_tipContribuyente", $model->tcon_id, $arr_tipoContribuyente, ["class" => "form-control", "id" => "cmb_tipContribuyente", ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_cargas" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Family Responsibilities") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $model->empl_carga_familiar ?>" id="frm_cargas" data-type="number" placeholder="<?= financiero::t("empleado", "Family Responsibilities") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_discapacidad" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Discapacity") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_discapacidad" value="<?= (isset($model->dis_id) && $model->dis_id != "")?"1":"0" ?>" placeholder="<?= financiero::t("empleado", "Discapacity") ?>">
                    <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus frm_disc"><i class="iconAccStatus glyphicon glyphicon-<?= (isset($model->dis_id) && $model->dis_id != "")?"check":"unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group csdiscp" style="<?= (isset($model->dis_id) && $model->dis_id != "")?"":"display: none;" ?>">
            <label for="frm_tip_disc" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Discapacity Type") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("frm_tip_disc", (isset($model->dis_id) && $model->dis_id != "")?$model->dis_id:0, $arr_discapacidad, ["class" => "form-control", "id" => "frm_tip_disc", ]) ?>
            </div>
        </div>
        <div class="form-group csdiscp" style="<?= (isset($model->dis_id) && $model->dis_id != "")?"":"display: none;" ?>">
            <label for="frm_per_disc" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Discapacity Percentage") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="<?= (isset($model->empl_porcentaje_discapacidad) && $model->empl_porcentaje_discapacidad != "")?$model->empl_porcentaje_discapacidad:0 ?>" id="frm_per_disc" data-type="number" />
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>
</div>