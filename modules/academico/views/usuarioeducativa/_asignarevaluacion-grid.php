<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<!--<div></br></div>-->
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Asignar_Evaluacion',
        'showExport' => true,
        // 'fnExportEXCEL' => "exportExcelEduregistro",
        // 'fnExportPDF' => "exportPdfEduregistro",
        'dataProvider' => $model,
        'columns' =>
        [         
            [
                'attribute' => 'Período',
                'header' => academico::t("Academico", "Period"),
                'value' => 'Periodo',
            ],
            [
                'attribute' => 'Nombre',
                'header' => Yii::t("formulario", "Complete Names"),
                'value' => 'Nombre',
            ], 
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'Modalidad',
            ],            
            [
                'attribute' => 'Aula',
                'header' => Yii::t("formulario", "Subject"),
                'value' => 'Aula',
            ],
            [
                'attribute' => 'Unidad',
                'header' => Yii::t("formulario", "Unit"),
                'value' => 'Unidad',
            ],
        ],
    ])
    ?>
</div>