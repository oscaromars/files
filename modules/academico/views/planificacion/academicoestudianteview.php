<?php
use yii\helpers\Url;
use app\models\Utilities;
use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;

//print_r($arr_malla[0]['cod_asignatura']);
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_pla_id', $_GET['pla_id'], ['id' => 'txth_pla_id']); ?>
<?= Html::hiddenInput('txth_per_id', $_GET['per_id'], ['id' => 'txth_per_id']); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h4><span id="lbl_planear"><? academico::t("Academico", "Estudiantes por Materia Planificada") ?></span></h4>
</div>
<form class="form-horizontal">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="form-group">
                <div class="col-lg-6 col-md-6">
                    <label for="lbl_unidadest" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Yii::t("crm", "Academic Unit"); ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?= Html::dropDownList("cmb_unidadest", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidadest", "Disabled" => "disabled"]) ?>
                    </div>              
                </div>        
                <div class="col-lg-6 col-md-6">
                    <label for="lbl_modalidadest" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?= Html::dropDownList("cmb_modalidadest", $id_modalidad, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadest", "Disabled" => "disabled"]) ?>
                    </div>                       
                </div>        
            </div> 
        </div>

    </div>
</form>
<div>      
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4><span id="lbl_evaluar"><?= academico::t("Academico", "Student Planning Detail") ?></span></h4>
    </div>
    <?=
    PbGridView::widget([
        'id' => 'PbPlanificaestudianteview',
        'dataProvider' => $model_detalle,
        'pajax' => true,
        //'summary' => false,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
           [
            'attribute' => 'Estudiante',
            'header' => academico::t("Academico", "Estudiante"),
            'value' => 'pes_nombres',
            ],  
           [
                'attribute' => 'asignatura',
                'header' => academico::t("Academico", "Subject"),
                'value' => 'Materia',
                /*'value'=>function ($model_detalle) {
                    return $model_detalle['cod_asignatura']  . ' - ' . $model_detalle['asignatura'];
                },*/
            ],  
            /*[
                'attribute' => 'jornada',
                'header' => academico::t("Academico", "Working day"),
                'value' => 'jor_materia',
            ],*/  
            [
                'attribute' => 'bloque',
                'header' => Yii::t("formulario", "Block"),
                'value' => 'bloque',
            ],
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'Modalidad',
            ],
            [
                'attribute' => 'Periodo',
                'header' => Yii::t("formulario", "Periodo"),
                'value' => 'Periodo',
            ],
            /*[
                'attribute' => 'hora',
                'header' => academico::t("Academico", "Hour"),
                'value' => 'Hora 1',
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'header' => 'Acciones',
                //'template' => '{view}{delete}{Approbe}{Download}{Reversar}',
                'template' => '{view}',
              //  'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'view' => function ($url, $model,$id_modalidad) {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['/academico/planificacion/newplanificacion', 
                                                                                                            'estudiante' => $model['per_id'],
                                                                                                            'modalidad' => $model['mod_id'],
                                                                                                            'unidad' => 1,
                                                                                                            'periodo' => $model['saca_id'],
                                                                                                            ]));
                    },
                ],
            ],
        ],
    ])
    ?>
</div>
