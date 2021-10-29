<?php
use app\widgets\PbSearchBox\PbSearchBox;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
 use kartik\date\DatePicker;
 use kartik\datetime\DateTimePicker;
use \yii\data\ArrayDataProvider;
use app\modules\academico\Module as academico;
academico::registerTranslations();
//$currentpaca = $arr_periodos[$modeldata[0]["croe_paca_id"]];
//var_dump($modalidades);

?>
 <?= Html::hiddenInput('txth_modalidades', $modalidades, ['id' => 'txth_modalidades']); ?>
<?=

  //  $count = $this->dataProvider->getCount();
// Modalidad Periodo Unidad Ejecucion Estado
// croe_mod_id croe_paca_id croe_uaca_id croe_fecha_ejecucion croe_exec
PbGridView::widget([
    'id' => 'Tbg_Calificaciones',
    'showExport' => false,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Periodo Academico"),
            'options' => ['width' => '160'],
            'value' => 'paca_nombre',
                    ],   
        [
                'attribute' => 'Unidad',
                'header' => Yii::t("Academico", "Unidad Academica"),
                'options' => ['width' => '160'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["croe_uaca_id"] == 1)
                        return 'Grado';                    
                    if ($model["croe_uaca_id"] == 2)                        
                        return 'Post Grado';
                    if ($model["croe_uaca_id"] == 3)                        
                        return 'Idiomas';
                },                
        ],
        [
                'attribute' => 'Unidad',
                'header' => Yii::t("Academico", "Modalidad"),
                'options' => ['width' => '160'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["croe_mod_id"] == 1)
                        return 'Online';                    
                    if ($model["croe_mod_id"] == 2)                        
                        return 'Presencial';
                    if ($model["croe_mod_id"] == 3)                        
                        return 'Semipresencial';
                     if ($model["croe_mod_id"] == 4)                        
                        return 'Distancia';
                },                
        ],
        [
                'attribute' => 'Ejecucion',
                'header' => Yii::t("Academico", "Estado"),
                'options' => ['width' => '160'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["croe_exec"] == 0)
                        return '<small class="label label-primary">&nbsp;&nbsp;&nbsp;Finalizado</small>';
                    if ($model["croe_exec"] == 1)                        
                        return '<small class="label label-primary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Activo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small>';
                     if ($model["croe_exec"] == 2)               
                        return '<small class="label label-danger">No Activado</small>';
                
                },                
        ],
        [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Fecha de Ejecución"),
                'options' => ['width' => '160','height' => '60'],
                'template' => '{activa}', //
                'buttons' => [
                    'activa' => function ($url, $model) {
                        if ($model["croe_exec"] == 2 ) {
                   
                   return DateTimePicker::widget([
                    'name' => 'datetime_10',
                     'value' => '',
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND, //TYPE_COMPONENT_PREPEND TYPE_INPUT
                    'options' => ["class" => "form-control PBvalidation",'placeholder' => 'elija fecha...','id' => 'F'.$model["croe_id"] ],
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => 'yyyy-M-dd hh:mm', 
                        'todayHighlight' => true,
                        'autoclose' => true,
    ]
]);
                        } else {

                            return $model["croe_fecha_ejecucion"];
                        }
                    },
                    
                ],
        ],
        [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'options' => ['width' => '160'],
                'template' => '{activa}', //
                'buttons' => [
                    'activa' => function ($url, $model) {
                        if ($model["croe_exec"] == 2 ) {
                            return "<a href='javascript:activateCron(".$model['croe_id'].")' class='glyphicon glyphicon-plus-sign' >" . academico::t("registro", "&nbsp;Grabar") . "</a>";

                            return Html::a('<span class="glyphicon glyphicon-plus"></span>', Url::to(['/academico/calificacionregistrodocente/index', 'sids' => base64_encode($model['sins_id']), 'adm' => base64_encode($model['adm_id'])]), ["data-toggle" => "tooltip", "title" => "Activar", "data-pjax" => 0]);
                        } else {
                            return '<span class="glyphicon glyphicon-ok"></span>';
                        }
                    },
                    
                ],
        ],
        
      
    ],
])
?>