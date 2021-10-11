<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\CFileInputAjax;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;

academico::registerTranslations();
financiero::registerTranslations();
use app\assets\StripeAsset;
StripeAsset::register($this);
//print_r('arr: '.$arr_forma_pago);
// print_r('mod_id: '.$modalidad);
// print_r(':1 '.$modalidad);
// print_r(':2 '.$id_flujo);
// print_r(':3 '.$macs_id);
//print_r('</br>');
//print_r($dataSiga);

$status = 'disabled';

if ($isDuplicate) {
    echo "
    <style>
        .alert-danger {
            color: #721c24 !important;
            background-color: #f8d7da !important;
            border-color: #f5c6cb !important;
        }
        .alert {
            position: relative !important;
            padding: .75rem 1.25rem !important;
            margin-bottom: 1rem !important;
            border: 1px solid transparent !important;
            border-radius: .25rem !important;
        }
    </style>
   ";
    echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group' >";
    echo "  <div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group alert alert-danger' role='alert' >";
    echo "  YA SE REGISTRAN PAGOS PARA ESTE PROCESO";
    echo"   </div>";
    echo "  <div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group' style='right:10'>        
                <div class='col-sm-10 col-md-10 col-xs-8 col-lg-10'></div>
                <div class='col-sm-2 col-md-2 col-xs-4 col-lg-2'>                
                    <a style='right:10' disabled='disabled' id='btn_modificarcargacartera' href='javascript:' class='btn btn-default btn-Action'> <i class='glyphicon glyphicon-floppy-disk'></i>&nbsp;&nbsp;  Guardar<?= Yii::t('formulario', '&nbsp;&nbsp; Guardar') ?></a>
                </div>        
            </div> 
 
        </div>";
}else{
    echo '  <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">        
                <div class="col-sm-10 col-md-10 col-xs-8 col-lg-10"></div>
                <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                    <a id="btn_modificarcargacartera" href="javascript:" class="btn btn-default btn-Action"> <i class="glyphicon glyphicon-floppy-disk"></i> &nbsp;&nbsp; Guardar<?= Yii::t("formulario", "&nbsp;&nbsp; Guardar") ?></a>
                </div>        
            </div>';
}
?>
<?= Html::hiddenInput('txt_per_id', $id, ['id' => 'txt_per_id']); ?>
<?= Html::hiddenInput('txt_rama', $rama, ['id' => 'txt_rama']); ?>
<?= Html::hiddenInput('txt_id_code', $id_en, ['id' => 'txt_id_code']); ?>
<?= Html::hiddenInput('txt_ron_id', $ron_id, ['id' => 'txt_ron_id']); ?>
<?= Html::hiddenInput('txt_existe2', $pes_id, ['id' => 'txt_existe2']); ?>
<?= Html::hiddenInput('txt_pla_id', $pla_id, ['id' => 'txt_pla_id']); ?>
<?= Html::hiddenInput('txt_cuotas', $cuotas, ['id' => 'txt_cuotas']); ?>
<?= Html::hiddenInput('txt_saca_id', $saca_id, ['id' => 'txt_saca_id']); ?>
<?= Html::hiddenInput('txt_bloque', $bloque, ['id' => 'txt_bloque']); ?>
<?= Html::hiddenInput('txt_cedula', $cedula, ['id' => 'txt_cedula']); ?>
<?= Html::hiddenInput('isDuplicate', $isDuplicate, ['id' => 'isDuplicate']); ?>
<?= Html::hiddenInput('datasiga', $datasiga, ['id' => 'datasiga']); ?>
<?= Html::hiddenInput('numregistros', $numregsitros, ['id' => 'numregistros']); ?>
<?= Html::hiddenInput('frm_cuota', 0, ['id' => 'frm_cuota']); ?>
<?= Html::hiddenInput('txt_mod_nombre', $modalidad, ['id' => 'txt_mod_nombre']); ?>
<?= Html::hiddenInput('txt_flujo', $id_flujo, ['id' => 'txt_flujo']); ?>
<?= Html::hiddenInput('txt_macs', $macs_id, ['id' => 'txt_macs']); ?>
<?= Html::hiddenInput('txt_usuario', $usuario, ['id' => 'txt_usuario']); ?>

