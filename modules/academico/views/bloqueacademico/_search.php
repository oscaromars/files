<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="semestreacademico-search">

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
    <div class="form-group row">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            <?= $form->field($model, 'baca_nombre')->textInput(['style' => 'width:300px']) ?>

            <?= $form->field($model, 'baca_descripcion')->textInput(['style' => 'width:300px']) ?>

            <?= $form->field($model, 'baca_anio')->textInput(['style' => 'width:100px','maxlength' => 4]) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
