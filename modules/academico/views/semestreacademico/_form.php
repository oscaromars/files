<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\modules\academico\Module as academico;
academico::registerTranslations();

/* @var $this yii\web\View */
/* @var $model app\models\Rol */
/* @var $form yii\widgets\ActiveForm */
if ($model->saca_id == null) {//Ingresa un nuevo   
    $model->saca_estado = '1';
    $model->saca_estado_logico = '1';
    $model->saca_usuario_ingreso = Yii::$app->session->get("PB_iduser");
    $model->saca_usuario_modifica = Yii::$app->session->get("PB_iduser");
    $model->saca_fecha_creacion = date("Y-m-d H:i:s");
    $model->saca_fecha_registro = date("Y-m-d H:i:s");
} else {//Modifica
   $model->saca_usuario_modifica = Yii::$app->session->get("PB_iduser");
    $model->saca_fecha_modificacion = date("Y-m-d H:i:s");
}
?>

<div class="semestreacademico-form">

    <?php  $form = ActiveForm::begin(['layout' => 'horizontal',
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
    ]); ?>

    <div class="row">   
        <div class="col-md-6">
            <?= $form->field($model, 'saca_nombre')->textInput(['maxlength' => true, 'style' => 'width:300px']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'saca_descripcion')->textInput(['style' => 'width:300px']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'saca_anio')->textInput(['style' => 'width:100px','maxlength' => 4]) ?>
        </div>
        <div class="col-md-6">
          <?=
            $form->field($model, 'saca_estado')->widget(Select2::classname(), [
                'data' => ['1' => "Activo", '0' => "Inactivo"],
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => 'Seleccione Estado ...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '295px',
                ],
            ]);
            ?>
        </div>
        <div class="col-sm-8">
            <label for="frm_semestre_intensivo" class="col-sm-3 control-label"><?= academico::t("semestreacademico", "Intensivo") ?></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_semestre_intensivo" value="0" data-type="number" placeholder="<?= academico::t("semestreacademico", "Intensivo") ?>">
                    <span id="spanSemIntensivo" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconSemIntensivo" class="glyphicon glyphicon-unchecked"></i></span>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="form-group">
        <div class="col-sm-offset-4">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>
    </div>-->
        <?= $form->field($model, 'saca_estado_logico')->hiddenInput() ?>
        <?= $form->field($model, 'saca_usuario_ingreso')->hiddenInput() ?>
        <?= $form->field($model, 'saca_usuario_modifica')->hiddenInput() ?>
        <?= $form->field($model, 'saca_fecha_creacion')->hiddenInput() ?>
        <?= $form->field($model, 'saca_fecha_modificacion')->hiddenInput() ?>
  
    <?php ActiveForm::end(); ?>

</div>
