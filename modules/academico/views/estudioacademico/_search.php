<?php

use app\modules\academico\models\TipoEstudioAcademico;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="estudioacademico-search">

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
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'eaca_nombre')->textInput(['style' => 'width:300px']) ?>

            <?= $form->field($model, 'eaca_descripcion')->textInput(['style' => 'width:300px']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'eaca_alias')->textInput(['style' => 'width:300px']) ?>

            <?=
            $form->field($model, 'teac_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(TipoEstudioAcademico::find()->all(), 'teac_id', 'teac_nombre'),
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => 'Seleccione Tipo de Estudio Academico ...', 'multiple' => false],
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
