<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$var = ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->where(['paca_estado' => 1, 'paca_estado_logico' => 1, 'paca_activo' => 'A'])->all(), 'paca_id',
                function ($model) {
                    return $model->baca->baca_nombre . '-' . $model->sem->saca_nombre . '-' . $model->sem->saca_anio;
                    // return strtoupper($model->per->per_pri_apellido . ' ' . $model->per->per_seg_apellido . ' ' . $model->per->per_pri_nombre . ' ' . $model->per->per_seg_nombre);
                });
?>
<div class="usuario-search">

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
                'action' => ['reportematrizdistributivo'],
                'method' => 'get',
    ]);
    ?>
    <?=
    $form->field($model, 'paca_id')->label('Período:')->widget(Select2::classname(), [
        //  'data' => ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->all(), 'paca_id', 'sem.saca_nombre'),
        'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Distributivo ...', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '295px',
        ],
    ]);
    ?>

    

    <?=
    $form->field($model, 'pro_id')->label('Dedicación:')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(app\modules\academico\models\DedicacionDocente::find()->all(), 'ddoc_id', 'ddoc_nombre'),
        // 'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Dedicación ...', 'multiple' => false],
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


