<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\academico\Module as academico;

academico::registerTranslations();

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 450px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';
?>

<?= Html::hiddenInput('txth_twin_id', 0, ['id' => 'txth_twin_id']); ?>
<?= Html::hiddenInput('txth_ftem_id', 0, ['id' => 'txth_ftem_id']); ?>
<?= Html::hiddenInput('txth_errorFile', Yii::t("formulario", "The file extension is not valid or exceeds the maximum size in MB recommending him try again"), ['id' => 'txth_errorFile']); ?>


<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Formulario de Inscripción Grado") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
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
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "First Name") ?>">
            </div>
            <label for="txt_segundo_nombre" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Middle Name") ?><span class="text-danger">*</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_segundo_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_primer_apellido" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Last Name") ?><span class="text-danger">*</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
            </div>
            <label for="txt_segundo_apellido" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Last Second Name") ?><span class="text-danger">*</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_segundo_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_lugar_nacimiento" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Lugar de Nacimiento") ?><span class="text-danger">*</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_lugar_nacimiento" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Ciudad de nacimiento") ?>">
            </div>
            <label for="txt_fecha_nac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Fecha de Nacimiento") ?><span class="text-danger">*</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_nac',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_nac", "placeholder" => Yii::t("formulario", "Fecha de Nacimiento")],
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
                <?= Html::dropDownList("cmb_nacionalidad", '', $arr_nacionalidad, ["class" => "form-control", "id" => "cmb_nacionalidad"]) ?>
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_datos4"><?= Yii::t("formulario", "Datos Académicos") ?></span></h3>
    </div><br><br></br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_malla" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Academic Mesh"); ?> <span class="text-danger">*</span> </label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_malla", 0, $arr_malla, ["class" => "form-control", "id" => "cmb_malla"]) ?>
            </div> 
        </div>
    </div><br><br></br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_datos4"><?= Yii::t("formulario", "Documentación") ?></span></h3>
    </div><br><br></br>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>
   
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_titulo cinteres">
        <div class="form-group">
            <label for="txth_doc_titulo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Title") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_titulo', '', ['id' => 'txth_doc_titulo']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_titulo',
                    'name' => 'txt_doc_titulo',
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/inscripciongrado/index']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_titulo", "inscripcion_id": $("#txth_twin_id").val()};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_titulo').val($('#txt_doc_titulo').val());
        $('#txt_doc_titulo').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_titulo').val('');
        //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
    }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
        $(this).parent().parent().children().first().addClass('hide');
    }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
        var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
    }",
                        "fileuploaded" => "function (event, data, previewId, index) {
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
    }",
                    ],
                ]);
                ?>
            </div>
        </div>        
    </div>    
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_dni cinteres">
        <div class="form-group">
            <label for="txth_doc_dni" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Identification document") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_dni', '', ['id' => 'txth_doc_dni']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_dni',
                    'name' => 'txt_doc_dni',
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/inscripciongrado/index']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_dni", "inscripcion_id": $("#txth_twin_id").val()};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_dni').val($('#txt_doc_dni').val());
        $('#txt_doc_dni').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_dni').val('');
        //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
    }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
        $(this).parent().parent().children().first().addClass('hide');
    }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
        var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
    }",
                        "fileuploaded" => "function (event, data, previewId, index) {
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
    }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divCertvota" style="display: block">
        <div class="form-group">
            <label for="txth_doc_certvota" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Voting Certificate") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_certvota', '', ['id' => 'txth_doc_certvota']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_certvota',
                    'name' => 'txt_doc_certvota',
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/inscripciongrado/index']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_certvota", "inscripcion_id": $("#txth_twin_id").val()};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_certvota').val($('#txt_doc_certvota').val());
        $('#txt_doc_certvota').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_certvota').val('');
        //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
    }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
        $(this).parent().parent().children().first().addClass('hide');
    }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
        var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
    }",
                        "fileuploaded" => "function (event, data, previewId, index) {
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
    }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_foto cinteres">
        <div class="form-group">
            <label for="txth_doc_foto" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Foto") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_foto', '', ['id' => 'txth_doc_foto']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_foto',
                    'name' => 'txt_doc_foto',
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/inscripciongrado/index']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_foto", "inscripcion_id": $("#txth_twin_id").val()};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_foto').val($('#txt_doc_foto').val());
        $('#txt_doc_foto').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_adj_disi').val('');
        //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
    }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
        $(this).parent().parent().children().first().addClass('hide');
    }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
        var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
    }",
                        "fileuploaded" => "function (event, data, previewId, index) {
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
    }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <div  id="divCertificado" style="display: none">   
        <!--<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_certificado cinteres">
            <div class="form-group">
                <label for="txth_doc_certificado" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Materials Certificate") ?></label>
                <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                    <?= Html::hiddenInput('txth_doc_certificado', '', ['id' => 'txth_doc_certificado']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_doc_certificado',
                        'name' => 'txt_doc_certificado',
                        'pluginLoading' => false,
                        'showMessage' => false,
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showCancel' => false,
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                            'browseLabel' => "Subir Archivo",
                            'uploadUrl' => Url::to(['/inscripciongrado/index']),
                            'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                return {"upload_file": true, "name_file": "doc_certificado", "inscripcion_id": $("#txth_twin_id").val()};
            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
            $('#txth_doc_certificado').val($('#txt_doc_certificado').val());
            $('#txt_doc_certificado').fileinput('upload');
        }",
                            "fileuploaderror" => "function (event, data, msg) {
            $(this).parent().parent().children().first().addClass('hide');
            $('#txth_doc_certificado').val('');
            //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
        }",
                            "filebatchuploadcomplete" => "function (event, files, extra) { 
            $(this).parent().parent().children().first().addClass('hide');
        }",
                            "filebatchuploadsuccess" => "function (event, data, previewId, index) {
            var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
        }",
                            "fileuploaded" => "function (event, data, previewId, index) {
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
        }",
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>-->
    
        <?php //Aqui voy a colocar la informacion de de la hoja de vida  ?>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_hoja_vida">
            <div class="form-group">
                <label for="txth_doc_hojavida" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Curriculum") ?></label>
                <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                    <?= Html::hiddenInput('txth_doc_hojavida', '', ['id' => 'txth_doc_hojavida']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_doc_hoja_vida',
                        'name' => 'txt_doc_hoja_vida',
                        'pluginLoading' => false,
                        'showMessage' => false,
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showCancel' => false,
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                            'browseLabel' => "Subir Archivo",
                            'uploadUrl' => Url::to(['/inscripciongrado/index']),
                            'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                return {"upload_file": true, "name_file": "doc_hojavida", "inscripcion_id": $("#txth_twin_id").val()};
            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
            $('#txth_doc_hojavida').val($('#txt_doc_hoja_vida').val());
            $('#txt_doc_hoja_vida').fileinput('upload');
        }",
                            "fileuploaderror" => "function (event, data, msg) {
            $(this).parent().parent().children().first().addClass('hide');
            $('#txth_doc_hojavida').val('');
            //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
        }",
                            "filebatchuploadcomplete" => "function (event, files, extra) { 
            $(this).parent().parent().children().first().addClass('hide');
        }",
                            "filebatchuploadsuccess" => "function (event, data, previewId, index) {
            var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
        }",
                            "fileuploaded" => "function (event, data, previewId, index) {
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
        }",
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <?php //Fin de la hoja de vida  ?>

    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divDocumAceptacion" style="display: none">
        <div class="form-group">
            <label for="txth_doc_aceptacion" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Documento Aceptación (Carta)") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_aceptacion', '', ['id' => 'txth_doc_aceptacion']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_aceptacion',
                    'name' => 'txt_doc_aceptacion',
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/inscripciongrado/index']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_aceptacion", "inscripgrado_id": $("#txth_twin_id").val()};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_aceptacion').val($('#txt_doc_aceptacion').val());
        $('#txt_doc_aceptacion').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_aceptacion').val('');
        //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
    }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
        $(this).parent().parent().children().first().addClass('hide');
    }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
        var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
    }",
                        "fileuploaded" => "function (event, data, previewId, index) {
        $(this).parent().parent().children().first().addClass('hide');
        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
    }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">                        
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align: right;">
                <input type="checkbox" id="chk_mensaje1" data-type="alfa" data-keydown="true" placeholder="" >                   
            </div>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">                     
                <label for="chk_mensaje1" class="col-lg-9 col-md-9 col-sm-9 col-xs-9"><?= Yii::t("formulario", "Expreso que la información declarada y documentos cargados son válidos y legales.") ?> </label>
            </div>

        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">                        
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align: right;">
                <input type="checkbox" id="chk_mensaje2" data-type="alfa" data-keydown="true" placeholder="" >   
            </div>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">                      
                <label for="chk_mensaje2" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?= Yii::t("formulario", "Acepto y me comprometo a respetar y cumplir lo estipulado en los reglamentos internos de la universidad con respecto a la admisión y procesos estudiantiles.") ?> </label>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">         
        </br>
        </br>
    </div>
</form>
