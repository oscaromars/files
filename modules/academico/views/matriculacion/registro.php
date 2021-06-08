<?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
use yii\helpers\Url;

Academico::registerTranslations();
// print_r($registredSuject);
 // print_r($costo);
  //print_r($planificacion[0]['CostSubject']);
   //print_r($planificacion);
   //CostSubject
 

if ($isdrop) {
echo "<b style='color:green'>REGISTRO ADICIONAL DE MATERIAS DISPONIBLE HASTA ". $isdrop[0]["rco_fecha_fin_periodoextra"]."</b>";
} Else

if ($isreg) {
echo "<b style='color:green'>REGISTRO DE MATERIAS ABIERTO HASTA ". $isreg[0]["rco_fecha_fin"]."</b>";
}
Else {
echo "<b style='color:green'>EL PERIODO DE INSCRIPCION ESTA CERRADO</b>";

}

 

?>

<div>
    <h3><?= Academico::t("matriculacion", "Online Registration") ?></h3>
    <br></br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
        <!-- Data -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="lbl_periodo" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "Academic Period") ?>:</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <span><?= $data_student['pla_periodo_academico'] ?></span>
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="lbl_estudiante" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "Student") ?>:</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <span><?= $data_student['pes_nombres'] ?></span>
                        </div>
                    </div>
                </div> 
            </div>
        
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="lbl_id" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "DNI") ?>: </label>
                         <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <span><?= $data_student['pes_dni'] ?></span>
                        </div>
                    </div>
                </div> 

            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_unidad" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "Academic Unit") ?>: </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span> <?= $data_student['pes_unidad'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_modalidad" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "Modality") ?>:  </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span> <?= $data_student['mod_nombre'] ?></span>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_programa" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "Career") ?>: </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span><?= $data_student['pes_carrera'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
            
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_modalidad" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "Registration Number") ?>: </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span><?= $data_student['est_matricula'] ?></span>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_programa" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Academico::t("matriculacion", "Phone") ?>: </label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <span><?= $data_student['per_celular'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
                                                           
    </div>
    
   
<input type="hidden" id="frm_ron_id" value="<?= $ron_id ?>">
<input type="hidden" id="frm_pes_id" value="<?= $pes_id ?>">
<input type="hidden" id="frm_per_id" value="<?= $per_id ?>">
<input type="hidden" id="frm_min_cancel" value="<?= $min_cancel ?>">

<br>
<?=
    $this->render('registro-grid', ['planificacion' => $planificacion,'materiasxEstudiante' => $materiasxEstudiante, 'registredSuject' => $registredSuject, "cancelStatus" => $cancelStatus, "ron_id" => $ron_id, "isdrop" => $isdrop, ]);
?>
<?php if($howmuchSubject > '1'): ?>

<div class="col-xs-8"><?php echo $leyenda; ?></div>
<div class="col-xs-4">

<a href="<?= Url::to(['/academico/registro/index', 'per_id' => $per_id, 'costo' => $costo ]) ?>" class="btn btn-primary pull-right" style="margin: 0px 5px;"><?= Academico::t("matriculacion", "Go to Pay") ?></a>
<?php endif; ?>

<a id="btn_registro_detalle" href="javascript:" class="btn btn-primary pull-right" style="margin: 0px 5px;"><?= Academico::t("matriculacion", "Continue") ?></a>

<?php if($cancelStatus == '0'): ?>
<?php endif; ?>



    <a id="btn_registro" href="javascript:" class="btn btn-success pull-right" style="margin: 0px 5px;"><?= Academico::t("matriculacion", "Register More Subjects") ?></a>

    <?php if(isset($hasSubject) && $hasSubject == true && $isadd ==Null): ?>
    <?php endif; ?>
        
    </div>
