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
            <label for="frm_id" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Code") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" disabled ="disabled" value="<?= $new_id ?>" id="frm_id" data-type="number"  data-lengthMax="3" placeholder="<?= financiero::t("bodega", "Code") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Cellar") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_name" data-type="all"  placeholder="<?= financiero::t("bodega", "Cellar") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_direccion" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Address") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_direccion" data-type="all"  placeholder="<?= financiero::t("bodega", "Address") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_pais" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Country") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_pais" data-type="all" placeholder="<?= financiero::t("bodega", "Country") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_ciudad" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "City") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_ciudad" data-type="all" placeholder="<?= financiero::t("bodega", "City") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_telefono" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Phone") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_telefono" data-type="alfa" data-lengthMax="20" placeholder="<?= financiero::t("bodega", "Phone") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_correo" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Mail") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_correo" data-type="email"  placeholder="<?= financiero::t("bodega", "Mail") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_responsable" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Responsable") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_responsable" data-type="all" data-lengthMax="10" placeholder="<?= financiero::t("bodega", "Responsable") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_punto" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Emission Point") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?= Html::dropDownList("cmb_punto", 0, $punto, ["class" => "form-control", "id" => "cmb_punto"]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_num_ingreso" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Income Number") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="000000000" id="frm_num_ing" data-type="number" data-lengthMax="10"  placeholder="<?= financiero::t("bodega", "Income Number") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_num_egreso" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Egress Number") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="000000000" id="frm_num_egr" data-type="number" data-lengthMax="10" placeholder="<?= financiero::t("bodega", "Egress Number") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fecha" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("bodega", "Creation Date") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fecha',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha", "data-type" => "fecha", "disabled" => "disabled", "placeholder" => financiero::t("bodega", "Creation Date")],
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