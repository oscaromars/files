<?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
Academico::registerTranslations();

//$total = $model_registroOnline->ron_valor_aso_estudiante + $model_registroOnline->ron_valor_gastos_adm + $model_registroOnline->ron_valor_matricula + $costoMaterias;
$total = $model_registroOnline->ron_valor_aso_estudiante + $model_registroOnline->ron_valor_gastos_adm + $costoMaterias;

?>
<div style="text-align: left; width: 60%; float: left; font-weight: bold; font-size: 19px;"><?= Academico::t('matriculacion', 'Detail Online Register')?></div>
<div style="text-align: right; width: 40%; font-weight: bold; font-size: 19px;"><?= Academico::t('matriculacion', 'Order') . '# ' . $orden?></div>
<div style="clear: both;"></div>
<br></br>
<div>        
    <br></br>
    <div style="margin-bottom: 5px;">
        <div style="width: 200px; float: left;"><span><strong><?= Academico::t("matriculacion", "Academic Period") ?>: </strong></span></div>
        <div><span><?= $data_student['pla_periodo_academico'] ?></span></div>
        <div style="clear: both;"></div>
    </div>
    <div style="margin-bottom: 5px;">
        <div style="width: 200px; float: left;"><span><strong><?= Academico::t("matriculacion", "Student") ?>: </strong></span></div>
        <div><span><?= $data_student['pes_nombres'] ?></span></div>
        <div style="clear: both;"></div>
    </div>
    <div style="margin-bottom: 5px;">
        <div style="width: 200px; float: left;"><span><strong><?= Academico::t("matriculacion", "DNI") ?>: </strong></span></div>
        <div><span><?= $data_student['pes_dni'] ?></span></div>
        <div style="clear: both;"></div>
    </div>
    <div style="margin-bottom: 5px;">
        <div style="width: 200px; float: left;"><span><strong><?= Academico::t("matriculacion", "Academic Unit") ?>: </strong></span></div>
        <div><span><?= Academico::t("matriculacion", "Modality") ?> <?= $data_student['mod_nombre'] ?></span></div>
        <div style="clear: both;"></div>
    </div>
    <div style="margin-bottom: 5px;">
        <div style="width: 200px; float: left;"><span><strong><?= Academico::t("matriculacion", "Career") ?>: </strong></span></div>
        <div><span><?= $data_student['pes_carrera'] ?></span></div>
        <div style="clear: both;"></div>
    </div>    
    <div style="margin-bottom: 5px;">
        <div style="width: 200px; float: left;"><span><strong><?= Academico::t("matriculacion", "Phone") ?>: </strong></span></div>
        <div><span><?= $data_student['per_celular'] ?></span></div>
        <div style="clear: both;"></div>
    </div>    
    <div style="margin-bottom: 5px;">
        <div style="width: 200px; float: left;"><span><strong><?= Academico::t("matriculacion", "Registration Number") ?>: </strong></span></div>
        <div><span><?= $data_student['est_matricula'] ?></span></div>
        <div style="clear: both;"></div>
    </div>
</div>
<br></br>
<?=
    $this->render('exportpdf-grid', ['planificacion' => $planificacion, 'materiasxEstudiante' => $materiasxEstudiante,]);
?>
<br></br>
<div>
    <div style="margin-bottom: 5px;">
        <div style="width: 70%; text-align: right; float: left;"><?= academico::t('matriculacion','Cost per Subject') ?></div>
        <div style="width: 30%; text-align: right;">$<?= isset($costoMaterias)?(number_format($costoMaterias, 2, '.', ',')):'0.00' ?></div>
        <div style="clear: both;"></div>
    </div>
    <div style='display: none; margin-bottom: 5px;'>
        <div style="width: 70%; text-align: right; float: left;"><?= academico::t('matriculacion','Registration payment') ?></div>
        <div style="width: 30%; text-align: right;">$<?= isset($model_registroOnline->ron_valor_matricula)?(number_format($model_registroOnline->ron_valor_matricula, 2, '.', ',')):'0.00' ?></div>
        <div style="clear: both;"></div>
    </div>
    <div style="margin-bottom: 5px;">
        <div style="width: 70%; text-align: right; float: left;"><?= academico::t('matriculacion','Administrative Expenses') ?></div>
        <div style="width: 30%; text-align: right;">$<?= isset($model_registroOnline->ron_valor_gastos_adm)?(number_format($model_registroOnline->ron_valor_gastos_adm, 2, '.', ',')):'0.00' ?></div>
        <div style="clear: both;"></div>
    </div>
    <div style="margin-bottom: 5px;">
        <div style="width: 70%; text-align: right; float: left;"><?= academico::t('matriculacion','Students Association') ?></div>
        <div style="width: 30%; text-align: right;">$<?= isset($model_registroOnline->ron_valor_aso_estudiante)?(number_format($model_registroOnline->ron_valor_aso_estudiante, 2, '.', ',')):'0.00' ?></div>
        <div style="clear: both;"></div>
    </div>
    <div style="margin-bottom: 5px;">
        <div style="width: 70%; text-align: right; float:left;font-weight: bold;"><?= academico::t('matriculacion', 'Register Cost') ?></div>
        <div style="width: 30%; text-align: right; font-weight: bold;">$<?= isset($total)?(number_format($total, 2, '.', ',')):'0.00' ?></div>
        <div style="clear: both;"></div>
    </div>
</div>
<div style="clear: both;"></div>
<br /><br />
<div class="col-xs-12">
<?=
    $this->render('exportcuopdf-grid', ['cuotas' => $cuotas,]);
?>
</div>