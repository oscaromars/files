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
    'tableOptions' => [
        'class' => 'table table-striped',
    ],
    'options' => [
        'class' => 'table-responsive',
    ],
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
            'header' => academico::t("Academico", "Materia"),
            'value' => 'asi_nombre',
        ],
        [
            'attribute' => 'PartialI',
            'header' => academico::t("Academico", "Partial I"),
            'value' => 'parcial_1',
        ],
        [
            'attribute' => 'PartialII',
            'header' => academico::t("Academico", "Partial II"),
            'value' => 'parcial_2',
        ],
        [
            'attribute' => 'PromedioFinal',
            'header' => academico::t("formulario", "Promedio Final"),
            'value' => 'promedio_final',
        ],
        [
            'attribute' => 'AsistenciaFinal',
            'header' => academico::t("Academico", "Asistencia Final"),
            'value' => 'asistencia_final',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => academico::t("Academico", "Acciones"),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    
                    if ($model['paca_id'] != null && $model['est_id'] != null && $model['asi_id'] != null && $model['pro_id'] != null
                           && $model['asistencia_parcial_1'] != null && ($model['asistencia_parcial_2'] != null || $model['asistencia_parcial_2'] == null)  ){

                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['calificacionestudiante/view', 
                                'paca_id' => base64_encode($model['paca_id']), 
                                'est_id' => base64_encode($model['est_id']), 
                                'asi_id' => base64_encode($model['asi_id']),
                                'pro_id' => base64_encode($model['pro_id']),
                                'asistencia_parcial_1' => base64_encode($model['asistencia_parcial_1']),
                                'asistencia_parcial_2' => base64_encode($model['asistencia_parcial_2'])
                                ]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    }else{
                        return Html::a('<span style ="color: gray" class="glyphicon glyphicon-eye-close"></span>');
                    }
                }
            ],
        ],
    ],
])
?>
