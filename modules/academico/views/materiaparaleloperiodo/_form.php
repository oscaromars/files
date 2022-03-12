<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;


//use kartik\mpdf\Pdf;
//use kartik\grid\GridView;
//use kartik\grid\DataColumn;
//use yii\bootstrap\ActiveForm;
use kartik\grid\EditableColumn;
use kartik\grid\Editable;
//use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use app\modules\academico\Module as academico;
?>
<div>
<?=
PbGridView::widget([
    "id"=>'tbl_materias_new',
    'dataProvider' => $model,
    //'dataProvider' => $dataProvider,
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
            'attribute' => 'asi_id',
            'header' => academico::t("Academico", "Id"),
            'value' => 'id',

        // 'group' => true,
        ],
        [
            'attribute' => 'asi_nombre',
            'header' => academico::t("Academico", "Asignatura"),
            'value' => 'name',
            /*'value' => function ($model, $key, $index, $widget) {
                return $model->asig->asi_nombre;
            },
            // 'group' => true,*/
        ],

        [
            'attribute' => 'orden_paralelo',
            'header' => Academico::t("Academico", "# Paralelo"),
            'filterInputOptions' => [
                'class' => 'form-control',
                'prompt' => 'Select'
            ],
            'format' => 'raw',
            'value'  => function ($model) {
                return Html::dropDownList('orden_paralelo', empty($model['num_paralelo'])?0:$model['num_paralelo'] , ArrayHelper::map($model['orden_paralelo'] , "Id", "Nombres"), ["class" => "form-control", "Id" => "cmb_num_paralelo_".$model['id'], "disabled" => false ]);
            }
        ],

        /*[
           'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'mpp_num_paralelo',
            'editableOptions' => [
                'header' => '#',
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 1, 'max' => 5]
                ]
            ],
            'hAlign' => 'right',
            'vAlign' => 'middle',
        ],*/
        /*[
          'class' => 'kartik\grid\EditableColumn',
          'attribute' => 'mpp_num_paralelo',
          'label' => '# Paralelo',
          'vAlign' => 'middle',
          'editableOptions' => function ($model, $key, $index) {
              $strValue    = $model->mpp_num_paralelo;
              $arrValue    = explode(',', $model->mpp_num_paralelo);
              $model->mpp_num_paralelo = explode(',', $model->mpp_num_paralelo);  // convert string into array (required for Select2 to display value as tags)
                return [
                    'asPopover' => false,
                    'attribute' => 'mpp_num_paralelo',
                    'header' => '# paralelo',
                    'inputType' => 'dropDownList',
                    'displayValue' => $strValue,    // display field before editing
                    'data' => [0 => 0,1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10,11 => 11,12 => 12,13 => 13,14 => 14,15 => 15,16 => 16,17 => 17,18 => 18,19 => 19,20 => 20],
                    'formOptions' => ['action' => ['materiaparaleloperiodo/editableupdate']], // point to the new action
                    ];
              }
        ],*/
    ],
]);
  ?>
</div>
