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
        'id' => 'grid_publicacion_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'TipoProduccion',
                'header' => Academico::t("profesor", "Type Production") ,
                'value' => function($value){
                    if(isset($value['TipoProduccion']) && $value['TipoProduccion'] != "" )
                        return $value['TipoProduccion'];
                    return "";
                }
            ],
            [
                'attribute' => 'Titulo',
                'header' => Academico::t("profesor", "Title"),
                'value' => function($value){
                    if(isset($value['Titulo']) && $value['Titulo'] != "" )
                        return $value['Titulo'];
                    return "";
                }
            ],
            [
                'attribute' => 'Editorial',
                'header' => Academico::t("profesor", "Editorial"),
                'value' => function($value){
                    if(isset($value['Editorial']) && $value['Editorial'] != "" )
                        return $value['Editorial'];
                    return "";
                }
            ],
            [
                'attribute' => 'ISBN',
                'header' => 'ISBN/ISSN',
                'value' => function($value){
                    if(isset($value['ISBN']) && $value['ISBN'] != "" )
                        return $value['ISBN'];
                    return "";
                }
            ],
            [
                'attribute' => 'Autor',
                'header' => Academico::t("profesor", "Author"),
                'value' => function($value){
                    if(isset($value['Autor']) && $value['Autor'] != "" )
                        return $value['Autor'];
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