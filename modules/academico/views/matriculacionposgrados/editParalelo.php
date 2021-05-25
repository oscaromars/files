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
<?= Html::hiddenInput('txth_parid', base64_decode($_GET["parid"]), ['id' => 'txth_parid']); ?>
<?= Html::hiddenInput('txth_proid', base64_decode($_GET["proid"]), ['id' => 'txth_proid']); ?>
<?= Html::hiddenInput('txth_cupoviejo', $cons_paralelo['pppr_cupo'], ['id' => 'txth_cupoviejo']); ?>
<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Data Parallel") ?></span><br/>    
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_cupo" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_solicitud"><?= academico::t("Academico", "Quota") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control PBvalidation" value="<?= $cons_paralelo['pppr_cupo'] ?>" id="txt_cupo" data-type="number" data-keydown="true" placeholder="<?= academico::t("Academico", "Quota") ?>">                                
            </div>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_cupodisponible" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_valor"><?= academico::t("Academico", "Quota Available") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control PBvalidation" value="<?= $cons_paralelo['pppr_cupo_actual'] ?> " id="txt_cupodisponible" disabled ="true" data-keydown="true" placeholder="<?= academico::t("Academico", "Quota Available") ?>">                                
                <br>
            </div>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12"> 
        <div class="form-group">
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">                                  
            </div> 
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">   
                <a id="btn_enviar"  class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Send") ?></a>                   
            </div>      
            <div class="col-sm-4">                                  
            </div> 
        </div>    
    </div> 
</form>