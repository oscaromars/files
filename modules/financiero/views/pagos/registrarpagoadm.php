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

admision::registerTranslations();
session_start();
$_SESSION['persona_solicita'] = $per_id;
$leyendarc = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 433px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';
?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ">    
    <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Payment Registration") ?></span>
</div>
<?= Html::hiddenInput('txth_ids', $opag_id, ['id' => 'txth_ids']); ?>
<?= Html::hiddenInput('txth_total', $valor_total, ['id' => 'txth_total']); ?>
<?= Html::hiddenInput('txth_saldo_pendiente', $saldo_pendiente, ['id' => 'txth_saldo_pendiente']); ?>
<?= Html::hiddenInput('txth_int', $int_id, ['id' => 'txth_int']); ?>
<?= Html::hiddenInput('txth_sins', $sins_id, ['id' => 'txth_sins']); ?>
<?= Html::hiddenInput('txth_perid', $per_id, ['id' => 'txth_perid']); ?>
<?= Html::hiddenInput('txth_empid', $emp_id, ['id' => 'txth_empid']); ?>
<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_forma_pago" class="col-sm-2  control-label"><?= financiero::t("Pagos", "Paid form") ?></label>
            <div class="col-sm-10 ">
                <?php
                $habilita = '';
                if ($saldo_pendiente <= '0') {
                    $habilita = 'disabled';
                    ?>
                    <?= Html::dropDownList("cmb_forma_pago", 0, $arr_forma_pago, ["class" => "form-control", "id" => "cmb_forma_pago", "disabled" => "disabled"]) ?>
                    <?php
                } else {
                    ?>
                    <?= Html::dropDownList("cmb_forma_pago", 0, $arr_forma_pago, ["class" => "form-control", "id" => "cmb_forma_pago"]) ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>     
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">            
            <label for="txt_pago" class="col-sm-2 control-label"><?= financiero::t("Pagos", "Pay Total") ?></label>
            <div class="col-sm-10 ">
                <?php
                if ($saldo_pendiente <= '0') {
                    ?>
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_pago" data-type="dinero" readonly = "readonly" data-keydown="true" placeholder="<?= financiero::t("Pagos", "Pay Total") ?>">
                    <?php
                } else {
                    ?>
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_pago" data-type="dinero" data-keydown="true" placeholder="<?= financiero::t("Pagos", "Pay Total") ?>">
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">            
            <label for="txt_numero_transaccion" class="col-sm-2 control-label"><?= financiero::t("Pagos", "Transaction Number") ?></label>
            <div class="col-sm-10 ">
                <?php
                if ($saldo_pendiente <= '0') {
                    ?>
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_numero_transaccion" data-type="number" readonly = "readonly" data-keydown="true" placeholder="<?= financiero::t("Pagos", "Transaction Number") ?>">
                    <?php
                } else {
                    ?>
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_numero_transaccion" data-type="number" data-keydown="true" placeholder="<?= financiero::t("Pagos", "Transaction Number") ?>">
                    <?php
                }
                ?>
            </div>
        </div>
    </div>    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">            
            <label for="txt_fecha_transaccion" class="col-sm-2 control-label"><?= financiero::t("Pagos", "Transaction Date") ?></label>
            <div class="col-sm-10 ">
                <?php
                if ($saldo_pendiente <= '0') {
                    ?>
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_transaccion',
                        'value' => '',
                        "disabled" => "disabled",
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_transaccion", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => financiero::t("Pagos", "Transaction Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                    <?php
                } else {
                    ?>
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_transaccion',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_transaccion", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => financiero::t("Pagos", "Transaction Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">       
        <div class="form-group">
            <label for="txt_observa" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">    
                <?php
                if ($saldo_pendiente <= '0') {
                    ?>
                    <textarea  class="form-control keyupmce" id="txt_observa" readonly = "readonly" rows="3"></textarea><?php
                } else {
                    ?>
                    <textarea  class="form-control keyupmce" id="txt_observa" rows="3"></textarea><?php
                }
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
                        'uploadUrl' => Url::to(['pagos/savepago']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        var name_pago= $("#txth_doc_titulo").val();
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
                        var name_pago = 'pago_' + $('#txth_per').val() + '-' + today.getFullYear() + '-' + d2(parseInt(today.getMonth()+1)) + '-' + d2(today.getDate()) + ' ' + d2(today.getHours()) + ':' + d2(today.getMinutes()) + ':' + d2(today.getSeconds());
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
        <?php
        if ($saldo_pendiente > '0') {
            echo $leyendarc;
        }
        ?>
    </div>    
    <?php if ($saldo_pendiente > '0') { ?>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="cmd_registrarPagoadm" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Send") ?> <span class=""></span></a>
            </div>
        </div>  
        <?php
    } else {
        $leyenda = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">                        
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">                
             <div style = "width: 350px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span>El aspirante ha cancelado toda su inscripción.</div>          
            </div>
        </div>
    </div>';
    }
    echo $leyenda;
    ?>
</div> 
<div>
    <?=
    PbGridView::widget([
        'id' => 'TbgPago',
        //'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'Forma',
                'header' => financiero::t("Pagos", "Paid form"),
                'value' => 'fpag_nombre',
            ],
            [
                'attribute' => 'Total',
                'header' => financiero::t("Pagos", "Pay Total"),
                'value' => 'dcar_valor',
            ],
            [
                'attribute' => 'Numtrans',
                'header' => financiero::t("Pagos", "Transaction Number"),
                'value' => 'icpr_num_transaccion',
            ],
            [
                'attribute' => 'Fechatrans',
                'header' => financiero::t("Pagos", "Transaction Date"),
                'value' => 'icpr_fecha_transaccion',
            ],
            [
                'attribute' => 'Revisado',
                'header' => admision::t("Solicitudes", 'Reviewed'),
                'value' => 'dcar_revisado',
            ],
            [
                'attribute' => 'Resultado',
                'header' => Yii::t("formulario", "Result"),
                'value' => 'dcar_resultado',
            ],
        ],
    ])
    ?>
</div>      
</form>

