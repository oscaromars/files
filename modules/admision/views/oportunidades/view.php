<?php

use yii\helpers\Html;
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
<?= Html::hiddenInput('txth_opoid', base64_encode($opo_id), ['id' => 'txth_opoid']); ?>
<?= Html::hiddenInput('txth_pgid', base64_encode($pges_id), ['id' => 'txth_pgid']); ?>
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
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_pri_nombre'] . " " . $personalData['pges_seg_nombre'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Names") ?>">
                        </div>                                
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_apellido1" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_pri_apellido'] . " " . $personalData['pges_seg_apellido'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Last Names") ?>">
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
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_celular'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                        </div>                                                  
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_correo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Email") ?></label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_correo'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "email") ?>">
                        </div>                                                                                                
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="txt_pais" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Country") ?></label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pais'] ?> " disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Country") ?>">
                        </div>                            
                    </div>
                </div>
            </div>
            <?php } else {
            ?> 
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombre_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Company Name") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_razon_social'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Company Name") ?>">
                        </div>                        
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_direccion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Address") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_direccion_empresa'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Address") ?>">
                        </div>                         
                    </div>
                </div> 
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombre_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Contact Name") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_contacto_empresa'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Contact Name") ?>">
                        </div>                                                  
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_cargo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Charges") ?> </label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_cargo'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Charges") ?>">
                        </div>                          
                    </div>
                </div> 
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_telefono_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre2"><?= Yii::t("formulario", "Phone Company") ?></label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_telefono_empresa'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Phone Company") ?>">
                        </div>                         
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_numero_contacto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido2"><?= Yii::t("formulario", "Number of contacts") ?> </label> 
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $personalData['pges_num_empleados'] ?>" disabled  data-type="alfa" placeholder="<?= Yii::t("formulario", "Number of contacts") ?>">
                        </div>                                                
                    </div>
                </div>                 
            </div> 
            <?php }
        ?>
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
                        <?= Html::dropDownList("cmb_empresa", $arr_oportunidad["empresa"], $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>           
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_linea_servicio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("crm", "Academic Unit") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_linea_servicio", $arr_oportunidad["uaca_id"], $arr_linea_servicio, ["class" => "form-control", "id" => "cmb_linea_servicio", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Moda") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", $arr_oportunidad["mod_id"], $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php if($arr_oportunidad["uaca_id"] > 1)  { ?>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_opportunity_type" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Opportunity type") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_opportunity_type", $arr_oportunidad["tove_id"], $arr_tipo_oportunidad, ["class" => "form-control", "id" => "cmb_opportunity_type", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_state_opportunity" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Opportunity state") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_state_opportunity", $arr_oportunidad["eopo_id"], $arr_state_oportunidad, ["class" => "form-control", "id" => "cmb_state_opportunity", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <?php if (empty($arr_oportunidad["esacademico"])) { ?>
                        <label for="cmb_modulo_estudio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Academic Study") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $arr_oportunidad['moestudio'] ?>" disabled  data-type="alfa">                        
                        </div>
                        <?php } else {
                        ?>
                        <label for="cmb_academic_study" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Academic Study") ?></label>
                        <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $arr_oportunidad['esacademico'] ?>" disabled  data-type="alfa">
                        </div>
                        <?php }
                    ?>

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_knowledge_channel" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("crm", "Contact Channel") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_knowledge_channel", $arr_oportunidad["ccan_id"], $arr_knowledge_channel, ["class" => "form-control", "id" => "cmb_knowledge_channel", "disabled" => "true"]) ?>
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
                        <input type="text" class="form-control keyupmce" value="<?= $tipocarrera["tcar_nombre"] ?>" disabled  data-type="alfa">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_subcarrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Sub Carrier") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?= $arr_oportunidad["sub_carrera"] ?>" disabled  data-type="alfa">
                    </div>
                </div>
            </div>
        </div>        
    </div>
</form>
