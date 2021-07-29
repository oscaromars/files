<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_pais" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Country") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_pais", "", $arr_pais, ["class" => "form-control", "id" => "cmb_pais"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_provincia" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Province") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_provincia", "", $arr_pro, ["class" => "form-control", "id" => "cmb_provincia"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_canton" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Canton") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_canton", "", $arr_can, ["class" => "form-control", "id" => "cmb_canton"]) ?>
            </div>
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_sector" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Sector") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_sector" data-type="all" placeholder="<?= Academico::t("formulario", "Sector") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_calle_pri" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Primary Street") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_calle_pri" data-type="all" placeholder="<?= Academico::t("profesor", "Primary Street") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_calle_sec" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Secondary Street") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_calle_sec" data-type="all" placeholder="<?= Academico::t("profesor", "Secondary Street") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_numeracion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Numeration") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_numeracion" data-type="all" placeholder="<?= Academico::t("profesor", "Numeration") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_referencia" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("profesor", "Reference") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_referencia" data-type="all" placeholder="<?= Academico::t("profesor", "Reference") ?>">
            </div>
        </div>
    </div>
</form>