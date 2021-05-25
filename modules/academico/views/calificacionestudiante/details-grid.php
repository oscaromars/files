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

/*
Debe traerlas notas del parcial  detallado de los componentes con su promedio y asistencia y  % de asistencia.
*/


PbGridView::widget([
    'id' => 'Tbg_Calificaciones',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Matricula',
            'header' => Yii::t("formulario", "Parcial"),
            'value' => 'matricula',
        ],
        [
            'attribute' => 'Supletorio',
            'header' => Yii::t("formulario", "Asincorona"),
        ],
        [
            'attribute' => 'Mejoramiento',
            'header' => Yii::t("formulario", "Sincronas"),
        ],
        [
            'attribute' => 'Promedio Final',
            'header' => Yii::t("formulario", "Autonomas"),
        ],
        [
            'attribute' => 'Asistencia Final',
            'header' => Yii::t("formulario", "Evaluacion"),
        ],
        [
            'attribute' => 'Asistencia ',
            'header' => Yii::t("formulario", "Examen"),
        ],
       /* [
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
