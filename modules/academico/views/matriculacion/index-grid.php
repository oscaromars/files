<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use yii\grid\CheckboxColumn;
academico::registerTranslations();
print_r($planificacion);
?>

<?=
    PbGridView::widget([
        'id' => 'grid_registro_list',
        'showExport' => false,       
        'dataProvider' => $planificacion,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],            
            [
                'attribute' => 'Subject',
                'header' => Academico::t("matriculacion", "Subject"),
                'value' => 'Subject',
            ],
            [
                'attribute' => 'Code',
                'header' => Academico::t("matriculacion", "Subject Code"),
                'value' => 'Code',
            ],          
            [
                'attribute' => 'Credit',
                'header' => Academico::t("matriculacion", "Credit"),
                'value' => 'Credit',
            ],
            [
                'attribute' => 'Cost',
                'header' => Academico::t("matriculacion", "Unit Cost"),
                'value' => function($data){
                    return '$' . (number_format(($data['Cost'] * $data['Credit']), 2, '.', ','));
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Select"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $planificacion) {
                        return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject']]);
                        /* return Html::checkbox("", false, ["value" => $planificacion['Subject'], "onchange" => "handleChange(this)"]); */
                    },                    
                ],
            ],
            /* [
                'header' => academico::t("Academico", "Seleccionar"),
                'class' => 'app\widgets\PbGridView\PbCheckboxColumn',                            
            ],  */
            /* [   
                'header' => academico::t("Academico", "Seleccionar"),
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($planificacion) {
                    if(!$planificacion->status){
                       return ['disabled' => true];
                    }else{
                       return [];
                    }
                 },
            ], */
        ]
    ])
?>