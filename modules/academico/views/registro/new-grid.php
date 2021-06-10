<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
academico::registerTranslations();
//print_r($dataGrid);
$cont = 0;
?>
<?=

PbGridView::widget([
    'id' => 'grid_direct_credit',
    'showExport' => false,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $dataGrid,
    'summary' => '',
    'pajax' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Pago',
            'header' => academico::t("registro", 'Payment'),
            'value' => 'Pago',
        ],
        [
            'attribute' => 'Porcentaje',
            'header' => academico::t("registro", 'Percentage'),
            'value' => 'Porcentaje',
        ], 
        [
            'attribute' => 'Valor',
            'header' => academico::t("registro", 'Value'),
            'value' => 'Valor',
        ],
        [
            'attribute' => 'Vencimiento',
            'header' => academico::t("registro", 'Expiration'),
            'value' => 'Vencimiento',
        ],
        [
            'attribute' => 'Status',
            'header' => academico::t("registro", 'Status Payment'),
            'format' => 'html',
            'value' => function($data) use(&$cont){
                if(isset($data['Valor']) && $cont == 0){
                    $cont++;
                    return '<small class="label label-success">' . academico::t("registro", "Status Payment") . '</small>';
                }
                return '<small class="label label-danger">' . academico::t("registro", "To Pay") . '</small>';
            },
        ],
        [// columna vacia ya que no hay acciones
            'attribute' => '',
            'header' => '',
            'value' => function($data){
                return '';
            },
        ],
    ],
]);
?>
<br />