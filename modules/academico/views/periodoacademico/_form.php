<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\academico\models\BloqueAcademico;
use app\modules\academico\models\SemestreAcademico;

/* @var $this yii\web\View */
/* @var $model frontend\models\Notas */
/* @var $form yii\widgets\ActiveForm */
if ($model->paca_id == null) {//Ingresa un nuevo   
    $model->paca_estado = '1';
    $model->paca_estado_logico = '1';
    $model->paca_usuario_ingreso = Yii::$app->session->get("PB_iduser");
    // $model->eaca_usuario_modifica = Yii::$app->session->get("PB_iduser");
    $model->paca_fecha_creacion = date("Y-m-d H:i:s");
    //$model->eaca_fecha_modificacion = date("Y-m-d H:i:s");
} else {//Modifica
    $model->paca_usuario_modifica = Yii::$app->session->get("PB_iduser");
    $model->paca_fecha_modificacion = date("Y-m-d H:i:s");
}
?>

<div class="estudioacademico-form">


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
    ]);
    ?>
    <?=
    $form->field($model, 'saca_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(SemestreAcademico::find()->all(), 'saca_id', 'saca_nombre', 'saca_anio'),
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
        'data' => ArrayHelper::map(BloqueAcademico::find()->all(), 'baca_id', 'baca_descripcion', 'baca_anio', 'baca_nombre'),
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Bloque Academico ...', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '295px',
        ],
    ]);
    ?>
         
            <?=
            $form->field($model, 'paca_estado')->widget(Select2::classname(), [
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
</div>
<?= $form->field($model, 'paca_usuario_ingreso')->hiddenInput() ?>
<?= $form->field($model, 'paca_usuario_modifica')->hiddenInput() ?>
<?= $form->field($model, 'paca_estado_logico')->hiddenInput() ?>
<?= $form->field($model, 'paca_fecha_creacion')->hiddenInput() ?>
<?= $form->field($model, 'paca_fecha_modificacion')->hiddenInput() ?>
<?php ActiveForm::end(); ?>

</div>
</div>
