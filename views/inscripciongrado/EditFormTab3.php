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
            <label for="txt_trabajo_direccionEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Dirección de Trabajo") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_trabajo_direccionEdit" value="<?= $persona_model->per_trabajo_direccion ?>" data-type="all" placeholder="<?= Academico::t("profesor", "Dirección de Trabajo")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_contc_emergenciasEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Contacto en caso de Emergencia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_contc_emergenciasEdit" value="<?= $contacto_model->pcon_nombre ?>" data-type="all" placeholder="<?= Academico::t("profesor", "Nombre de Contacto en caso de Emergencia")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_parentescoEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Parentesco") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_parentescoEdit", $contacto_model->tpar_id, $arr_tipparentesco, ["class" => "form-control", "id" => "cmb_parentescoEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cel_contactoEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "CellPhone") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_cel_contactoEdit" value="<?= $contacto_model->pcon_celular ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "CellPhone")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_direccion_contEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Dirección de Persona en Caso de Emergencia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_direccion_contEdit" value="<?= $contacto_model->pcon_direccion ?>" data-required="false" data-type="alfanumerico"  placeholder="<?= Yii::t("formulario", "Dirección de Persona en Caso de Emergencia") ?>">
            </div>
        </div>
    </div>
</form>