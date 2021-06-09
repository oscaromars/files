<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\models\ObjetoModulo;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_regconf_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'PeriodoAcademico',
                'header' => 'Periodo Academico',
                'value' => 'PeriodoAcademico',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => 'Modalidad',
                'value' => 'Modalidad',
            ],
            [
                'attribute' => 'Inicio',
                'header' => 'Fecha Inicio',
                'value' => 'Inicio',
            ],
            [
                'attribute' => 'Fin',
                'header' => 'Fecha Fin',
                'value' => 'Fin',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Accion',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['planificacion/viewreg', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