<?php 
    $registro = '';
    for($i=0; $i < count($datasiga); $i++){
        $registro .= $datasiga[$i]['detalle'].',';//explode(" ",$datasiga[$i]['detalle'])[0];        
    }
    echo "<input type='hidden' id='data_siga' value='".substr($registro,0,-1)."' />";
    echo "<input type='hidden' id='num_reg' value='".count($datasiga)."' />";
    //print_r(substr($registro,0,-1));
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group row">
    <h3><span id="lbl_registro_online"><?= academico::t("Academico", "Registro de pago en Línea") ?></span></h3>
</div>
<form class="form-horizontal" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span><?= academico::t("registro", "Student Information") ?></span></h4>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="frm_name" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("matriculacion", "Student") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <input type="text" class="form-control PBvalidation" value="<?= $estudiante ?>" id="frm_name" data-type="all" disabled='disabled' placeholder="<?= academico::t("matriculacion", "Student") ?>">
                    </div>
                </div>
        
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="frm_carrera" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("Academico", 'Program') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <input type="text" class="form-control PBvalidation" value="<?= $carrera ?>" id="frm_carrera" data-type="all" disabled='disabled' placeholder="<?= academico::t("Academico", "Career/Program") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="frm_periodo" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("Academico", 'Period') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <input type="text" class="form-control PBvalidation" value="<?= $periodo_actual ?>" id="frm_periodo" data-type="all" disabled='disabled' placeholder="<?= academico::t("Academico", "Period") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informacion de Pago -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span><?= academico::t("registro", "Payment Information") ?></span></h4>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label form="frm_tpago" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("registro", 'Credit') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?= Html::dropDownList("cmb_tpago", 2, $arr_credito, ["class" => "form-control", "id" => "cmb_tpago"]) ?>  
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="frm_valor" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("registro", 'Total Payment') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control PBvalidation" value="<?= $costo ?>" id="frm_valor" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "Total Payment") ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div id="ex1" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group nocredit" hidden <?= $style ?>>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">    
                    <label for="frm_int_ced" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("registro", 'Interest on Direct Credit') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control " value="<?= $costoCredito ?>" id="frm_int_ced" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "0.00") ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">  
                    <label for="frm_finan" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("registro", 'Financing Cost') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">   
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control " value="<?= $costoCredito ?>" id="frm_finan" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "0.00") ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div id="ex1" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group nocredit" hidden <?= $style ?>>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
                    <label for="cmb_cuota" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("registro", 'Payment Installments') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"> 
                    <input type="text" class="form-control " value="<?= $cuotas ?>" id="cmb_cuota" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "6") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
        </div>
    </div>


    <div class="row">
        <div id="ex1" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nocredit" hidden <?= $style ?>>
            <div>
                <h4><span><?= academico::t("registro", "Direct Credit") ?></span></h4>
            </div>

            <div style=" /*border: 1px solid;*/ margin-left: 0 !important;">
                <style>
                    .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td {
                    border-top: 0;
                    vertical-align: top;
                    }
                </style>
                <div id="ex1"  class="row nocredit form-group" hidden <?= $style ?>>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?= $this->render('new-grid', ['dataGrid' => $dataGrid]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group nocredit2">
                    <label for="cmb_fpago" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("registro", 'Payment Method') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?= Html::dropDownList("cmb_fpago", 0, $arr_forma_pago, ["class" => "form-control", "id" => "cmb_fpago"]) ?>  
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 nocredit2">
                    <div class="form-group"  id= "atach_docum_pago">
                        <label for="txth_pago_doc" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("registro", "Attach Voucher") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
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
                                        'uploadUrl' => Url::to(['registro/cargarpago']),
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
    </div>

     
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="cmb_req" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= academico::t("registro", 'Terms and Conditions') ?></label>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                    <div class="col-lg-8 col-md-8 col-sm-10 col-xs-10">
                        <div class="checkbox">
                            <label><input type="checkbox" id='cmb_req'> <?= academico::t('registro', 'I accept the terms and conditions of the university regarding payments for subject registration.')?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     


    <div  class= "row"   style="display: block;"  id="paylink2"  class="nocredit" >
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">  
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h4><b><span id="lbl_general"><?= financiero::t("Pagos", "Billing Data") ?></span></b></h4>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-3 control-label" for="txt_dpre_ssn_id_fact" id="lbl_nombre1"><?= Yii::t("formulario", "Cédula") ?><span class="text-danger">*</span></label>
                        <div   class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <?= Html::hiddenInput('txt_dpre_ssn_id_fact_aux', '', ['id' => 'txt_dpre_ssn_id_fact_aux']); ?>
                            <input <?=$status?> type="text" class="form-control PBvalidation keyupmce" value="<?= $datosFacturacion['cedula'] ?>"  id="txt_dpre_ssn_id_fact" data-required="true" data-type="number" placeholder="<?= Yii::t("formulario", "Cedula") ?>" >
                        </div>            
                    </div>            
                </div>  
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-3 control-label" for="txt_nombres_fac" id="lbl_nombre1"><?= Yii::t("formulario", "Name") ?><span class="text-danger">*</span></label>
                        <div   class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <?= Html::hiddenInput('txt_nombres_fac_aux', '', ['id' => 'txt_nombres_fac_aux']); ?>
                            <input <?=$status?> type="text" class="form-control PBvalidation keyupmce" value="<?= $datosFacturacion['nombre'] ?>" id="txt_nombres_fac" data-required="true" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                        </div>            
                    </div>            
                </div> 
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-3 control-label" for="txt_apellidos_fac" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?><span class="text-danger">*</span></label>
                        <div   class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <?= Html::hiddenInput('txt_apellidos_fac_aux', '', ['id' => 'txt_apellidos_fac_aux']); ?>
                            <input <?=$status?> type="text" class="form-control PBvalidation keyupmce" value="<?= $datosFacturacion['apellidos'] ?>" data-required="true" id="txt_apellidos_fac" data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-3 control-label" for="txt_dir_fac" id="lbl_dir_fac"><?= Yii::t("formulario", "Address") ?><span class="text-danger">*</span></label>
                        <div   class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <?= Html::hiddenInput('txt_dir_fac_aux', '', ['id' => 'txt_dir_fac_aux']); ?>
                            <input <?=$status?> type="text" class="form-control keyupmce PBvalidation" value="<?= $datosFacturacion['direccion'] ?>" data-required="true" id="txt_dir_fac" data-type="alfanumerico" placeholder="<?= Yii::t("formulario", "Address") ?>">
                        </div> 
                    </div>  
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-3 control-label" for="txt_tel_fac" id="lbl_apellido1"><?= Yii::t("formulario", "Phone") ?><span class="text-danger">*</span></label>
                        <div   class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <?= Html::hiddenInput('txt_tel_fac_aux', '', ['id' => 'txt_tel_fac_aux']); ?>
                            <input <?=$status?> type="text" class="form-control PBvalidation" value="<?= $datosFacturacion['telefono'] ?>" id="txt_tel_fac" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">                        
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-3 control-label" for="txt_correo_fac" ><?= Yii::t("formulario", "Email") ?><span class="text-danger">*</span> </label>
                        <div   class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <?= Html::hiddenInput('txt_correo_fac_aux', '', ['id' => 'txt_correo_fac_aux']); ?>
                            <input <?=$status?> type="text" class="form-control PBvalidation keyupmce" value="<?= $datosFacturacion['correo'] ?>" data-required="true" id="txt_correo_fac" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                        </div>
                    </div>
                </div>
            </div>        
        </div> 
       

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 nocredit2" >
            
            <div class="col-xs-0 col-sm-0 col-md-3 col-lg-3"></div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nocredit2" id="pago_stripe">
                <!------------------------------------------------------->
                <!----- INI PAGO STRIPE --------------------------------->                
                <!------------------------------------------------------->
                <style type="text/css">
                    #pago_stripe{
                        background-color: lightblue;
                        border-radius:6px;
                        border: 1px gray solid;
                        padding: 0 0 0 10px;
                    }

                    #card-element {
                    border-radius: 4px 4px 0 0 ;
                    padding: 12px;
                    border: 1px solid rgba(50, 50, 93, 0.1);
                    background: ghostwhite;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    }

                    #card-element  input {
                    border-radius: 6px;
                    margin-bottom: 6px;
                    padding: 8px;
                    border: 1px solid rgba(50, 50, 93, 0.1);
                    height: 44px;
                    font-size: 16px;
                    width: 100%;
                    background: white;
                    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
                    font-size: 16px;
                    }
                </style>

                
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style=" display: flex; justify-content: center;">
                    <img src="https://www.uteg.edu.ec/wp-content/themes/UTEG4/images/055693c79f5990e523846b9f43c6779d_logouteg.png" alt="UTEG" style="border-radius:4px;margin:10px;height:40px">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                    <div id="card-element"><!--Stripe.js injects the Card Element--></div>
                </div>
                <!------------------------------------------------------->
                <!----- FIN PAGO STRIPE --------------------------------->                
                <!------------------------------------------------------->      
            </div>       
        </div>       
    </div>

