<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\Academico\Module as Academico;
Academico::registerTranslations();
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
<?=
    PbGridView::widget([
        'id' => 'grid_experiencia_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Institucion',
                'header' => Academico::t("profesor", "Company / Organization"),
                'value' => function($value){
                    if(isset($value['Institucion']) && $value['Institucion'] != "" )
                        return $value['Institucion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Desde',
                'header' => Academico::t("profesor", "From") ,
                'value' => function($value){
                    return date(Yii::$app->params["dateByDefault"], strtotime($value['Desde']));
                }
            ],
            [
                'attribute' => 'Hasta',
                'header' => Academico::t("profesor", "To"),
                'value' => function($value){
                    if(isset($value['Hasta']) && $value['Hasta'] != "" )
                        return date(Yii::$app->params["dateByDefault"], strtotime($value['Hasta']));
                    return "";
                }
            ],
            [
                'attribute' => 'Denominacion',
                'header' => Academico::t("profesor", "Denomination"),
                'value' => function($value){
                    if(isset($value['Denominacion']) && $value['Denominacion'] != "" )
                        return $value['Denominacion'];
                    return "";
                }
            ], 
            [
                'attribute' => 'Funciones',
                'header' => Academico::t("profesor", "Functions and Responsibilities"),
                'value' => function($value){
                    if(isset($value['Funciones']) && $value['Funciones'] != "" )
                        return $value['Funciones'];
                    return "";
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '',
                'buttons' => [
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['per_id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
        </div>
    </div>
</form>