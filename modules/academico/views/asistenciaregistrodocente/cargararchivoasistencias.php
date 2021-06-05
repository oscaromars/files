<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

use app\modules\academico\models\Profesor;

financiero::registerTranslations();
academico::registerTranslations();

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
          <div class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 15 KB máximo y tipo xlsx o xls </div>
          </div>
          </div>
          </div>';

$per_id = Yii::$app->session->get("PB_perid");

$mod_profesor = new Profesor();
$admin = 1;
$pro_id = $mod_profesor->getProfesoresxid($per_id)['Id'];
if(isset($pro_id)){
    $aprobado = $mod_profesor->isAprobado($pro_id);
}
else{
    if($admin){
        $aprobado = 1;
    }
}

?>

<?= Html::hiddenInput('txth_doc_adj_asistencia', '', ['id' => 'txth_doc_adj_asistencia']); ?>


<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <a type="button" class="btn btn-primary" id="aceptar_btn" href="javascript:" data-dismiss="modal">Aceptar</a>
        <button type="button" class="btn btn-secondary" id="cancelar_btn" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Upload File") ?></span><br/>    
</div>
<div class="col-md-12">    
    <br/>    
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_plantilla" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= academico::t("Academico", "Template"); ?></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
                <?= Html::a(academico::t("matriculacion", "Download") . " Plantilla Asistencia", Url::to(['downloadplantillaasistencia', 'filename' => 'teacher_assistance.xlsx']));   ?>
            </div>                       
        </div> 
    </div>

    <div class="form-group">     
        <label for="txth_doc_adj_asistencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Company") ?></label>
        <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
            <?= Html::dropDownList("cmb_empresa", 0, ArrayHelper::map(\app\models\Empresa::getAllEmpresa(), 'id', 'value'), ["class" => "form-control", "id" => "cmb_empresa", "disabled" => "true"]) ?>
        </div>
    </div>


    <div class="form-group">     
        <label for="txth_doc_adj_asistencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Period") ?></label>
        <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
            <?php if($admin) : ?>
                <?= Html::dropDownList("cmb_periodo", 0, $periodos, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
            <?php else : ?>
                <?= Html::dropDownList("cmb_periodo", $periodo_actual[0]['id'], $periodos, ["class" => "form-control", "id" => "cmb_periodo", "disabled" => true]) ?>
            <?php endif; ?>
        </div>
    </div>

 <div class="form-group">     
        <label for="txth_doc_adj_asistencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= academico::t("Academico", "Unidad") ?></label>
        <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
            <?= Html::dropDownList("cmb_nunidad", 0, $unidades, ["class" => "form-control", "id" => "cmb_nunidad"]) ?>
        </div>
    </div>


<div class="form-group">     
        <label for="txth_doc_adj_asistencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= academico::t("Academico", "Modalidad") ?></label>
        <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
            <?= Html::dropDownList("cmb_nmodalidad", 0, $modalidades, ["class" => "form-control", "id" => "cmb_nmodalidad"]) ?>
        </div>
    </div>
    <div class="form-group">     
        <label for="txth_doc_adj_asistencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= academico::t("Academico", "Partial") ?></label>
        <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
            <?= Html::dropDownList("cmb_parcial", 0, $parciales, ["class" => "form-control", "id" => "cmb_parcial"]) ?>
        </div>
    </div>

    <div class="form-group">     
        <label for="txth_doc_adj_asistencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= academico::t("Academico", "Teacher") ?></label>
        <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
            <?php if($admin) : ?>
                <?= Html::dropDownList("cmb_profesor", $per_id, $profesores, ["class" => "form-control", "id" => "cmb_profesor"]) ?>
            <?php else : ?>
                <?= Html::dropDownList("cmb_profesor", $per_id, $profesores, ["class" => "form-control", "id" => "cmb_profesor", "disabled" => true]) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">     
        <label for="txth_doc_adj_asistencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Subject") ?></label>
        <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
            <?= Html::dropDownList("cmb_asig", 0, $materias, ["class" => "form-control", "id" => "cmb_asig"]) ?>
        </div>
    </div>

  
         <!-- BEGIN IF -->
        <div class="form-group">
            <label for="txth_doc_adj_asistencia" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
                <?= Html::hiddenInput('txth_doc_adj_asistencia', '', ['id' => 'txth_doc_adj_asistencia']); ?>
                <?= Html::hiddenInput('txth_doc_adj_asistencia2', '', ['id' => 'txth_doc_adj_asistencia2']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_adj_asistencia',
                    'name' => 'txt_doc_adj_asistencia',
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i>',
                        'browseLabel' => Yii::t("formulario", "Upload File"),
                        'uploadUrl' => Url::to(['asistenciaregistrodocente/cargararchivoasistencia']),
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                            return {"upload_file": true, "name_file": "asistencia-' . @Yii::$app->session->get("PB_iduser") . '-' . time() . '"};
                        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
                            $('#txth_doc_adj_asistencia2').val('asistencia-" . @Yii::$app->session->get("PB_iduser") . '-' . time() . "');
                            $('#txth_doc_adj_asistencia').val($('#txt_doc_adj_asistencia').val());
                            $('#txt_doc_adj_asistencia').fileinput('upload');
                        }",
                        "fileuploaderror" => "function (event, data, msg) {
                            $(this).parent().parent().children().first().addClass('hide');
                            $('#txth_doc_adj_asistencia').val('');
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
                ]);//style="display: none;"
                ?>
            </div>     
        </div>
      <?php if(count($materias) > 0 && $aprobado) : ?> <?php else: ?>
        <!--<div class="form-group">     
            <label for="txth_doc_adj_asistencia" style="color: red;" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("profesor", "You are still not approved") ?></label>
        </div> -->      
    <?php endif; ?>  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="col-md-2">
                <a id="btn_guardarasistencia" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?> </a>
            </div>
        </div>
    </div>
    </div>
</form>