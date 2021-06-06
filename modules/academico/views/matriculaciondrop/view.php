<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
use app\components\CFileInputAjax;
academico::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("matriculacion", "Student") ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label" style="text-align: left;">
                    <?= $data_student['pes_nombres'] ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("matriculacion", "SSN / Passport") ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label" style="text-align: left;">
                    <?= $data_student['pes_dni'] ?>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("matriculacion", "Career") ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label" style="text-align: left;">
                    <?= $data_student['pes_carrera'] ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("matriculacion", "Phone") ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label" style="text-align: left;">
                    <?= $data_student['per_celular'] ?>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("matriculacion", "Academic Period") ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label" style="text-align: left;">
                    <?= $data_student['pla_periodo_academico'] ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("matriculacion", "Academic Unit") ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label" style="text-align: left;">
                    <?= Academico::t("matriculacion", "Modality") ?> <?= $data_student['mod_nombre'] ?>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_tpago" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("registro", 'Credit') ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <?= Html::dropDownList("cmb_tpago", $value_credit, $arr_credito, ["class" => "form-control", "id" => "cmb_tpago", "disabled" => "disabled"]) ?>  
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_fpago" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("registro", 'Payment Method') ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <?= Html::dropDownList("cmb_fpago", $value_payment, $arr_forma_pago, ["class" => "form-control", "id" => "cmb_fpago", "disabled" => "disabled"]) ?>  
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_int_ced" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("registro", 'Interest on Direct Credit') ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control PBvalidation" value="<?= $value_interes ?>" id="frm_int_ced" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "Interest on Direct Credit") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_finan" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("registro", 'Financing Cost') ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control PBvalidation" value="<?= $value_financiamiento ?>" id="frm_finan" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "Financing Cost") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("registro", 'Payment') ?></label>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label" style="text-align: left;">
                    <a href='<?= Url::to(['registro/downloadpago', 'id' => $rpm_id]) ?>'><?= academico::t("registro", "Download") ?></a>
                </div>
            </div>
        </div> 
    </div>    
</form>
<input type="hidden" id="frm_ron_id" value="<?= $ron_id ?>">
<input type="hidden" id="frm_rpm_id" value="<?= $rpm_id ?>">

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span><?= academico::t("matriculacion", "Number Subjects") ?></span></h3>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<?=
    $this->render('registry-grid', ['materias' => $materias, "materiasxEstudiante" => $materiasxEstudiante, 'ron_id' => $ron_id,]);
?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span><?= academico::t("registro", "Total Payment") ?></span></h3>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= $this->render('../registro/new-grid', ['dataGrid' => $dataGrid]); ?>
</div>