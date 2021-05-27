<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\Module as academico;
use kartik\mpdf\Pdf;
use kartik\grid\EditableColumn;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use yii\helpers\Url;
?>

<?php echo $this->render('_searchindex', ['model' => $searchModel]); ?>

<?=

GridView::widget([
    "id" => 'tbl_materias',
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'pjax' => true,
    //  'autoXlFormat' => true,
    // 'striped' => false,

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
            'attribute' => 'mod_id',
            'header' => academico::t("Academico", "Modalidad"),
            'value' => function ($model, $key, $index, $widget) {
                return $model->mod->mod_nombre;
            },
            'group' => true,
        ],
        [
            'attribute' => 'asi_id',
            'header' => academico::t("Academico", "Asignatura"),
            'value' => function ($model, $key, $index, $widget) {
                return $model->asig->asi_nombre;
            },
            'group' => true,
        ],
        [
            'attribute' => 'mpp_num_paralelo',
            'header' => academico::t("Academico", "Paralelo"),
            'value' => function ($model, $key, $index, $widget) {
                return $model->mpp_num_paralelo;
            },
        // 'group' => true,
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Acciones',
            'template' => '{edit}',
            
            'buttons' => [
                'edit' => function ($url, $model) {
                if($model->mpp_num_paralelo<20){
                    return Html::a('<span class="' . Utilities::getIcon('edit') . '"></span>', Url::to(['update', 'id' => $model->mpp_id,'mod_id' => $model->mod_id,'asi_id' => $model->asi_id,'paca_id' => $model->paca_id]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "Modificar")]);
                }else{
                    return null;
                }
                    
                },
            ],
        ],
    ],
]);
?>

