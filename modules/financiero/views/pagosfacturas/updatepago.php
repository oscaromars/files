<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use kartik\date\DatePicker;
use app\modules\academico\Module as Especies;
use app\modules\financiero\Module as Pagos;
use app\modules\admision\Module as crm;
use app\modules\academico\Module as academico;

//print_r($pagodetalle);
Especies::registerTranslations();
Pagos::registerTranslations();
crm::registerTranslations();
academico::registerTranslations();

$leyendarc = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 433px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';
?>

<?= Html::hiddenInput('txth_idest', $arr_persona['est_id'], ['id' => 'txth_idest']); ?>
<?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
<?= Html::hiddenInput('txth_pfes_id', $pagodetalle['cabecera_id'], ['id' => 'txth_pfes_id']); ?>
<?= Html::hiddenInput('txth_pfesid', base64_encode($pagodetalle['cabecera_id']), ['id' => 'txth_pfesid']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Student Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre'] . " " . $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Pagos::t("Pagos", "Cell") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Academic Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_ninteres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Academic unit") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_ninteres", $arr_persona['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
                    </div>
                </div>  
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
                <div class="form-group">
                    <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Modality") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", $arr_persona['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>                             
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Career/Program") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_carrera", $arr_persona['eaca_id'], $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera", "disabled" => "true"]) ?>
                    </div>
                </div>                                        
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Payment Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_referencia" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Pagos::t("Pagos", "Reference") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $pagodetalle['referencia'] ?>" id="txt_referencia" data-type="number" disabled placeholder="<?= Pagos::t("Pagos", "Reference") ?>">
                    </div>
                </div>  
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_formapago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= crm::t("crm", "Payment Method") ?><span class="text-danger"> * </span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_formapago", $pagodetalle['pago_id'], ['0' => Yii::t('formulario', 'Select')] + $arr_forma_pago, ["class" => "form-control PBvalidation", "id" => "cmb_formapago", "disabled" => "disabled"]) ?>
                    </div>
                </div>
            </div>                             
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_valor" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Pagos::t("Pagos", "Value") ?><span class="text-danger"> * </span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $pagodetalle['valor_pago'] ?>" id="txt_valor" data-type="dinero" disabled  placeholder="<?= Pagos::t("Pagos", "Value") ?>">
                    </div>
                </div>                                        
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_fechapago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Pagos::t("Pagos", "Payment Date") ?><span class="text-danger"> * </span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?=
                        DatePicker::widget([
                            'name' => 'txt_fechapago',
                            'disabled'=> true,
                            'value' => $pagodetalle['fecha_pago'],
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fechapago", "placeholder" => Pagos::t("Pagos", "Payment Date")],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => Yii::$app->params["dateByDatePicker"],
                            ]]
                        );
                        ?></div>
                </div>
            </div> 
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">      
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_observa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <textarea  class="form-control keyupmce" id="txt_observa" disabled rows="3"><?php echo $pagodetalle['observacion'] ?></textarea>                        
                    </div>
                </div>   
            </div> 
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txth_doc_pago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label  keyupmce" id="txth_doc_titulo" name="txth_doc_pago"><?= Yii::t("formulario", "Attach document") ?><span class="text-danger"> * </span></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">
                        <?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
                        <?= Html::hiddenInput('txth_doc_pago', '', ['id' => 'txth_doc_pago']); ?>
                        <?php
                        echo CFileInputAjax::widget([
                            'id' => 'txt_doc_pago',
                            'name' => 'txth_doc_pago',
                            'pluginLoading' => false,
                            'showMessage' => false,
                            //'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_doc_pago", "placeholder" => Pagos::t("Pagos", "Payment Date")],
                            'pluginOptions' => [
                                'showPreview' => false,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showCancel' => false,
                                'browseClass' => 'btn btn-primary btn-block',
                                'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                                'browseLabel' => "Subir Archivo",
                                'uploadUrl' => Url::to(['pagosfacturas/cargarpago']),
                                'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                        var name_pago= $("#txth_doc_pago").val();
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
                        var name_pago = 'pagoest_' + $('#txth_per').val() + '-' + today.getFullYear() + '-' + d2(parseInt(today.getMonth()+1)) + '-' + d2(today.getDate()) + ' ' + d2(today.getHours()) + ':' + d2(today.getMinutes()) + ':' + d2(today.getSeconds());
                        $('#txth_doc_pago').val(name_pago);    
        
        $('#txt_doc_pago').fileinput('upload');
        var fileSent = $('#txt_doc_pago').val();
        var ext = fileSent.split('.');
        $('#txth_doc_pago').val(name_pago + '.' + ext[ext.length - 1]);
    }",
                                "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_pago').val('');
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
        </div>
    </div>
    <div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12 col-lg-12"></div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            &nbsp;&nbsp;
        </div>
    </div>  
    <?php echo $leyendarc; ?>
    <div class="col-md-12">
        <div class="form-group">
            <div class="box-body table-responsive no-padding">
                <table  id="TbG_Productos" class="table table-striped table-bordered dataTable">
                    <thead>
                        <tr>
                            <th><?= Yii::t("formulario", "Descripción Factura") ?></th>
                            <th><?= Yii::t("formulario", "Valor cuota") ?></th>
                            <th><?= Yii::t("formulario", "Número Cuota") ?></th>
                            <th><?= Yii::t("formulario", "Estado Pago") ?></th>
                            <th><?= Yii::t("formulario", "Observación") ?></th>                                                  
                            <th></th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo substr($pagodetalle['descripcion_factura'], 0, 20) . '...' ?></td>
                            <td><?php echo $pagodetalle['valor_cuota'] ?></td>
                            <td><?php echo $pagodetalle['dpfa_num_cuota'] ?></td>
                            <td><?php echo $pagodetalle['estado_pago'] ?></td>
                            <td><?php echo $pagodetalle['dpfa_observacion_rechazo'] ?></td>                       
                        </tr> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row"> 
        <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11"></div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">&nbsp;&nbsp;  
            <a id="btn_modificarpago" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Update") ?> </a>
        </div>
    </div>
</form>
