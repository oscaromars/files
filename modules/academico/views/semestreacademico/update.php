<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\modules\academico\Module as academico;
academico::registerTranslations();

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = 'Actualizar Semestre ';
$this->params['breadcrumbs'][] = ['label' => 'Semestre', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->saca_id, 'url' => ['view', 'id' => $model->saca_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rol-update">

    <h3><?= Html::encode($this->title) ?></h3>


    <!--<div class="semestreacademico-form">-->

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
                    <input type="hidden" class="form-control PBvalidation" id="frm_semestre_intensivo" value="<?= $model->saca_intensivo ?>" data-type="number" placeholder="<?= academico::t("semestreacademico", "Intensivo") ?>">
                    <span id="spanSemIntensivo" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconSemIntensivo" class="<?= ($model->saca_intensivo == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
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
</div>
<input type="hidden" id="frm_saca_id" value="<?= $model->saca_id ?>">