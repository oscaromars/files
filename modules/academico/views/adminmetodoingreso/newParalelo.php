<?php

use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as aspirante;

admision::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();
aspirante::registerTranslations();

?>
<?= Html::hiddenInput('txth_id', $pmin_id, ['id' => 'txth_id']); ?>

<!--<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= Yii::t("academico", "Parallel") ?></span></h3><br/>    
</div>-->
<div class="col-md-12">    
    <h4><span id="lbl_titulo1"><?= $periodo ?></span></h4><br/>    
</div>

<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_nombre" class="col-sm-4 control-label" id="lbl_descripcion"><?= Yii::t("formulario", "Name") ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombre" data-type="alfanumerico" placeholder="<?= Yii::t("formulario", "Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_descripcion" class="col-sm-4 control-label" id="lbl_descripcion"><?= Yii::t("formulario", "Description") ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_descripcion" data-type="alfanumerico" placeholder="<?= Yii::t("formulario", "Description") ?>">
            </div>
        </div>
    </div>  
    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_cupo" class="col-sm-4 control-label" id="lbl_descripcion"><?= academico::t("Academico", "Quota") ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_cupo" data-type="numeracion" placeholder="<?= Yii::t("academico", "Quota") ?>">
            </div>
        </div>
    </div>              
   
</form>


