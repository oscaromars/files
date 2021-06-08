<?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
Academico::registerTranslations();

?>

<div>
    <h3><?= $title ?></h3>
    <br></br>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
        <!-- Data -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><strong><?= Academico::t("matriculacion", "Academic Period") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><?= $data_student['pla_periodo_academico'] ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><strong><?= Academico::t("matriculacion", "Student") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><?= $data_student['pes_nombres'] ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><strong><?= Academico::t("matriculacion", "DNI") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><?= $data_student['pes_dni'] ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><strong><?= Academico::t("matriculacion", "Academic Unit") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><?= Academico::t("matriculacion", "Modality") ?> <?= $data_student['mod_nombre'] ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><strong><?= Academico::t("matriculacion", "Career") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span style="font-size: 20px;"><?= $data_student['pes_carrera'] ?></span>
                </div>                
            </div>
            <br></br>
            <div class="row">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label style="font-size: 20px;" for="txt_issue" class="col-sm-2 control-label"><?= Academico::t("matriculacion", "Issue") ?></label>
                            <div class="col-sm-8 ">
                                <input type="text" class="form-control PBvalidation" id="txt_issue" value="" data-type="all" placeholder="<?= Academico::t("matriculacion", "Issue") ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="font-size: 20px" for="txt_message" class="col-sm-2 control-label"><?= Academico::t("matriculacion", "Message") ?></label>
                            <div class="col-sm-8 ">
                            <textarea class="form-control PBvalidation" id="txt_message" rows="5" value="" data-type="all" placeholder="<?= Academico::t("matriculacion", "Message") ?>"></textarea>
                            </div>
                        </div>
                    </form>                
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
    </div>
</div>
<input type="hidden" id="frm_ron_id" value="<?= $ron_id ?>">
<input type="hidden" id="frm_per_correo" value="<?= $data_student['per_correo'] ?>">
<br></br>