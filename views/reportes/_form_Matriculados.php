<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */

$var = ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->where(['paca_estado' => 1,'paca_estado_logico' => 1,'paca_activo' => 'A'])->all(), 'paca_id',
                function ($model) {
                     return $model->baca->baca_nombre . '-' . $model->sem->saca_nombre . '-' . $model->sem->saca_anio;
                });
?>
<div class="matriculados-search">

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
                'action' => ['matriculados'],
                'method' => 'get',
    ]);
    ?>
  
    <?=
    $form->field($model, 'pla_id')->label('Planificacion:')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(app\modules\academico\models\Planificacion::find()->where(['pla_estado_logico' => '1','pla_estado' => '1'])->all(), 'pla_id', 'pla_periodo_academico'),
        // 'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Planificacion ...', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '295px',
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'mod_id')->label('Modalidad:')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(app\modules\academico\models\Modalidad::find()->where(['mod_estado_logico' => '1','mod_estado' => '1'])->all(), 'mod_id', 'mod_nombre'),
        // 'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Modalidad ...', 'multiple' => false],
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
