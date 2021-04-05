<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\select2\Select2;
use app\modules\academico\models\DistributivoHorarioParalelo;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\Module as academico;

academico::registerTranslations();

use kartik\export\ExportMenu;

$this->title = 'Academico Horarios';
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = ['daho_descripcion', 'daho_horario', 'daho_total_horas', 'daho_total_horas', 'daho_jornada', 'mod.mod_nombre', 'uaca.uaca_nombre'];
?>

<div class="distributivohorarioparalelo-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
    <?= Html::a('Nuevo Horario', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    echo GridView::widget([
        'id' => 'grid-id',
        'dataProvider' => $dataProvider,
        /// 'filterModel' => $searchModel,
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
        'hover' => true,
        'columns' => [
            ['attribute' => 'daho_descripcion',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->daho_descripcion == null) {
                        return '';
                    } else {
                        return $model->daho_descripcion;
                    }
                },
            ],
            ['attribute' => 'daho_horario',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->daho_horario == null) {
                        return '';
                    } else {
                        return $model->daho_horario;
                    }
                },
            ],
            ['attribute' => 'daho_total_horas',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->daho_total_horas == null) {
                        return '';
                    } else {
                        return $model->daho_total_horas;
                    }
                },
            ],
            ['attribute' => 'daho_jornada',
                'label' => 'Jornada',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->daho_jornada == 1) {
                        return "Matutino";
                    }
                    if ($model->daho_jornada == 2) {
                        return "Nocturno";
                    }
                    if ($model->daho_jornada == 3) {
                        return "Semipresencial";
                    }
                    if ($model->daho_jornada == 4) {
                        return "Distancia";
                    }
                },
            ],
            [
                'attribute' => 'mod_id',
                'vAlign' => 'middle',
                'label' => 'Modalidad',
                'value' => function ($model, $key, $index, $widget) {
                    return Html::a($model->mod->mod_nombre,
                            '#',
                            ['title' => $model->mod->mod_nombre, 'onclick' => '']);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Modalidad::find()->asArray()->all(), 'mod_id', 'mod_nombre'),
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Modalidad', 'multiple' => false], // allows multiple authors to be chosen
                'format' => 'raw'
            ],
            [
                'attribute' => 'uaca_id',
                'vAlign' => 'middle',
                'label' => 'Unidad Academica',
                'value' => function ($model, $key, $index, $widget) {
                    return Html::a($model->uaca->uaca_nombre,
                            '#',
                            ['title' => $model->uaca->uaca_nombre, 'onclick' => '']);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(UnidadAcademica::find()->asArray()->all(), 'uaca_id', 'uaca_nombre'),
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Unidad Academica', 'multiple' => false], // allows multiple authors to be chosen
                'format' => 'raw'
            ],
            [
                'attribute' => 'eaca_id',
                'vAlign' => 'middle',
                'label' => 'Estudio Academico',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->eaca->eaca_descripcion == null) {
                        return 'N/A';
                    } else {
                        return $model->eaca->eaca_descripcion;
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(EstudioAcademico::find()->asArray()->all(), 'eaca_id', 'eaca_descripcion'),
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Estudio Academico', 'multiple' => false], // allows multiple authors to be chosen
                'format' => 'raw'
            ],
            ['attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => 'Estado',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->daho_estado == "1")
                        return '<small class="label label-success">' . academico::t("asignatura", "Enabled") . '</small>';
                    else
                        return '<small class="label label-danger">' . academico::t("asignatura", "Disabled") . '</small>';
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