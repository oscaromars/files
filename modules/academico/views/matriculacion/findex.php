<?php

use app\modules\academico\Module as Academico;
use yii\helpers\Url;

Academico::registerTranslations();

if ($isdrop) {
	echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group'>";
	echo "<b style='color:green'>REGISTRO ADICIONAL DE MATERIAS DISPONIBLE HASTA " . $isdrop[0]["rco_fecha_fin_periodoextra"] . "</b>";
	echo "</div>";
} Else

if ($isreg) {
	echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group'>";
	echo "<b style='color:green'>REGISTRO DE MATERIAS ABIERTO HASTA " . $isreg[0]["rco_fecha_fin"] . "</b>";
	echo "</div>";
} Else {
	echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group'>";
	echo "<b style='color:red'>EL PERIODO DE INSCRIPCION ESTA CERRADO</b>";
	echo "</div>";
}

$total = $costo['costo'] + $costo['gastos'] + $costo['asociacion'];

?>
    <style>
    .bottomLess{
        margin-bottom: 0 !important;
        margin-left: 0 !important;
    }
    .paddingLess{
        padding-left: 0 !important;
    }
    </style>

<div>
    <h3><?=Academico::t("matriculacion", "Online Registration")?></h3>
    <br></br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
        <!-- Data -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="lbl_periodo" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?=Academico::t("matriculacion", "Academic Period")?>:</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <span><?=$data_student['pla_periodo_academico']?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="lbl_estudiante" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?=Academico::t("matriculacion", "Student")?>:</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <span><?=$data_student['pes_nombres']?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="lbl_id" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?=Academico::t("matriculacion", "DNI")?>: </label>
                         <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <span><?=$data_student['pes_dni']?></span>
                        </div>
                    </div>
                </div>

            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_unidad" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?=Academico::t("matriculacion", "Academic Unit")?>: </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span> <?=$data_student['pes_unidad']?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_modalidad" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?=Academico::t("matriculacion", "Modality")?>:  </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span> <?=$data_student['mod_nombre']?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_programa" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?=Academico::t("matriculacion", "Career")?>: </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span><?=$data_student['pes_carrera']?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_modalidad" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?=Academico::t("matriculacion", "Registration Number")?>: </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span><?=$data_student['est_matricula']?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_programa" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?=Academico::t("matriculacion", "Phone")?>: </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <span><?=$data_student['per_celular']?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>


<input type="hidden" id="frm_ron_id" value="<?=$ron_id?>">
<input type="hidden" id="frm_pes_id" value="<?=$pes_id?>">
<input type="hidden" id="frm_per_id" value="<?=$per_id?>">
<input type="hidden" id="frm_min_cancel" value="<?=$min_cancel?>">
<input type="hidden" id="frm_modalidad" value="<?=$data_student['mod_id']?>">
<input type="hidden" id="frm_carrera" value="<?=$data_student['eaca_id']?>">
<input type="hidden" id="frm_num_min" value="<?=$num_min?>">
<input type="hidden" id="frm_num_max" value="<?=$num_max?>">
<input type="hidden" id="costoadm" value="<?=$gastoAdm?>">

<br>
<?=
$this->render('findex-grid', ['planificacion' => $planificacion, 'materiasxEstudiante' => $materiasxEstudiante, 'registredSuject' => $registredSuject, "cancelStatus" => $cancelStatus, "ron_id" => $ron_id, "isdrop" => $isdrop, "data_student" => $data_student]);
?>


<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

            <div class="box-body table-responsive">
        <table style="text-align: right;" class="table">
            <tbody>
                <tr>
                    <th style="font-size: 20px;"> Datos de Costos</th>
                </tr>
                <tr>
                    <br>
                    <th style="width:50%"><?=academico::t('matriculacion', 'Cost per Subject')?></th>
                    <td id="costo">$<?=isset($costo['costo']) ? (number_format($costo['costo'], 2, '.', ',')) : '0.00'?></td>
                </tr>
                <tr style='display: none;'>
                    <th><?=academico::t('matriculacion', 'Registration payment')?></th>
                    <td id="costMatr">$<?=isset($modelRonOn->ron_valor_matricula) ? (number_format($modelRonOn->ron_valor_matricula, 2, '.', ',')) : '0.00'?></td>
                </tr>
                <tr>
                    <th><?=academico::t('matriculacion', 'Administrative Expenses')?></th>
                    <td id="costAdmin">$<?=isset($costo['gastos']) ? (number_format($costo['gastos'], 2, '.', ',')) : '0.00'?></td>
                </tr>
                <tr style='display: none;'>
                    <th><?=academico::t('matriculacion', 'Students Association')?></th>
                    <td id="costStud">$<?=isset($aso_est) ? (number_format($aso_est, 2, '.', ',')) : '0.00'?></td>
                </tr>
                <tr>
                    <th style="font-size: 20px;"><?=academico::t('matriculacion', 'Register Cost')?></th>
                    <td style="font-size: 20px; font-weight: bold;" id="costTotal">$<?=isset($total) ? (number_format($total, 2, '.', ',')) : '0.00'?></td>
                </tr>






            </tbody>
        </table>

    <?php if ($howmuchSubject > '1' && !$pagado): ?>
        <a href="<?=Url::to(['/academico/matriculacion/registrodetalle'])?>" class="btn btn-success pull-right" style="margin: 0px 5px;"><?=Academico::t("matriculacion", "Continuar")?></a>
    <?php endif;?>
        <a href="<?=Url::to(['/academico/matriculacion/registroadmin'])?>" class="btn btn-primary pull-right" style="margin: 0px 5px;"><?=Academico::t("matriculacion", "Regresar al Listado")?></a>
 </div>
</div>
