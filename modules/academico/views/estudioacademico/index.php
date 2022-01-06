<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\Module as academico;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$gridColumns =['eaca_nombre','teac.teac_nombre','eaca_descripcion','eaca_alias_resumen'];
?>


<div class="estudioacademico-index">


    <div style="float: right;">


    </div>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
<?php echo
    GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,

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
             ['attribute' =>'eaca_codigo',
                 'header'=>'Código',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->eaca_codigo == null) {
                        return 'N/A';
                    } else {
                        return $model->eaca_codigo;
                    }
                },
                ],
            'eaca_nombre',
            'teac.teac_nombre',
            'eaca_descripcion',
            ['attribute' =>'eaca_alias',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->eaca_alias == null) {
                        return 'N/A';
                    } else {
                        return $model->eaca_alias;
                    }
                },
                ],
            ['attribute' => 'eaca_alias_resumen',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->eaca_alias_resumen == null) {
                        return 'N/A';
                    } else {
                        return $model->eaca_alias_resumen;
                    }
                },
            ],
             ['attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => 'Estado',
                'value' => function ($model, $key, $index, $widget) {
                    if($model->eaca_estado == "1")
                        return '<small class="label label-success">'.academico::t("asignatura", "Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.academico::t("asignatura", "Disabled").'</small>';
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {create} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        $mod_est = new EstudioAcademico();
                        $result = $mod_est->consultarEstudioAcademicoEnUso($model->eaca_id);
                        if(empty($result) && $model->eaca_estado == '1'){
                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', null, ['href' =>  "javascript:confirmDelete('deleteItem', [ '" . $model->eaca_id . "' ]);", "data-toggle" => "tooltip", "title" => Yii::t("formulario", "Delete")]);
                        }
                        else{
                            return "<span class = 'glyphicon glyphicon-remove' data-toggle = 'tooltip' title =" . Yii::t("accion","Delete") . " data-pjax = 0></span>";
                        }
                    },
                ],
            ],
        ],
    ]);
    ?>


</div>

