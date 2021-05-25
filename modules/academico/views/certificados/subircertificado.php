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
use app\modules\academico\Module as certificados;

//  print_r($arr_certificado);
admision::registerTranslations();
certificados::registerTranslations();
$leyendarc = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 400px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo pdf.</div>
          </div>
          </div>
          </div>';
?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ">    
    <h3><span id="lbl_Personeria"><?= certificados::t("certificados", "Upload certificate") ?></span>
</div>
<?= Html::hiddenInput('txth_cgenid', base64_decode($_GET["cgen_id"]), ['id' => 'txth_cgenid']); ?>
<form class="form-horizontal"> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">      
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="form-group">
                <label for="txt_nombres" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="<?php echo $arr_certificado[0]['Nombres'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>            
                <div class="form-group">
                    <label for="txt_codigocerti" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_nombre1">Código Certificado</label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_certificado[0]['cgen_codigo'] ?>" id="txt_codigocerti" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>            
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">       
            <div class="form-group">
                <label for="txt_observa" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">              
                    <textarea  class="form-control keyupmce" id="txt_observa" rows="3"></textarea>                             
                </div>
            </div>      
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 doc_titulo cinteres">
            <div class="form-group">
                <label for="txth_doc_certificado" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label keyupmce" id="txth_doc_titulo" name="txth_doc_certificado"><?= Yii::t("formulario", "Attach document") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">
                    <?= Html::hiddenInput('txth_per_id', $arr_certificado[0]['per_id'], ['id' => 'txth_per_id']); ?>
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
                            'uploadUrl' => Url::to(['certificados/savecertificado']),
                            'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                        var name_pago= $("#txth_doc_certificado").val();
                        return {"upload_file": true, "name_file": name_pago};
        }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {                        
                        function d2(n) {
                        if(n<9) return '0'+n;
                        return n;
                        }
                        today = new Date();
                        var name_certificado = 'certificado_' + $('#txth_per_id').val() + '-' + today.getFullYear() + '-' + d2(parseInt(today.getMonth()+1)) + '-' + d2(today.getDate()) + ' ' + d2(today.getHours()) + ':' + d2(today.getMinutes()) + ':' + d2(today.getSeconds());
                        $('#txth_doc_certificado').val(name_certificado);    
        
        $('#txt_doc_certificado').fileinput('upload');
        var fileSent = $('#txt_doc_certificado').val();
        var ext = fileSent.split('.');
        $('#txth_doc_certificado').val(name_certificado + '.' + ext[ext.length - 1]);
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
        </div>    
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <!--    <a id="btn_subircertificado" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Send") ?> <span class=""></span></a> -->
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            &nbsp;&nbsp;
        </div> 
        <?php
        $leyenda = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">                        
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">                
             <div style = "width: 350px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span>El aspirante ha cancelado toda su inscripción.</div>          
            </div>
        </div>
    </div>';

        echo $leyendarc;
        ?>
</form>

