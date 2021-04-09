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
            <label for="frm_id" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("lineaarticulo", "Code") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_id" data-type="all" data-lengthMin="3" data-lengthMax="3" placeholder="<?= financiero::t("tipoarticulo", "Code") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("lineaarticulo", "Item Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_name" data-type="alfa" placeholder="<?= financiero::t("lineaarticulo", "Item Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fecha" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("lineaarticulo", "Creation Date") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fecha',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha", "data-type" => "fecha", "disabled" => "disabled", "placeholder" => financiero::t("lineaarticulo", "Creation Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
    </div> 
</form>