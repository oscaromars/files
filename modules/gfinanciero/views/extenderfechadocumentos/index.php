<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use kartik\date\DatePicker;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<div class="row">
    <form class="form-horizontal">
        
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_tipo_establecimiento" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("establecimiento", "Establishment Code") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tipo_establecimiento", $cod_est, $arr_establecimiento, ["class" => "form-control", "id" => "cmb_tipo_establecimiento",  "disabled"=>"disabled"]) ?>                                
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="cmb_tipo_emision" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= financiero::t("emision", "Issue") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tipo_emision", "0", $arr_emision, ["class" => "form-control", "id" => "cmb_tipo_emision"]) ?>                                
                </div>
            </div>
        </div>
        <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="txt_search" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("accion", "Search") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_search" placeholder="<?= financiero::t("tipocontrato", "Search by Type Contract") ?>">
                </div>
            </div>
        </div> -->
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">
                <label for="frm_fecha" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("establecimiento", "Date") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=
                    DatePicker::widget([
                        'name' => 'frm_fechadocumento',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha", "data-type" => "fecha", "placeholder" => financiero::t("establecimiento", "Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?> 
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
