<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use app\components\CFileInputAjax;
use yii\data\ArrayDataProvider;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Utilities;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;

$leyendarc = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 370px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo pdf.</div>
          </div>
          </div>
          </div>';

session_start();
$_SESSION['peradmitido'] = $_GET['per_id'];
?>
<?= Html::hiddenInput('txth_sids', base64_decode($_GET['sids']), ['id' => 'txth_sids']); ?>
<?= Html::hiddenInput('txth_admid', base64_decode($_GET['adm_id']), ['id' => 'txth_admid']); ?>
<div class="col-md-12">    
    <h3><span id="lbl_contrato"><?= financiero::t("Pagos", "Upload Contract") ?></span>
</div>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
    <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
        <div class="form-group">
            <h4><span id="lbl_general"><?= Yii::t("formulario", "Data Personal") ?></span></h4> 
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_solicitud" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= admision::t("Solicitudes", "Request #") ?></label> 
            <span for="txt_solicitud" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $arr_datoadmitido['solicitud'] ?> </span> 
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_dni" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= Yii::t("formulario", "DNI Document") ?></label> 
            <span for="txt_dni" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $arr_datoadmitido['per_cedula'] ?> </span> 
        </div>
    </div>
</div>
<div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="txt_nombre" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre"><?= Yii::t("formulario", "Names") ?></label>
            <span for="txt_nombre" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre"><?= $arr_datoadmitido['per_pri_nombre'] . " " . $personalData['per_seg_nombre'] ?> </span> 
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="form-group">
            <label for="txt_apellido" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_apellido"><?= Yii::t("formulario", "Last Names") ?></label>
            <span for="txt_apellido" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_apellido"><?= $arr_datoadmitido['per_pri_apellido'] . " " . $personalData['per_seg_apellido'] ?> </span> 
        </div>
    </div> 
</div>
<div>
    <br><br><br><br><br><br><br><br><br>
</div>
<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_convenio" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Company Agreement") ?></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
                <?= Html::dropDownList("cmb_convenio", 0, array_merge([Yii::t("formulario", "Select")], $arr_convenio_empresa), ["class" => "form-control", "id" => "cmb_convenio"]) ?>
            </div>
        </div> 
    </div>   
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 doc_titulo cinteres">
        <div class="form-group">
            <label for="txth_doc_contrato" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label keyupmce" id="txth_doc_titulo" name="txth_doc_contrato"><?= Yii::t("formulario", "Attach document") ?></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5 ">
                <?= Html::hiddenInput('txth_per', $arr_datoadmitido['per_id'], ['id' => 'txth_per']); // CONSEGUIR EL PER_ID DEL ADMITIDO?> 
                <?= Html::hiddenInput('txth_doc_contrato', '', ['id' => 'txth_doc_contrato']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_contrato',
                    'name' => 'txt_doc_contrato',
                    'disabled' => false,
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
                        'uploadUrl' => Url::to(['pagoscontrato/savecontrato']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        var name_pagocontrato= $("#txth_doc_contrato").val();
            return {"upload_file": true, "name_file": name_pagocontrato};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {                        
                        function d2(n) {
                        if(n<9) return '0'+n;
                        return n;
                        }
                        today = new Date();
                        var name_pagocontrato = 'pagocontrato_' + $('#txth_per').val() + '-' + today.getFullYear() + '-' + d2(parseInt(today.getMonth()+1)) + '-' + d2(today.getDate());
                        $('#txth_doc_contrato').val(name_pagocontrato);    
        
        $('#txt_doc_contrato').fileinput('upload');
        var fileSent = $('#txt_doc_contrato').val();
        var ext = fileSent.split('.');
        $('#txth_doc_contrato').val(name_pagocontrato + '.' + ext[ext.length - 1]);
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_contrato').val('');
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
    <?php echo $leyendarc; ?>
    <div class="row"> 
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"></div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <a id="btn_guardarcontrato" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?> </a>
        </div>
    </div>
</form>