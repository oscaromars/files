<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\EstudioAcademico;

/* @var $this yii\web\View */
/* @var $model app\models\RolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="distributivoacademicohorario-search">

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
                    ],
                ],
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>
    <div class="row">      
        <div class="col-md-6">
            <?=
            $form->field($model, 'mod_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Modalidad::find()->all(), 'mod_id', 'mod_nombre'),
                'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Seleccione Modalidad ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '200px',
                ],
            ]);
            ?>
            <?=
            $form->field($model, 'uaca_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(UnidadAcademica::find()->all(), 'uaca_id', 'uaca_nombre'),
                'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Seleccione  Unidad Academica ...', 'multiple' => false],
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
                'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Seleccione Estudio Academico ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '200px',
                ],
            ]);
            ?>

            <?= $form->field($model, 'daho_descripcion')->textInput(['style' => 'width:300px']) ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'daho_horario')->textInput(['style' => 'width:300px']) ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'daho_jornada')->widget(Select2::classname(), [
                'data' => [1 => "Matutino", 2 => "Nocturno", 3 => "Semipresencial", 4 => "Distancia"],
                'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Seleccione Jornada ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '295px',
                ],
            ]);
            ?>


        </div>
    </div>
    <div class="form-group">

        <div class="col-sm-offset-4">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>