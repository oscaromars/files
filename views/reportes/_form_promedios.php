<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */

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

    <div class="col-md-12">
        <div class="form-group">
            <label for="txt_buscarest" class="col-sm-2 control-label"><?= Yii::t("formulario", "Student") ?> <span class="text-danger">*</span> </label>
            <div class="col-sm-8">
            <!--    <input type="text" class="form-control" value="" id="txt_buscarest" placeholder="<?= Yii::t("formulario", "Search by Names") ?>"> -->
                <?php //echo '<label class="control-label">Tag Single</label>';
                echo Select2::widget([
                'name' => 'cmb_estudiante',
                'id' => 'cmb_estudiante',
                'value' => '0', // initial value
                'data' => $estudiante,
                'options' => ['placeholder' => 'Seleccionar'],
                'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [',', ' '],
                'maximumInputLength' => 50
                ],
                ]); ?>
            </div>                 
        </div>      
    </div>

    
    <div class="form-group">
        <div class="col-sm-offset-4">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary', 'id' => 'btn_buscarDatapromedios', 'href' => 'javascript:'
        ]) ?>
               </div>   
    </div>

<?php ActiveForm::end(); ?>

</div>
