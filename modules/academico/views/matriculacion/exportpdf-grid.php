<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use yii\grid\CheckboxColumn;
academico::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_registro_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        /* 'dataProvider' => $model, */
        'summary' => '',
        'dataProvider' => $planificacion,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],            
            [
                'attribute' => 'Subject',
                'header' => Academico::t("matriculacion", "Subject"),
                'value' => 'Subject',
            ],
            [
                'attribute' => 'CodeAsignatura',
                'header' => Academico::t("matriculacion", "Subject Code"),
                'value' => 'CodeAsignatura',
            ],
            [
                'attribute' => 'Block',
                'header' => Academico::t("matriculacion", "Block"),
                'value' => 'Block',
            ],
            [
                'attribute' => 'Hour',
                'header' => Academico::t("matriculacion", "Hour"),
                'value' => 'Hour',
            ],
            [
                'attribute' => 'Credit',
                'header' => Academico::t("matriculacion", "Credit"),
                'value' => 'Credit',
            ],
        ]
    ])
?>