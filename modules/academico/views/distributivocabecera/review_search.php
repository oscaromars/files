<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;

/* @var $this yii\web\View */
/* @var $model app\models\RolSearch */
/* @var $form yii\widgets\ActiveForm */

//$var = ArrayHelper::map(app\modules\academico\models\Profesor::find()->all(), 'pro_id',
//                function ($model) {
//                     return $model->per->per_pri_nombre ;
//                   // return strtoupper($model->per->per_pri_apellido . ' ' . $model->per->per_seg_apellido . ' ' . $model->per->per_pri_nombre . ' ' . $model->per->per_seg_nombre);
//                });
//print_r($var);
$disable=false;
if($resCab['estado']==1){
    $disable=true;
}
?>

<div class="semestreacademico-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['review'],
                'method' => 'get',
    ]);
    ?>
    <div class="form-group row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
            <h3><label id="lbl_profesor"><?= Yii::t("formulario", "Data Teacher") ?></label></h3>
        </div>
        <div class="col-sm-9">
            <label for="cmb_profesor" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Teacher") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_profesor", $resCab['pro_id'], $arr_profesor, ["class" => "form-control", "id" => "cmb_profesor"]) ?>

            </div> 
            <label for="cmb_periodo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodo", $resCab['paca_id'], $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
            </div>   
        </div>


        <div class="col-sm-offset-4">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <div class="form-group row"> 
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
            <h3><label id="lbl_profesor"><?= Yii::t("formulario", "Asignación") ?></label></h3>
        </div>
        <div class="col-sm-9">

            <label for="cmb_estado"  class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Review Status") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?php //Html::dropDownList("cmb_estado", $resCab['estado'], $arr_estado, ["class" => "form-control", "id" => "cmb_estado",'disabled' => $disable])
                echo Select2::widget([
                        'id'=> 'cmb_estado',
                        'name' => 'cmb_estado',
                        'data' => $arr_estado,
                        'disabled' => $disable,
                        'value' => $resCab['estado'],
                        'options' => [ 'placeholder' => 'Seleccione Estado ...',
                                    'options' => [
                                        2 => ['disabled' => true],
                                        3 => ['disabled' => true],
                                    ]
                 ],
                        
                ]);
                
                
                ?>
            </div> 

        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" id="observacion" style="display: none" >
            <div class="form-group">
                <label for="txt_detalle" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_detalle"><?= Yii::t("formulario", "Observation") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <textarea  class="form-control keyupmce" id="txt_detalle" rows="5"></textarea>   
                </div>
            </div>
        </div>
    </div>

    <?= Html::hiddenInput('txth_ids', $resCab['dcab_id'], ['id' => 'txth_ids']); ?>
    <?php ActiveForm::end(); ?>

</div>
