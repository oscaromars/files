<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\modules\academico\models\EstudioAcademico;
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
    $form->field($model, 'pla_id')->label('Periodo:')->widget(Select2::classname(), [
        //'data' => ArrayHelper::map(app\modules\academico\models\Planificacion::find()->where(['paca_estado_logico' => '1','paca_estado' => '1'])->all(), 'paca_id', 'pla_periodo_academico'),
        'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Periodo ...', 'multiple' => false],
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

    <div class="col-sm-11">       
            <div class="form-group">
                <label for="cmb_carrera" class="control-label col-sm-4"><?= Yii::t("formulario", "Carrera:") ?>  </label>
                <div class="col-sm-4">
   
                    <?php
                    echo Select2::widget([
                    //'size' => Select2::MEDIUM,
                    'options' => ['placeholder' => 'Seleccione Carrera ...', 'multiple' => false],
                    'name' => 'cmb_carrera',
                    'id' => 'cmb_carrera',
                    'value' => '0', // initial value
                    'data' => $arr_carrera,
                    //'options' => ['placeholder' => 'Seleccionar'],
                    'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 50
                    ],
                    ]); 
                
                ?>
                    <!--<?= Html::dropDownList("cmb_carrera", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera"])?>-->                     
                </div>
            </div>     
    </div>
    

    <div class="form-group">
        <div class="col-sm-offset-3">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary', 'id' => 'btn_buscarDatamatriculados', 'href' => 'javascript:']) ?>
        </div>   
    </div>

<?php ActiveForm::end(); ?>

</div>
