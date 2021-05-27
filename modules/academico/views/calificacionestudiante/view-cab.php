<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as aspirante;

academico::registerTranslations();
financiero::registerTranslations();
aspirante::registerTranslations();
?>

<form class="form-horizontal" enctype="multipart/form-data" id="form">    

    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="txt_nombres" class="col-lg-5 col-md-5 col-sm-4 col-xs-3 control-label" id="lbl_nombres"><?= academico::t("Academico", "Teacher") ?></label>
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-9">
                    <input type="text" class="form-control" value="<?= $profesor ?>" id="txt_nombres" disabled data-type="alfa">
                </div>
            </div>
        </div> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="txt_cedula" class="col-lg-5 col-md-5 col-sm-4 col-xs-3 control-label"><?= academico::t("Academico", 'Enrollment Number') ?></label>
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-9">
                    <input type="text" class="form-control" value="<?= $matricula ?>" id="txt_matricula" data-type="alfa" disabled>
                </div>
            </div>
        </div> 
    </div>

    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="cmb_unidad" class="col-lg-5 col-md-5 col-sm-4 col-xs-3 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-9">
                    <input type="text" class="form-control" value="<?= $unidad ?>" id="txt_unidad" data-type="alfa" disabled>
                </div>
            </div>  
        </div>
        <div class="ol-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="cmb_modalidad" class="col-lg-5 col-md-5 col-sm-4 col-xs-3 control-label"><?= academico::t("Academico", "Modality") ?></label>
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-9">
                   <input type="text" class="form-control" value="<?= $modalidad ?>" id="txt_modalidad" data-type="alfa" disabled>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="cmb_carrera" id="lbl_carrera" class="col-lg-5 col-md-5 col-sm-4 col-xs-3 control-label"><?= academico::t("Academico", "Program") ?></label>
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-9">
                    <input type="text" class="form-control" value="<?= $programa ?>" id="txt_programa" data-type="alfa" disabled>
                </div>
            </div>     
        </div> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="txt_matricula" class="col-lg-5 col-md-5 col-sm-4 col-xs-3 control-label" id="lbl_nombre1"><?= academico::t("Academico", 'Period') ?></label>
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-9">
                    <input type="text" class="form-control" value="<?= $periodo ?>" id="txt_periodo" data-type="alfa" disabled>
                </div>
            </div>
        </div>         
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="cmb_carrera" id="lbl_carrera" class="col-lg-5 col-md-5 col-sm-4 col-xs-3 control-label"><?= academico::t("Academico", "Subject") ?></label>
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-9">
                    <input type="text" class="form-control" value="<?= $asignatura ?>" id="txt_materia" data-type="alfa" disabled>
                </div>
            </div>     
        </div> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="txt_matricula" class="col-lg-5 col-md-5 col-sm-4 col-xs-3 control-label" id="lbl_nombre1"><?= academico::t("estudiantes", 'Final Grade') ?></label>
                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-9">
                    <input type="text" class="form-control" value="<?= $promedio_final ?>" id="txt_calif" data-type="alfa" disabled>
                </div>
            </div>
        </div>         
    </div> 

</form>