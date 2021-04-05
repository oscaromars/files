<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use app\modules\academico\Module as academico;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$gridColumns=['saca_nombre',
            'saca_descripcion',
            'saca_anio'];
?>
<div class="semestreacademico-index">

    

    

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
  
<?php echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'columnSelectorOptions'=>[
                'label' => 'Columnas'
               
            ],
            'fontAwesome' => true,
            'dropdownOptions' => [
                'label' => 'Exportar Todo',
                'class' => 'btn btn-outline-secondary'
            ],
            'exportConfig' => [
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_CSV => false,
        ExportMenu::FORMAT_EXCEL => false,
    ],
        ])  . "<hr>\n".GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
     
            'saca_nombre',
            'saca_descripcion',
            'saca_anio',
            ['attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => 'Estado',
                'value' => function ($model, $key, $index, $widget) {
                    if($model->saca_estado == "1")
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
    ]); ?>


</div>
