<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use yii\grid\CheckboxColumn;
academico::registerTranslations();
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
                'attribute' => 'Block',
                'header' => Academico::t("matriculacion", "Block"),
                'value' => 'Block',
            ],
            /*[
                'attribute' => 'Hour',
                'header' => Academico::t("matriculacion", "Hour"),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                
                'value' => function ($model) {

                    if ($model['modalidad']=='1'){
                        if ($model["Hour"] == 'H1')
                            return '<span title="L-M-W :: 19:00-20:00">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H2')
                            return '<span title="L-M-W :: 20:00-21:00">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H3')
                            return '<span title="L-M-W :: 21:00-22:00">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H4')
                            return '<span title="L-M-W :: 19:00-20:30">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H5')
                            return '<span title="L-M-W :: 20:00-21:30">'.$model['Hour'].'</span>';
                        else
                            return '<span title="'.$model['Hour'].'">'.$model['Hour'].'</span>';
                    } else if ($model['modalidad']=='2'){
                        if ($model["Hour"] == 'H1')
                            return '<span title="L-M-J :: 18:20-20:20">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H2')
                            return '<span title="L-M-W :: 20:20-22:20">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H3')
                            return '<span title="Mie - Vie :: 18:20-21:20">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H4')
                            return '<span title="Vier :: 18:20-21:20">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H5')
                            return '<span title="Sáb :: 07:15-09:15">'.$model['Hour'].'</span>';
                        else
                            return '<span title="">'.$model['Hour'].'</span>';
                    }
                    else if ($model['modalidad']=='3'){
                        if ($model["Hour"] == 'H1')
                            return '<span title="Sáb :: 07:15-10:15">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H2')
                            return '<span title="Sáb :: 10:30-13:30">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H3')
                            return '<span title="Sáb :: 14:30-17:30">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H4')
                            return '<span title="">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H5')
                            return '<span title="">'.$model['Hour'].'</span>';
                        else
                            return '<span title="">'.$model['Hour'].'</span>';
                    }
                    else if ($model['modalidad']=='4'){
                        if ($model["Hour"] == 'H1')
                            return '<span title="Sáb :: 08:15-10:15">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H2')
                            return '<span title="Sáb :: 10:30-12:30">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H3')
                            return '<span title="Sáb :: 13:30-15:30">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H4')
                            return '<span title="">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H5')
                            return '<span title="">'.$model['Hour'].'</span>';
                        else
                            return '<span title="">'.$model['Hour'].'</span>';
                    }

                },

            ],*/
            [
                'attribute' => 'Credit',
                'header' => Academico::t("matriculacion", "Credit"),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'value' => 'Credit',
            ],
            /*[
                'attribute' => 'Cost',
                'header' => Academico::t("matriculacion", "Unit Cost"),
                'value' => function($data){
                    return '$' . number_format($data['Cost'],2 );
                },
            ],*/
            [
                'attribute' => 'CostSubject',
                'header' => Academico::t("matriculacion", "Cost Subject"),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'value' => function($data){
                    return '$' . number_format( (empty($data['Cost'])?0:$data['Cost']) /* * (empty($data['Credit'])?0:$data['Credit'])*/,2 );
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