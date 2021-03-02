<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\modules\academico\Module as Especies;
use app\modules\financiero\Module as Financiero;

Especies::registerTranslations();
Financiero::registerTranslations();
?>
<?= Html::hiddenInput('txth_cgenid', base64_decode($_GET["cgen_id"]), ['id' => 'txth_cgenid']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud"> 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">        
        <h3>Certificado: <span id="lbl_num_solicitud"><?= app\models\Utilities::add_ceros($model[0]['egen_numero_solicitud'], 9) ?><?php echo "-".$model[0]['identificacion'] ?></span></h3>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Especies::t("Especies", "Datos del Estudiante") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $model[0]['Nombres'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1">Cédula</label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $model[0]['identificacion'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">        
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_ninteres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Academic unit") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_ninteres", $model[0]['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
                    </div>
                </div>  
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
                <div class="form-group">
                    <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Modality") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", $model[0]['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>
                        
        </div>
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general2"><?= Especies::t("Especies", "Resultado Revisión") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="cmb_revision" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Result") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_estado_autoriza", 0, $arrEstados, ["class" => "form-control", "id" => "cmb_estado_autoriza"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txth_doc_pago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Especies::t("Especies", "Certificado") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">                
                        <?php
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "/uploads/certificados/" . $model[0]['imagen']]) . "' download='" . $model[0]['imagen'] . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Certificado</a>"
                        ?>
                    </div>
                </div>
            </div> 
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12' id="Divobservacion" style="display: none;">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" >
                <div class="form-group">
                    <label for="cmb_observacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Observations") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_observacion", 0, $arrObservacion, ["class" => "form-control", "id" => "cmb_observacion"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_observacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_obs1"><?= Yii::t("formulario", "Detail") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <textarea  class="form-control keyupmce" id="txt_observacion" rows="5" placeholder="<?= Yii::t("formulario", "Detail") ?>"></textarea>                           
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">            
            <a id="btn_grabar" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?></a>
        </div>
    </div>
    </div>   
</form>