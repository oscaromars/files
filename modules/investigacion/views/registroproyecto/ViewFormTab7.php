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
        'id' => 'grid_idioma_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Languages',
                'header' => Academico::t("profesor", "Languages"),
                'value' => function($value){
                    if(isset($value['Languages']) && $value['Languages'] != "" )
                        return $value['Languages'];
                    return "";
                }
            ],
            [
                'attribute' => 'NivelEscrito',
                'header' => Academico::t("profesor", "Written Level"),
                'value' => function($value){
                    if(isset($value['NivelEscrito']) && $value['NivelEscrito'] != "" )
                        return $value['NivelEscrito'];
                    return "";
                }
            ],
            [
                'attribute' => 'NivelOral',
                'header' => Academico::t("profesor", "Oral Level"),
                'value' => function($value){
                    if(isset($value['NivelOral']) && $value['NivelOral'] != "" )
                        return $value['NivelOral'];
                    return "";
                }
            ],
            [
                'attribute' => 'Certificado',
                'header' => Academico::t("profesor", "Certificate of Sufficiency"),
                'value' => function($value){
                    if(isset($value['Certificado']) && $value['Certificado'] != "" )
                        return $value['Certificado'];
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