<?php

use app\modules\academico\Module as academico;
use kartik\grid\GridView;
use yii\helpers\Html;
use \yii\helpers\ArrayHelper;

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
			'paralelos' => $paralelos,
		]);
		?>
    </form>
</div>
<?php $data_from_desiredModel = ArrayHelper::map(\app\modules\academico\models\DistributivoAcademico::find()
		->orderBy('daca_id')->asArray()->all(), 'daca_id', 'mpp_id');?>
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
			'attribute' => 'Estudiante',
			'header' => academico::t("Academico", "Estudiante"),
			'value' => 'Estudiante',
		],
		[
			'attribute' => 'Cedula',
			'header' => academico::t("Academico", "Cédula"),
			'value' => 'Cedula',
		],
		[
			'attribute' => 'matricula',
			'header' => academico::t("Academico", "Matricula"),
			'value' => 'matricula',
		],
		[
            'attribute' => 'daes_id',
            'header' => Yii::t("formulario", "Daes Id"),
            'value' => 'daes_id',
            'headerOptions' => ['class' => 'hidden'],
            'filterOptions' => ['class' => 'hidden'],
            'contentOptions' => ['class' => 'hidden'],
        ],
		/*[
			'class' => 'kartik\grid\CheckboxColumn',
			'headerOptions' => ['class' => 'kartik-sheet-style'],
			'header' => academico::t("Academico", "Asignar"),
			'checkboxOptions' => function ($model, $key, $index, $column) {
				if ($model['daes_estado']) {
					return ['style' => ['display' => 'none']]; // OR ['disabled' => true]
					//return ['value' => $key];
				} else {
					return ['value' => $key];
				}
			},
		],*/
		[
            'attribute' => 'paralelos',
            'header' => Academico::t("Academico", "Paralelo"),
            'filterInputOptions' => [
                'class' => 'form-control',
                'prompt' => 'Select'
            ],
            'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
            'format' => 'raw',
            'value'  => function ($model) {
            	if ($model['daes_id']!=null && $model['daca_id'] != null) {
                	return Html::dropDownList('paralelos', empty($model['daes_id'])?0:$model['daca_id'] , ArrayHelper::map($model['paralelo_grid'], "id", "name"), ["class" => "form-control", "Id" => "cmb_paralelo_".$model['daes_id'] ]);
            	}else{
            		return Html::dropDownList('paralelos', empty($model['daes_id'])?0:$model['est_id'] /*$model['daes_id'] */, ArrayHelper::map($model['paralelo_grid'], "id", "name"), ["class" => "form-control", "Id" => "cmb_paralelo_".$model['est_id'] /*$model['daes_id'] */ ]);
            	}
            }
        ],

        [
            'attribute' => 'daca_id',
            'header' => Yii::t("formulario", "Daca Id"),
            'value' => 'daca_id',
            'headerOptions' => ['class' => 'hidden'],
            'filterOptions' => ['class' => 'hidden'],
            'contentOptions' => ['class' => 'hidden'],
        ],
         [
            'attribute' => 'est_id',
            'header' => Yii::t("formulario", "Est Id"),
            'value' => 'est_id',
            'headerOptions' => ['class' => 'hidden'],
            'filterOptions' => ['class' => 'hidden'],
            'contentOptions' => ['class' => 'hidden'],
        ],

		[
			'class' => 'yii\grid\ActionColumn',
			'header' => 'Acciones',
			'template' => '{update} {edit} {delete}',
			'headerOptions' => ['class' => 'text-center'],
			'contentOptions' => ['class' => 'text-center'],
			'buttons' => [
				'update' => function ($url, $model) {
					//if ($model['daes_estado']) {//JLC: 26 ABRIL 2022
					if ($model['daes_id']) { //JLC: 262 ABRIL 2022
						return Html::a('<span class="fa fa-pencil fa-fw"></span>', null, ['href' => 'javascript:cambiarparalelo(' . $model['daca_id'] . ',' . $model['daes_id'] . ');', "data-toggle" => "tooltip", "title" => academico::t("distributivoacademico", "Cambiar paralelo")]);
					} /*else {

					}*/
				},
				'edit' => function ($url, $model) {
                    if($model['daes_id'] != null && $model['daca_id'] != null) {
                        return Html::checkbox($model['daes_id'], true, ["value" => $model['daes_id'], "class" => "byregister", "disabled" => true,  "Id" => "cmb_check_estudiante_".$model['daes_id']    ]);
                    }else {
                    	return Html::checkbox($model['daes_id'], false, ["value" => $model['daes_id'], "class" => "byregister",   "Id" => "cmb_check_estudiante_".$model['est_id']    ]);
                    }
                },
                'delete' => function ($url, $model) {
                    if($model['daes_id'] != null && $model['daca_id'] != null) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteDaesId\',[\'' . $model['daes_id'] . '\' ] );', "data-toggle" => "tooltip", "title" => academico::t("Academico", 'Borrar asignación')]);
                    }
                },
			],
		],

	],
]);
?>
</div>