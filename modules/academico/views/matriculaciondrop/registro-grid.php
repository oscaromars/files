<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\models\CancelacionRegistroOnline;
use app\modules\academico\models\CancelacionRegistroOnlineItem;
use app\modules\academico\Module as academico;
use yii\grid\CheckboxColumn;
academico::registerTranslations();
//print_r($HowelSuject);
//print_r(count($registredSuject));
$numcan= count($registredSuject);
 if($numcan < 3)  { echo "<b style ='color:red'>Usted debe mantener un minimo de dos materias </b>"; }

$modelCancelItem = array();

$modelCancelRon = CancelacionRegistroOnline::findOne(['ron_id' => $ron_id, 'cron_estado' => '1', 'cron_estado_logico' => '1',]);
if($modelCancelRon){
    $cancelStatus = $modelCancelRon->cron_estado_cancelacion;
    $modelCancelItem = CancelacionRegistroOnlineItem::find()
    ->select(['r.roi_materia_cod as code'])
    ->join('INNER JOIN', 'registro_online_item as r', 'r.roi_id = cancelacion_registro_online_item.roi_id')
    ->where(['cron_id' => $modelCancelRon->cron_id, 'croi_estado' => '1', 'croi_estado_logico' => '1',])
    ->asArray()
    ->all();
}

?>

<?=
    PbGridView::widget([
        'id' => 'grid_registro_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        /* 'dataProvider' => $model, */
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
                            return '<span title="S??b :: 07:15-09:15">'.$model['Hour'].'</span>';
                        else
                            return '<span title="">'.$model['Hour'].'</span>';
                    }
                    else if ($model['modalidad']=='3'){
                        if ($model["Hour"] == 'H1')
                            return '<span title="S??b :: 07:15-10:15">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H2')
                            return '<span title="S??b :: 10:30-13:30">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H3')
                            return '<span title="S??b :: 14:30-17:30">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H4')
                            return '<span title="">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H5')
                            return '<span title="">'.$model['Hour'].'</span>';
                        else
                            return '<span title="">'.$model['Hour'].'</span>';
                    }
                    else if ($model['modalidad']=='4'){
                        if ($model["Hour"] == 'H1')
                            return '<span title="S??b :: 08:15-10:15">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H2')
                            return '<span title="S??b :: 10:30-12:30">'.$model['Hour'].'</span>';
                        else if ($model["Hour"] == 'H3')
                            return '<span title="S??b :: 13:30-15:30">'.$model['Hour'].'</span>';
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
                'attribute' => 'Status',
                'header' => Academico::t("matriculacion", "Status"),
                'format' => 'html',
                'value' => function($data) use ($cancelStatus, $modelCancelItem){
                    if($cancelStatus == '1' || $cancelStatus == '2'){

                        
                        $code = $data['Code'];
                        foreach($modelCancelItem as $key => $value){
                            if($value['code'] == $code){
                                if($cancelStatus == 2)
                                    return "<span class='label label-success'>".Academico::t('matriculacion', 'Cancelled')."</span>";
                                return "<span class='label label-warning'>".Academico::t('matriculacion', 'Cancellation in process')."</span>";
                            }
                        }
                        return "<span class='label label-success'>".Academico::t("matriculacion", "OK")."</span>";
                    }else{
                        return "<span class='label label-success'>".Academico::t("matriculacion", "periodo de registro")."</span>";
                    }
                },
            ],
           
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Remove Subject"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '100'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $planificacion) use ($registredSuject, $cancelStatus, $modelCancelItem,$numcan) {
                        /*if($cancelStatus){
                            return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true,]);
                        }*/
                        if(isset($registredSuject)){
                            if($cancelStatus == '2'){
                                $code = $planificacion['Code'];
                                foreach($modelCancelItem as $key => $value){
                                    if($value['code'] == $code){
                                        return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true,]);
                                    }
                                }
                            }
                            
                            if($numcan < 3)  {
                            return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true,"class" => "chequeado","id"=>"chequeado"]);
                            }
                             return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "class" => "byremove",]);
                             
                             
                            if(array_search($planificacion['Code'], $registredSuject) === FALSE){
                              //  return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true, ]);
                              return "<span class='label label-warning'>".Academico::t("matriculacion", "removida")."</span>";
                            }else{

                                if($cancelStatus == 1 || $anulling=True)
                                return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true,]);
                                return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "class" => "byremove",]);
                            }
                        }else{
                            return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true,]);
                        }
                        /* return Html::checkbox("", false, ["value" => $planificacion['Subject'], "onchange" => "handleChange(this)"]); */
                    },                    
                ],                

            ],

        ]
    ])
?>