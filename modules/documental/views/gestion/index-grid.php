<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\documental\Module as documental;
use app\modules\documental\Models\Documento;

documental::registerTranslations();

?>

<?=
    // print_r($model);
    PbGridView::widget([
        'id' => 'grid_documento_list',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Departamento',
                'header' => documental::t("documento", "UNITY"),
                'value' => 'Departamento',
            ],
            [
                'attribute' => 'Proceso',
                'header' => documental::t("documento", 'PROCESS'),
                'value' => 'Proceso',
            ],
            [
                'attribute' => 'Cod. Archivo',
                'header' => documental::t("documento", 'FILE CODE'),
                'value' => 'Codigo',
            ],
            [
                'attribute' => 'Tipo de Info.',
                'header' => documental::t("documento", 'TYPE OF INFORMATION'),
                'value' => 'TipoInfo',
            ],
            [
                'attribute' => 'Observaciones',
                'header' => documental::t("documento", 'OBSERVATIONS'),
                'value' => 'Observaciones',
            ],
            [
                'attribute' => 'Estado del Documento',
                'header' => documental::t("documento", 'DOCUMENT STATUS'),
                'value' => 'Estado',
            ],
        ],
    ])
?>