<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use app\modules\academico\Module as academico;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$formatter = \Yii::$app->formatter;

?>
<div class="estudioacademico-index"> 
   
    
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
  
<?php echo   GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
         'pjax' => true,
        'panel' => [
            'type' => 'primary'
        ],
        'export' => [
            'showConfirmAlert' => false,
            'target' => GridView::TARGET_BLANK,
        ],
        'exportConfig' => [
            //GridView::CSV => ['label' => 'Save as CSV'],
            // GridView::HTML => [// html settings],

            GridView::EXCEL => [
                'label' => Yii::t('kvgrid', 'Excel'),
                //'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
                'iconOptions' => ['class' => 'text-success'],
                'showHeader' => true,
                'showPageSummary' => false,
                'showFooter' => true,
                'showCaption' => true,
                'filename' => Yii::t('kvgrid', 'grid-export'),
                'alertMsg' => Yii::t('kvgrid', 'The EXCEL export file will be generated for download.'),
                'options' => ['title' => Yii::t('kvgrid', 'Microsoft Excel 95+')],
                'mime' => 'application/vnd.ms-excel',
                'config' => [
                    'worksheet' => Yii::t('kvgrid', 'ExportWorksheet'),
                    'cssFile' => ''
                ]
            ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'saca.saca_nombre',
           
            'saca.saca_anio',
            'baca.baca_nombre',
            
            'baca.baca_anio',
            ['attribute' => 'paca_fecha_inicio',
                 'value' => function ($model, $key, $index, $widget) {
                    return Yii::$app->formatter->asDate($model->paca_fecha_inicio, 'yyyy-MM-dd');
                          },
                ],
                                  ['attribute' => 'paca_fecha_fin',
                 'value' => function ($model, $key, $index, $widget) {
                    return Yii::$app->formatter->asDate($model->paca_fecha_fin, 'yyyy-MM-dd');
                          },
                ],
          
            'paca_semanas_periodo',
            ['attribute' => 'paca_activo',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => 'Estado',
                'value' => function ($model, $key, $index, $widget) {
                    if($model->paca_activo == "A")
                        return '<small class="label label-success">'.academico::t("asignatura", "Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.academico::t("asignatura", "Disabled").'</small>';
                },
            ],
            
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'vAlign' => 'middle',
                'template' => '{update}',
              
            ],
            
        ],
        
        
    ]);
    ?>


</div>

