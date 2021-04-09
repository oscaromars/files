<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("discapacidad", "Disability Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->dis_nombre ?>" id="frm_name" data-type="alfa" disabled="disabled" placeholder="<?= financiero::t("discapacidad", "Disability Name") ?>">
            </div>
        </div>
    </div>
     <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_porcentaje" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("discapacidad", "Percentage") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->dis_porcentaje ?>" id="frm_porcentaje" data-type="alfa" disabled="disabled" placeholder="<?= financiero::t("discapacidad", "Percentage") ?>">
            </div>
        </div>
    </div>
    
  
    
</form>
<input type="hidden" id="frm_id" value="<?= $model->dis_id ?>">
