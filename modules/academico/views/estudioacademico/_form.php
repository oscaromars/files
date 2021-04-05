<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\TipoEstudioAcademico;

/* @var $this yii\web\View */
/* @var $model frontend\models\Notas */
/* @var $form yii\widgets\ActiveForm */
if ($model->eaca_id == null) {//Ingresa un nuevo   
    $model->eaca_estado = '1';
    $model->eaca_estado_logico = '1';
    $model->eaca_usuario_ingreso = Yii::$app->session->get("PB_iduser");
    // $model->eaca_usuario_modifica = Yii::$app->session->get("PB_iduser");
    $model->eaca_fecha_creacion = date("Y-m-d H:i:s");
    //$model->eaca_fecha_modificacion = date("Y-m-d H:i:s");
} else {//Modifica
    $model->eaca_usuario_modifica = Yii::$app->session->get("PB_iduser");
    $model->eaca_fecha_modificacion = date("Y-m-d H:i:s");
}
?>


<?php
$form = ActiveForm::begin(['layout' => 'horizontal',
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
        ]);
?>


<?=
$form->field($model, 'teac_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(TipoEstudioAcademico::find()->all(), 'teac_id', 'teac_nombre'),
    'size' => Select2::SMALL,
    'options' => ['placeholder' => 'Seleccione Tipo de Estudio Academico ...', 'multiple' => false],
    'pluginOptions' => [
        'allowClear' => true,
        'width' => '295px',
    ],
]);
?>

<?= $form->field($model, 'eaca_nombre')->textInput(['style'=>'width:300px']) ?>
<?= $form->field($model, 'eaca_descripcion')->textInput(['style'=>'width:300px']) ?>

<?= $form->field($model, 'eaca_alias_resumen')->textInput(['style'=>'width:300px']) ?>
<?= $form->field($model, 'eaca_alias')->textInput(['style'=>'width:300px']) ?>
 
            <?=
            $form->field($model, 'eaca_estado')->widget(Select2::classname(), [
                'data' => ['1' => "Activo", '0' => "Inactivo"],
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => 'Seleccione Estado ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '295px',
                ],
            ]);
            ?>
        

<div class="form-group">
    <div class="col-sm-offset-4">

<?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?= $form->field($model, 'eaca_usuario_ingreso')->hiddenInput() ?>
<?= $form->field($model, 'eaca_usuario_modifica')->hiddenInput() ?>
<?= $form->field($model, 'eaca_estado_logico')->hiddenInput() ?>
<?= $form->field($model, 'eaca_fecha_creacion')->hiddenInput() ?>
<?= $form->field($model, 'eaca_fecha_modificacion')->hiddenInput() ?>






<?php ActiveForm::end(); ?>

</div>
</div>
