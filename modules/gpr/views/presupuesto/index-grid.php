<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gpr\models\Umbral;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();


?>
<?=
    PbGridView::widget([
        'id' => 'grid_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Anio',
                'header' => gpr::t("presupuesto", 'Year'),
                'value' => 'Anio',
            ],
            [
                'attribute' => 'Mes',
                'header' => gpr::t("presupuesto", 'Month'),
                'value' => function($data){
                    $mes = "";
                    switch($data['Mes']){
                        case 1:
                            $mes = gpr::t("meta", 'JAN');
                            break;
                        case 2:
                            $mes = gpr::t("meta", 'FEB');
                            break;
                        case 3:
                            $mes = gpr::t("meta", 'MAR');
                            break;
                        case 4:
                            $mes = gpr::t("meta", 'APR');
                            break;
                        case 5:
                            $mes = gpr::t("meta", 'MAY');
                            break;
                        case 6:
                            $mes = gpr::t("meta", 'JUN');
                            break;
                        case 7:
                            $mes = gpr::t("meta", 'JUL');
                            break;
                        case 8:
                            $mes = gpr::t("meta", 'AUG');
                            break;
                        case 9:
                            $mes = gpr::t("meta", 'SEP');
                            break;
                        case 10:
                            $mes = gpr::t("meta", 'OCT');
                            break;
                        case 11:
                            $mes = gpr::t("meta", 'NOV');
                            break;
                        case 12:
                            $mes = gpr::t("meta", 'DEC');
                            break;
                    }
                    return $mes;
                },
            ],
            [
                'attribute' => 'Programado',
                'header' => gpr::t("presupuesto", 'Scheduled Budget'),
                'value' =>  function($data){
                    return "$".(number_format($data['Programado'], 2, '.', ','));
                },
            ],
            [
                'attribute' => 'Ejecutado',
                'header' => gpr::t("presupuesto", 'Executed Budget'),
                'value' => function($data){
                    return "$".(number_format($data['Ejecutado'], 2, '.', ','));
                },
            ],
            [
                'attribute' => 'Progreso',
                'header' => gpr::t("presupuesto", 'Progress'),
                'value' => function($data){
                    return round(($data['Ejecutado'] / $data['Programado']) * 100, 0) . "%";
                }
            ],
            [
                'attribute' => 'Avance',
                'header' => gpr::t("proyecto", 'Advance'),
                'value' => function($data) use ($presupuestoTotal){
                    return round(($data['Ejecutado'] / $presupuestoTotal) * 100, 0) . "%";
                }
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("presupuesto", "Status"),
                'value' => function($data){
                    $progreso = round(($data['Ejecutado'] / $data['Programado']) * 100, 0);
                    // parametros para medir exceso en presupuesto

                    if($progreso > 100)
                        return '<small class="label label-danger">'.gpr::t("presupuesto", "Underrated Budget").'</small>';
                    elseif($progreso == 100 || $progreso > 95)
                        return '<small class="label label-success">'.gpr::t("presupuesto", "Approximate Budget").'</small>';
                    else
                        return '<small class="label label-warning">'.gpr::t("presupuesto", "Overrated Budget").'</small>';
                },
            ],
        ],
    ])
?>

