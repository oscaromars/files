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
use app\modules\repositorio\Module as repositorio;
?>
<div class="col-md-12">    
    <h3><span id="lbl_Personeria"><?= repositorio::t("repositorio", "Evidence Repository") ?></span>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>        
</div>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6"> 
            <div class="form-group"> 
                <label for="cmb_modelo_evi" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= repositorio::t("repositorio", "Model") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?php //Html::dropDownList("cmb_modelo_evi", 1,array_merge($arr_modelos) , ["class" => "form-control", "id" => "cmb_modelo_evi"]) ?>
                    <?=
                    Html::dropDownList(
                            "cmb_modelo_evi", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_modelos, ["class" => "form-control", "id" => "cmb_modelo_evi"]
                    )
                    ?>
                </div>
            </div>   
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_funcion_evi" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= repositorio::t("repositorio", "Function") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_funcion_evi", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_funciones, ["class" => "form-control", "id" => "cmb_funcion_evi"]) ?>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="display: block;" id="Divcomponente">
            <div class="form-group">            
                <label for="cmb_componente_evi" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= repositorio::t("repositorio", "Component") ?></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_componente_evi", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_componentes, ["class" => "form-control", "id" => "cmb_componente_evi"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_estandar_evi" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= repositorio::t("repositorio", "Standar") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_estandar_evi", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_estandares, ["class" => "form-control", "id" => "cmb_estandar_evi"]) ?>
                </div>
            </div>
        </div>
    </div>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_tipo_evi" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= Yii::t("formulario", "Type") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::dropDownList("cmb_tipo_evi", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_tipos, ["class" => "form-control", "id" => "cmb_tipo_evi"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txt_fecha_documento_evi" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= repositorio::t("repositorio", "Document date") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_documento_evi',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_documento_evi", "placeholder" => repositorio::t("repositorio", "Document date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">         
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="txth_doc_archivo" class="col-lg-5 col-md-5 col-sm-5 col-xs-5 control-label"><?= repositorio::t("repositorio", "Attach document") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <?= Html::hiddenInput('txth_per', $per_id, ['id' => 'txth_per']); ?>
                    <?= Html::hiddenInput('txth_doc_archivo', '', ['id' => 'txth_doc_archivo']); ?>
                    <?= Html::hiddenInput('txth_doc_archivo_ruta', '', ['id' => 'txth_doc_archivo_ruta']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_doc_archivo',
                        'name' => 'txt_doc_archivo',
                        'disabled' => '',
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
                            'uploadUrl' => Url::to(['/repositorio/repositorio/savedocumentos']), // CABIAR RUTA QUE ES
                            'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                                    var name_doc= $("#txt_doc_archivo").val();
                                    var modelo=$("#cmb_modelo_evi option:selected").text();
                                    var funcion=$("#cmb_funcion_evi option:selected").text();
                                    var componente=$("#cmb_componente_evi option:selected").text();
                                    var estandar=$("#cmb_estandar_evi option:selected").text();
                                    var tipo=$("#cmb_tipo_evi option:selected").text();
                                    
                                    return {"upload_file": true, 
                                            "name_file": name_doc,
                                            "modelo": modelo,
                                            "funcion": funcion,
                                            "componente": componente,
                                            "estandar": estandar,
                                            "tipo": tipo};
                                    
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
                                    //$('#txth_docarchivo').val(name_pago);
                                    
                                    if($('#cmb_modelo_evi').val() != 0 && $('#cmb_funcion_evi').val() != 0 && $('#txth_docarchivo').val() != ''
                                        && $('#cmb_estandar_evi').val() != 0 && $('#cmb_tipo_evi').val() != 0 && $('#txt_fecha_documento_evi').val() != ''){
                                        $('#txt_doc_archivo').fileinput('upload');
                                    }else{
                                        showAlert('NO_OK', 'error', {'wtmessage': 'No Existe datos Selecionados ', 'title':'Información'});
                                        $('#txt_doc_archivo').fileinput('clear');
                                        $('#txt_doc_archivo').fileinput('refresh');
                                    }
        
                                    
                                    
                                    //var fileSent = $('#txt_doc_archivo').val();
                                    //var ext = fileSent.split('.');
                                    //$('#txth_doc_archivo').val(name_pago + '.' + ext[ext.length - 1]);
                        }",
                            "fileuploaderror" => "function (event, data, msg) {
                                    $(this).parent().parent().children().first().addClass('hide');
                                    $('#txth_doc_archivo').val('');
                                    $('#txth_doc_archivo_ruta').val('');
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
                                response = data.response
                                $('#txth_doc_archivo').val('');
                                $('#txth_doc_archivo_ruta').val('');
                                if(response.status){
                                    $('#txth_doc_archivo').val(response.nombre);
                                    $('#txth_doc_archivo_ruta').val(response.ruta);
                                }
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
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_descripcion_evi" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Description") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                
                    <textarea  class="form-control keyupmce" id="txt_descripcion" rows="3"></textarea>                  
                </div>
            </div>
        </div>
    </div> 
    <br/>
    <br/>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6"></div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class='col-md-7 col-xs-7 col-lg-7 col-sm-7'></div>
            <div class='col-md-3 col-xs-3 col-lg-3 col-sm-3'>         
                <p> <a id="btn_AgregarItem" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Add") ?></a></p>
            </div>
        </div>        
    </div> 
    <br/>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_cargado"><?= repositorio::t("repositorio", "Document Loaded") ?></span></h3>
    </div>
    <!--    <div id = "dataListItem"></div>-->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="box-body table-responsive no-padding">
                <table  id="TbG_Data" class="table table-hover">
                    <thead>
                        <tr>
                            <th style="display:none; border:none;"><?= Yii::t("formulario", "Indice") ?></th>
                            <th style="display:none; border:none;"><?= Yii::t("formulario", "Ids") ?></th>
                            <th><?= Yii::t("formulario", "Modelo") ?></th>
                            <th><?= Yii::t("formulario", "Función") ?></th>
                            <th><?= Yii::t("formulario", "Componente") ?></th>                            
                            <th style="display:none; border:none;"></th>
                            <th><?= Yii::t("formulario", "Estandar") ?></th> 
                            <th style="display:none; border:none;"></th>
                            <th><?= Yii::t("formulario", "Tipo") ?></th> 
                            <th><?= Yii::t("formulario", "Imagen") ?></th> 
                            <th><?= Yii::t("formulario", "Fecha") ?></th> 
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

