<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use \yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('new-search', [
            'distributivo_model' => $distributivo_model,
        ]);
        ?>
    </form>
</div>
<?php $data_from_desiredModel=ArrayHelper::map(\app\modules\academico\models\DistributivoAcademico::find()
->orderBy('daca_id')->asArray()->all(), 'daca_id', 'mmp_id');?>
<div>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        //  'filterModel' => $searchModel,
        'pjax' => true,
        'id' => 'grid',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'est_id',
                'header' => academico::t("Academico", "Estudiante"),
                'value' => function ($model, $key, $index, $widget) {
                    return strtoupper($model->est->persona->per_pri_nombre . ' ' . $model->est->persona->per_seg_nombre . ' ' . $model->est->persona->per_pri_apellido . ' ' . $model->est->persona->per_seg_apellido);
                },
            ],
            [
                'attribute' => 'est_id',
                'header' => academico::t("Academico", "Cédula"),
                'value' => function ($model, $key, $index, $widget) {
                    return strtoupper($model->est->persona->per_cedula);
                },
            ],
            [
                'attribute' => 'est_id',
                'header' => academico::t("Academico", "Matricula"),
                'value' => function ($model, $key, $index, $widget) {
                    return strtoupper($model->est->est_matricula);
                },
            ],
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'header' => academico::t("Academico", "Asignar"),
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    if ($model->daes_estado) {
                        return ['style' => ['display' => 'none']]; // OR ['disabled' => true]
//return ['value' => $key];
                    } else {
                        return ['value' => $key];
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '',
                'template' => '{update}',
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'update' => function ($url, $model) {
                        if ($model->daes_estado) {
                            //print_r($model->daes_id);die();
                            return Html::a('<span class="fa fa-pencil fa-fw"></span>', null, ['href' => 'javascript:cambiarparalelo(' . $model->daca_id .','.$model->daes_id.');', "data-toggle" => "tooltip", "title" => academico::t("distributivoacademico", "Cambiar paralelo")]);
                        } else {
                            
                        }
                    }
                ],
            ],
                        
        ],
    ]);
    ?>
</div>