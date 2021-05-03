<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\Module as academico;
use kartik\mpdf\Pdf;
use kartik\grid\EditableColumn;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
?>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>
  <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <button type="button" class="btn btn-primary" onclick="javascript:save()"><?= Academico::t('profesor', 'Add') ?></button>
                </div>
            </div>
        </div>  
<?=

GridView::widget([
    "id"=>'tbl_materias',
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
         [
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
          'data' => [0 => 0,1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
          'formOptions' => ['action' => ['materiaparaleloperiodo/editableupdate']], // point to the new action
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
  ?>

