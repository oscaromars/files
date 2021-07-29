<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\models\ObjetoModulo;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_macro_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],            
            [
                'attribute' => 'linea',
                'header' => investigacion::t("lineainvestigacion", "Line of research"),
                'value' => 'linea',
            ],
            [
                'attribute' => 'macro',
                'header' => investigacion::t("macroproyecto", "Macroproject"),
                'value' => 'macro',
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Accion',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['macroproyecto/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
