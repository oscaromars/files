<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\CancelacionRegistroOnline;
use app\modules\academico\Module as academico;
use yii\grid\CheckboxColumn;
academico::registerTranslations();

/*
$modelCancelItem = array();

$modelCancelRon = CancelacionRegistroOnline::findOne(['ron_id' => $ron_id, 'cron_estado' => '1', 'cron_estado_logico' => '1',]);
if($modelCancelRon){
    $cancelStatus = $modelCancelRon->cron_estado_cancelacion;
    $modelCancelItem = CancelacionRegistroOnlineItem::find()
    ->select(['r.roi_materia_cod as code'])
    ->join('INNER JOIN', 'registro_online_item as r', 'r.roi_id = cancelacion_registro_online_item.roi_id')
    ->where(['cron_id' => $modelCancelRon['cron_id'], 'croi_estado' => '1', 'croi_estado_logico' => '1',])
    ->asArray()
    ->all();
}
*/


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
            [
                'attribute' => 'Hour',
                'header' => Academico::t("matriculacion", "Hour"),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => function ($model) {
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
                        //return Html::a('<span>' . $model['Hour']. '</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['Hour']]);
                },

            ],
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
                    return '$' . number_format($data['Cost']*$data['Credit'],2 );
                },
            ],
            [
                'attribute' => 'Status',
                'header' => Academico::t("matriculacion", "Status"),
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
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
                        return "<span class='label label-success'>".Academico::t("matriculacion", "Periodo de Registro")."</span>";
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