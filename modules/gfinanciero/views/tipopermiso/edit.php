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
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipopermiso", "Permit Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->tper_nombre ?>" id="frm_name" data-type="alfa" placeholder="<?= financiero::t("tipopermiso", "Permit Name") ?>">
            </div>
        </div>
    </div>
    
      
    
</form>
<input type="hidden" id="frm_id" value="<?= $model->tper_id ?>">

