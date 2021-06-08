<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>
<form class="form-horizontal">
    <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="form-group">                 
                <label for="cmb_per_acad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_per_acad", 0, $arr_pla, ["class" => "form-control", "id" => "cmb_per_acad"]) ?>
                </div>
                <label for="cmb_mod" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("matriculacion", 'Modality') ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_mod", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_mod"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_buscarRegConf" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>
    </div>
</form>
<br />
<?=
    $this->render('register-index-grid', ['model' => $model,]);
?>