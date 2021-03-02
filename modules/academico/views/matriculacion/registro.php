<?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
Academico::registerTranslations();


//$total = $model_registroOnline->ron_valor_aso_estudiante + $model_registroOnline->ron_valor_gastos_adm + $model_registroOnline->ron_valor_matricula + $costoMaterias;
$total = $model_registroOnline->ron_valor_aso_estudiante + $model_registroOnline->ron_valor_gastos_adm + $costoMaterias;

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
<input type="hidden" id="frm_ron_id" value="<?= $ron_id ?>">
<br></br>
<?=
    $this->render('registro-grid', ['planificacion' => $planificacion,'materiasxEstudiante' => $materiasxEstudiante,]);
?>
<div class="col-xs-8"><?php echo $leyenda; ?></div>
<div class="col-xs-4">
    <div class="table-responsive">
        <table style="text-align: right;" class="table">
            <tbody>
                <tr>
                    <th style="width:50%"><?= academico::t('matriculacion','Cost per Subject') ?></th>
                    <td id="costMat">$<?= isset($costoMaterias)?(number_format($costoMaterias, 2, '.', ',')):'0.00' ?></td>
                </tr>
                <tr style='display: none;'>
                    <th><?= academico::t('matriculacion','Registration payment') ?></th>
                    <td id="costMatr">$<?= isset($model_registroOnline->ron_valor_matricula)?(number_format($model_registroOnline->ron_valor_matricula, 2, '.', ',')):'0.00' ?></td>
                </tr>
                <tr>
                    <th><?= academico::t('matriculacion','Administrative Expenses') ?></th>
                    <td id="costAdmin">$<?= isset($model_registroOnline->ron_valor_gastos_adm)?(number_format($model_registroOnline->ron_valor_gastos_adm, 2, '.', ',')):'0.00' ?></td>
                </tr>
                <tr>
                    <th><?= academico::t('matriculacion','Students Association') ?></th>
                    <td id="costStud">$<?= isset($model_registroOnline->ron_valor_aso_estudiante)?(number_format($model_registroOnline->ron_valor_aso_estudiante, 2, '.', ',')):'0.00' ?></td>
                </tr>
                <tr>
                    <th style="font-size: 25px;"><?= academico::t('matriculacion', 'Register Cost') ?></th>
                    <td style="font-size: 25px; font-weight: bold;" id="costTotal">$<?= isset($total)?(number_format($total, 2, '.', ',')):'0.00' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="col-xs-12">
<?=
    $this->render('cuotas-grid', ['cuotas' => $cuotas,]);
?>
</div>