<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\modules\academico\Module as Especies;

Especies::registerTranslations();
$leyenda = '<div ALIGN="justify" style = "width: 380px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> El detalle del trámite es la constancia de 
          la solicitud. Tenga cuidado al ingresar especialmente en el detalle del trámite puesto que este texto también es parte de la especie valorada, luego de generar la solicitud, no puede realizar modificaciones.</div>';

$formatoimagen = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div ALIGN="justify" style = "width: 450px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';
$pagodia = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div ALIGN="justify" style = "width: 600px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Si no se encuentra al día en los pagos no podra crear solicitud de especies valoradas.</div>
          </div>
          </div>
          </div>';
?>

<?= Html::hiddenInput('txth_idest', $arr_persona['est_id'], ['id' => 'txth_idest']); ?>
<?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
<?php echo $pagodia ?>  
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">   

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Especies::t("Especies", "Datos del Estudiante") ?></span></h4> 
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
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1">Cédula</label>
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
                <h4><span id="lbl_general"><?= Especies::t("Especies", "Datos de Solicitud") ?></span></h4> 
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
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_tramite" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_tramite"><?= especies::t("Especies", "Procedure") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?=
                        Html::dropDownList(
                                "cmb_tramite", 0, array_merge([Yii::t("formulario", "Select")], $arr_tramite), ["class" => "form-control", "id" => "cmb_tramite"]
                        )
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_especie" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_especie"><?= Yii::t("formulario", "Especies") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?=
                        Html::dropDownList(
                                "cmb_especies", 0, array_merge([Yii::t("formulario", "Select")], $arr_especies), ["class" => "form-control", "id" => "cmb_especies"]
                        )
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_dsol_cantidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_dsol_cantidad"><?= Especies::t("Pagos", "Cantidad") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="1" id="txt_dsol_cantidad" disabled="true" data-type="alfa" align="rigth"  
                               onblur="pedidoEnterGrid(true, this)"
                               onkeydown="pedidoEnterGrid(true, this)"
                               placeholder="<?= Especies::t("Pagos", "Cantidad") ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_dsol_valor" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_dsol_valor"><?= Especies::t("Pagos", "Precio") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="0" id="txt_dsol_valor" data-type="alfa" align="rigth" disabled="true" placeholder="<?= Especies::t("Pagos", "Precio") ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_dsol_total" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_dsol_total"><?= Especies::t("Pagos", "Total") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="0" id="txt_dsol_total" data-type="alfa" align="rigth" disabled="true" placeholder="<?= Especies::t("Pagos", "Total") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_fpago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_fpago"><?= Yii::t("formulario", "Forma Pago") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?=
                        Html::dropDownList(
                                "cmb_fpago", 0, ArrayHelper::map(app\modules\academico\models\Especies::getFormaPago(), 'Ids', 'Nombre'), ["class" => "form-control", "id" => "cmb_fpago"]
                        )
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_observacion" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_observacion"><?= Yii::t("formulario", "Detalle Trámite") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <textarea  class="form-control keyupmce" id="txt_observacion" rows="5"></textarea>   
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">               
                <label for="lbl_leyenda" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_leyenda"></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?php echo $leyenda ?>  
                </div>             
            </div>            
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="lbl_doc_adj_img" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::hiddenInput('txth_doc_adj_img', '', ['id' => 'txth_doc_adj_img']); ?>  
                        <?= Html::hiddenInput('txth_doc_adj_leads2', '', ['id' => 'txth_doc_adj_leads2']); ?>
                        <?php
                        echo CFileInputAjax::widget([
                            'id' => 'txt_doc_adj_img',
                            'name' => 'txt_doc_adj_img',
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
                                'uploadUrl' => Url::to(['/academico/especies/cargarimagen']),
                                'maxFileSize' => Yii::$app->params["MaxFileSize"],
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                                            var name_archivo= $("#txth_doc_adj_img").val();
                                return {"upload_file": true, "name_file": name_archivo};
                            }',
                            ],
                            'pluginEvents' => [
                                "filebatchselected" => "function (event) {                        
                                            function d2(n) {
                                            if(n<9) return '0'+n;
                                            return n;
                                            }
                                            today = new Date();
                                            var name_archivo = 'IMG_' + $('#txth_per').val() + '-' + today.getFullYear() + '-' + d2(parseInt(today.getMonth()+1)) + '-' + d2(today.getDate()) + ' ' + d2(today.getHours()) + ':' + d2(today.getMinutes()) + ':' + d2(today.getSeconds());
                                            $('#txth_doc_adj_img').val(name_archivo);    

                            $('#txt_doc_adj_img').fileinput('upload');
                            var fileSent = $('#txt_doc_adj_img').val();
                            var ext = fileSent.split('.');
                            $('#txth_doc_adj_img').val(name_archivo + '.' + ext[ext.length - 1]);
                        }",
                                "fileuploaderror" => "function (event, data, msg) {
                            $(this).parent().parent().children().first().addClass('hide');
                            $('#txth_doc_adj_img').val('');
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
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <?php echo $formatoimagen; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5"> </div>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <a id="btn_addData" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Add") ?></a>
            </div>
        </div>
    </div>
    <div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12 col-lg-12"></div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_total" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="txt_total"><?= Especies::t("Pagos", "Total a Pagar") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
<!--                    <input type="text" class="form-control keyupmce" value="0" id="txt_dsol_total" data-type="alfa" align="rigth" disabled="true" placeholder="<?= Especies::t("Pagos", "Total") ?>">-->
                    <label for="lbl_total" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_total">0.00</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="box-body table-responsive no-padding">
                <table  id="TbG_Productos" class="table table-hover">
                    <thead>
                        <tr>
                            <th style="display:none; border:none;"><?= Yii::t("formulario", "Ids") ?></th>
                            <th style="display:none; border:none;"><?= Yii::t("formulario", "uaca_id") ?></th>
                            <th><?= Yii::t("formulario", "Unidad") ?></th>
                            <th style="display:none; border:none;"><?= Yii::t("formulario", "tra_id") ?></th>
                            <th><?= Yii::t("formulario", "Trámite") ?></th>
                            <th style="display:none; border:none;"><?= Yii::t("formulario", "esp_id") ?></th>
                            <th><?= Yii::t("formulario", "Especie") ?></th>
                            <!--<th><?= Yii::t("formulario", "Forma Pago") ?></th>-->
                            <th><?= Yii::t("formulario", "Cantidad") ?></th>
                            <th><?= Yii::t("formulario", "Valor") ?></th>
                            <th><?= Yii::t("formulario", "Total") ?></th>
                            <!--<th><? Yii::t("formulario", "F.Aut") ?></th>-->
                            <!--<th><? Yii::t("formulario", "F.Cad") ?></th>-->
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
<script>
    var AccionTipo = 'Create';
</script>