<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();

$institution=0;
?>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_planear"><i class="fas fa-users-cog"></i> <?= investigacion::t("registroproyecto", "Registration of Members") ?></span></h3>
    </div><br><br>

    <form class="form-horizontal" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span id="lbl_planear"><?= investigacion::t("registroproyecto", "Information - Members") ?></span></h4>
        </div><br><br>
         <div class="form-group">
            <label for="frm_caracteristica" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= investigacion::t("registroproyecto", "Institution") ?></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

                <input type="checkbox" id="chk_institucion1" value="<?php echo $institution=0 ?>" onchange="javascript:showContent()"> 
                <label for="chk_uteg"  class="control-label" name="institucion1"><?php echo $model_di['entidad'] ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" id="chk_institucion2" value="<?php echo $institution=1 ?>" onchange="javascript:showContent()"> 
                <label for="chk_otro" class="control-label" name="institucion1"><?= investigacion::t("registroproyecto", "Other Entities") ?></label>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="content" style="display:none">
            <div class="form-group">            
                <label for="cmb_tipoinst" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= investigacion::t("registroproyecto", "Type of institution") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                    <?= Html::dropDownList("cmb_tipoinst", 0,  $arr_tipins , ["class" => "form-control", "id" => "cmb_tipoinst"]) ?>
                </div>
                   
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="content1" style="display:none">
            <div class="form-group">
                <label for="txt_institucion" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= investigacion::t("registroproyecto", "Institution") ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation " id="txt_institucion" data-type="all"  placeholder="<?= investigacion::t("registroproyecto", "Institution") ?>">
                </div>
            </div>
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                     <label for="txt_responsable" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Responsible") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" id="txt_responsable" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Responsible") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                     <label for="txt_rol" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Role") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" id="txt_rol" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Role") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                     <label for="txt_telefono" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "CellPhone") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" id="txt_telefono" data-type="number" placeholder="<?= investigacion::t("registroproyecto", "CellPhone") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                     <label for="txt_emailin" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Mail") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" id="txt_emailin" data-type="email" placeholder="<?= investigacion::t("registroproyecto", "Mail") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class="form-group pago_documento col-lg-12 col-md-12 col-sm-12 col-xs-12" id="content2" style="display:none">
            <label class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label" for="txt_pago_documento" id="txth_doc_titulo" name="txth_doc_pago"><?= Yii::t("formulario", "Attach document") ?><span class="text-danger"> * </span></label>
            <div   class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
                <?= Html::hiddenInput('txth_pago_documento', '', ['id' => 'txth_pago_documento']); ?>
                <?= Html::hiddenInput('txth_pago_documento2', '', ['id' => 'txth_pago_documento2']); ?>
                <?php
                    echo CFileInputAjax::widget([
                        'id'            => 'txt_pago_documento',
                        'name'          => 'txt_pago_documento',
                        'pluginLoading' => false,
                        'showMessage'   => false,
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showCaption' => true,
                            'showRemove'  => true,
                            'showUpload'  => false,
                            'showCancel'  => false,
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon'  => '<i class="fa fa-folder-open"></i> ',
                            'browseLabel' => "Subir Archivo",
                            'uploadUrl'  => Url::to(['investigacion/registrointegrante']),
                            //'maxFileSize' => Yii::$app->params["MaxFileSize"],
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                                return {"upload_file": true, "name_file": "registrointegrante-' . $per_id . '-' . time() . '"};
                            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
                                $('#txth_pago_documento2').val('registrointegrante-" . $per_id . '-' . time() . "');
                                $('#txth_pago_documento').val($('#txt_pago_documento').val());
                                $('#txt_pago_documento').fileinput('upload');
                            }",
                            "fileuploaderror" => "function (event, data, msg) {
                                $(this).parent().parent().children().first().addClass('hide');
                                $('#txth_pago_documento').val('');        
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
                        ],//'pluginEvents'
                    ]); 
                ?>
            </div>  
        </div>
        <div class="form-group pago_documento" id="content3" style="display:none">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-4">

                <a id="register_subject_btn" href="javascript:" class="btn btn-primary pull-right" onclick="registerSubject()" style="margin: 0px 5px; "><?= investigacion::t("registroproyecto", "Agregar Integrante") ?></a>
            </div>
            <div class="col-xs-2">

            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-4">
                
               <a id="register_form_btn" href="javascript:" class="btn btn-success pull-right" onclick="registerInt()" style="margin: 0px 5px; display: block;"><?= investigacion::t("registroproyecto", "Siguiente") ?></a>

            </div>
            <div class="col-xs-2">

            </div>
        </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
           <!--  /*
    PbGridView::widget([
        'id' => 'grid_integrante_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombre',
                'header' => investigacion::t("registroproyecto", "Contact Name") ,
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Cargo',
                'header' => investigacion::t("registroproyecto", "Position"),
                'value' => 'Cargo',
            ],
            [
                'attribute' => 'Organizacion',
                'header' => investigacion::t("registroproyecto", "Company / Organization"),
                'value' => 'Organizacion',
            ],
            [
                'attribute' => 'Numero',
                'header' => investigacion::t("registroproyecto", "Contact Number"),
                'value' => 'Numero',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['per_id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?> -->

        </div>
    </div>
</form>
</div>