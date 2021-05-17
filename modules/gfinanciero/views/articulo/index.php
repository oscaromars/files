<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<div class="row">
    <form class="form-horizontal">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="txt_search" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("accion", "Search") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_search" placeholder="<?= financiero::t("articulo", "Search by Article") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_tipo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("tipoarticulo", "Type Name") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tipo", "0", $arr_tipo, ["class" => "form-control", "id" => "cmb_tipo"]) ?>
                </div>
                <label for="cmb_marca" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("marcaarticulo", "Mark Name") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_marca", "0", $arr_marca, ["class" => "form-control", "id" => "cmb_marca"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_linea" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("lineaarticulo", "Line Name") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_linea", "0", $arr_linea, ["class" => "form-control", "id" => "cmb_linea"]) ?>
                </div>
                <label for="cmb_tpro" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("tipoitem", "Product Type") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tpro", "0", $arr_tpro, ["class" => "form-control", "id" => "cmb_tpro"]) ?>
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
