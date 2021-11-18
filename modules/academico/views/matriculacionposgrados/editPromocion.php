<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as aspirante;

admision::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();
aspirante::registerTranslations();
?>
<?= Html::hiddenInput('txth_progid', base64_decode($_GET["ids"]), ['id' => 'txth_progid']); ?>
<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Data Promotion Program") ?></span><br/>    
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_anio" class="col-sm-4 control-label" id="lbl_anio"><?= academico::t("Academico", "Year") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" value="<?= $data_promo['ppro_anio'] ?>" id="txt_anio" data-type="graduacion" data-keydown="true" placeholder="<?= Yii::t("academico", "Year") ?>">
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_mes" class="col-sm-4 control-label" id="lbl_mes"><?= academico::t("Academico", "Month") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_mes", $data_promo['ppro_mes'], $mes, ["class" => "form-control", "id" => "cmb_mes"]) ?>
                </div>
            </div>
        </div>  
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_unidad" class="col-sm-4 control-label" id="lbl_unidad"><?= academico::t("Academico", "Academic unit") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_unidad", $data_promo['uaca_id'], $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad", "disabled" => "tue"]) ?>
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_modalidad" class="col-sm-4 control-label" id="lbl_modalidad"><?= academico::t("Academico", "Modality") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_modalidad", $data_promo['mod_id'], $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
        </div>  
    </div>    
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_programa" class="col-sm-4 control-label" id="lbl_programa"><?= Yii::t("formulario", "Program") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_programa", $data_promo['eaca_id'], $arr_programa1, ["class" => "form-control", "id" => "cmb_programa"]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_grupo" class="col-sm-4 control-label" id="lbl_grupo"><?= academico::t("Academico", "Group") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" value="<?= $data_promo['ppro_grupo'] ?>" id="txt_grupo" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("academico", "Grupo") ?>">
                </div>
            </div>
        </div>               
        
    </div> 
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>    
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_paralelo" class="col-sm-4 control-label" id="lbl_paralelo"><?= academico::t("Academico", "Parallel") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" value="<?= $data_promo['ppro_num_paralelo'] ?>" id="txt_paralelo" disabled ="true" data-type="number" data-keydown="true" placeholder="<?= Yii::t("academico", "Parallel") ?>">
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_cupo" class="col-sm-4 control-label" id="lbl_cupo"><?= academico::t("Academico", "Quota") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" value="<?= $data_promo['ppro_cupo'] ?>" id="txt_cupo" disabled ="true" data-type="number" data-keydown="true" placeholder="<?= Yii::t("academico", "Quota") ?>">
                </div>
            </div>
        </div>
    </div>  
    <!--<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_modificar" href="javascript:" class="btn btn-primary btn-block"> <? Yii::t("formulario", "Update") ?></a>
        </div>
    </div>-->
</form>