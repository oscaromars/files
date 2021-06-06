<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Abe_listado_create',
        //'showExport' => false,
        'fnExportEXCEL' => "exportExcelDistpagopos",
        'fnExportPDF' => "exportPdfDispagopos",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [

             

            
            [
                'attribute' => 'Cédula',
                'header' => academico::t("Academico", "Cedula"),
                'value' => 'per_cedula',
            ],
            [
                'attribute' => 'Nombre',
                'header' => academico::t("matriculacion", "Nombre"),
                'value' => 'per_pri_nombre',
            ],
            [
                'attribute' => 'Apellido',
                'header' => academico::t("matriculacion", "Apellido"),
                'value' => 'per_pri_apellido',
            ],
            /*[
                'attribute' => 'State',
                'header' => Yii::t("formulario", 'Status'),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["abe_id"] != '' || $model["abe_id"] != null )
                    return '<small class="label label-success">&nbsp;&nbsp;&nbsp;Activa&nbsp;&nbsp;&nbsp;</small>';                  
                    else
                    return '<small class="label label-warning">No Activa</small>'; 
                },
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Select"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $model) {
                        //return Html::checkbox("", false, ["value" => $model['est_id']]);  
                          return Html::a('<span class="glyphicon glyphicon glyphicon-file"></span>', Url::to(['/academico/matriculacion/indexfunda', 'per_id' =>  $model['per_id']]), ["data-toggle" => "tooltip", "title" => "Registrar Materias", "data-pjax" => 0]);
                    
                        
                    },                    
                ],
            ],
           /* [
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Select"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $model) {
                        //return Html::checkbox("", false, ["value" => $model['est_id']]);  
                          return Html::a('<span class="glyphicon glyphicon glyphicon-file"></span>', Url::to(['/academico/matriculacion/registro', 'per_id' =>  $model['per_id']]), ["data-toggle" => "tooltip", "title" => "Crear Planificacion", "data-pjax" => 0]);
                    
                        
                    },                    
                ],
            ],*/
        ],
        
    ])
    ?>
</div>


 
