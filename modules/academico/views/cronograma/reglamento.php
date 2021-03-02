<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
//use kartik\tabs\TabsX;
use app\modules\academico\Module as academico;
academico::registerTranslations();
//print_r($model);
?>
<!-- <input type="hidden" id="frm_per_id" value="<?= $persona_model->per_id ?>">
<input type="hidden" id="frm_pro_id" value="<?= $pro_id ?>">-->

<?=

PbGridView::widget([
    'id' => 'grid_reglemento',
    'showExport' => false,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        /*[
            'attribute' => 'id',
            'header' => Academico::t("profesor", "Name"),
            'value' => 'reg_id',
        ],*/
        [
            'attribute' => 'descripcion',
            'header' => Academico::t("profesor", "Surname"),
            'value' => 'reg_descripcion',
        ],        
        [
            'attribute' => 'fecha',
            'header' => Academico::t("profesor", "Surname"),
            'value' => 'reg_fecha_creacion',
        ], 
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Acción',
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '90'],
            'template' => '{download} {pdf}',
            'buttons' => [              
                /*'download' => function ($url, $model) {
                    //if ($model['perfil'] == 0) {
                        //if($model['Cv'] != "")
                        //return Html::a('<span class="'.Utilities::getIcon('download').'"></span>', 'javascript:', ["data-toggle" => "tooltip", "title" => Yii::t("accion","Download"), 'data-href' => Url::to(['profesor/download', 'route' => $model['Cv'], 'type' => 'down']), 'onclick' => 'downloadPdf(this)']);
                        return Html::a('<span class="glyphicon glyphicon-download"></span>', Url::to(['/academico/profesor/curriculumpdf', 'ids' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => "Descargar Curriculum", "data-pjax" => "0"]);
                    //}
                },*/
                'pdf' => function ($url, $model) {  
                        return Html::a('<span class="' . Utilities::getIcon('info') . '"></span>', 'javascript:', ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View Document"), 'data-href' => Url::to(['cronograma/download', 'route' => $model['reg_archivo'], 'type' => 'view']), 'onclick' => 'viewPdf(this)']);
                },
            ],
        ],
    ],
])
?>