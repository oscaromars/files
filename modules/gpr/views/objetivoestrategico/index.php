<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>

<div class="row">
    <form class="form-horizontal">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("accion", "Search") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= gpr::t("objetivoestrategico", "Search by Strategic Objective Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_bscs" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= gpr::t("categoriabsc", "Category BSC") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_bscs", "0", $arr_bsc, ["class" => "form-control", "id" => "cmb_bscs"]) ?>                                
                </div>
                <label for="cmb_enfs" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= gpr::t("enfoque", "Focus") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_enfs", "0", $arr_enfoque, ["class" => "form-control", "id" => "cmb_enfs"]) ?>                                
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_plan" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= gpr::t("planificacionpedi", "Pedi Planning") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_plan", "0", $arr_plan, ["class" => "form-control", "id" => "cmb_plan"]) ?>                                
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_buscarData" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>
    </form>
</div>
<br />
<?=
    $this->render('index-grid', ['model' => $model,]);
?>