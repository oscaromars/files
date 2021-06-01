<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="cmb_grupo" class="col-sm-3 control-label"><?= Yii::t("grupo", "Name of Group") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_grupo", $gru_id, $arr_grupos, ["class" => "form-control", "id" => "cmb_grupo", "disabled" => "disabled"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_rol" class="col-sm-3 control-label"><?= Yii::t("rol", "Name of Role") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_rol", $rol_id, $arr_roles, ["class" => "form-control", "id" => "cmb_rol", "disabled" => "disabled"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_objmods" class="col-sm-3 control-label"><?= Yii::t("modulo", "Assign permissions to Modules") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_objmods", $arr_ids, $arr_objMod, ["class" => "form-control", "id" => "cmb_objmods", "multiple" => "", "disabled" => "disabled"]) ?>
        </div>
    </div>
</form>
<input type="hidden" id="frm_grol_id" value="<?= $grol_id ?>">