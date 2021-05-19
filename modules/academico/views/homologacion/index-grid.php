<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
//academico::registerTranslations();
?>
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Distributivo_Aca',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [
            [
                'attribute' => 'Periodo',
                'header' => Yii::t("homologacion", "Period"),
                'value' => 'Periodo',
            ],
            [
                'attribute' => 'institucion',
                'header' => Yii::t("homologacion", "Institution"),
                'value' => 'Cedula',
            ],       
            [
                'attribute' => 'previous_net',
                'header' => Yii::t("homologacion", "Previous Net Subject"),
                'value' => 'Nombres',
            ],  
            [
                'attribute' => 'new_net',
                'header' => Yii::t("homologacion", "New Net Subject"),
                'value' => 'estadoRevision',
            ],    
            [
                'attribute' => 'final_note',
                'header' => Yii::t("homologacion", "Final note"),
                'value' => 'estadoRevision',
            ],  
            [
                'attribute' => 'result',
                'header' => Yii::t("homologacion", "Result"),
                'value' => 'estadoRevision',
            ],  
            [
                'attribute' => 'comments',
                'header' => Yii::t("homologacion", "Comments"),
                'value' => 'estadoRevision',
            ], 
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '',
                'template' => '{view}{delete}{Approbe}{Download}',
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['distributivoacademico/view', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                        if ($model['estado'] == 1 or $model['estado'] == 3 ) {
                            return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['Id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                        } else {
                          return "<span class = 'glyphicon glyphicon-remove' data-toggle = 'tooltip' title ='Eliminar'  data-pjax = 0></span>";  
                        }
                        
                    },
                    'Approbe' => function ($url, $model){
                        if ($model['estado'] == 1 or $model['estado'] == 3 ) {
                            return Html::a('<span class="'.Utilities::getIcon('edit').'"></span>', Url::to(['distributivocabecera/review', 'id' => $model['Id']]), ["data-toggle" => "tooltip", "title" => Yii::t("formulario","Revisar Distributivo")]);
                        } else {
                          return "<span class = 'glyphicon glyphicon-ok' data-toggle = 'tooltip' title ='Revisar Distributivo'  data-pjax = 0></span>";  
                        }
                    },
                    'Download' => function ($url, $model){
                        return Html::a('<span class="'.Utilities::getIcon('download').'"></span>', Url::to(['/academico/distributivocabecera/generarmateriacarga', 'ids' => base64_encode($model['Id'])]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","Download"),"data-pjax" => "0"]);
                    }
                ],               
            ],                                
        ],
    ])
    ?>
</div>