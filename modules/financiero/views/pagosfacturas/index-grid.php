<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Persona;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
 <div>
    <?=
    PbGridView::widget([
        'id' => 'TbG_Estcartera',
        'showExport' => false,
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
            //[
            //    'class' => 'yii\grid\ActionColumn',
            //    'header' => Yii::t("formulario", "Actions"),
            //    'template' => '{view} {delete}',
            //    'buttons' => [
            //        'view' => function ($url, $model) {
            //            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['usuarioeducativa/viewusuario', 'uedu_id' => base64_encode($model["uedu_id"])]), ["data-toggle" => "tooltip", "title" => "Ver Usuario", "data-pjax" => 0]);
            //        },
            //        'delete' => function ($url, $model) {
            //           return Html::a('<span class="glyphicon glyphicon-trash"></span>', "#", ['onclick' => "eliminarusuario(" . $model['uedu_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Usuario", "data-pjax" => 0]);
            //         }
            //    ],
            //],
        ],
    ])
    ?>
</div>
