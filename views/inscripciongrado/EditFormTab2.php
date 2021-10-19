<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\Academico\Module as Academico;
Academico::registerTranslations();
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_paisEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Country") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_paisEdit", $persona_model->pai_id_domicilio, $arr_pais, ["class" => "form-control", "id" => "cmb_paisEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_provinciaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Province") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_provinciaEdit", $persona_model->pro_id_domicilio, $arr_pro, ["class" => "form-control", "id" => "cmb_provinciaEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_cantonEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Canton") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_cantonEdit", $persona_model->can_id_domicilio, $arr_can, ["class" => "form-control", "id" => "cmb_cantonEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_parroquiaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Parroquia") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_parroquiaEdit" value="<?= $persona_model->per_domicilio_csec ?>" data-type="all"  placeholder="<?= Academico::t("profesor", "Parroquia")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_domicilioEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Dirección del domicilio") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_domicilioEdit" value="<?= $persona_model->per_domicilio_ref ?>" data-type="all"  placeholder="<?= Academico::t("profesor", "Detallar la dirección de su domicilio")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_celEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "CellPhone") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_celEdit" value="<?= $persona_model->per_celular ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "CellPhone") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_phoneEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Phone") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_phoneEdit" value="<?= $persona_model->per_domicilio_telefono ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Phone") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_correoEdit" class="col-sm-3 control-label"><?= Academico::t("profesor", "Mail") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_correoEdit" value="<?= $persona_model->per_correo ?>" data-type="email" placeholder="<?= Academico::t("profesor", "Mail") ?>">
            </div>
        </div>
    </div>
</form>