<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gpr\models\Umbral;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

$template = '{edit}';
$buttons = [
    'edit' => function ($url, $model) {
        if($model['PeriodoCerrado'] == 0)
            return Html::a('<span class="'.Utilities::getIcon('edit').'"></span>', 'javascript:editResultado('.$model['id'].', "'.gpr::t('meta', $model['Periodo']).'", false)', ["data-toggle" => "tooltip", "title" => Yii::t("accion","Edit")]);
        return Html::a('<span class="'.Utilities::getIcon('edit').'"></span>', 'javascript:', ["data-toggle" => "tooltip", "title" => Yii::t("accion","Edit"), "style" => "color: #97a0b3;"]);
    },
];
if($isAdmin){
    $template .= ' {open}';
    $buttons['open'] = function ($url, $model) {
        return Html::a('<span class="fa fa-unlock-alt"></span>', null, ['href' => 'javascript:openPeriodoResultado('.$model['id'].');', "data-toggle" => "tooltip", "title" => gpr::t("meta","Open Period Goal")]);
    };
}
?>

<?=
    PbGridView::widget([
        'id' => 'grid_list_resultado',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Periodo',
                'header' => gpr::t("meta", "Period"),
                'value' => function($data){
                    return gpr::t('meta', $data['Periodo']);
                },
            ],
            [
                'attribute' => 'MetaPeriodo',
                'header' => gpr::t("meta", 'Period Goal'),
                'value' => function($data){
                    if(isset($data['MetaPeriodo']) && $data['MetaPeriodo'] != "")
                        return $data['MetaPeriodo'];
                    return "";
                },
            ],
            /*[
                'attribute' => 'Numerador',
                'header' => gpr::t("meta", 'Numerator'),
                'value' => 'Numerador',
            ],
            [
                'attribute' => 'Denominador',
                'header' => gpr::t("meta", 'Denominator'),
                'value' => 'Denominador',
            ],*/
            [
                'attribute' => 'Resultado',
                'header' => gpr::t("meta", 'Result'),
                'value' => function($data){
                    if(isset($data['Resultado']) && $data['Resultado'] != "")
                        return $data['Resultado'];
                    return "";
                },
            ],
            [
                'attribute' => 'AvancePeriodo',
                'header' => gpr::t("meta", 'Advance Period'),
                'value' => function($data){
                    if(isset($data['AvancePeriodo']) && $data['AvancePeriodo'] != "")
                        return $data['AvancePeriodo'] . "%";
                    return "";
                },
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("meta", "Goal Status"),
                'value' => function($data) use ($ind_fraccional){
                    if(isset($data["Resultado"]) && $data["Resultado"] != ""){
                        $parameter = $data["Resultado"];
                        if($ind_fraccional == 0){
                            $parameter = ($data["Resultado"]/$data["MetaPeriodo"])*100;
                        }
                        $arr_data = Umbral::getUmbralByParameter($parameter);
                        $per = round($parameter, 0);
                        $color = $arr_data['Color'];
                        if($per >= 100){
                            $color = "#3d754c";
                        }
                        return '<span class="badge" data-toggle="tooltip" title="'.$arr_data['Nombre'].'" style="background-color: '.$color.';">'.$per.'%</span>';
                    }else{
                        return '';
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => gpr::t("meta", 'Attached'),
                'template' => '{text}',
                'buttons' => [
                    'text' => function($url, $data){
                        if(isset($data['RutaAnexo']) && $data['RutaAnexo'] != "")
                            return Html::a('<span>' . basename($data['RutaAnexo']) . '</span>', 'javascript:downloadFile('.$data['id'].')', ["data-toggle" => "tooltip", "title" => gpr::t("meta","Download File")]);
                        return "";
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => gpr::t("meta", 'Attached File Name Description'),
                'template' => '{text}',
                'buttons' => [
                    'text' => function ($url, $model) {
                        if(strlen($model['Descripcion']) < 70)
                            return $model['Descripcion'];
                        return Html::a('<span>' . substr($model['Descripcion'], 0, 70) . '...</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['Descripcion']]);
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => gpr::t("meta", 'Comments'),
                'template' => '{text}',
                'buttons' => [
                    'text' => function ($url, $model) {
                        if(strlen($model['Comentario']) < 70)
                            return $model['Comentario'];
                        return Html::a('<span>' . substr($model['Comentario'], 0, 70) . '...</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['Comentario']]);
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '90'],
                'template' => $template,
                'buttons' => $buttons,
            ],
        ],
    ])
?>
