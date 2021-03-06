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
<?= Html::hiddenInput('txth_ipos_id', base64_encode($ipos_id), ['id' => 'txth_igra_id']); ?>
<?= Html::hiddenInput('txth_per_id', base64_encode($per_id), ['id' => 'txth_per_id']); ?>
<?= Html::hiddenInput('txth_emp_id', base64_encode($emp_id), ['id' => 'txth_emp_id']); ?>
<?= Html::hiddenInput('txth_cemp_id', $personaData["cemp_id"], ['id' => 'txth_cemp_id']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-md-6  doc_documento">
        <div class="form-group">
            <label for="txth_doc_documento" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Document") ?></label>
            <div class="col-sm-7 ">
                 <?php
                        if (!empty($arch16)) {
                            echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch16"]) . "' download='" . $arch16 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                        }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch16 . "' >Documento no Cargado</a>";
                }
                    ?>
            </div>
        </div>
    </div>
    <div class="col-md-6  doc_foto">
        <div class="form-group">
            <label for="txth_doc_foto" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Photo") ?></label>
            <div class="col-sm-7 ">
                <?php
                        if (!empty($arch1)) {
                            echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch1"]) . "' download='" . $arch1 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                        }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch1 . "' >Documento no Cargado</a>";
                }
                    ?>
            </div>
        </div>
    </div>
    <!-- <div class="col-md-6  doc_dni">
        <div class="form-group">
            <label for="txth_doc_dni" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "DNI") ?></label>
            <div class="col-sm-7 ">
                 <?php
                if (!empty($arch2)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch2"]) . "' download='" . $arch2 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch2 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>-->
    <!-- <div class="col-md-6  doc_certvota">
        <div class="form-group">
            <label for="txth_doc_certvota" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Voting Certificate") ?></label>
            <div class="col-sm-7 ">
                 <?php
                if (!empty($arch3)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch3"]) . "' download='" . $arch3 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch3 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>-->
    <!-- <div class="col-md-6 doc_titulo">
        <div class="form-group">
            <label for="txth_doc_titulo" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Title") ?></label>
            <div class="col-sm-7 ">
               <?php
                if (!empty($arch4)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch4"]) . "' download='" . $arch4 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch4 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>-->
    <div class="col-md-6  doc_comprobantepago">
        <div class="form-group">
            <label for="txth_doc_comprobantepago" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Comprobante de Pago") ?></label>
            <div class="col-sm-7 ">
                <?php
                if (!empty($arch5)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch5"]) . "' download='" . $arch5 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch5 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- <div class="col-md-6  doc_recordacademico">
        <div class="form-group">
            <label for="txth_doc_record1" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Record Acad??mico") ?></label>
            <div class="col-sm-7 ">
                <?php
                if (!empty($arch6)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch6"]) . "' download='" . $arch6 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch6 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>-->
    <!-- <div class="col-md-6  doc_senecyt">
        <div class="form-group">
            <label for="txth_doc_senecyt" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Senescyt") ?></label>
            <div class="col-sm-7 ">
                <?php
                if (!empty($arch7)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch7"]) . "' download='" . $arch7 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch7 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>-->
    <!-- <div class="col-md-6  doc_hoja_vida">
        <div class="form-group">
            <label for="txth_doc_hojavida" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Hoja de Vida") ?></label>
            <div class="col-sm-7 ">
                 <?php
                if (!empty($arch8)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch8"]) . "' download='" . $arch8 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch8 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>-->
    <!-- <div class="col-md-6  doc_cartarecomendacion">
        <div class="form-group">
            <label for="txth_doc_cartarecomendacion" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Carta de Recomendaci??n") ?></label>
            <div class="col-sm-7 ">
                <?php
                if (!empty($arch9)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch9"]) . "' download='" . $arch9 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch9 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-6  doc_certificadolaboral">
        <div class="form-group">
            <label for="txth_doc_certificadolaboral" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Certificado Laboral") ?></label>
            <div class="col-sm-7 ">
                 <?php
                if (!empty($arch10)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch10"]) . "' download='" . $arch10 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch10 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-6  doc_certificadoingles">
        <div class="form-group">
            <label for="txth_doc_certificadoingles" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Certificado Ingl??s") ?></label>
            <div class="col-sm-7 ">
               <?php
                if (!empty($arch11)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch11"]) . "' download='" . $arch11 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch11 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div><br><br></br>
    </div>-->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Documentos adicionales por homologaci??n") ?></span></h3><br><br></br>
    </div>
    <div class="col-md-6  doc_recordacad">
        <div class="form-group">
            <label for="txth_doc_recordacad" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Record Acad??mico") ?></label>
            <div class="col-sm-7 ">
                 <?php
                if (!empty($arch12)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch12"]) . "' download='" . $arch12 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch12 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6  doc_nosancion">
        <div class="form-group">
            <label for="txth_doc_nosancion" class="col-sm-4 control-label keyupmce"><?= admision::t("Solicitudes", "Certificado No Sanci??n") ?></label>
            <div class="col-sm-7 ">
                <?php
                if (!empty($arch13)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch13"]) . "' download='" . $arch13 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch13 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6  doc_syllabus">
        <div class="form-group">
            <label for="txth_doc_syllabus" class="col-sm-4 control-label keyupmce"><?= financiero::t("Syllabus", "Syllabus") ?></label>
            <div class="col-sm-7 ">
                <?php
                if (!empty($arch14)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch14"]) . "' download='" . $arch14 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch14 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6  doc_homologacion">
        <div class="form-group">
            <label for="txth_doc_homologacion" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Especie valorada por Homologaci??n") ?></label>
            <div class="col-sm-7 ">
               <?php
                if (!empty($arch15)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch15"]) . "' download='" . $arch15 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch15 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>
</form>
