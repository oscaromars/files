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
        'id' => 'grid_referencia_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombre',
                'header' => Academico::t("profesor", "Contact Name") ,
                'value' => function($value){
                    if(isset($value['Nombre']) && $value['Nombre'] != "" )
                        return $value['Nombre'];
                    return "";
                }
            ],
            [
                'attribute' => 'Cargo',
                'header' => Academico::t("profesor", "Position"),
                'value' => function($value){
                    if(isset($value['Cargo']) && $value['Cargo'] != "" )
                        return $value['Cargo'];
                    return "";
                }
            ],
            [
                'attribute' => 'Organizacion',
                'header' => Academico::t("profesor", "Company / Organization"),
                'value' => function($value){
                    if(isset($value['Organizacion']) && $value['Organizacion'] != "" )
                        return $value['Organizacion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Numero',
                'header' => Academico::t("profesor", "Contact Number"),
                'value' => function($value){
                    if(isset($value['Numero']) && $value['Numero'] != "" )
                        return $value['Numero'];
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