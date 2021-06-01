<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
use app\modules\academico\models\DistributivoHorarioParalelo;
use app\modules\academico\models\DistributivoAcademicoHorario;
$this->title = 'Registrar Horario';
$this->params['breadcrumbs'][] = $this->title;
$data = Yii::$app->request->get();
$unidad = $_GET['unidad'];
if ($data['id']) {
    $unidad = $data['uaca_id'];
    $model->dhpa_grupo = $data['dhpa_grupo'];
    $model->dhpa_usuario_modifica = Yii::$app->session->get("PB_iduser");

    $model->dhpa_fecha_modificacion = date("Y-m-d H:i:s");
} else {
    $model->daho_id = $_GET['daho_id'];
    $model->dhpa_estado = '1';
    $model->dhpa_estado_logico = '1';
    $model->dhpa_usuario_ingreso = Yii::$app->session->get("PB_iduser");
    $model->dhpa_usuario_modifica = Yii::$app->session->get("PB_iduser");

    $model->dhpa_fecha_creacion = date("Y-m-d H:i:s");
    $model->dhpa_fecha_modificacion = date("Y-m-d H:i:s");
}
$array = [
    ['id' => '1', 'data' => '1'],
    ['id' => '2', 'data' => '2'],
    ['id' => '3', 'data' => '3'],
    ['id' => '4', 'data' => '4'],
];
?>

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Registrar Paralelo") ?></span></h3>
</div>
<br/>
<br/>
<div class="distributivohorarioparalelo-form">
    
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
             
               
        <?= $form->field($model, 'dhpa_paralelo')->textInput(['maxlength' => true, 'style' => 'width:300px']) ?>
                          <?=
                $form->field($model, 'daho_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(DistributivoAcademicoHorario::find()->all(), 'daho_id','daho_descripcion', 'mod.mod_descripcion'),
                    'size' => Select2::MEDIUM,
                    'options' => ['placeholder' => 'Seleccione Horario ...', 'multiple' => false],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '400px',
                    ],
                ]);
                ?>

            
            
                <?php if ($unidad == 2) { ?>
                    <?=
                    $form->field($model, 'dhpa_grupo')->widget(Select2::classname(), [
                        'data' => ArrayHelper::getColumn($array, 'id'),
                        'size' => Select2::SMALL,
                        'options' => ['placeholder' => 'Seleccione un Grupo ...', 'multiple' => false],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '280px',
                        ],
                    ]);
                    ?>
               
           
        
    <?php } ?>
    <br/>
    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>
   
    <?= $form->field($model, 'dhpa_usuario_ingreso')->hiddenInput() ?>
    <?= $form->field($model, 'dhpa_usuario_modifica')->hiddenInput() ?>
    <?= $form->field($model, 'dhpa_estado')->hiddenInput() ?>
    <?= $form->field($model, 'dhpa_fecha_creacion')->hiddenInput() ?>
    <?= $form->field($model, 'dhpa_fecha_modificacion')->hiddenInput() ?>
    <?= $form->field($model, 'dhpa_estado_logico')->hiddenInput() ?>


    <?php ActiveForm::end(); ?>
</div>







