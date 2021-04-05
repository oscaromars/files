<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>
<?=
PbGridView::widget([
      'id' => 'grid_dhp_list',
    'showExport' => true,
    'dataProvider' => $model,
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'dhpa_id',
            'header' => Academico::t("formulario", "Código"),
            'value' => 'dhpa_id',
        ],
        [
            'attribute' => 'Nombre',
            'header' => Academico::t("formulario", "Nombre"),
            'value' => 'Nombre',
        ],
        [
            'attribute' => 'Horario',
            'header' => Academico::t("formulario", "Horario"),
            'value' => 'Horario',
        ],
            [
            'attribute' => 'dhpa_estado',
            'header' => Academico::t("formulario", "dhpa_estado"),
            'value' => 'Estado',
        ],      
        [
            'class' => 'yii\grid\ActionColumn',
            //'header' => 'Action',
            'contentOptions' => ['style' => 'text-align: center;'],
            'headerOptions' => ['width' => '90'],
            'template' => '{update}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="' . Utilities::getIcon('edit') . '"></span>', Url::to(['update', 'id' => $model['dhpa_id'], 'uaca_id'=>$model['uaca_id'], 'dhpa_grupo' => $model['dhpa_grupo'], 'daho_id' => $model['daho_id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "Ver")]);
                },
                

                
       
            ],
        ],
       ],
    
    ])
?>

