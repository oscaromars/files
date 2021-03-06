<?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
use yii\helpers\Url;

Academico::registerTranslations();

// \app\models\Utilities::putMessageLogFile($data_student);

?>
<?php if(!isset($rama)){ ?>
    <div>
        <h3><?= Academico::t("matriculacion", 'No tiene materias pendientes de pago') ?></h3> 
    </div>
<?php }else{ ?>
    <style>
    .bottomLess{
        margin-bottom: 0 !important;
        margin-left: 0 !important;
    }
    .paddingLess{
        padding-left: 0 !important;
    }
    </style>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><?= Academico::t("matriculacion", 'Consulta de Registro de Matrícula') ?></h3>
    </div>
<div>
    
    <div class="row" style="border: 1px solid;margin-left: 0 !important;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
        <!-- Data -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group bottomLess paddingLess">
                <div class="form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 paddingLess">
                        <label for="lbl_periodo" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Academico::t("matriculacion", "Academic Period") ?>:</label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <span><?= $data_student['pla_periodo_academico'] ?></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 paddingLess">
                        <label for="lbl_programa" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Academico::t("matriculacion", "Career") ?>: </label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <span><?= $data_student['pes_carrera'] ?></span>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group bottomLess paddingLess">
                <div class="form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 paddingLess">
                        <label for="lbl_estudiante" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Academico::t("matriculacion", "Student") ?>:</label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <span><?= $data_student['pes_nombres'] ?></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 paddingLess">
                        <label for="lbl_modalidad" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Academico::t("matriculacion", "Registration Number") ?>: </label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <span><?= $data_student['est_matricula'] ?></span>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group bottomLess paddingLess">
                <div class="form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 paddingLess">
                        <label for="lbl_id" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Academico::t("matriculacion", "DNI") ?>: </label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <span><?= $data_student['pes_dni'] ?></span>
                        </div>
                    </div> 
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 paddingLess">
                        <label for="lbl_modalidad" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Academico::t("matriculacion", "Dirección") ?>:  </label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <span> <?= $persona['per_domicilio_cpri'] . 
                                        " " . 
                                        $persona['per_domicilio_csec'] . 
                                        " " . 
                                        $persona['per_domicilio_num'] 
                                    ?>
                            </span>
                        </div>
                    </div>
                </div> 
            </div>
    
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group bottomLess paddingLess">
                <div class="form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 paddingLess">
                        <label for="lbl_unidad" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Academico::t("matriculacion", "Academic Unit") ?>: </label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <span> <?= $data_student['pes_unidad'] ?> <?= $data_student['mod_nombre'] ?> </span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 paddingLess">
                        <label for="lbl_programa" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Academico::t("matriculacion", "Phone") ?>: </label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <span><?= $data_student['per_celular'] ?></span>
                        </div>
                    </div>
                </div> 
            </div> </br>
        </div> 
</div>
<input type="hidden" id="frm_per_id" value="<?=$per_id?>">

<div class="form-group">
<?=
    $this->render('registrodetalle-grid-materias', [
        "matDataProvider" => $matDataProvider,
        "valor_total" => $valor_total
    ]);
?>
</div>
<br>
<div class="container">
<div class="centered">
<?=
    $this->render('registrodetalle-grid-pagos', [
        "pagosDataProvider" => $pagosDataProvider,
        "valor_total" => $valor_total
    ]);
?>
</div>
</div>
<br>

<a href="<?= Url::to(['/academico/registro/new', 
                    'id' => base64_encode($persona['per_id']), 
                    'ron' => base64_encode($ron_id),
                    'cuotas' => base64_encode($cuotas),
                    'idtotal' => base64_encode($valor_total),
                    'idpla' => base64_encode($data_student['pla_periodo_academico']),
                    'rama_id' => base64_encode($rama["rama_id"]),
                    'saca_id' => base64_encode($saca_id),
                    'bloque' => base64_encode($bloque),
                ]) ?>" class="btn btn-primary pull-right" style="margin: 0px 5px;"><?= Academico::t("matriculacion", "Continue") ?></a>

<?php } ?>
