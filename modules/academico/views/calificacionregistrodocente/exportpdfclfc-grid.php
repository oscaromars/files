<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_calificaciones_pdf_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        /* 'dataProvider' => $model, */
        'summary' => '',
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],            
            
        [
            'attribute' => 'Matricula',
            'header' => academico::t("Academico", "Enrollment Number"),
            'value' => 'est_matricula',
        ],
        [
            'attribute' => 'Nombres Completos',
            'header' => academico::t("Academico", "Names"),
            'value' => 'Nombres_completos',
        ],
        [
            'attribute' => 'Materia',
            'header' => academico::t("Academico", "course"),
            'value' => 'asi_nombre',
        ],
        [
            'attribute' => 'Paralelo',
            'header' => academico::t("Academico", "Paralelo"),
            'value' => 'par_nombre',
        ],
        [
            'attribute' => 'Promedio Parcial I',
            'header' => academico::t("Academico", "Promedio Parcial I"),
            'value' => 'Parcial_I',
        ],
        [
            'attribute' => 'Promedio Parcial II',
            'header' => academico::t("Academico", "Promedio Parcial II"),
            'value' => 'Parcial_II',
        ],
        [
            'attribute' => 'Supletorio',
            'header' => academico::t("Academico", "Extension"),
            'value' =>  'supletorio',
        ],
        [
            'attribute' => 'Promedio Final',
            'header' => academico::t("Academico", "Final average"),
            'value' => 'promedio_final',
        ],
        [
            'attribute' => 'Asistencia Final',
            'header' => academico::t("Academico", "Asistencia Final"),
        ],
            
           
                     
        ]
    ])
?>