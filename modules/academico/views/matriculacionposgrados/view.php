<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
$tipodoc = 'Cédula';
?>
<?= Html::hiddenInput('txth_sins_id', base64_encode($personalData["sins_id"]), ['id' => 'txth_sins_id']); ?>
<?= Html::hiddenInput('txth_adm_id', $_GET['adm'], ['id' => 'txth_adm_id']); ?>
<?= Html::hiddenInput('txth_per_id', $_GET['perid'], ['id' => 'txth_per_id']); ?>

<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_index"><?= 'Matriculación' ?></span></h3>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Data Contact") ?></span></h4> 
            </div>
        </div>    
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_solicitud" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_solicitudes"><?= admision::t("Solicitudes", "Request #") ?>:</label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $personalData["num_solicitud"] ?>" id="txt_solicitud" data-type="alfa" disabled="true">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_dni" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_dni"><?= Yii::t("formulario", "DNI") ?>: </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $personalData["per_dni"] ?>" id="txt_dni" data-type="alfa" disabled="true">
                    </div>
                </div>
            </div> 
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombres"><?= Yii::t("formulario", "Names") ?>:</label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $personalData["per_nombres"] ?>" id="txt_nombres" data-type="alfa" disabled="true">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellidos" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellidos"><?= Yii::t("formulario", "Last Names") ?>: </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $personalData["per_apellidos"] ?>" id="txt_apellidos" data-type="alfa" disabled="true">
                    </div>
                </div>
            </div> 
        </div>            

        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_unidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombres"><?= academico::t("Academico", "Academic unit") ?>:</label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $personalData["uaca_nombre"] ?>" id="txt_unidad" data-type="alfa" disabled="true">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellidos"><?= academico::t("Academico", "Modality") ?>: </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $personalData["mod_nombre"] ?>" id="txt_modalidad" data-type="alfa" disabled="true">
                    </div>
                </div>
            </div> 
        </div>        
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_programa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_programa"><?= academico::t("Academico", "Career/Program") ?>:</label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $personalData["carrera"] ?>" id="txt_programa" data-type="alfa" disabled="true">
                    </div>
                </div>
            </div>             
        </div>
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <br/><h4><span id="lbl_general1"><?= Yii::t("formulario", "Asignación Paralelo") ?></span></h4> 
            </div>
        </div>        
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_promocion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_promocion"><?= academico::t("Academico", "Promotion") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                    
                        <?= Html::dropDownList("cmb_promocion", $arr_matriculacion["promocion"], $arr_promocion, ["class" => "form-control", "disabled" => "true", "id" => "cmb_promocion"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_paralelo" class="col-sm-5 control-label" id="lbl_periodo"><?= academico::t("Academico", "Parallel") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        <?= Html::dropDownList("cmb_paralelo", $arr_matriculacion["paralelo"], $arr_paralelo, ["class" => "form-control", "disabled" => "true", "id" => "cmb_paralelo"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cupodisponible" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_solicitudes"><?= academico::t("Academico", "Quota Available") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_matriculacion["disponible"] ?>" id="txt_cupodisponible" disabled = "true" data-type="number">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_matricula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_dni"><?= academico::t("Academico", "Enrollment Number") ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_matriculacion["matricula"] ?>" id="txt_matricula" disabled = "true" data-type="number">
                    </div>
                </div>
            </div> 
        </div>
    </div>
</form>