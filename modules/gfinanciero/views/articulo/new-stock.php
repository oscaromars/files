<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>
<br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_min" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Minimal") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="0" id="frm_min" data-type="number" />
            </div>
        </div>
        <div class="form-group">
            <label for="frm_max" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Maximum") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="0" id="frm_max" data-type="number" />
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_compr" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Engaged") ?> </label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidations" disabled="disabled" value="0" id="frm_compr" data-type="all" />
            </div>
        </div>
        <div class="form-group">
            <label for="frm_exitot" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("articulo", "Total") ?> </label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control PBvalidations" disabled="disabled" value="0" id="frm_exitot" data-type="all" />
            </div>
        </div>
    </div>
</div>
<div class="row">

</div>