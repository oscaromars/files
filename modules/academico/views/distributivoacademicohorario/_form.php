<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\EstudioAcademico;

/* @var $this yii\web\View */
/* @var $model frontend\models\Notas */
/* @var $form yii\widgets\ActiveForm */
if ($model->daho_id == null) {//Ingresa un nuevo   
    $model->daho_estado = '1';
    $model->daho_estado_logico = '1';
    //$model->daho_usuario_ingreso = Yii::$app->session->get("PB_iduser");
    //  $model->daho_usuario_modifica = Yii::$app->session->get("PB_iduser");
    $model->daho_fecha_creacion = date("Y-m-d H:i:s");
    //$model->daho_fecha_modificacion = date("Y-m-d H:i:s");
} else {//Modifica
    // $model->daho_usuario_modifica = Yii::$app->session->get("PB_iduser");
    $model->daho_fecha_modificacion = date("Y-m-d H:i:s");
}
?>

<div class="estudioacademico-form">



    <?php
    $form = ActiveForm::begin(['layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-4',
                        'offset' => 'col-sm-offset-4',
                        'wrapper' => 'col-sm-8',
                        'error' => '',
                        'hint' => ''
                    ],
                ],
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'mod_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Modalidad::find()->all(), 'mod_id', 'mod_nombre'),
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => 'Seleccione Modalidad ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '295px',
                ],
            ]);
            ?>
            <?=
            $form->field($model, 'uaca_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(UnidadAcademica::find()->all(), 'uaca_id', 'uaca_nombre'),
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => 'Seleccione  Unidad Académica ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '295px',
                ],
            ]);
            ?>

        </div>
        <div class="col-md-6">

            <?=
            $form->field($model, 'eaca_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(EstudioAcademico::find()->all(), 'eaca_id', 'eaca_descripcion'),
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => 'Seleccione Estudio Académico ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '295px',
                ],
            ]);
            ?>

            <?= $form->field($model, 'daho_descripcion')->textInput(['style' => 'width:300px']) ?>
        </div>
        <div class="col-md-6">        
            <?=
            $form->field($model, 'daho_jornada')->widget(Select2::classname(), [
                'data' => [1 => "Matutino", 2 => "Nocturno", 3 => "Semipresencial", 4 => "Distancia"],
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => 'Seleccione Jornada ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '295px',
                ],
            ]);
            ?>
        </div>
        
        <div class="col-md-6">        
            <?=
            $form->field($model, 'daho_estado')->widget(Select2::classname(), [
                'data' => ['1' => "Activo", '0' => "Inactivo"],
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => 'Seleccione Estado ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '295px',
                ],
            ]);
            ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'daho_horario')->textInput(['style' => 'width:300px']) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'daho_total_horas')->textInput(['style' => 'width:300px']) ?>

        </div>
        <div class="form-group">
            <div class="col-sm-offset-4">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

      
        <?= $form->field($model, 'daho_estado_logico')->hiddenInput() ?>
        <?= $form->field($model, 'daho_fecha_creacion')->hiddenInput() ?>
        <?= $form->field($model, 'daho_fecha_modificacion')->hiddenInput() ?>

        <?php ActiveForm::end(); ?>



    </div>