<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();

?>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h4><span id="lbl_planear"><?= investigacion::t("macroproyecto", "Register - Macroproject") ?></span></h4>
</div><br><br><br>
<form class="form-horizontal">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
        <label for="cmb_nameline" class="col-sm-3 control-label"><?= investigacion::t("lineainvestigacion", 'Name line of investigation') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?= Html::dropDownList("cmb_linea", 0,  $arr_linv, ["class" => "form-control", "id" => "cmb_linea"]) ?>
            </div>
               
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_namemacro" class="col-sm-3 control-label"><?= investigacion::t("macroproyecto", 'Macroproject') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="" id="txt_nameline" data-type="all" data-keydown="true" placeholder="<?= investigacion::t("macroproyecto", 'Name Macroproject') ?>"> 

            </div>
        </div>
     </div>
   
</form>

<input type="hidden" id="frm_linv_id" value="<?= $arr_linv['linv_id'] ?>"