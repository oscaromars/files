<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\widgets\PbSearchBox\PbSearchBox;
use app\modules\academico\Module as academico;
use yii\web\Session;

session_start();
if (!empty($per_cedula)) {
    $tipodoc = "CED";    
} else {
    if (!empty($per_pasaporte)) {
        $tipodoc = "PASS";    
    }
    else{
        $tipodoc = "CED";    
    }   
}

academico::registerTranslations();

?>
<?= Html::hiddenInput('txth_ids', base64_decode($_GET['ids']), ['id' => 'txth_ids']); ?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Formulario de Inscripción Grado") ?></span></h3>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    
    <div id="datos_persona" style="display: block;" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_unidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Academic Unit"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidad", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad", "Disabled" => "true"]) ?>
                </div>
                <label for="lbl_carrera" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Carrera"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_carrera", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera"]) ?>
                </div> 
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_modalidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>  
                <label for="lbl_periodo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
                </div>
            </div>        
        </div> <br><br></br> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3><span id="lbl_datos"><?= Yii::t("formulario", "Datos Personales") ?></span></h3>
        </div><br><br></br>  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cmb_tipo_dni" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "DNI 1") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tipo_dni", 0, $tipos_dni, ["class" => "form-control", "id" => "cmb_tipo_dni"]) ?>
                </div>
                <div id="Divcedula">
                    <label for="txt_cedula" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Number") ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <input type="text" maxlength="10" class="form-control PBvalidation keyupmce" id="txt_cedula" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
                    </div>
                </div>
                <div style="display: none;" id="Divpasaporte">
                    <label for="txt_pasaporte" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Number") ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <input type="text" maxlength="15" class="form-control keyupmce" id="txt_pasaporte" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Passport") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_primer_nombre" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "First Name") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_pri_nombre ?>" id="txt_primer_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                </div>
                <label for="txt_segundo_nombre" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Middle Name") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_seg_nombre ?>" id="txt_segundo_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_primer_apellido" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Last Name") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_pri_apellido ?>" id="txt_primer_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                </div>
                <label for="txt_segundo_apellido" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Last Second Name") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_seg_apellido ?>" id="txt_segundo_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cmb_ciu_nac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "City of birth") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_ciu_nac", $can_id_nacimiento, $arr_ciudad, ["class" => "form-control can_combo", "id" => "cmb_ciu_nac"]) ?>
                </div>
                <label for="txt_fecha_nac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Birth Date") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_nac',
                        'value' => $per_fecha_nacimiento,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_nac", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Birth Date yyyy-mm-dd")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cmb_nacionalidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Nacionalidad") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" id="cmb_nacionalidad" value="<?= $per_nacionalidad ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nationality") ?>">
                </div>
                <label for="cmb_estado_civil" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Estado Civil"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_estado_civil", 0, $arr_estado_civil, ["class" => "form-control", "id" => "cmb_estado_civil"]) ?>
                </div>
            </div>
        </div><br><br></br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3><span id="lbl_datos2"><?= Yii::t("formulario", "Datos de Contacto") ?></span></h3>
        </div><br><br></br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cmb_pais" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Pais") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_pais", 0, $arr_pais, ["class" => "form-control", "id" => "cmb_pais"]) ?>
                </div>
                <label for="cmb_provincia" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Provincia / Estado") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_provincia", 0, $arr_provincia, ["class" => "form-control", "id" => "cmb_provincia"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cmb_ciudad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Ciudad"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_ciudad", 0, $arr_ciudad, ["class" => "form-control", "id" => "cmb_ciudad"]) ?>
                </div>
                <label for="txt_parroquia" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Parroquia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_parroquia" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Parroquia") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_domicilio" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Dirección Domiciliaria") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_domicilio" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Detallar la dirección de su domicilio") ?>">
                </div>
                <label for="txt_celular" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "CellPhone")?><span class="text-danger">*</span></label> 
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation" value="" data-required="false" id="txt_celular" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_telefono" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Phone") ?></label> 
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation" data-required="false" value="" id="txt_telefono" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                </div>
                <label for="txt_correo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Email") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_correo" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                </div>
            </div>
        </div><br><br></br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3><span id="lbl_datos3"><?= Yii::t("formulario", "Datos en Caso de Emergencia") ?></span></h3>
        </div><br><br></br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br></br>
            <div class="form-group">
                <label for="txt_direccion_trabajo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Dirección de Trabajo") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_direccion_trabajo" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Donde trabajo actualmente") ?>">
                </div>
                <label for="txt_contacto_emergencia" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "En Caso de Emergencia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_contacto_emergencia" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Persona por contactar en caso de Emergencia") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_parentesco" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Tipo de Parentesco") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_parentesco" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Tipo de Parentesco") ?>">
                </div>
                <label for="txt_telefono_emergencia" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Phone")?><span class="text-danger">*</span></label> 
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation" value="" data-required="false" id="txt_telefono_emergencia" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Teléfono de contacto en caso de emergencia ") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_direccion_persona_contacto" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Dirección de Persona en Caso de Emergencia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_direccion_persona_contacto" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Dirección de la Persona de Contacto en Caso de Emergencia") ?>">
                </div>
            </div>
        </div><br><br></br>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="divmetodocan" style="display: none">   
            <div class="form-group">            
                <label for="cmb_metodo_solicitud" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Income Method") ?><span class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                    <?= Html::dropDownList("cmb_metodo_solicitud", 0, array_merge([Yii::t("formulario", "Select")], $arr_metodos), ["class" => "form-control", "id" => "cmb_metodo_solicitud"]) ?>
                </div>
            </div>
        </div>   
    </div>   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-10"></div>
        <div class="col-md-2">
            <a id="paso1next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
        </div>
    </div>
</form>