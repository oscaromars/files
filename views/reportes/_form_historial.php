<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */

$var = ArrayHelper::map(app\models\Persona::find()->where(['per_estado' => 1,'per_estado_logico' => 1])->all(), 'per_id',
                function ($model) {
                     return $model->per_id->per_pri_apellido . '-' . $model->per_id->per_seg_apellido . '-' . $model->per_id->per_pri_nombre;
                });

?>
<div class="historialacademico-search">

    <?php
    $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-4',
                        'offset' => 'col-sm-offset-4',
                        'wrapper' => 'col-sm-8',
                        'error' => '',
                        'hint' => '',
                        'button' => 'col-sm-4'
                    ],
                ],
                'action' => ['historialacademico'],
                'method' => 'get',
    ]);
    ?>
  

    <?=
    $form->field($model, 'est_id')->label('Estudiante:')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(app\models\Persona::find()->where(['per_estado_logico' => '1','per_estado' => '1'])->all(), 'per_id', 'per_pri_apellido','per_pri_nombre'),
        //'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Estudiante ...', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '295px',
        ],
    ]);
    ?>

    <div class="form-group">
        <div class="col-sm-offset-4">
<?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
               </div>   
    </div>

<?php ActiveForm::end(); ?>

</div>
