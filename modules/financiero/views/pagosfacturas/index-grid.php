<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
//print_r($model);
admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
 <div>
    <?=
    PbGridView::widget([
        'id' => 'TbG_Estcartera',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelestcartera",
        'fnExportPDF' => "exportPdfestcartera",
        'tableOptions' => [
            'class' => 'table table-condensed',
        ],
        'options' => [
            'class' => 'table-responsive table-striped',
        ],
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'cedula',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'ccar_documento_identidad',
            ],
            [
                'attribute' => 'nombres',
                'header' => Yii::t("formulario", "Names"),
                'value' => 'nombres',
            ],
            [
                'attribute' => 'correo',
                'header' => Yii::t("formulario", "Email"),
                'value' => 'per_correo',
            ],
            [
                'attribute' => 'matricula',
                'header' => academico::t("Academico", "Enrollment Number"),
                'value' => 'matricula',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['pagosfacturas/viewsaldo', 'per_ids' => base64_encode($model["per_id"])]), ["data-toggle" => "tooltip", "title" => "Pagos Facturas Estudiantes", "data-pjax" => 0]);
                    },
                ],
            ],
        ],
    ])
    ?>
</div>
