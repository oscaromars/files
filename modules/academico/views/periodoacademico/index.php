<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use app\modules\academico\Module as academico;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Periodo Academico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estudioacademico-index"> 
<div style="float: right;">
        <p>
            <?= Html::a('Nuevo Periodo Academico ', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <h1 align="center" ><?= Html::encode($this->title) ?></h1>
    
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    
   <?php $gridColumns =['saca.saca_nombre',
                        'saca.saca_descripcion',
                        'saca.saca_anio',
                        'baca.baca_nombre',
                        'baca.baca_descripcion',
                        'baca.baca_anio'];
           
           ?>    
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
            'saca.saca_descripcion',
            'saca.saca_anio',
            'baca.baca_nombre',
            'baca.baca_descripcion',
            'baca.baca_anio',
            ['attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => 'Estado',
                'value' => function ($model, $key, $index, $widget) {
                    if($model->paca_estado == "1")
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

