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
    'id' => 'edu_aulas',
    'showExport' => false,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'pajax' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
        [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Curso"),
            'options' => ['width' => '240'],
            'value' => 'name',
                    ],  
        [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Unidad"),
            'options' => ['width' => '80'],
            'value' => 'uaca_nombre',
                    ], 
        [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Periodo/Bloque"),
            'options' => ['width' => '80'],
            'value' => 'paca_id',
                    ], 
        [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Modalidad"),
            'options' => ['width' => '80'],
            'value' => 'mod_nombre',
                    ], 
        [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Profesor"),
            'options' => ['width' => '160'],
            'value' => 'per_pri_apellido',
                    ],  

 [
                'attribute' => 'bloque',
                'header' => Academico::t("Academico", "Bloque"),
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => 'Select'
                ],
                'format' => 'raw',
                'value'  => function ($model) {
                   /* if($model['uaca_id'] == 1) {
                        return Html::dropDownList('bloque', empty($model['bloque_campo'])?0:$model['bloque_campo'] , ArrayHelper::map($model['bloque'] , "Id", "Nombres"), ["class" => "form-control", "Id" => "cmb_bloque_".$model['mpmo_id'], "disabled" => false ]);
                    }elseif($model['uaca_id'] == 2) {
                        return Html::dropDownList('bloque', empty($model['bloque_campo'])?0:$model['bloque_campo'] , ArrayHelper::map($model['bloque'] , "Id", "Nombres"), ["class" => "form-control", "Id" => "cmb_bloque_".$model['mpmo_id'], "disabled" => false ]);
                    }else{
                        return Html::dropDownList('bloque', empty($model['bloque_campo'])?0:$model['bloque_campo'] , ArrayHelper::map($model['bloque'] , "Id", "Nombres"), ["class" => "form-control", "Id" => "cmb_bloque_".$model['mpmo_id'], "disabled" => false ]);
                    }             */       
                }
            ],
        
       [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'options' => ['width' => '80'],
                'headerOptions' => ['style' => 'text-align: center;color:#000000'],
                'contentOptions' => ['style' => 'text-align: center;'],
                'template' => '{activa}', //
                'buttons' => [
                    'activa' => function ($url, $model) {
                        if ($model["uaca_id"] == 1 ) {

                             return "<a href='javascript:activateCron(".$model['croe_id'].")' class='glyphicon glyphicon-plus-sign' ></a>";
                            return "<a href='javascript:activateCron(".$model['croe_id'].")' class='glyphicon glyphicon-plus-sign' >" . academico::t("registro", "&nbsp;Grabar") . "</a>";

                            return Html::a('<span class="glyphicon glyphicon-plus"></span>', Url::to(['/academico/calificacionregistrodocente/index', 'sids' => base64_encode($model['sins_id']), 'adm' => base64_encode($model['adm_id'])]), ["data-toggle" => "tooltip", "title" => "Activar", "data-pjax" => 0]);
                        } else {
                             if ($model["uaca_id"] == 2)
                            return '<span class="glyphicon glyphicon-saved"></span>'; 
                            return '<span class="glyphicon glyphicon-time"></span>';
                        }
                    },
                    
                ],
        ],
        
      
    ],
])
?>