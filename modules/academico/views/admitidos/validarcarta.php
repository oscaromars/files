<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use yii\data\ArrayDataProvider;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', $opag_id, ['id' => 'txth_ids']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ">
    <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Aprobar Carta de Aceptacion") ?></span></h3>
    <br>
</div>

<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
    <div class="form-group">
        <label for="txt_solicitud" class="col-sm-4 control-label" id="lbl_solicitud"><?= admision::t("Solicitudes", "Documento") ?></label>
        <div class="col-sm-8 ">
            <?= $sins_id ?>
        </div>
    </div>
</div>  