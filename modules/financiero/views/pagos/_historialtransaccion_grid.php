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
<div>
    <?=
    PbGridView::widget([
        'id' => 'TbG_HISTORIAL_TRANSACCIONES',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelhis",
        'fnExportPDF' => "exportPdfhis",
        'dataProvider' => $model,     
        'columns' =>
        [   
            [
                'attribute' => 'referencia',
                'header' => Yii::t("formulario", "Reference"),
                'value' => 'referencia',
            ],
            [
                'attribute' => 'estudiante',
                'header' => Yii::t("formulario", "Student"),
                'value' => 'estudiante',
            ],
            [
                'attribute' => 'fecha_pago',
                'header' => Yii::t("formulario", "Date"),
                'value' => 'fecha_pago',
            ],           
            [
                'attribute' => 'total_pago',
                'header' => Yii::t("formulario", "Pago"),
                'value' => 'total_pago',
            ],
            [
                'attribute' => 'Estado',
                'header' => financiero::t("Pagos", "Payment status"),
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["estado"] == 'APPROVED')
                        return '<small class="label label-success">Pagado</small>';                        
                    elseif ($model["estado"] == 'REJECTED')
                        return '<small class="label label-danger">Rechazado</small>';
                    elseif ($model["estado"] == 'PENDING')
                        return '<small class="label label-warning">Pendiente</small>';
                    elseif ($model["estado"] == 'FAILED')
                        return '<small class="label label-default">Fallido</small>';                    
                    else
                        return '<small class="label label-info">"En espera</small>';                    
                },
            ],            
        ],
    ])
    ?>
</div>