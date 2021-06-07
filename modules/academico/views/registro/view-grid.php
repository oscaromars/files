<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\models\CancelacionRegistroOnline;
use app\modules\academico\models\CancelacionRegistroOnlineItem;
use app\modules\academico\Module as academico;
use yii\grid\CheckboxColumn;
academico::registerTranslations();

?>

<?=
    PbGridView::widget([
        'id' => 'grid_viewcancel_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        /* 'dataProvider' => $model, */
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],            
            [
                'attribute' => 'Materia',
                'header' => Academico::t("matriculacion", "Subject"),
                'value' => 'Materia',
            ],
            [
                'attribute' => 'CodigoMateria',
                'header' => Academico::t("matriculacion", "Subject Code"),
                'value' => 'CodigoMateria',
            ],
            [
                'attribute' => 'Creditos',
                'header' => Academico::t("matriculacion", "Credit"),
                'value' => 'Creditos',
            ],
            [
                'attribute' => 'Costo',
                'header' => Academico::t("matriculacion", "Unit Cost"),
                'value' => function($data){
                    return '$' . (number_format(($data['Costo']), 2, '.', ','));
                },
            ],
            [
                'attribute' => 'Estado',
                'header' => Academico::t("matriculacion", "Status"),
                'format' => 'html',
                'value' => function($data){
                    if($data['Estado'] == 2)
                        return "<span class='label label-success'>".Academico::t('matriculacion', 'Cancelled')."</span>";
                    if($data['Estado'] == 1)
                        return "<span class='label label-info'>".Academico::t('matriculacion', 'Cancellation in process')."</span>";
                  //  return "<span class='label label-warning'>".Academico::t('registro', 'To be Approved')."</span>";
                  return "<span class='label label-success'>".Academico::t('matriculacion', 'Cancelled')."</span>";
                },
            ],
        ]
    ])
?>