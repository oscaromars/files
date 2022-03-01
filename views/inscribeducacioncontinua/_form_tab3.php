<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use app\components\CFileInputAjax;
use yii\helpers\Url;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
financiero::registerTranslations();
admision::registerTranslations();
?>
<form class="form-horizontal">

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h3><b><span id="lbl_titulo"><?= Yii::t("formulario", "Check the detail of your request") ?></span></b></h3><br>
    </div>
    <div class="col-md-6 col-xs-6 col-sm-6col-lg-6">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <span id="lbl_detalle1"><?= Yii::t("formulario", "Estás a un paso de formalizar tu inscripción, comienza hoy mismo a vivir la experiencia de una auténtica enseñanza empresarial.") ?></span>
            <span id="lbl_detalle2"><?= Yii::t("formulario", "A continuación te presentamos un resumen de lo que has elegido:") ?></span>
            <br/><br/>
        </div>
        <!-- Aqui he colocado la informacion -->
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                <span id="lbl_uaca_lb"><b><?= Yii::t("formulario", "Unidad Academica: ") ?></b></span>
            </div>
            <div class="col-md-8 col-xs-8 col-sm-8 col-lg-8">
                <span id="lbl_uaca_tx"><?= "" ?></span>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                <span id="lbl_moda_lb"><b><?= Yii::t("formulario", "Modalidad: ") ?></b></span>
            </div>
            <div class="col-md-8 col-xs-8 col-sm-8 col-lg-8">
                <span id="lbl_moda_tx"><?= "" ?></span>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                <span id="lbl_carrera_lb"><b><?= Yii::t("academico", "Programa: ") ?></b></span>
            </div>
            <div class="col-md-8 col-xs-8 col-sm-8 col-lg-8">
                <span id="lbl_carrera_tx"><?= "" ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xs-6 col-sm-6col-lg-6">
        <!-- fin de ingreso de informacion -->
        <br/><br/><br/>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <label for="cmb_item" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("facturacion", "Item") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?= Html::dropDownList("cmb_item", 0, $arr_item, ["class" => "form-control", "id" => "cmb_item"]) ?>
            </div>            
            <br/><br/>
        </div>
        
        <!-- item 1 -->
        <div id="id_item_1"  class="col-md-12 col-xs-12 col-sm-12 col-lg-12" style="display:none">
            <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                <span id="lbl_carrera_lb"><b><?= Yii::t("formulario", "Pago: ") ?></b></span>
            </div>
            <div class="col-md-8 col-xs-8 col-sm-8 col-lg-8">
                $ <span id="val_item_1"></span>
            </div>
        </div>                
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        </br>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><b><span id="lbl_general"><?= financiero::t("Pagos", "Billing Data") ?></span></b></h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="txt_nombres_fac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control keyupmce" value="" id="txt_nombres_fac" data-required="true" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="form-group">
                <label for="txt_dir_fac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Address") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control keyupmce" value="" data-required="true" id="txt_dir_fac" data-type="alfanumerico" placeholder="<?= Yii::t("formulario", "Address") ?>">
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="form-group">
                <label for="opt_tipo_DNI" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Type DNI") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <label><input type="radio" name="opt_tipo_DNI"  value="1" checked>&nbsp;&nbsp;<b><?= Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?></b></label><br/>
                    <label><input type="radio" name="opt_tipo_DNI"  value="2" ><b>&nbsp;&nbsp;<?= Yii::t("formulario", "Passport") ?></b></label><br/>
                    <label><input type="radio" name="opt_tipo_DNI"  value="3" ><b>&nbsp;&nbsp;<?= Yii::t("formulario", "RUC") ?></b></label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="form-group">
                <label for="txt_apellidos_fac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control keyupmce" value="" data-required="true" id="txt_apellidos_fac" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="form-group">
                <label for="txt_tel_fac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Phone") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_tel_fac" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12' id="DivcedulaFac">
            <div class="form-group">
                <label for="txt_dni_fac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "DNI Document") . '/' . Yii::t("formulario", "DNI 1") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" maxlength="10" class="form-control" value="" id="txt_dni_fac" data-type="cedula" data-keydown="true" placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="display: none;" id="DivpasaporteFac">
            <div class="form-group">
                <label for="txt_pasaporte_fac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Passport") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" maxlength="15" class="form-control keyupmce" id="txt_pasaporte_fac" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Passport") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="display: none;" id="DivRucFac">
            <div class="form-group">
                <label for="txt_ruc_fac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "RUC") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" maxlength="15" class="form-control keyupmce" id="txt_ruc_fac" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "RUC") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="txt_correo_fac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Email") ?> </label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control keyupmce" value="" data-required="true" id="txt_correo_fac" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        </br>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><b><span id="lbl_subtitulo1"><?= financiero::t("Pagos", "Details of payment") ?></span></b></h4>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-2 col-xs-2 col-sm-2 col-lg-2">
            <span><b><?= Yii::t("formulario", "Forma Pago: ") ?></b></span>
        </div>
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">     
            <label>
                <input type="radio" name="rdo_forma_pago_deposito" id="rdo_forma_pago_deposito" value="1" checked>Depósito<br>
            </label>  
            <label>
                <input type="radio" name="rdo_forma_pago_transferencia" id="rdo_forma_pago_transferencia" value="2">Transferencia<br>
            </label>         
            <label>
                <input type="radio" name="rdo_forma_pago_otros" id="rdo_forma_pago_otros" value="3" >Tarjeta<br>
            </label>                                
        </div>
    </div>
    <div id="DivSubirPago" style="display:block">                
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="form-group">
                    <label for="txt_numtransaccion" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_num_transaccion"><?= admision::t("Solicitudes", "Transaction number") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <input type="text" class="form-control keyupmce" value="" id="txt_numtransaccion" data-type="number" placeholder="<?= admision::t("Solicitudes", "Transaction number") ?>">
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
                <div class="form-group">
                    <label for="txt_observacion" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_observacion"><?= Yii::t("formulario", "Observation") ?></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                        <textarea  class="form-control keyupmce" id="txt_observacion" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                <div class="form-group">
                    <label for="txt_fecha_transaccion" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label"><?= admision::t("Solicitudes", "Transaction date") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                        <?=
                        DatePicker::widget([
                            'name' => 'txt_fecha_transaccion',
                            'value' => '',
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => ["class" => "form-control keyupmce", "id" => "txt_fecha_transaccion", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => admision::t("Solicitudes", "Transaction date")],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => Yii::$app->params["dateByDatePicker"],
                            ]]
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                <div class="form-group">
                    <label for="txth_doc_pago" class="col-sm-2 col-md-2 col-xs-2 col-lg-2  control-label keyupmce" id="txth_doc_pago" name="txth_doc_pago"><?= Yii::t("formulario", "Attach document") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                       <?= Html::hiddenInput('txth_per', $per_id, ['id' => 'txth_per']); ?>
                        <?= Html::hiddenInput('txth_doc_pago', '', ['id' => 'txth_doc_pago']); ?>
                        <?php
                        echo CFileInputAjax::widget([
                            'id' => 'txt_doc_pago',
                            'name' => 'txt_doc_pago',
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
                                'uploadUrl' => Url::to(['inscribeducacioncontinua/saveinscripciontemp']),
                                'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                            return {"upload_filepago": true, "name_file": "pago", "inscripcion_id": $("#txth_twin_id").val()};
                        }',
                                    ],
                                    'pluginEvents' => [
                                        "filebatchselected" => "function (event) {
                        $('#txth_doc_pago').val($('#txt_doc_pago').val());
                        $('#txt_doc_pago').fileinput('upload');
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
                  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-2">
            <a id="paso2back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
        <div class="col-md-2">
            <a id="sendInscripcionSubirPago" href="javascript:" class="btn btn-primary btn-block"> <?php echo "Pagar"; ?></a>
        </div>
    </div>


        <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="DivBoton">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
            <div class="col-md-2">
                <a id="sendInscripcionsolicitud" href="javascript:" class="btn btn-primary btn-block"> <?php echo "Pagar"; ?> </a>
            </div>
            <a id="btn_pago_i" href="javascript:" class="btn btn-primary btn-block pbpopup"></a>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:none" id="DivSubirPagoBtn">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
            <div class="col-md-2">
            <a id="sendInscripcionSubirPago" href="javascript:" class="btn btn-primary btn-block"> <?php echo "Pagar"; ?> </a>
            </div>
        </div>
    </div>-->
</form>