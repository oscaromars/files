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
//print_r($arr_item);
 print_r('ree '. $resp_solicitudescitem['ddit_porcentaje']);
$tipodoc = '';
if (!empty($personalData['per_cedula'])) {
    $tipodoc = "Cédula";
    $docdni = $personalData['per_cedula'];
} else {
    if (!empty($personalData['per_pasaporte'])) {
        $tipodoc = "Pasaporte";
        $docdni = $personalData['per_pasaporte'];
    } else {
        $tipodoc = "Cédula";
        $docdni = $personalData['per_cedula'];
    }
}
$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 450px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';
?>
<?= Html::hiddenInput('txth_ids', base64_encode($per_id), ['id' => 'txth_ids']); ?>
<?= Html::hiddenInput('txth_sins_id', /*base64_decode(*/$_GET['id_sol']/*)*/, ['id' => 'txth_sins_id']); ?>
<?= Html::hiddenInput('txth_opag_id', /*base64_decode(*/$_GET['opag_id']/*)*/, ['id' => 'txth_opag_id']); ?>
<?= Html::hiddenInput('txth_nac', base64_encode($_GET['nac']), ['id' => 'txth_nac']); ?>
<?= Html::hiddenInput('txth_extranjero', $arr_persona['nacionalidad'], ['id' => 'txth_extranjero']); ?>
<?= Html::hiddenInput('txth_intId', base64_encode($int_id), ['id' => 'txth_intId']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= admision::t("Solicitudes", "View Application for Registration") ?></span></h3>
    </div>-->
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= aspirante::t("Aspirante", "Data General Aspirants") ?></span></h4>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellidos" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido'] ?>" id="txt_apellidos" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= $tipodoc ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Request Data") ?></span></h4>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Company") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_empresa", $arr_solicitudesp['emp_id'], $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ninteres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_ninteres", $arr_solicitudesp['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
            <div class="form-group">
                <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Modality") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_modalidad", $arr_solicitudesp['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_carrera" id="lbl_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= academico::t("Academico", "Career/Program") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_carrera", $arr_solicitudesp['eaca_id'], $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera", "disabled" => "true"]) ?>
                </div>
            </div>
            <div class="form-group ccmodestudio hide">
                <label for="cmb_modalidad_estudio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= admision::t("crm", "Academic Study") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_modalidad_estudio", $arr_solicitudesp['mest_id'], array(), ["class" => "form-control", "id" => "cmb_modalidad_estudio" , "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divMetodo" style="display: none">
            <div class="form-group">
                <label for="cmb_metodos" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= admision::t("Solicitudes", "Income Method") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_metodos", 0, array_merge([Yii::t("formulario", "Select")], $arr_metodos), ["class" => "form-control", "id" => "cmb_metodos" , "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divItem" style="display: block">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_item" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= financiero::t("Pagos", "Item") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_item", $arr_solicitudesp['ite_id'], $arr_item, ["class" => "form-control", "id" => "cmb_item" , "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_precio_items" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Price") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $precio_dect ?>" id="txt_precio_items" data-type="alfa" disabled align="rigth" placeholder="<?= financiero::t("Pagos", "Price") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divBeca" style="display: none">
        <div class="form-group">
            <label for="txt_declararbeca" class="col-sm-5 control-label"><?= admision::t("Solicitudes", "Apply Cala Foundation scholarship") ?></label>
            <div class="col-sm-7">
                <label><input type="radio" name="opt_declara_si" disabled id="opt_declara_si" value="1"><b>Si</b></label>
                <label><input type="radio" name="opt_declara_no" disabled  id="opt_declara_no" value="2" checked><b>No</b></label>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divAplicaDescuento" style="display: block">
        <div class="form-group">
            <label for="txt_declararDescuento" class="col-sm-5 control-label"><?= financiero::t("Pagos", "Apply Discount") ?></label>
            <div class="col-sm-7">
            <?php
                if($tiene_desct == '0'){
                    $checkedestno = "checked";
                    $displaydes = "none";
                }else {
                    $checkedestsi = "checked";
                    $displaydes = "block";
                }
            ?>
                <label><input type="radio" name="opt_declara_Dctosi" disabled id="opt_declara_Dctosi" value="1" <?php echo $checkedestsi; ?> ><b>Si</b></label>
                <label><input type="radio" name="opt_declara_Dctono" disabled id="opt_declara_Dctono" value="2" <?php echo $checkedestno; ?> ><b>No</b></label>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divDescuento" style="display: <?php echo $displaydes; ?> ">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_descuento" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= financiero::t("Pagos", "Discount") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_descuento", $resp_solicitudescuento['ddit_id']/*, array_merge([Yii::t("formulario", "Select")]*/, $arr_descuento/*)*/, ["class" => "form-control", "id" => "cmb_descuento", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_precio_item2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Price with discount") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?= $arr_solicitudesp['opag_total'] ?>" id="txt_precio_item2" disabled data-type="alfa" align="rigth" placeholder="<?= financiero::t("Pagos", "Price") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_convenio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Company Agreement") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_convenio", 0, array_merge([Yii::t("formulario", "Select")], $arr_convenio_empresa), ["class" => "form-control", "id" => "cmb_convenio" , "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_observacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_observacion"><?= Yii::t("formulario", "Observations") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <!--<input type="text" class="form-control keyupmce" id="txt_observacion" data-type="alfa" placeholder="<? Yii::t("formulario", "Observations") ?>">-->
                    <textarea  class="form-control keyupmce" id="txt_observacion" disabled rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= financiero::t("Pagos", "Billing Data") ?></span></h4>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" disabled value="<?php echo $arr_solicitudesp['sdfa_nombres']?>" id="txt_nombres_fac" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellidos_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_solicitudesp['sdfa_apellidos'] ?>" id="txt_apellidos_fac" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_dir_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Address") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_solicitudesp['sdfa_direccion'] ?>" id="txt_dir_fac" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "Address") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_tel_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Phone") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control" value="<?php echo $arr_solicitudesp['sdfa_telefono'] ?>" id="txt_tel_fac" data-type="number" disabled data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="opt_tipo_DNI" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Type DNI") ?></label>
                    <div class="col-sm-7">
                        <?php
                            $checked = "";
                            if($arr_solicitudesp['sdfa_tipo_dni'] == 'CED'){
                                $checked = "checked";
                            }elseif ($arr_solicitudesp['sdfa_tipo_dni'] == 'PASS') {
                                $checkepass = "checked";
                            } elseif($arr_solicitudesp['sdfa_tipo_dni'] == 'RUC') {
                                $checkedruc = "checked";
                            }else {
                                $checked = "";
                            }
                        ?>
                        <label><input type="radio" name="opt_tipo_DNI" disabled value="1" <?php echo $checked; ?> >&nbsp;&nbsp;<b><?= Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?></b></label><br/>
                        <label><input type="radio" name="opt_tipo_DNI" disabled value="2" <?php echo $checkeruc; ?>><b>&nbsp;&nbsp;<?= Yii::t("formulario", "RUC") ?></b></label><br/>
                        <label><input type="radio" name="opt_tipo_DNI" disabled value="3" <?php echo $checkepass; ?> ><b>&nbsp;&nbsp;<?= Yii::t("formulario", "Passport") ?></b></label>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_dni_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation " value="<?php echo $arr_solicitudesp['sdfa_dni'] ?>" id="txt_dni_fac" data-type="cedula" disabled data-keydown="true" placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_correo_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="txt_correo_fac"><?= Yii::t("formulario", "Email") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $arr_solicitudesp['sdfa_correo'] ?>" id="txt_correo_fac" data-type="email" data-keydown="true" disabled placeholder="<?= Yii::t("formulario", "Email") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= Html::hiddenInput('txth_ruc_lb', Yii::t("formulario", "RUC"), ['id' => 'txth_ruc_lb']); ?>
    <?= Html::hiddenInput('txth_ced_lb', Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1"), ['id' => 'txth_ced_lb']); ?>
    <?= Html::hiddenInput('txth_pas_lb', Yii::t("formulario", "Passport"), ['id' => 'txth_pas_lb']); ?>
    <?= Html::hiddenInput('txth_extranjero', $txth_extranjero, ['id' => 'txth_extranjero']); ?>
</form>