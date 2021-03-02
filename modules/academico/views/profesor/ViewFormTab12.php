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
        'id' => 'grid_coordinacion_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Estudiante',
                'header' => Academico::t("profesor", "Student Name") ,
                'value' => function($value){
                    if(isset($value['Estudiante']) && $value['Estudiante'] != "" )
                        return $value['Estudiante'];
                    return "";
                }
            ],
            [
                'attribute' => 'Programa',
                'header' => Academico::t("profesor", "Thesis topic"),
                'value' => function($value){
                    if(isset($value['Programa']) && $value['Programa'] != "" )
                        return $value['Programa'];
                    return "";
                }
            ],
            [
                'attribute' => 'Academico',
                'header' => Academico::t("profesor", "Academic Program"),
                'value' => function($value){
                    if(isset($value['Academico']) && $value['Academico'] != "" )
                        return $value['Academico'];
                    return "";
                }
            ],
            [
                'attribute' => 'Institucion',
                'header' => Academico::t("profesor", "Institution"),
                'value' => function($value){
                    if(isset($value['Institucion']) && $value['Institucion'] != "" )
                        return $value['Institucion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Anio',
                'header' => Academico::t("profesor", "Year of Approval"),
                'value' => function($value){
                    if(isset($value['Anio']) && $value['Anio'] != "" )
                        return $value['Anio'];
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