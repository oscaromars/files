<?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
use yii\helpers\Url;

Academico::registerTranslations();

if ($isdrop) {
echo "<b style='color:green'>Los Becarios deben ser estudiantes a tiempo completo, no es posible cancelar asignaturas. </b>";
} Else

if ($isreg) {
echo "<b style='color:green'>Los Becarios deben ser estudiantes a tiempo completo, no es posible cancelar asignaturas.</b>";
}
Else {
echo "<b style='color:green'>EL PERIODO DE INSCRIPCION ESTA CERRADO</b>";

}
?>

<div>
    <h3><?= Academico::t("matriculacion", "Cancel Registration") ?></h3>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_periodo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Academic Period") ?>:</label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['pla_periodo_academico'] ?></span>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_estudiante" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Student") ?>:</label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['pes_nombres'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_id" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "SSN/Passport") ?>: </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['pes_dni'] ?></span>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_unidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Academic Unit") ?>:</label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span> <?= $data_student['pes_jornada'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
    
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_modalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Modality") ?>:  </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span> <?= $data_student['mod_nombre'] ?></span>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_programa" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Program") ?>: </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['pes_carrera'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_telefono" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Phone") ?>:  </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['per_celular'] ?></span>
                    </div>
                </div>
            </div>              
        </div>  
    </div>                                                           
</div>
    
 
<input type="hidden" id="frm_ron_id" value="<?= $ron_id ?>">
<input type="hidden" id="frm_pes_id" value="<?= $pes_id ?>">
<input type="hidden" id="frm_min_cancel" value="<?= $min_cancel ?>">

<br>
<?=
    $this->render('registrosch-grid', ['planificacion' => $planificacion,'materiasxEstudiante' => $materiasxEstudiante, 'registredSuject' => $registredSuject, "cancelStatus" => $cancelStatus, "ron_id" => $ron_id,"anulling" => $cancelpending]);
?>


 


 
 
 