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
$cancelStatus = 0;
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
<?= PbGridView::widget([
    'id' => 'grid_registry_grid',
    'showExport' => false,
    //'fnExportEXCEL' => "exportExcel",
    //'fnExportPDF' => "exportPdf",
    'dataProvider' => $materiasxEstudiante,
    'summary' => '',
    'pajax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'roi_materia_cod',
            'header' => academico::t("matriculacion", 'Subject Code'),
            'value' => 'roi_materia_cod',            
            //'value' => 'Code',            
        ],
        [
            'attribute' => 'roi_materia_nombre',
            'header' => academico::t("matriculacion", "Subject"),
            'value' => 'roi_materia_nombre',            
            //'value' => 'Subject',   
        ],
        [
            'attribute' => 'roi_creditos',
            'header' => academico::t("registro", 'Credits'),
            'value' => 'roi_creditos',            
            //'value' => 'Credit',
        ],
        [
            'attribute' => 'roi_costo',
            'header' => academico::t("registro", 'Cost'),
            'value' => function($data){
                return "$".(number_format($data['roi_costo'], 2, '.', ','));                
                //return "$".(number_format($data['Cost'], 2, '.', ','));
            },
        ],
        [
            'attribute' => 'Status',
            'header' => Academico::t("matriculacion", "Status"),
            'format' => 'html',
            'value' => function($data) use ($cancelStatus, $modelCancelItem){
                if($cancelStatus == '1' || $cancelStatus == '2'){
                    $code = $data['roi_materia_cod'];
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
    ],
]);
?>