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
    <h4><span id="lbl_planear"><?= investigacion::t("proyecto", "Register - Type of Project") ?></span></h4>
</div><br><br><br>
<form class="form-horizontal">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    <div class="form-group">
        <label for="cmb_nameline" class="col-sm-3 control-label"><?= investigacion::t("proyecto", 'Name Type of Project') ?><span class="text-danger"> *</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation " value="" id="txt_nameline" data-type="all" data-keydown="true" placeholder="<?= investigacion::t("proyecto", 'Name Type of Project') ?>"> 

        </div>
    </div>
    
</form>