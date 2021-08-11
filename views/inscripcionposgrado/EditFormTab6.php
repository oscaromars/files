<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;

academico::registerTranslations();
financiero::registerTranslations();
//print_r($arr_condcurriculum);
?>
<?= Html::hiddenInput('txth_igra_id', base64_encode($igra_id), ['id' => 'txth_igra_id']); ?>
<?= Html::hiddenInput('txth_per_id', base64_encode($per_id), ['id' => 'txth_per_id']); ?>
<?= Html::hiddenInput('txth_emp_id', base64_encode($emp_id), ['id' => 'txth_emp_id']); ?>
<?= Html::hiddenInput('txth_cemp_id', $personaData["cemp_id"], ['id' => 'txth_cemp_id']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-md-6 doc_titulo cinteres">
        <div class="form-group">
            <label for="txth_doc_titulo" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Title") ?></label>
            <div class="col-sm-7 ">  
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch1"]) . "' download='" . $arch1 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-6  doc_dni cinteres">
        <div class="form-group">
            <label for="txth_doc_dni" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "DNI") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch2"]) . "' download='" . $arch2 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>
            </div>
        </div>
    </div> 

    <div class="col-md-6  doc_certvota cinteres">
        <div class="form-group">
            <label for="txth_doc_certvota" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Voting Certificate") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch3"]) . "' download='" . $arch3 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>
            </div>
        </div>
    </div>      

    <div class="col-md-6  doc_foto cinteres">
        <div class="form-group">
            <label for="txth_doc_foto" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Photo") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch4"]) . "' download='" . $arch4 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>
            </div>
        </div>
    </div> 

    <div class="col-md-6  doc_comprobantepago cinteres">
        <div class="form-group">
            <label for="txth_doc_comprobantepago" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Comprobante de Pago") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch5"]) . "' download='" . $arch5 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>
            </div>
        </div>
    </div>        

    <div class="col-md-6  doc_recordacademico cinteres">
        <div class="form-group">
            <label for="txth_doc_recordacademico" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Record Académico") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch6"]) . "' download='" . $arch6 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-6  doc_certificado cinteres">
        <div class="form-group">
            <label for="txth_doc_certificado" class="col-sm-4 control-label keyupmce"><?= admision::t("Solicitudes", "Certificado No Sanción") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch7"]) . "' download='" . $arch7 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-6  doc_syllabus cinteres">
        <div class="form-group">
            <label for="txth_doc_syllabus" class="col-sm-4 control-label keyupmce"><?= financiero::t("Syllabus", "Syllabus") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch8"]) . "' download='" . $arch8 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>"
                ?>
            </div>
        </div>
    </div> 

    <div class="col-md-6  doc_homologacion cinteres">
        <div class="form-group">
            <label for="txth_doc_homologacion" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Especie valorada por Homologación") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch9"]) . "' download='" . $arch9 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>
            </div>
        </div>
    </div>  
     
</form>
