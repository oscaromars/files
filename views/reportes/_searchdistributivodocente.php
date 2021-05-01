<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */

$var = ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->where(['paca_estado' => 1,'paca_estado_logico' => 1,'paca_activo' => 'A'])->all(), 'paca_id',
                function ($model) {
                     return $model->baca->baca_nombre . '-' . $model->sem->saca_nombre . '-' . $model->sem->saca_anio;
                   // return strtoupper($model->per->per_pri_apellido . ' ' . $model->per->per_seg_apellido . ' ' . $model->per->per_pri_nombre . ' ' . $model->per->per_seg_nombre);
                });
?>
<div class="reportdistributivodocente-search">

    <?php
   // setlocale(LC_TIME, 'de_ES');

    $form = ActiveForm::begin(['id' => 'login-form-inline',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
                'fieldConfig' => ['options' => ['class' => 'form-group mr-2']], // spacing field groups
                'formConfig' => ['showErrors' => true],
                'options' => ['style' => 'align-items: flex-start'],
                'action' => ['reportdistributivodocente'],
                'method' => 'get',
    ]);
    ?>
     <?=
    $form->field($model, 'paca_id')->label('Período:')->widget(Select2::classname(), [
      //  'data' => ArrayHelper::map(app\modules\academico\models\PeriodoAcademico::find()->all(), 'paca_id', 'sem.saca_nombre'),
         'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Distributivo ...', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '295px',
        ],
    ]);
    ?>
    
    <?=
    $form->field($model, 'tdis_id')->label('Tipo Asignación:')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(app\modules\academico\models\TipoDistributivo::find()->all(), 'tdis_id', 'tdis_nombre'),
        // 'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Distributivo ...', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '295px',
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'mod_id')->label('Modalidad:')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(app\modules\academico\models\Modalidad::find()->all(), 'mod_id', 'mod_nombre'),
        // 'data' => $var,
        'size' => Select2::MEDIUM,
        'options' => ['placeholder' => 'Seleccione Modalidad ...', 'multiple' => false],
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


