<?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
use yii\helpers\Url;

Academico::registerTranslations();

?>

<div>
    <h3><?= Academico::t("matriculacion", "Online Registration Cancellation") ?></h3>
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

<br>
<?=
    $this->render('view-grid', ['model' => $model]);
?>