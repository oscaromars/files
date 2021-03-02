<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;

use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as aspirante;

admision::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();
aspirante::registerTranslations();

?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
 <div>        
    <?=
    PbGridView::widget([
        //'dataProvider' => new yii\data\ArrayDataProvider(array()),
        'id' => 'Pbgperiodo',
        //'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $mod_periodo,
        'columns' => [            
            [
                'attribute' => 'Año',
                'header' => academico::t("Academico", "Year"),
                'value' => 'anio',
            ],
            [
                'attribute' => 'Mes',
                'header' => academico::t("Academico", "Month"),
                'value' => 'mes',
            ],
            [
                'attribute' => 'Código',
                'header' => Yii::t("academico", "Code"),
                'value' => 'codigo',
            ],
            [
                'attribute' => 'Método Ingreso',
                'header' => admision::t("Solicitudes", "Income Method"),
                'value' => 'metodo',
            ],
            [
                'attribute' => 'Fecha Inicial',
                'header' => Yii::t("formulario", "Start date"),
                'value' => 'fecha_inicial',
            ],
            [
                'attribute' => 'Fecha Final',
                'header' => Yii::t("formulario", "End date"),
                'value' => 'fecha_final',
            ],
            [
                'attribute' => 'Num. Paralelos',
                'header' => academico::t("Academico", "Parallels"),
                'value' => 'paralelos',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{view} {update} {listar}', //
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-check"></span>', Url::to(['adminmetodoingreso/newparalelo', 'pami_id' => base64_encode($model['pami_id']), 'codigo' => base64_encode($model['codigo'])]), ["data-toggle" => "tooltip", "title" => "Registrar Paralelos", "data-pjax" => 0]);                        
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>', Url::to(['adminmetodoingreso/update', 'pami_id' => base64_encode($model['pami_id']), 'codigo' => base64_encode($model['codigo'])]), ["data-toggle" => "tooltip", "title" => "Modificar Período", "data-pjax" => 0]);
                    },
                    'listar' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-list"></span>', Url::to(['adminmetodoingreso/listarparalelo', 'pami_id' => base64_encode($model['pami_id']), 'codigo' => base64_encode($model['codigo'])]), ["data-toggle" => "tooltip", "title" => "Listar Paralelos", "data-pjax" => 0]);     
                    },
                ],
            ],
        ],
    ])
    ?>
    </div>   
