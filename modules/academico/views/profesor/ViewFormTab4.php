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
        'id' => 'grid_instruccion_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Instruccion',
                'header' => Academico::t("profesor", "Instruction Level"),
                'value' => function($value){
                    if(isset($value['Instruccion']) && $value['Instruccion'] != "" )
                        return $value['Instruccion'];
                    return "";
                }
            ],
            [
                'attribute' => 'NombreInstitucion',
                'header' => Academico::t("profesor", "Institution"),
                'value' => function($value){
                    if(isset($value['NombreInstitucion']) && $value['NombreInstitucion'] != "" )
                        return $value['NombreInstitucion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Especializacion',
                'header' => Academico::t("profesor", "Career"),
                'value' => function($value){
                    if(isset($value['Especializacion']) && $value['Especializacion'] != "" )
                        return $value['Especializacion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Titulo',
                'header' => Academico::t("profesor", "Degree"),
                'value' => function($value){
                    if(isset($value['Titulo']) && $value['Titulo'] != "" )
                        return $value['Titulo'];
                    return "";
                }
            ], 
            [
                'attribute' => 'Registro',
                'header' => Academico::t("profesor", "Senescyt Registry"),
                'value' => function($value){
                    if(isset($value['Registro']) && $value['Registro'] != "" )
                        return $value['Registro'];
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