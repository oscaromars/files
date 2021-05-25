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
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Matricula',
            'header' => Yii::t("formulario", "Matricula"),
            'value' => 'matricula',
        ],
        [
            'attribute' => 'Nombres Completos',
            'header' => Yii::t("formulario", "Nombres Completos"),
            'value' => 'nombres',
        ],
        [
            'attribute' => 'Promedio Parcial I',
            'header' => Yii::t("formulario", "Promedio Parcial I"),
        ],
        [
            'attribute' => 'Promedio Parcial II',
            'header' => Yii::t("formulario", "Promedio Parcial II"),
        ],
        [
            'attribute' => 'Supletorio',
            'header' => Yii::t("formulario", "Supletorio 3"),
        ],
        [
            'attribute' => 'Mejoramiento',
            'header' => Yii::t("formulario", "Mejoramiento"),
        ],
        [
            'attribute' => 'Promedio Final',
            'header' => Yii::t("formulario", "Promedio Final"),
        ],
        [
            'attribute' => 'Asistencia Final',
            'header' => Yii::t("formulario", "Asistencia Final"),
        ],
        [
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
        ],
        /*[
            'attribute' => 'categoria',
            'header' => Yii::t("formulario", "Category"),
            'value' => 'categoria',
        ],*/
      
    ],
])
?>
