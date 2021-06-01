<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use yii\data\ArrayDataProvider;
use yii\widgets\DetailView;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Utilities;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;

//rpfa_imagen
admision::registerTranslations();
//$per_id = Yii::$app->session->get("PB_perid");
$leyendarc = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 433px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';
?>
<div class="col-md-12">   
    <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Carga Factura") ?></span>
</div>
<?= Html::hiddenInput('txth_sins_id', $sins_id, ['id' => 'txth_sins_id']); ?>
<?= Html::hiddenInput('txth_opag_total', $opag_total, ['id' => 'txth_opag_total']); ?>
<form class="form-horizontal">      
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">            
            <label for="txt_rpfa_num_solicitud" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label"><?= admision::t("Solicitudes", "Número de Solicitud") ?></label>
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10 ">
                <input type="text" class="form-control PBvalidation keyupmce" value="<?= $sins_id ?>" disabled id="txt_rpfa_num_solicitud" data-type="number"  data-keydown="true" placeholder="<?= admision::t("Solicitudes", "Número de Solicitud") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">            
            <label for="txt_rpfa_numero_documento" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label"><?= admision::t("Solicitudes", "Número de Factura") ?></label>
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10 ">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_rpfa_numero_documento" data-type="number"  data-keydown="true" placeholder="<?= admision::t("Solicitudes", "Número de Factura") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">            
            <label for="txt_rpfa_valor_documento" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label"><?= financiero::t("Pagos", "Valor de Factura") ?></label>
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10 ">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_rpfa_valor_documento" data-type="dinero"  data-keydown="true" placeholder="<?= financiero::t("Pagos", "Valor de Factura") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_rpfa_fecha_documento" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label"><?= admision::t("Solicitudes", "Fecha de Factura") ?></label>
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10 ">
                <?=
                DatePicker::widget([
                    'name' => 'txt_rpfa_fecha_documento',
                    'value' => '',
                    'disabled' => $habilita,
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_rpfa_fecha_documento", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => admision::t("Solicitudes", "Fecha Documento")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 doc_titulo cinteres">
        <div class="form-group">
            <label for="txth_doc_titulo" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label keyupmce" id="txth_doc_titulo" name="txth_doc_titulo"><?= Yii::t("formulario", "Attach document") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">
                <?= Html::hiddenInput('txth_per', $per_id, ['id' => 'txth_per']); ?>
                <?= Html::hiddenInput('txth_doc_titulo', '', ['id' => 'txth_doc_titulo']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_titulo',
                    'name' => 'txt_doc_titulo',
                    'disabled' => $habilita,
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
                        'uploadUrl' => Url::to(['pagos/savefactura']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                                var name_pago= $("#txth_doc_titulo").val();
                                var name_perid= $("#txth_per").val();
                                return {"upload_file": true, "name_file": name_pago,"name_perid": name_perid};
                    }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {                        
                        function d2(n) {
                        if(n<9) return '0'+n;
                        return n;
                        }
                        today = new Date();
                        var name_pago = 'factura_' + $('#txth_per').val() + '-' + today.getFullYear() + '-' + d2(parseInt(today.getMonth()+1)) + '-' + d2(today.getDate()) + ' ' + d2(today.getHours()) + ':' + d2(today.getMinutes()) + ':' + d2(today.getSeconds());
                        $('#txth_doc_titulo').val(name_pago);    
        
        $('#txt_doc_titulo').fileinput('upload');
        var fileSent = $('#txt_doc_titulo').val();
        var ext = fileSent.split('.');
        $('#txth_doc_titulo').val(name_pago + '.' + ext[ext.length - 1]);
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
</form>
