<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'PbMarcacion',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'columns' => [               
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => academico::t("Academico", "Period"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['desc_periodo']) > 15) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['desc_periodo'], 0, 15) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['desc_periodo']]);
                    },
                ],
            ],
            [
                'attribute' => 'profesor',
                'header' => Yii::t("formulario", "Teacher"),
                'value' => 'nombres',
            ],  
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Matter"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['materia']) > 20) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['materia'], 0, 20) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['materia']]);
                    },
                ],
            ],
            [
                'attribute' => 'Modalidad',
                'header' => academico::t("Academico", "Modality"),
                'value' => 'modalidad',
            ],
            [
                'attribute' => 'Fecha',
                'header' => Yii::t("formulario", "Date"),
                'value' => 'fecha',
            ],
            [
                'attribute' => 'Horaini',
                'header' => academico::t("Academico", "Hour start date"),
                'value' => 'hora_inicio',
            ],
            [
                'attribute' => 'Horainipon',
                'header' => academico::t("Academico", "Hour start date") . ' ' . academico::t("Academico", "Expected"),
                'value' => 'inicio_esperado',
            ],
            [
                'attribute' => 'Horafin',
                'header' => academico::t("Academico", "Hour end date"),
                'value' => 'hora_salida',
            ],
            [
                'attribute' => 'Horafinpon',
                'header' => academico::t("Academico", "Hour end date") . ' ' . academico::t("Academico", "Expected"),
                'value' => 'salida_esperada',
            ],
            [
                'attribute' => 'ip',
                'header' => academico::t("Academico", "Start IP"),
                'value' => 'ip',
            ],
            [
                'attribute' => 'ips',
                'header' => academico::t("Academico", "End IP"),
                'value' => 'ip_salida',
            ],
        ],
    ])
    ?>
</div>   
