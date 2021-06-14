<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\EstudioAcademico;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */

$var = ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->where(['paca_estado' => 1,'paca_estado_logico' => 1,'paca_activo' => 'A'])->all(), 'paca_id',
                function ($model) {
                     return $model->sem->saca_nombre . '-' . $model->sem->saca_anio;
                });
?>
<div class="reportemallas-search">

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
                'action' => ['reportemallas'],
                'method' => 'get',
    ]);
    ?>
    
    
    <?=
    $form->field($model, 'uaca_id')->label('Unidad Academica:')->widget(Select2::classname(), [
        //'id' => 'cmb_unidad',
        //'name' => 'cmb_unidad',
        'data' => ArrayHelper::map(app\modules\academico\models\UnidadAcademica::find()->where(['uaca_estado_logico' => '1','uaca_estado' => '1'])->all(), 'uaca_id', 'uaca_nombre'),
        // 'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Unidad Academica ...', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '295px',
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'mod_id')->label('Modalidad:')->widget(Select2::classname(), [
        //'id' => 'cmb_modalidad',
        //'name' => 'cmb_modalidad',
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

    <?=
    $form->field($model, 'eaca_id')->label('Carrera:')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(app\modules\academico\models\EstudioAcademico::find()->where(['eaca_estado_logico' => '1','eaca_estado' => '1'])->all(), 'eaca_id', 'eaca_nombre'),
        // 'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Carrera ...', 'multiple' => false],
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
