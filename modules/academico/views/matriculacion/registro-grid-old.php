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
                        return "<span class='label label-success'>".Academico::t("matriculacion", "OK")."</span>";
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Select"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $planificacion) use ($registredSuject, $cancelStatus, $modelCancelItem) {
                        if(isset($registredSuject)){
                            if(array_search($planificacion['Code'], $registredSuject) === FALSE){
                                if($cancelStatus == '1'){
                                    return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true,]);
                                }
                                if($cancelStatus == '2'){
                                    $code = $planificacion['Code'];
                                    foreach($modelCancelItem as $key => $value){
                                        if($value['code'] == $code){
                                            return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true,]);
                                        }
                                    }
                                }
                                return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "class" => "byregister", ]);
                            }else{
                                if($cancelStatus == '2'){
                                    $code = $planificacion['Code'];
                                    foreach($modelCancelItem as $key => $value){
                                        if($value['code'] == $code){
                                            return Html::checkbox($planificacion['Code'], true, ["value" => $planificacion['Subject'], "disabled" => true,]);
                                        }
                                    }
                                }
                                return Html::checkbox($planificacion['Code'], true, ["value" => $planificacion['Subject'], "disabled" => true,]);
                            }
                        }else{
                            return Html::checkbox($planificacion['Code'], true, ["value" => $planificacion['Subject'], "disabled" => true,]);
                        }
                        /* return Html::checkbox("", false, ["value" => $planificacion['Subject'], "onchange" => "handleChange(this)"]); */
                    },                    
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Academico::t("matriculacion", "Remove Subject"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '100'],
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $planificacion) use ($registredSuject, $cancelStatus, $modelCancelItem) {
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
                            if(array_search($planificacion['Code'], $registredSuject) === FALSE){
                                return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject'], "disabled" => true, ]);
                            }else{
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