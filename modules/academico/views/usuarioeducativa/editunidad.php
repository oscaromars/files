<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

financiero::registerTranslations();
academico::registerTranslations();
//print_r($arr_unidad);
?>
<?= Html::hiddenInput('txth_unidadid', base64_decode($_GET['ceuni_id']), ['id' => 'txth_unidadid']); ?>
<!-- <div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Edit Unit") ?></span><br/>    
</div>
<br><br><br><br>-->
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_periodoeditunidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodoeditunidad", $arr_unidad ["paca_id"], $arr_periodoAcademico, ["class" => "form-control pro_combo", "id" => "cmb_periodoeditunidad"]) ?>
            </div> 
            <label for="lbl_cursoeditunidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Course") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                 <?= Html::dropDownList("cmb_cursoeditunidad", $arr_unidad ["cedu_id"], $arr_curso, ["class" => "form-control pro_combo", "id" => "cmb_cursoeditunidad"]) ?>
            </div>   
        </div>
    </div>   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">  
            <label for="txt_codigoeditunidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label" id="lbl_codigoeditunidad"><?= academico::t("matriculacion", 'Código Unidad') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_unidad ["ceuni_codigo_unidad"]?>" id="txt_codigoeditunidad" data-type="number" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Código Unidad') ?>">                    
            </div>                         
            <label for="txt_descripcioneditunidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_descripcioneditunidad"><?= Yii::t("formulario", "Nombre Unidad") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_unidad ["ceuni_descripcion_unidad"]?>" id="txt_descripcioneditunidad" data-type="all" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Nombre Unidad') ?>"> 
         </div>
    </div>        
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_fecha_inied" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_inicio"><?= Yii::t("formulario", "Start date") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_inied',
                    'disabled' => false,
                    'value' => $arr_unidad ["ceuni_fecha_inicio"],
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation", "id" => "txt_fecha_inied", "placeholder" => Yii::t("formulario", "Start date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="txt_fecha_fined" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_fin"><?= Yii::t("formulario", "End date") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_fined',
                    'disabled' => false,
                    'value' => $arr_unidad ["ceuni_fecha_fin"],
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation", "id" => "txt_fecha_fined", "placeholder" => Yii::t("formulario", "End date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>   
</div>
<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_editunidad" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Update") ?></a>
</div>-->    
</br>
</form>