</form>
<input type="hidden" id="frm_per_id" value="<?= $id_en ?>" />
<input type="hidden" id="frm_id" value="<?= $ron_id ?>" />
<input type="hidden" id="frm_rama_id" value="<?= $rama_id ?>" />
<input type="hidden" id="frm_costo_item" value="<?= $costoItem ?>" />
<input type="hidden" id="frm_credito_item" value="<?= $creditoItem ?>" />
<input type="hidden" id="frm_costo_cred" value="<?= $costoCredito ?>" />
<input type="hidden" id="frm_creditos_carr" value="<?= $creditosCarrera ?>" />
<input type="hidden" id="frm_costo_carr" value="<?= $costCarrera ?>" />
<input type="hidden" id="frm_ini_cuota" value="<?= $costoItem ?>" />
<input type="hidden" id="frm_num_cuota" value="1" />
<input type="hidden" id="lbl_payment" value="<?= academico::t("registro", 'Payment #') ?>" />
<input type="hidden" id="vencimiento_1" value="<?=explode(" ",$arr_ve[0]['fvpa_fecha_vencimiento'])[0] ?>" />
<input type="hidden" id="vencimiento_2" value="<?=explode(" ",$arr_ve[1]['fvpa_fecha_vencimiento'])[0] ?>" />
<input type="hidden" id="vencimiento_3" value="<?=explode(" ",$arr_ve[2]['fvpa_fecha_vencimiento'])[0] ?>" />
<input type="hidden" id="vencimiento_4" value="<?=explode(" ",$arr_ve[3]['fvpa_fecha_vencimiento'])[0] ?>" />
<input type="hidden" id="vencimiento_5" value="<?=explode(" ",$arr_ve[4]['fvpa_fecha_vencimiento'])[0] ?>" />
<input type="hidden" id="vencimiento_6" value="<?=explode(" ",$arr_ve[5]['fvpa_fecha_vencimiento'])[0] ?>" />
<input type="hidden" id="vencimiento_7" value="<?=explode(" ",$arr_ve[6]['fvpa_fecha_vencimiento'])[0] ?>" />

