<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;

if (!empty($arr_datosc['cedula'])) {
    $tipodoc = "CED";
    $docdni = $arr_datosc['cedula'];
} else {
    if (!empty($arr_datosc['pasaporte'])) {
        $tipodoc = "PASS";
        $docdni = $arr_datosc['pasaporte'];
    } else {
        $tipodoc = "CED";
        $docdni = $arr_datosc['cedula'];
    }
}
?>
<?= Html::hiddenInput('txth_idg', '', ['id' => 'txth_idg']); ?>
<?= Html::hiddenInput('txth_idc', '', ['id' => 'txth_idc']); ?>
<?= Html::hiddenInput('txth_pcon_id', base64_encode($pges_id), ['id' => 'txth_pcon_id']); ?>
<?= Html::hiddenInput('txth_tper_id', base64_encode($tper_id), ['id' => 'txth_tper_id']); ?>

<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Data General Contact") ?></span></h4> 
            </div>
        </div> 
    </div> 
    <?php if (base64_decode($_GET["tper"]) == 1) { ?> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre1"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "First Name") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="<?= $arra_pnomb_con ?>" disabled id="txt_nombre1" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre2"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Middle Name") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control" value="<?= $arra_snomb_con ?>" disabled id="txt_nombre2" data-type="alfa" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellido1"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Name") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="<?= $arra_papellido_con ?>" disabled id="txt_apellido1" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellido12"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Last Second Name") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="<?= $arra_sapellido_con ?>" disabled id="txt_apellido12" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_tipo_dni" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Type DNI") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_tipo_dni", $tipodoc, $tipo_dni, ["class" => "form-control", "id" => "cmb_tipo_dni", "disabled" => true]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "DNI") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?= $docdni ?>" disabled id="txt_cedula" data-type="cedula" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?> 
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre_empresa"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Company Name") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="<?= $arr_datosc['razon_social'] ?>" disabled id="txt_nombre_empresa" data-type="alfa" placeholder="<?= Yii::t("formulario", "Company Name") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_direccion"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Address") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?= $arr_datosc['direccion_empresa'] ?>" disabled id="txt_direccion" data-type="alfanumerico" placeholder="<?= Yii::t("formulario", "Address") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre_contacto"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Contact Name") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?= $arr_datosc['contacto_empresa'] ?>" disabled id="txt_nombre_contacto" data-type="alfa" placeholder="<?= Yii::t("formulario", "Contact Name") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cargo"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Charges") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?= $arr_datosc['cargo'] ?>" disabled id="txt_cargo" data-type="alfa" placeholder="<?= Yii::t("formulario", "Charges") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_telefono_empresa"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Phone Company") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?= $arr_datosc['telefono_empresa'] ?>" disabled id="txt_telefono_empresa" data-type="telefono_sin" placeholder="<?= Yii::t("formulario", "Phone Company") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_numero_contacto"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Number of contacts") ?> </label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?= $arr_datosc['num_empleados'] ?>" disabled id="txt_numero_contacto" data-type="alfa" placeholder="<?= Yii::t("formulario", "Number of contacts") ?>">
                    </div>
                </div>
            </div> 
        </div> 
    <?php } ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_pais"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Country") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_pais", $pais, $arr_pais, ["class" => "form-control", "id" => "cmb_pais", "disabled" => true]) ?> 
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_prov"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "State") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_prov", $provincia, $arr_prov, ["class" => "form-control", "id" => "cmb_prov", "disabled" => true]) ?>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ciu"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "City") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_ciu", $ciudad, $arr_ciu, ["class" => "form-control can_combo", "id" => "cmb_ciu", "disabled" => true]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "CellPhone") ?> </label> 
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $celular ?>" disabled id="txt_celular" data-type="celular_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular2"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "CellPhone") . ' 2' ?> </label> 
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control " value="<?= $telf ?>" disabled id="txt_celular2" data-type="celular_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") . ' 2' ?>">
                </div>
            </div> 
        </div> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_telefono_con"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Phone") ?></label> 
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control" data-required="false" value="<?= $convenc ?>" disabled id="txt_telefono_con" data-type="telefono_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                </div>
            </div>
        </div> 
    </div> 

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_correo"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Email") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $correo ?>" disabled id="txt_correo" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_medio"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Half Contact") ?> </label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_medio", $mcon_id, $arr_conocimiento, ["class" => "form-control pro_combo", "id" => "cmb_medio", "disabled" => true]) ?>
                </div>
            </div>
        </div> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
            <div class="form-group">
                <label for="lbl_nacionalidad"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Nac. Ecuatoriano") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <label>
                        <input type="radio" name="signup-ecu" disabled value="1" <?php
                    if ($arr_datosc["pges_nac_ecuatoriano"] == '1') {
                        echo 'checked';
                    }
                    ?>> Si<br>
                    </label>
                    <label>
                        <input type="radio" name="signup-ecu" disabled value="0" <?php
                        if ($arr_datosc["pges_nac_ecuatoriano"] == '0') {
                            echo 'checked';
                        }
                    ?>> No<br>
                    </label>
                </div> 
            </div>
        </div>
    </div> 
    <?php if (base64_decode($_GET["tper"]) == 1) { ?> 
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
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
                        <label for="txt_nombrebene1"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombrebene1"><?= Yii::t("formulario", "First Name") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control" value="<?= $arr_datosb["pgco_primer_nombre"] ?>" disabled id="txt_nombrebene1" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombrebene2"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombrebene1"><?= Yii::t("formulario", "Middle Name") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control" value="<?= $arr_datosb["pgco_segundo_nombre"] ?>" disabled id="txt_nombrebene2" data-type="alfa" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
                        </div>
                    </div>
                </div> 
            </div> 
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_apellidobene1"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellidobene1"><?= Yii::t("formulario", "Last Name") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control" value="<?= $arr_datosb["pgco_primer_apellido"] ?>" disabled id="txt_apellidobene1" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_apellidobene2"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="txt_apellidobene2"><?= Yii::t("formulario", "Last Second Name") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control" value="<?= $arr_datosb["pgco_segundo_apellido"] ?>" disabled id="txt_apellidobene2" data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                        </div>
                    </div>
                </div> 
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_celularbene"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "CellPhone") ?> </label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control" value="<?= $arr_datosb["pgco_celular"] ?>" disabled id="txt_celularbene" data-type="celular_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_telefono_pcontacto"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Phone") ?> </label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control" value="<?= $arr_datosb["pgco_telefono"] ?>" disabled id="txt_ptelefono" data-type="celular_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                        </div>
                    </div>
                </div>                 
            </div>    
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_correobeni"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Email") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control" value="<?= $arr_datosb["pgco_correo"] ?>" disabled id="txt_correobeni" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_ppais"  class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Country") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <?= Html::dropDownList("cmb_ppais", $arr_datosb["pai_id_contacto"], $arr_pais, ["class" => "form-control", "id" => "cmb_ppais", "disabled" => true]) ?> 
                        </div>
                    </div>
                </div>
            </div>    
        </div> 
    <?php } ?>
</form>
