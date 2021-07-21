<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module;

$fecha_actual = date("Y-m-d");
?>
<?= Html::hiddenInput('txth_idpa', $persona_autentica, ['id' => 'txth_idpa']); ?>

<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_Beneficiario" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Are you the Student") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <label>
                        <input type="radio" name="rdo_beneficio" id="rdo_beneficio" value="1" checked> Si<br>
                    </label>
                    <label>
                        <input type="radio" name="rdo_beneficio_no" id="rdo_beneficio_no" value="2" > No<br>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Data General Contact") ?></span></h4>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12' style="display: none;">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_tipo_persona" class="col-sm-5 control-label"><?= Yii::t("formulario", "Person type") ?></label>
                <div class="col-sm-7">
                    <label><input type="radio" name="opt_tipo_persona_n" id="opt_tipo_persona_n" value="1" checked><b>Natural</b></label>
                    <!-- <label><input type="radio" name="opt_tipo_persona_j" id="opt_tipo_persona_j" value="2"><b>Jurídica</b></label> -->
                </div>
                <div class="col-md-4">
                </div>
            </div>
        </div>
    </div>
    <div id="divTipopersonaN" style="display: block;">
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "First Name") ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombre1" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Middle Name") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="" id="txt_nombre2" data-type="alfa" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellido1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Name") ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_apellido1" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellido2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Last Second Name") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="" id="txt_apellido2" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="divTipopersonaJ" style="display: none;">
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Company Name") ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombre_empresa" data-type="alfa" placeholder="<?= Yii::t("formulario", "Company Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_direccion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Address") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="" id="txt_direccion" data-type="alfanumerico" placeholder="<?= Yii::t("formulario", "Address") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Contact Name") ?><span class="text-danger">*</span> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombre_contacto" data-type="alfa" placeholder="<?= Yii::t("formulario", "Contact Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cargo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Charges") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="" id="txt_cargo" data-type="alfa" placeholder="<?= Yii::t("formulario", "Charges") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_telefono_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Phone Company") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="" id="txt_telefono_empresa" data-type="telefono_sin" placeholder="<?= Yii::t("formulario", "Phone Company") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_numero_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Number of contacts") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="" id="txt_numero_contacto" data-type="alfa" placeholder="<?= Yii::t("formulario", "Number of contacts") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_pais" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Country") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <select id="cmb_pais" name="cmb_pais" class="form-control pai_combo">
                        <?php
                        $pai_id_nacimiento = 1;
                        $code = "";
                        foreach ($arr_pais as $key => $value) {

                            $selected = ($pai_id_nacimiento == $value['id']) ? "selected='seleted'" : "";
                            if ($selected != "")
                                $code = "+" . preg_replace('/\s+/', '', $value['code']);
                            echo "<option value='" . $value['id'] . "' data-code='" . "+" . preg_replace('/\s+/', '', $value['code']) . "' $selected >" . $value['value'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_prov" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "State") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_prov", 0, $arr_prov, ["class" => "form-control pro_combo", "id" => "cmb_prov"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ciu" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "City") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_ciu", 0, $arr_ciu, ["class" => "form-control can_combo", "id" => "cmb_ciu"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "CellPhone") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">

                    <input type="text" class="form-control PBvalidations" value="" id="txt_celular" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "CellPhone") . ' 2' ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="" data-required="false" id="txt_celular2" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") . ' 2' ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_telefono_con" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Phone") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" data-required="false" value="" id="txt_telefono_con" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_correo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Email") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_correo" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_medio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Channel Contact") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_medio", 0, $arr_canalconta, ["class" => "form-control pro_combo", "id" => "cmb_medio"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_nacionalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Nac. Ecuatoriano") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <label>
                        <input type="radio" name="signup-ecu" value="1" <?='checked';?>/> Si<br>
                    </label>
                    <label>
                        <input type="radio" name="signup-ecu" value="0" /> No<br>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div id="estudiante" style="display: block;">
        <div id="beneficio" style="display: none;">
            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
                <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
                    <div class="form-group">
                        <h4><span id="lbl_general"><?= Yii::t("formulario", "Data to contact") ?></span></h4>
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombrebene1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombrebene1"><?= Yii::t("formulario", "First Name") ?> <span class="text-danger">*</span> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombrebene1" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombrebene2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombrebene1"><?= Yii::t("formulario", "Middle Name") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control keyupmce" value="" id="txt_nombrebene2" data-type="alfa" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_apellidobene1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellidobene1"><?= Yii::t("formulario", "Last Name") ?> <span class="text-danger">*</span> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_apellidobene1" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_apellidobene2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellidobene2"><?= Yii::t("formulario", "Last Second Name") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control keyupmce" value="" id="txt_apellidobene2" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_celularbene" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "CellPhone") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <!--<div class="input-group">-->
                            <!--<span id="lbl_codeCountrybeni" class="input-group-addon"><? $area ?></span>-->
                            <input type="text" class="form-control PBvalidation" value="" id="txt_celularbene" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                            <!--</div>-->
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_telefono_conbeni" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Phone") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation" data-required="false" value="" id="txt_telefono_conbeni" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_correobeni" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Email") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="" data-required="false" id="txt_correobeni" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="cmb_pais" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Country") ?> <span class="text-danger">*</span> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <select id="cmb_pais_contacto" name="cmb_pais_contacto" class="form-control pai_combo">
                                <?php
                                $pai_id_nacimiento = 1;
                                $code = "";
                                foreach ($arr_pais as $key => $value) {

                                    $selected = ($pai_id_nacimiento == $value['id']) ? "selected='seleted'" : "";
                                    if ($selected != "")
                                        $code = "+" . preg_replace('/\s+/', '', $value['code']);
                                    echo "<option value='" . $value['id'] . "' data-code='" . "+" . preg_replace('/\s+/', '', $value['code']) . "' $selected >" . $value['value'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_oportunidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Module::t("crm", "Generate Oportunity") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="checkbox" id="genAspirante" data-toggle="toggle" data-on="SI" data-off="NO">
                </div>
            </div>
        </div>
    </div>
    <div id="oportunidadDiv" style="display: none;">
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
                        <?= Html::dropDownList("cmb_empresa", $emp_id, $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa","disabled" => "true"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= "Cédula/Pasaporte" ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control" value="" id="txt_cedula" data-type="all" data-keydown="true">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_nivelestudio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("crm", "Academic Unit") ?></label>
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
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divtiopor" style="display: none;">
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
                <div class="form-group ccmodalidad">
                    <label for="cmb_carrera1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Academic Study") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_carrera1", 1, $arr_academic_study, ["class" => "form-control", "id" => "cmb_carrera1"]) ?>
                    </div>
                </div>
                <div class="form-group ccmodestudio hide">
                    <label for="cmb_modalidad_estudio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Academic Study") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad_estudio", 1, array(), ["class" => "form-control", "id" => "cmb_modalidad_estudio"]) ?>
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
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="cmb_medio_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_descripcion"><?= Yii::t("formulario", "Half Contact") ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_medio_contacto", 0, $arr_seguimiento, ["class" => "multiSelects form-control", "id" => "cmb_medio_contacto", "name" => "cmb_medio_contacto[]", "multiple"=>"multiple",]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7 subcareers">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Module::t("crm", "Another interest carreer") ?></span></h4>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subcareers">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_carrera2" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("academico", "Career") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_carrera2", 0, ['0' => Yii::t('formulario', 'Any')] + $arr_carrerra2, ["class" => "form-control", "id" => "cmb_carrera2"]) ?>
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
    </div>
</form>
<style>
    span.select2-container{
        width: 100% !important;
    }
</style>