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
            'header' => academico::t("Academico", "Periodo/Bloque"),
            'options' => ['width' => '80'],
            'value' => 'paca_nombre',
                    ], 
           [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Unidad"),
            'options' => ['width' => '30'],
            'value' => 'uaca_nombre',
                    ], 
            [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Modalidad"),
            'options' => ['width' => '30'],
            'value' => 'mod_nombre',
                    ], 
        [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Curso"),
            'options' => ['width' => '300'],
            'value' => 'name',
                    ],  
     
        [
            'attribute' => 'Paca',
            'header' => academico::t("Academico", "Profesor"),
            'options' => ['width' => '120'],
            'value' => 'docente',
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

                         return "<a href='javascript:transferAula(".$model['id'].",".$model['ecal_id'].")' class='glyphicon glyphicon glyphicon-refresh' ></a>";

                        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDeletea(\'deleteItema\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);

                            return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['/academico/calificacionregistrodocente/transaulas', 'sids' => base64_encode($model['sins_id']), 'adm' => base64_encode($model['id'])]), ["data-toggle" => "tooltip", "title" => "Activar", "data-pjax" => 0]);
                    
                    },
                    
                ],
        ],
        
      
    ],
])
?>