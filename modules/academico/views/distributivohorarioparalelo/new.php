<?php

use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
use yii\data\ArrayDataProvider;

use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\select2\Select2;
use app\modules\academico\models\DistributivoHorarioParalelo;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\EstudioAcademico;
?>

<div class="distributivohorarioparalelo-index">
    <?=
    GridView::widget([
        'id' => 'grid-id',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'export' => false,
        'hover' => true,
        'panel' => ['type' => 'primary', 'heading' => 'Seleccione un Horario para el Paralelo'],
        'columns' => [
            'daho_descripcion',
            /*[
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                // uncomment below and comment detail if you need to render via ajax
                // 'detailUrl' => Url::to(['/site/book-details']),
                'detail' => function ($model, $key, $index, $column) {
                    if (!empty($model->dhp)) {
                        $tds = ""; //Inicmaos variable tds
                        foreach ($model->dhp as $i => $v) {  //Iteramos tu objeto
                            $tds .= "<td>" . $v->dhpa_paralelo . "</td>"; // Extraemos solo el valor concatenandolo en la variable $tds.
                        }
                    }
                   // Imprimimos el resultado final

                    return  "<tr>" . $tds . "</tr>";
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true
            ],*/
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
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'vAlign' => 'middle',
                'template' => '{copy} {view}',
                'buttons' => [
                    'copy' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-ok-sign"></span>', ['create', 'daho_id' => $model->daho_id, 'unidad' => $model->uaca->uaca_id], ['title' => 'Seleccionar Horario']);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-th"></span>', ['view', 'id' => $model->daho_id, 'unidad' => $model->uaca->uaca_id], ['title' => 'Ver paralelos']);
                    },
                ],
            ],
        ],
    ]);
    ?>


    
</div>