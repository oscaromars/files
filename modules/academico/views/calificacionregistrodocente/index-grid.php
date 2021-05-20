<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>

<?=

    //$count = $this->dataProvider->getCount();

PbGridView::widget([
    'id' => 'Tbg_Calificaciones',
    'showExport' => false,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
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
            'attribute' => 'Asistencia Parcial I',
            'header' => academico::t("Academico", "Asistencia Parcial I"),
            'value' => 'asistencia_parcial_I',
        ],
        [
            'attribute' => 'Asistencia Parcial I',
            'header' => academico::t("Academico", "Asistencia Parcial II"),
            'value' => 'asistencia_parcial_II',
        ],
        /*[
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t("Formulario", "Acciones"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    if (strlen($model['carrera']) > 30) {
                        $texto = '...';
                    }
                    return Html::a('<span>' . substr("carrera", 0, 20) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => "Carrera"]);
                },
            ],
        ],*/
        /*[
            'attribute' => 'categoria',
            'header' => Yii::t("formulario", "Category"),
            'value' => 'categoria',
        ],*/
      
    ],
])
?>