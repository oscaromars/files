<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>

<div>
<?=
PbGridView::widget([
    "id" => 'tbl_materias',
    'dataProvider' => $model,
    //'filterModel' => $searchModel,
    //'pjax' => true,
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
            'header' => academico::t("Academico", "Modality"),
            'value' => 'mod_nombre',
            /*'value' => function ($model, $key, $index, $widget) {
                return $model->mod->mod_nombre;
            },
            group' => true,*/
        ],
        [
            'attribute' => 'asi_id',
            'header' => academico::t("Academico", "Asignatura"),
            'value' => 'asi_nombre',
            /*'value' => function ($model, $key, $index, $widget) {
                return $model->asig->asi_nombre;
            },
            'group' => true,*/
        ],
        [
            'attribute' => 'mpp_num_paralelo',
            'header' => academico::t("Academico", "Cantidad de Paralelos"),
            'value' => 'mpp_num_paralelo',
            /*'value' => function ($model, $key, $index, $widget) {
                return $model->mpp_num_paralelo;
            },
           'group' => true,*/
        ],
        [
            //'class' => 'kartik\grid\ActionColumn',
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Acciones',
            'template' => '{edit}{parallel}',

            'buttons' => [
                'edit' => function ($url, $model) {
                if(/*$model->mpp_num_paralelo*/  $model['mpp_num_paralelo'] <20){
                    return Html::a('<span class="' . Utilities::getIcon('edit') . '"></span>', Url::to(['update', 'id' => /*$model->mpp_id*/ $model['mpp_id'],' mod_id' => /*$model->mod_id*/   $model['mod_id'],'asi_id' => /*$model->asi_id*/ $model['asi_id'],'paca_id' => /*$model->paca_id*/  $model['paca_id'] ]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "Modificar Paralelo")]);
                }else{
                    return null;
                }
               },
                'parallel' => function ($url, $model) {
                    if($model->mpp_num_paralelo <20){
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>', Url::to(['updateschedule', 'id' => /*$model->mpp_id*/ $model['mpp_id'] ,'mod_id' => /*$model->mod_id*/ $model['mod_id'],'asi_id' => /*$model->asi_id*/ $model['asi_id'],'paca_id' => /*$model->paca_id*/  $model['paca_id'] ]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "Modificar Horario")]);
                    }else{
                        return null;
                    }
                   },
            ],
        ],
    ],
]);
?>
</div>