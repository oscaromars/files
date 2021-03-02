<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\modules\academico\Module as Especies;

Especies::registerTranslations();
?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">     

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">       
        <h3>Solicitud: <span id="lbl_num_solicitud"><?= app\models\Utilities::add_ceros($cab_solicitud[0]['csol_id'], 9) ?></span></h3>
    </div>
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
                        <?= Html::dropDownList("cmb_ninteres", $cab_solicitud[0]['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
                    </div>
                </div>  
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
                <div class="form-group">
                    <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Modality") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", $cab_solicitud[0]['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_dsol_total" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_dsol_total"><?= Especies::t("Pagos", "Total") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value=<?= $cab_solicitud[0]['csol_total'] ?> id="txt_dsol_total" data-type="alfa" align="rigth" disabled="true" placeholder="<?= Especies::t("Pagos", "Total") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_fpago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_fpago"><?= Yii::t("formulario", "Forma Pago") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?=
                        Html::dropDownList(
                                "cmb_fpago", $cab_solicitud[0]['fpag_id'], ArrayHelper::map(app\modules\academico\models\Especies::getFormaPago(), 'Ids', 'Nombre'), ["class" => "form-control", "id" => "cmb_fpago", "disabled" => "true"]
                        )
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="lbl_doc_adj_pago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::hiddenInput('txth_doc_adj_pago', '', ['id' => 'txth_doc_adj_pago']); ?>
                        <?= Html::hiddenInput('txth_doc_adj_leads2', '', ['id' => 'txth_doc_adj_leads2']); ?>
                        <?php
                        echo CFileInputAjax::widget([
                            'id' => 'txt_doc_adj_pago',
                            'name' => 'txt_doc_adj_pago',
                            'pluginLoading' => false,
                            'showMessage' => false,
                            'disabled' => true,
                            'pluginOptions' => [
                                'showPreview' => false,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showCancel' => false,
                                'browseClass' => 'btn btn-primary btn-block',
                                'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                                'browseLabel' => "Subir Archivo",
                                'uploadUrl' => Url::to(['/academico/especies/cargarpago']),
                                'maxFileSize' => Yii::$app->params["MaxFileSize"],
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                                        return {"upload_file": true, "name_file": "DOC-' . @Yii::$app->session->get("PB_iduser") . '-' . time() . '"};
                                    }',
                            ],
                            'pluginEvents' => [
                                "filebatchselected" => "function (event) {
                                    $('#txth_doc_adj_pago').val('DOC-" . @Yii::$app->session->get("PB_iduser") . '-' . time() . "');
                                    $('#txth_doc_adj_leads2').val($('#txt_doc_adj_pago').val());
                                    $('#txt_doc_adj_pago').fileinput('upload');
                                }",
                                "fileuploaderror" => "function (event, data, msg) {
                                    $(this).parent().parent().children().first().addClass('hide');
                                    $('#txth_doc_adj_pago').val('');        
                                }",
                                "filebatchuploadcomplete" => "function (event, files, extra) { 
                                    $(this).parent().parent().children().first().addClass('hide');
                                }",
                                "filebatchuploadsuccess" => "function (event, data, previewId, index) {
                                    var form = data.form, files = data.files, extra = data.extra,
                                    response = data.response, reader = data.reader;
                                    $(this).parent().parent().children().first().addClass('hide');
                                    var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];       
                                }",
                                "fileuploaded" => "function (event, data, previewId, index) {
                                    $(this).parent().parent().children().first().addClass('hide');
                                    var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];                           
                                }",
                            ],
                        ]);
                        ?>
                    </div>     
                </div>                  
            </div>     
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txth_doc_pago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Especies::t("Especies", "Payment") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">                
                        <?php
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "/uploads/especies/" . $img_pago]) . "' download='" . $img_pago . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pago</a>"
                        ?>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <?php //echo $leyenda;  ?>
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
                            <th><?= Yii::t("formulario", "Cantidad") ?></th>
                            <th><?= Yii::t("formulario", "Valor") ?></th>
                            <th><?= Yii::t("formulario", "Total") ?></th>
                            <th><?php // Yii::t("formulario", "F.Aut")       ?></th>
                            <th><?php // Yii::t("formulario", "F.Cad")       ?></th>
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
    var AccionTipo = 'SubirPago';
    var varSolicitud =<?= $det_solicitud; ?>
</script>