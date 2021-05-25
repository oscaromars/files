<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use yii\grid\CheckboxColumn;
academico::registerTranslations();
?>
<h3><?= Academico::t("matriculacion", "Credit Direct") ?></h3>
<?=
    PbGridView::widget([
        'id' => 'grid_cuotas_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        /* 'dataProvider' => $model, */
        'dataProvider' => $cuotas,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],            
            [
                'attribute' => 'Cuota',
                'header' => Academico::t("matriculacion", "Payment Quote"),
                'value' => function($data){
                    return Academico::t("matriculacion", "Payment") . " #" . $data['Cuota'];
                },
            ],
            [
                'attribute' => 'Vencimiento',
                'header' => Academico::t("matriculacion", "Expiration Date"),
                'value' => 'Vencimiento',
            ],
            [
                'attribute' => 'Porcentaje',
                'header' => Academico::t("matriculacion", "Percentage"),
                'value' => 'Porcentaje',
            ],
            [
                'attribute' => 'Price',
                'header' => Academico::t("matriculacion", "Payment"),
                'value' => function($data){
                    return  "$".(number_format($data['Price'], 2, '.', ','));
                },
            ],
        ]
    ])
?>