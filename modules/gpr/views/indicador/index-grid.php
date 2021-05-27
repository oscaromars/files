<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gpr\models\Umbral;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombre',
                'header' => gpr::t("indicador", "Indicator Name"),
                'value' => 'Nombre',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => gpr::t("objetivooperativo", "Operative Objective"),
                'template' => '{text}',
                'buttons' => [
                    'text' => function ($url, $model) {
                        if(strlen($model['ObjetivoOperativo']) < 70)
                            return $model['ObjetivoOperativo'];
                        return Html::a('<span>' . substr($model['ObjetivoOperativo'], 0, 70) . '...</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['ObjetivoOperativo']]);
                    },
                ],
            ],
            [
                'attribute' => 'FechaInicio',
                'header' => gpr::t("indicador", "Indicator Initial Date"),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"],strtotime($data['FechaInicio']));
                },
            ],
            [
                'attribute' => 'Frecuencia',
                'header' => gpr::t("indicador", "Indicator Frecuency"),
                'value' => 'Frecuencia',
            ],
            [
                'attribute' => 'Meta',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'header' => gpr::t("indicador", "Goal Entered by Period"),
                'value' => function($data){
                    if($data['Fraccional'] == 1){
                        return $data['Meta'] . "%";
                    }
                    return $data['Meta'];
                },
            ],
            [
                'attribute' => 'Resultado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'header' => gpr::t("indicador", "Last Result Entered"),
                'value' => function($data){
                    if($data['Fraccional'] == 1){
                        return $data['Resultado'] . "%";
                    }
                    return $data['Resultado'];
                },
            ],
            [
                'attribute' => 'Bracket',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'header' => gpr::t("indicador", "Last Period Entered"),
                'value' => function($data){
                    if(isset($data['Bracket']) && $data['Bracket'] != ""){
                        return gpr::t("meta", $data['Bracket']);
                    }
                    return "-";
                },
            ],
            [
                'attribute' => 'ResultadoPeriodo',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'header' => gpr::t("indicador", "Indicator Result"),
                'value' => function($data){
                    return ($data['SumMeta'] != 0)?(round((($data['SumResultado']/$data['SumMeta'])*100),0) . "%"):"0%";
                },
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("indicador", "Indicator Status"),
                'value' => function($data){
                    $parameter = ($data['SumMeta'] != 0)?(round((($data['SumResultado']/$data['SumMeta'])*100),0)):0;
                    $arr_data = Umbral::getUmbralByParameter($parameter);
                    $color = $arr_data['Color'];
                    if($parameter >= 100){
                        $color = "#3d754c";
                    }elseif($parameter <= 0){
                        $color = "#dd4b39";
                    }
                    return '<span class="badge" data-toggle="tooltip" title="'.$arr_data['Nombre'].'" style="background-color: '.$color.';">'.$parameter.'%</span>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {meta} {resultado} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/gpr/indicador/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                        //return  Html::a('Action', Url::to(['mceformulariotemp/solicitudpdf','ids' => 1],['class' => 'btn btn-default',"target" => "_blank"]));
                    },
                    'meta' => function ($url, $model) {
                        return Html::a('<span class="fa fa-cubes"></span>', Url::to(['/gpr/meta/index', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => gpr::t("meta","Goal")]);
                   },
                    'resultado' => function ($url, $model) {
                        return Html::a('<span class="fa fa-registered"></span>', Url::to(['/gpr/resultado/index', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => gpr::t("meta","Result")]);
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
