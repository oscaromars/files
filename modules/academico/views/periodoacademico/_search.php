<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\academico\models\BloqueAcademico;
use app\modules\academico\models\SemestreAcademico;

/* @var $this yii\web\View */
/* @var $model app\models\RolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rol-search">

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

    <?=
    $form->field($model, 'saca_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(SemestreAcademico::find()->orderBy('saca_anio')->all(), 'saca_id', 'saca_nombre', 'saca_anio'),
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione  Semestre Academico ...', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '295px',
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'baca_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(BloqueAcademico::find()->orderBy('baca_anio')->all(), 'baca_id', 'baca_descripcion', 'baca_anio'),
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Bloque Academico ...', 'multiple' => false],
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