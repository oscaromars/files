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
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipoliquidacion", "Liquidation Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->tliq_nombre ?>" id="frm_name" data-type="all" placeholder="<?= financiero::t("tipoliquidacion", "Liquidation Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fdate" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipoliquidacion", "Creation Date") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= date('Y-m-d', strtotime($model->tliq_fecha_creacion)) ?>" id="frm_fdate" disabled="disabled" data-type="all" placeholder="<?= financiero::t("tipoliquidacion", "Creation Date") ?>">
            </div>
        </div>
    </div> 
</form>
<input type="hidden" id="frm_id" value="<?= $model->tliq_id ?>">