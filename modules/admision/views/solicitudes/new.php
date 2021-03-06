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
<?= Html::hiddenInput('txth_nac', base64_encode($_GET['nac']), ['id' => 'txth_nac']); ?>
<?= Html::hiddenInput('txth_extranjero', $arr_persona['nacionalidad'], ['id' => 'txth_extranjero']); ?>
<?= Html::hiddenInput('txth_intId', base64_encode($int_id), ['id' => 'txth_intId']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= admision::t("Solicitudes", "Create Application for Registration") ?></span></h3>
    </div>
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
                    <?= Html::dropDownList("cmb_empresa", $emp_id, $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ninteres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_ninteres", 0, /*array_merge([Yii::t("formulario", "Select")],*/ $arr_unidad/*)*/, ["class" => "form-control", "id" => "cmb_ninteres"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
            <div class="form-group">
                <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Modality") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_modalidad", 0, array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_carrera" id="lbl_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= academico::t("Academico", "Career/Program") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_carrera", 0, array_merge([Yii::t("formulario", "Select")], $arr_carrera), ["class" => "form-control", "id" => "cmb_carrera"]) ?>
                </div>
            </div>
            <div class="form-group ccmodestudio hide">
                <label for="cmb_modalidad_estudio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= admision::t("crm", "Academic Study") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_modalidad_estudio", 1, array(), ["class" => "form-control", "id" => "cmb_modalidad_estudio"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divMetodo" style="display: none">
            <div class="form-group">
                <label for="cmb_metodos" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= admision::t("Solicitudes", "Income Method") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_metodos", 0, array_merge([Yii::t("formulario", "Select")], $arr_metodos), ["class" => "form-control", "id" => "cmb_metodos"]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divItem" style="display: block">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_item" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= financiero::t("Pagos", "Item") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_item", 0, array_merge([Yii::t("formulario", "Select")], $arr_item), ["class" => "form-control", "id" => "cmb_item"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_precio_item" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Price") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="0" id="txt_precio_item" data-type="alfa" align="rigth" disabled="true" placeholder="<?= financiero::t("Pagos", "Price") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divBeca" style="display: none">
        <div class="form-group">
            <label for="txt_declararbeca" class="col-sm-5 control-label"><?= admision::t("Solicitudes", "Apply Cala Foundation scholarship") ?></label>
            <div class="col-sm-7">
                <label><input type="radio" name="opt_declara_si"  id="opt_declara_si" value="1"><b>Si</b></label>
                <label><input type="radio" name="opt_declara_no"  id="opt_declara_no" value="2" checked><b>No</b></label>
            </div>
        </div>
    </div>


   <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divAplicaAcuerdo" style="display: block">
        <div class="form-group">
            <label for="txt_declararAcuerdo" class="col-sm-5 control-label"><?= financiero::t("Pagos", "Apply agreement") ?></label>
            <div class="col-sm-7">
                <label><input type="radio" name="opt_agree_Dctosi"  id="opt_agree_Dctosi" value="1"><b>Si</b></label>
                <label><input type="radio" name="opt_agree_Dctono"  id="opt_agree_Dctono" value="2" checked><b>No</b></label>
            </div>
        </div>
    </div>
    
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divConvenio" style="display: none">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_descuento" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= financiero::t("Pagos", "Discount") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_descuento", 0, array_merge([Yii::t("formulario", "Select")], $arr_descuento), ["class" => "form-control", "id" => "cmb_descuento"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_precio_item2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Price with discount") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="0" id="txt_precio_item2" disabled data-type="alfa" align="rigth" placeholder="<?= financiero::t("Pagos", "Price") ?>">
                </div>
            </div>
        </div>
    </div> 
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divConvenioID" style="display: none">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_convenio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Company Agreement") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_convenio", 0, array_merge([Yii::t("formulario", "Select")], $arr_convenio_empresa), ["class" => "form-control", "id" => "cmb_convenio"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12' id="divConvenioObs" style="display: none">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_observacionc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_observacion"><?= Yii::t("formulario", "Observations") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <!--<input type="text" class="form-control keyupmce" id="txt_observacionc" data-type="alfa" placeholder="<? Yii::t("formulario", "Observations") ?>">-->
                    <textarea  class="form-control keyupmce" id="txt_observacionc" rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divAplicaDescuento" style="display: block">
        <div class="form-group">
            <label for="txt_declararDescuento" class="col-sm-5 control-label"><?= financiero::t("Pagos", "Apply Discount") ?></label>
            <div class="col-sm-7">
                <label><input type="radio" name="opt_declara_Dctosi"  id="opt_declara_Dctosi" value="1"><b>Si</b></label>
                <label><input type="radio" name="opt_declara_Dctono"  id="opt_declara_Dctono" value="2" checked><b>No</b></label>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divDescuento" style="display: none">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_descuento" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= financiero::t("Pagos", "Discount") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_descuento", 0, array_merge([Yii::t("formulario", "Select")], $arr_descuento), ["class" => "form-control", "id" => "cmb_descuento"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_precio_item2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Price with discount") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="0" id="txt_precio_item2" disabled data-type="alfa" align="rigth" placeholder="<?= financiero::t("Pagos", "Price") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12' id="divObservacion" style="display: none">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_observacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_observacion"><?= Yii::t("formulario", "Observations") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <!--<input type="text" class="form-control keyupmce" id="txt_observacion" data-type="alfa" placeholder="<? Yii::t("formulario", "Observations") ?>">-->
                    <textarea  class="form-control keyupmce" id="txt_observacion" rows="3"></textarea>
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
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre'] ?>" id="txt_nombres_fac" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellidos_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido'] ?>" id="txt_apellidos_fac" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_dir_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Address") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="" id="txt_dir_fac" data-type="alfa" placeholder="<?= Yii::t("formulario", "Address") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_tel_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Phone") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control" value="" id="txt_tel_fac" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="opt_tipo_DNI" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Type DNI") ?></label>
                    <div class="col-sm-7">
                        <label><input type="radio" name="opt_tipo_DNI"  value="1" checked>&nbsp;&nbsp;<b><?= Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?></b></label><br/>
                        <label><input type="radio" name="opt_tipo_DNI"  value="2" ><b>&nbsp;&nbsp;<?= Yii::t("formulario", "RUC") ?></b></label><br/>
                        <label><input type="radio" name="opt_tipo_DNI"  value="3" ><b>&nbsp;&nbsp;<?= Yii::t("formulario", "Passport") ?></b></label>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_dni_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation " value="<?php echo $arr_persona['per_cedula'] ?>" id="txt_dni_fac" data-type="cedula" data-keydown="true" placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_correo_fac" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_correo_fac"><?= Yii::t("formulario", "Email") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation" value="<?php echo $arr_persona['per_correo'] ?>" id="txt_correo_fac" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
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