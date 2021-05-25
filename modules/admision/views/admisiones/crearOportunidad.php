<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module;

if (!empty($personalData['pges_cedula'])) {
    $tipodoc = "Cédula";
    $dni = $personalData['pges_cedula'];
} else {
    if (!empty($personalData['pges_pasaporte'])) {
        $tipodoc = "Pasaporte";
        $dni = $personalData['pges_pasaporte'];
    } else {
        $tipodoc = "Cédula";
        $dni = $personalData['pges_cedula'];
    }
}
$fecha_actual = date("Y-m-d");
?>
<?= Html::hiddenInput('txth_pgid', $_GET["pgid"], ['id' => 'txth_pgid']); ?>
<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= Module::t("crm", "Create sale opportunity") ?></span></h3><br/>    
</div>
<form class="form-horizontal" enctype="multipart/form-data" > 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Data General Contact") ?></span></h4> 
            </div>
        </div>
        <?php if ($personalData['tper_id'] == 1) { ?>             
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombre1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control keyupmce" value="<?= $personalData['pges_pri_nombre'] . " " . $personalData['pges_seg_nombre'] ?>" id="txt_nombre1" disabled = "true" data-type="alfa" data-keydown="true"> 
                        </div> 
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_apellido1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control keyupmce" value="<?= $personalData['pges_pri_apellido'] . " " . $personalData['pges_seg_apellido'] ?>" id="txt_apellido1" disabled = "true" data-type="alfa" data-keydown="true"> 
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= $tipodoc ?></label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control keyupmce" value="<?= $dni ?>" id="txt_cedula" disabled = "true" data-type="alfa" data-keydown="true"> 
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_celular" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "CellPhone") ?></label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control keyupmce" value="<?= $personalData['pges_celular'] ?>" id="txt_celular" disabled = "true" data-type="alfa" data-keydown="true"> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_correo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Email") ?></label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control keyupmce" value="<?= $personalData['pges_correo'] ?>" id="txt_correo" disabled = "true" data-type="alfa" data-keydown="true"> 
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_pais" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Country") ?></label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control keyupmce" value="<?= $personalData['pais'] ?>" id="txt_pais" disabled = "true" data-type="alfa" data-keydown="true"> 
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?> 
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombre_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Company Name") ?> </label>
                        <span for="txt_nombre_empresa" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= $personalData['pges_razon_social'] ?> </span> 
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_direccion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Address") ?></label>
                        <span for="txt_direccion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= $personalData['pges_direccion_empresa'] ?></span>
                    </div>
                </div> 
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombre_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Contact Name") ?></label>
                        <span for="txt_nombre_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= $personalData['pges_contacto_empresa'] ?></span> 
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_cargo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Charges") ?> </label>
                        <span for="txt_cargo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= $personalData['pges_cargo'] ?> </span> 
                    </div>
                </div> 
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_telefono_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Phone Company") ?></label> 
                        <span for="txt_cargo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= $personalData['pges_cargo'] ?> </span> 
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_numero_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Number of contacts") ?> </label> 
                        <span for="txt_numero_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= $personalData['pges_num_empleados'] ?> </span> 
                    </div>
                </div>                 
            </div> 
        <?php } ?>
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Module::t("crm", "Opportunity Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="cmb_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Company") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_empresa", 0, $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa", "disabled" => "true" ]) ?>
                    </div>
                </div>
            </div>           
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_nivelestudio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Service Line") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_nivelestudio", 1, $arr_linea_servicio, ["class" => "form-control", "id" => "cmb_nivelestudio"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Moda") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", 1, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_tipo_oportunidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Opportunity type") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_tipo_oportunidad", 1, $arr_tipo_oportunidad, ["class" => "form-control", "id" => "cmb_tipo_oportunidad"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_state_opportunity" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Opportunity state") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_state_opportunity", 1, $arr_state_oportunidad, ["class" => "form-control", "id" => "cmb_state_opportunity"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_carrera1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Academic Study") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_carrera1", 1, $arr_academic_study, ["class" => "form-control", "id" => "cmb_carrera1"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_knowledge_channel" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Contact Channel") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_knowledge_channel", 1, $arr_knowledge_channel, ["class" => "form-control", "id" => "cmb_knowledge_channel"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Module::t("crm", "Another interest carreer") ?></span></h4> 
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_carrera2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("academico", "Career") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_carrera2", 0,['0' => Yii::t('formulario', 'Any')] + $arr_carrerra2, ["class" => "form-control", "id" => "cmb_carrera2"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_subcarrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Sub Carrier") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_subcarrera", 0, ['0' => Yii::t('formulario', 'Any')] + $arr_subcarrerra, ["class" => "form-control", "id" => "cmb_subcarrera"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <a id="btn_grabarOportunidad" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("accion", "Save") ?></a> 
            </div>
        </div> 
    </div>
</form>
