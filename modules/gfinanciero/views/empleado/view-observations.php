<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>
<br />
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="txta_observacion" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("empleado", "Observations") ?> </label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <textarea name="textarea" id="txta_observacion" rows="10" disabled="disabled" class="form-control PBvalidations" data-type="all"><?= $modelGemp->OBSER01 ?></textarea>
            </div>
        </div>
        
    </div>
</div>