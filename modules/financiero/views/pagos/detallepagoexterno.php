<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\admision\Module;

admision::registerTranslations();

?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Items Detail") ?></span></h3>
</div>
<div>
    <?=
    PbGridView::widget([
        'id' => 'TbG_DETALLE_PAGOS_EXTERNOS',
        'showExport' => true,
        'fnExportEXCEL' => "exportExceldpex",
        'fnExportPDF' => "exportPdfdpex",
        'dataProvider' => $model,     
        'columns' =>
        [   
            [
                'attribute' => 'codigo',
                'header' => Yii::t("formulario", "Code"),
                'value' => 'codigo',
            ],
            [
                'attribute' => 'item',
                'header' => Yii::t("formulario", "Item"),
                'value' => 'item',
            ],
            [
                'attribute' => 'cantidad',
                'header' => financiero::t("Pagos", "Amount"),
                'value' => 'cantidad',
            ],           
            [
                'attribute' => 'iva',
                'header' => financiero::t("Pagos", "Tax"),
                'value' => 'iva',
            ],
            [
                'attribute' => 'total',
                'header' => financiero::t("Pagos", "Value"),
                'value' => 'total',
            ],               
        ],
    ])
    ?>
</div>