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
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_tplan" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= gpr::t("proyecto", 'Planning Type') ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tplan", "0", $arr_tplan, ["class" => "form-control", "id" => "cmb_tplan"]) ?>                                
                </div>          
                <label for="cmb_filter" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= gpr::t("proyecto", "Search By") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_filter", "-1", $arr_filter, ["class" => "form-control", "id" => "cmb_filter"]) ?>
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