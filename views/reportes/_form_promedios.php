<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */

$var = ArrayHelper::map(app\modules\academico\models\Estudiante::find()->where(['est_estado' => 1,'est_estado_logico' => 1])->all(), 'per_id',
                function ($model) {
                     return $model->per_id->per_pri_nombre . '-' . $model->per_id->per_pri_apellido;
                });

?>
<div class="reportepromedios-search">

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
                'action' => ['reportepromedios'],
                'method' => 'get',
    ]);
    ?>

    <div class="col-md-6">
        <?=
        //$mod_estudiante = new EstudianteCarreraProgramaSearch();
        //$estudiante = $mod_estudiante->getEstudiantesporpersona();
        $form->field($model, 'est_id')->label('Estudiante:')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(app\modules\academico\models\Estudiante::find()->where(['est_estado_logico' => '1','est_estado' => '1'])->all(), 'est_id','per_pri_apellido', 'per_seg_apellido'),
            //'data' => $var,
            'size' => Select2::MEDIUM,
            'options' => ['placeholder' => 'Seleccione el Estudiante...', 'multiple' => false],
            'pluginOptions' => [
                'allowClear' => true,
                'width' => '295px',
            ],
        ]);
        ?>
    </div>

    
    <div class="form-group">
        <div class="col-sm-offset-4">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary', 'id' => 'btn_buscarDatapromedios', 'href' => 'javascript:']) ?>
               </div>   
    </div>

<?php ActiveForm::end(); ?>

</div>
