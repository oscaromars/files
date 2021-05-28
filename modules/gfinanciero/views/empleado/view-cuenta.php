<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>
<br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="cmb_grupo" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("grupo", "Group") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_grupo", $userGroup, $arr_grupo, ["class" => "form-control", "id" => "cmb_grupo", "disabled" => "disabled"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label for="cmb_rol" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= Yii::t("rol", "Role") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_rol", $userRol, $arr_rol, ["class" => "form-control", "id" => "cmb_rol", "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_username" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 control-label"><?= financiero::t("empleado", "Username") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" value="<?= $modelUser->usu_user ?>" disabled="disabled" id="frm_username" data-type="all" placeholder="<?= financiero::t("empleado", "Username") ?>">
            </div>
        </div>
    </div>
</div>
