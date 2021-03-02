<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

$style = "display: none;";
if($loginPer < 1000){
    $style = "";
}
?>

<div>
    <h3><?= Academico::t("matriculacion", "Online Registration") ?></h3>
    <br></br>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
        <!-- Data -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= Academico::t("matriculacion", "Academic Period") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $data_student['pla_periodo_academico'] ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= Academico::t("matriculacion", "Student") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $data_student['pes_nombres'] ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= Academico::t("matriculacion", "DNI") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $data_student['pes_dni'] ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= Academico::t("matriculacion", "Academic Unit") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= Academico::t("matriculacion", "Modality") ?> <?= $data_student['mod_nombre'] ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= Academico::t("matriculacion", "Career") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $data_student['pes_carrera'] ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= Academico::t("matriculacion", "Phone") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $data_student['per_celular'] ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= Academico::t("matriculacion", "Registration Number") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $data_student['est_matricula'] ?></span>
                </div>                
            </div>
            <!-- <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= Academico::t("matriculacion", "Register Cost") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $cost_total ?></span>
                </div>
            </div>  -->
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
    </div>
</div>
<br></br>
<?=
$this->render('index-grid', ['planificacion' => $planificacion,]);
?>

<div class="col-xs-8"><?php echo $leyenda; ?></div>
<div class="col-xs-4">
    <div class="table-responsive">
        <table style="text-align: right;" class="table">
            <tbody>
                <tr>
                    <th style="width:70%"><?= academico::t('matriculacion','Cost per Subject') ?></th>
                    <td id="costMat">$0.00</td>
                </tr>
                <tr style='<?= $style ?>'>
                    <th><?= academico::t('matriculacion','Registration payment') ?> <small class="text-light-blue">(<?= academico::t('matriculacion', 'Previously Canceled') ?>)</small></th>
                    <td id="costMatr" style="text-decoration:line-through;">$<?= isset($dataMat['MAT-GRAD'])?(number_format($dataMat['MAT-GRAD'], 2, '.', ',')):'0.00' ?></td>
                </tr>
                <tr>
                    <th><?= academico::t('matriculacion','Administrative Expenses') ?></th>
                    <td id="costAdmin">$<?= isset($dataMat['VARIOS'])?(number_format($dataMat['VARIOS'], 2, '.', ',')):'0.00' ?></td>
                </tr>
                <tr>
                    <th><?= academico::t('matriculacion','Students Association') ?></th>
                    <td id="costStud">$<?= isset($dataMat['ASOEST'])?(number_format($dataMat['ASOEST'], 2, '.', ',')):'0.00' ?></td>
                </tr>
                <tr>
                    <th style="font-size: 25px;"><?= academico::t('matriculacion', 'Register Cost') ?></th>
                    <td style="font-size: 25px; font-weight: bold;" id="costTotal">$0.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



<input type="hidden" id="frm_pes_id" value="<?= $pes_id ?>">
<input type="hidden" id="frm_per_id" value="<?= $per_id ?>">
<input type="hidden" id="frm_num_min" value="<?= $num_min ?>">
<input type="hidden" id="frm_num_max" value="<?= $num_max ?>">
<input type="hidden" id="frm_modalidad" value="<?= $data_student['mod_nombre'] ?>">
<input type="hidden" id="frm_carrera" value="<?= $data_student['pes_carrera'] ?>">
<input type="hidden" id="frm_categoria" value="<?= $data_student['est_categoria'] ?>">
<input type="hidden" id="frm_cat_price" value="<?= $CatPrecio ?>">
<input type="hidden" id="frm_asc_est" value="<?= isset($dataMat['ASOEST'])?(number_format($dataMat['ASOEST'], 2, '.', ',')):'0.00' ?>">
<input type="hidden" id="frm_mat_cos" value="<?= isset($dataMat['MAT-GRAD'])?(number_format($dataMat['MAT-GRAD'], 2, '.', ',')):'0.00' ?>">
<input type="hidden" id="frm_gas_adm" value="<?= isset($dataMat['VARIOS'])?(number_format($dataMat['VARIOS'], 2, '.', ',')):'0.00' ?>">