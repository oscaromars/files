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
<?= Html::hiddenInput('txth_sins_id', base64_encode($sins_id), ['id' => 'txth_sins_id']); ?>
<?= Html::hiddenInput('txth_per_id', base64_encode($per_id), ['id' => 'txth_per_id']); ?>
<?= Html::hiddenInput('txth_int_id', base64_encode($int_id), ['id' => 'txth_int_id']); ?>
<?= Html::hiddenInput('txth_rsin_id', base64_encode($personaData["rsin_id"]), ['id' => 'txth_rsin_id']); ?>
<?= Html::hiddenInput('txth_emp_id', base64_encode($emp_id), ['id' => 'txth_emp_id']); ?>
<?= Html::hiddenInput('txth_cemp_id', $personaData["cemp_id"], ['id' => 'txth_cemp_id']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <!--<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= Yii::t("solicitud_ins", "See Request") ?></span></h3>
    </div>        
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h4><b><span id="lbl_Personeria"><?= Yii::t("formulario", "Attached Files") ?></span></b></h4>    
    </div>-->
    <?php if ($personaData["uaca_id"] < 3) { ?>   
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

        <?php if (($txth_extranjero == "1") or ( empty($txth_extranjero))) { ?>
        <div class="col-md-6  doc_certvota cinteres">
            <div class="form-group">
                <label for="txth_doc_certvota" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Voting Certificate") ?></label>
                <div class="col-sm-7 ">                
        <?php
        echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch3"]) . "' download='" . $arch3 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
        ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($personaData["uaca_id"] == 2) { ?>   
            <!-- <div class="col-md-6  doc_foto cinteres">
                <div class="form-group">
                    <label for="txth_doc_certificado" class="col-sm-4  control-label keyupmce"><? Yii::t("formulario", "Materials Certificate") ?></label>
                    <div class="col-sm-7 ">                
            <?php
            //echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch6"]) . "' download='" . $arch6 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
            ?>
                    </div>
                </div>
            </div> -->    

            <div class="col-md-6  doc_certvota cinteres">
                <div class="form-group">
                    <label for="txth_doc_hojavida" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Curriculum") ?></label>
                    <div class="col-sm-7 ">                
                        <?php
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch7"]) . "' download='" . $arch7 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (!empty($arch5)) { ?>
            <div class="col-md-6  doc_beca cinteres">
                <div class="form-group">
                    <label for="txth_doc_beca" class="col-sm-4 control-label keyupmce"><?= admision::t("Solicitudes", "Scholarship document") ?></label>
                    <div class="col-sm-7 ">                
                        <?php
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch5"]) . "' download='" . $arch5 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>  
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6  doc_foto cinteres">
        <div class="form-group">
            <label for="txth_doc_pago" class="col-sm-4 control-label keyupmce"><?= financiero::t("Pagos", "Payment") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "/uploads/documento/" . $per_id . "/" . $img_pago]) . "' download='" . $img_pago . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pago</a>"
                ?>
            </div>
        </div>
    </div> 
    <?php if ($personaData["cemp_id"] > 0) { ?>            
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6  doc_foto cinteres">
            <div class="form-group">
                <label for="txth_doc_convenio" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Company Agreement") ?></label>
                <div class="col-sm-7 ">                
                    <?php
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch8"]) . "' download='" . $arch8 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                    ?>
                </div>
            </div>
        </div>  
    <?php } ?>
     
</form>
