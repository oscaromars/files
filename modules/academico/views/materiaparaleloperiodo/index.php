<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\Module as academico;
use kartik\mpdf\Pdf;
use kartik\grid\EditableColumn;
?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'pjax' => true,
    //  'autoXlFormat' => true,
    // 'striped' => false,
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
    ],
    /* 'exportConfig' => [
      GridView::PDF => [
      'label' => 'PDF',
      'filename' => 'Distributivo',
      'title' => 'Distributivo',
      'options' => ['title' => 'Distributivo Académico','author' => 'UTEG'],
      ],
      ],
      'export' => [
      'PDF' => [
      'options' => [
      'title' => 'UTEG',
      'subject' => 'UTEG',
      'author' => 'UTEG',
      'keywords' => 'NYCSPrep, preceptors, pdf'
      ]
      ],
      ], */
    'columns' => [
        // [ 'class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'asi_id',
            'header' => academico::t("Academico", "Asignatura"),
            'value' => function ($model, $key, $index, $widget) {
                    return $model->asig->asi_nombre;
                },
        // 'group' => true,
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'mpp_num_paralelo',
            'label' => '# Paralelo',
            'vAlign' => 'middle',
          
            'editableOptions' => function ($model, $key, $index) {
                return [
            'header' => '# paralelo',
            'inputType' => 'dropDownList',
            'data' => [0 => 0,1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
            'formOptions' => ['action' => ['materiaparaleloperiodo/editparalelo']], // point to the new action
                ];
            }
        ],
                [
            'attribute' => 'paca_id',
            'header' => academico::t("Academico", "Asignatura"),
            'value' => 'paca_id',
        // 'group' => true,
        ],
                
    ],
]);
