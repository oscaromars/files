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
                    <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= gpr::t("responsablesubunidad", "Search by Person's Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_empresa" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= yii::t("formulario", "Company") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_empresa", "0", $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa"]) ?>                                
                </div>
                <label for="cmb_nivel" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= gpr::t("responsablesubunidad", "Level") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_nivel", "0", $arr_nivel, ["class" => "form-control", "id" => "cmb_nivel"]) ?>                                
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_uni" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= gpr::t("unidad", "Unity") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_uni", "0", $arr_unidad, ["class" => "form-control", "id" => "cmb_uni"]) ?>                                
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