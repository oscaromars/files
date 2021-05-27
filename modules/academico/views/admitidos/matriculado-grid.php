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
<div>
    <?=
    PbGridView::widget([
        'id' => 'TbG_MATRICULADO',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelmat",
        'fnExportPDF' => "exportPdfmat",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [
            [
                'attribute' => 'solicitud',
                'header' => admision::t("Solicitudes", "Request #"),
                'value' => 'solicitud',
            ],
            [
                'attribute' => 'DNI',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'per_dni',
            ],
            [
                'attribute' => 'Nombres',
                'header' => Yii::t("formulario", "First Names"),
                'value' => 'per_nombres',
            ],
            [
                'attribute' => 'Apellidos',
                'header' => Yii::t("formulario", "Last Names"),
                'value' => 'per_apellidos',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => admision::t("Solicitudes", "Income Method"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . $model['abr_metodo'] . '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['ming_nombre']]);
                    },
                ],
            ],
            [
                'attribute' => 'modalidad',
                'header' => admision::t("Solicitudes", "Modalidad"),
                'value' => 'mod_nombre',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => academico::t("Academico", "Career/Program"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . substr($model['carrera'], 0, 10) . '..</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                    },
                ],
            ],
            [
                'attribute' => 'estado',
                'header' => Yii::t("formulario", "Status"),
                'value' => 'beca',
            ],
            [
                'attribute' => 'contrato',
                'header' => academico::t("Academico", "Contract"),
                'value' => 'beca',
            ],
            /* [
              'attribute' => 'promocion',
              'header' => academico::t("Academico", "Promotion"),
              'value' => 'pami_codigo',
              ], */
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{matricula} {ver} {ficha}', //
                'buttons' => [
                    'matricula' => function ($url, $model) {
                        if ($model['matriculado']== "MAT_NO") {
                            return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['/academico/matriculacionposgrados/new', 'sids' => base64_encode($model['sins_id']), 'adm' => base64_encode($model['adm_id']), 'perid' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => "Matriculación", "data-pjax" => 0]);
                        } else {
                            return '<span class="glyphicon glyphicon-list-alt"></span>';                            
                        }
                    },
                    'ver' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/academico/matriculacionposgrados/view', 'sids' => base64_encode($model['sins_id']), 'adm' => base64_encode($model['adm_id']), 'perid' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => "Ver Matriculación", "data-pjax" => 0]);
                    },
                    'ficha' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', Url::to(['/academico/ficha/update', 'mat' => base64_encode(1), 'perid' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => "Ficha Matriculado", "data-pjax" => 0]);
                    },
                ],
            ],
        ],
    ])
    ?>
</div>