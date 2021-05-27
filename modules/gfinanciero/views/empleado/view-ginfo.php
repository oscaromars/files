<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;
use app\components\CFileInputAjax;

financiero::registerTranslations();

$token = SearchAutocomplete::getToken();

?>
<br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_pri_nombre" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "First Name") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_pri_nombre ?>" disabled="disabled" id="frm_pri_nombre" data-type="all" placeholder="<?= Yii::t("formulario", "First Name") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_seg_nombre" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Middle Name") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_seg_nombre ?>" disabled="disabled" id="frm_seg_nombre" data-type="all" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_pri_apellido" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Last Name2") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_pri_apellido ?>" disabled="disabled" id="frm_pri_apellido" data-type="all" placeholder="<?= Yii::t("formulario", "Last Name2") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_seg_apellido" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Last Second Name1") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_seg_apellido ?>" disabled="disabled" id="frm_seg_apellido" data-type="all" placeholder="<?= Yii::t("formulario", "Last Second Name1") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_ecivil" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Marital Status") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_ecivil", $modelPer->eciv_id, $arr_civil, ["class" => "form-control", "id" => "cmb_ecivil", "disabled" => "disabled"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_genero" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Gender") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <?= Html::dropDownList("cmb_genero", $modelPer->per_genero, $arr_genero, ["class" => "form-control", "id" => "cmb_genero", "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div> 
    <div class="col-md-6">
        <div class="row">
            <div class="col-sm-1">                                
            </div>
            <div class="col-sm-10">
                <div class="col-sm-12 text-center">
                    <img src="<?= $urlFoto ?>" style="width:<?= $widthImg ?>px; height:<?= $heightImg ?>px" id='img_destino' alt="User Image" />
                </div>
                <div class="col-sm-12 text-center"></br></div>                            
            </div>
            <div class="col-sm-1">                        
            </div>                          
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_correo" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Email") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_correo ?>" disabled="disabled" id="frm_correo" data-type="email" placeholder="<?= Yii::t("formulario", "Email") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_celular" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "CellPhone") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_celular ?>" disabled="disabled" id="frm_celular" data-type="all" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_telefono" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Phone") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_domicilio_telefono ?>" disabled="disabled" id="frm_telefono" data-type="all" placeholder="<?= Yii::t("formulario", "Phone") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_telefonoc" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Phone Company") ?> </label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidations" value="<?= $modelPer->per_trabajo_telefono ?>" disabled="disabled" id="frm_telefonoc" data-type="all" placeholder="<?= Yii::t("formulario", "Phone Company") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_extension" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Extension") ?> </label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidations" value="<?= $modelPer->per_trabajo_ext ?>" disabled="disabled" id="frm_extension" data-type="all" placeholder="<?= Yii::t("formulario", "Extension") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_tipo_sangre" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Blood Type") ?><span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_tipo_sangre", $modelPer->tsan_id, $tipos_sangre, ["class" => "form-control", "id" => "cmb_tipo_sangre", "disabled" => "disabled"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_raza_etnica" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Ethnic") ?> </label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_raza_etnica", $modelPer->etn_id, $etnica, ["class" => "form-control", "id" => "cmb_raza_etnica", "disabled" => "disabled"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_nacimiento" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "B. Date") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <?=
                DatePicker::widget([
                    'name' => 'frm_nacimiento',
                    'value' => date(Yii::$app->params["dateByDefault"], strtotime($modelPer->per_fecha_nacimiento)),
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation", "id" => "frm_nacimiento", "data-type" => "fecha", "placeholder" => Yii::t("formulario", "Birth Date yyyy-mm-dd"), "disabled" => "disabled"],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_cedula" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "DNI Document") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_cedula ?>" disabled="disabled" id="frm_cedula" data-type="all" placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_ruc" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "RUC") ?> </label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidations" value="<?= $modelPer->per_ruc ?>" disabled="disabled" id="frm_ruc" data-type="all" placeholder="<?= Yii::t("formulario", "RUC") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_pasaporte" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Passport") ?> </label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidations" value="<?= $modelPer->per_pasaporte ?>" disabled="disabled" id="frm_pasaporte" data-type="all" placeholder="<?= Yii::t("formulario", "Passport") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_nacionalidad" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Nationality") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelPer->per_nacionalidad ?>" disabled="disabled" id="frm_nacionalidad" data-type="all" placeholder="<?= Yii::t("formulario", "Nationality") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_pai_nac" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "Country of birth") ?><span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_pai_nac", $pai_nac_id, $arr_pais_nac, ["class" => "form-control", "id" => "cmb_pai_nac", "disabled" => "disabled"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_pro_nac" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "State of birth") ?><span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_pro_nac", $pro_nac_id, $arr_prov_nac, ["class" => "form-control", "id" => "cmb_pro_nac", "disabled" => "disabled"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_ciu_nac" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("formulario", "City of birth") ?><span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_ciu_nac", $can_nac_id, $arr_ciu_nac, ["class" => "form-control", "id" => "cmb_ciu_nac", "disabled" => "disabled"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="frm_ecua" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Ecuadorian") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_ecua" value="<?= ($modelPer->per_nac_ecuatoriano == '1')?"1":"0" ?>" placeholder="<?= financiero::t("empleado", "Ecuadorian") ?>">
                    <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatuss"><i class="iconAccStatus glyphicon glyphicon-<?= ($modelPer->per_nac_ecuatoriano == '1')?"check":"unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>