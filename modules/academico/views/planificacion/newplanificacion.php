<?php

use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use kartik\select2\Select2;
academico::registerTranslations();

?>

<style>
    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
    height: 98% !important;
}
</style>

<?= Html::hiddenInput('txth_pla_id', $_SESSION['plan_id'], ['id' => 'txth_pla_id']); ?>
<?= Html::hiddenInput('pla_id', $pla_id, ['id' => 'pla_id']); ?>
<?= Html::hiddenInput('txt_periodo', $periodo, ['id' => 'txt_periodo']); ?>
<?= Html::hiddenInput('txth_per_id', $per_id, ['id' => 'txth_per_id']); ?>
<?= Html::hiddenInput('txt_malla', $arr_malla, ['id' => 'txt_malla']); ?>
<?= Html::hiddenInput('txt_select', $perSelectSe, ['id' => 'txt_select']); ?>
<?= Html::hiddenInput('cmb_modalidadest', $arr_modalidad, ['id' => 'cmb_modalidadest']); ?>
<?= Html::hiddenInput('txt_carrera', $carrera_activa, ['id' => 'txt_carrera']); ?>
<?= Html::hiddenInput('txt_existe', $existe, ['id' => 'txt_existe']); ?>
<div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
    <div class="col-sm-10 col-md-10 col-xs-8 col-lg-10"></div>
    <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
        <a id="btn_modificarplanificacionaut" href="javascript:" class="btn btn-default btn-Action"> <i class="glyphicon glyphicon-floppy-disk"></i><?= Yii::t("formulario", "&nbsp;&nbsp; Guardar") ?></a>
    </div>        
</div> 
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h4><span id="lbl_planear"><?= academico::t("Academico", "Headboard Student Planning") ?></span></h4>
</div>

<form class="form-horizontal">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="lbl_unidadest" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label"><?= Yii::t("crm", "Academic Unit"); ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?= Html::dropDownList("cmb_unidadest", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidadest", "Disabled" => "true"]) ?>
                    </div> 
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="lbl_per_act_est" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 control-label"><?= Yii::t("crm", "Periodo"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <?= Html::dropDownList("cmb_periodoest", $perSelect, $periodo_activo, ["class" => "form-control", "id" => "cmb_periodoest","Enabled" => "true"]) ?>
                    </div>                
                </div>
            </div>        
        </div> 
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
             <div class="form-group">
                <div class="col-lg-12 col-md-12">
                    <label for="txt_buscarest" class="col-lg-2 col-md-2 col-sm-12 col-xs-12 control-label"><?= Yii::t("formulario", "Student") ?>  </label>
                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" >
                    <?php 
                    echo Select2::widget([
                    'name' => 'cmb_buscarest',
                    'id' => 'cmb_buscarest',
                    'value' => $arr_initial, // initial value
                    'data' => $arr_alumno,
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
        </div> 

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"></div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">                
                <a id="btn_limpiarbuscador" href="javascript:" class="btn btn-default btn-block"> <?= Yii::t("formulario", "Limpiar busqueda") ?></a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">                
                <a id="btn_buscarPlanest" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>    
    </div> 

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
        <h4><span id="lbl_evaluar"><?= Yii::t("formulario", "Detalle Planificación Estudiante") ?></span></h4>
    </div>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
        <div class="col-lg-6 col-md-6" style="float: left;">
            <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
        </div>
        <div class="col-lg-6 col-md-6"></div>
    </div>  

    
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="col-lg-6 col-md-6">
                <label for="lbl_asignaest" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 control-label"><?= academico::t("Academico", "Subject"); ?> <span class="text-danger">*</span> </label>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <?= Html::dropDownList("cmb_asignaest", 0, $arr_materia, ["class" => "form-control", "id" => "cmb_asignaest"]) ?>
                </div>   
            </div>
            <div class="col-lg-6 col-md-6">
                <label for="lbl_jornadaest" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 control-label"><?= academico::t("Academico", "Working day") ?> <span class="text-danger">*</span> </label>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <?= Html::dropDownList("cmb_jornadaest", 0, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornadaest"]) ?>
                </div>                        
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="col-lg-6 col-md-6">
                <label for="lbl_bloqueest" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 control-label"><?= Yii::t("formulario", "Block"); ?> <span class="text-danger">*</span> </label>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <?= Html::dropDownList("cmb_bloqueest", 0, $arr_bloque, ["class" => "form-control", "id" => "cmb_bloqueest"]) ?>
                </div>                     
            </div>
            <div class="col-lg-6 col-md-6">
                <label for="lbl_modalidadesth" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 control-label"><?= Yii::t("formulario", "Mode"); ?> <span class="text-danger">*</span> </label>
                <div class="col-lg-9 col-md-9 col-xs-12 col-xs-12">
                    <?= Html::dropDownList("cmb_modalidadesth", 0, $arr_modalidadh, ["class" => "form-control", "id" => "cmb_modalidadesth"]) ?>
                </div>                      
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">  
            <div class="col-lg-6 col-md-6">               
                    <label for="lbl_horaest" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 control-label"><?= academico::t("Academico", "Hour"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" id="hora_cmb">
                        <?= Html::dropDownList("cmb_horaest", 0, $arr_hora, ["class" => "form-control", "id" => "cmb_horaest"]) ?>
                    </div> 
            </div>
            <div class="col-lg-6 col-md-6">               
                <label for="lbl_paraleloest" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 control-label"><?= academico::t("Academico", "Paralelo"); ?> <span class="text-danger">*</span> </label>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" id="paralelo_cmb">
                    <?= Html::dropDownList("cmb_paraleloest", 0, $arr_paralelo, ["class" => "form-control", "id" => "cmb_paraleloest","disabled" => "true"]) ?>
                </div> 
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">  
            <div class="col-lg-6 col-md-6">               
            </div> 
            <div class="col-lg-6 col-md-6">               
                    <label for="lbl_horario" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 control-label"><?= academico::t("Academico", "Horario"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" id="horario_cmb">
                        <?= Html::dropDownList("cmb_horario", 0, $arr_paralelo, ["class" => "form-control", "id" => "cmb_horario","disabled" => "true"]) ?>
                    </div> 
            </div>
        </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                <a id="btn_AgregarItemat" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Add") ?></a>
            </div>
        </div>
    </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span id="lbl_evaluar"><?= Yii::t("formulario", "Asignaturas Planificadas del estudiante") ?></span></h4>
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="box-body table-responsive no-padding">
                    <?=
                    PbGridView::widget([
                        'id' => 'PbPlanificaestudiante',
                        'dataProvider' => $model_detalle,
                        'pajax' => true,
                        'summary' => false,
                        'columns' => [
                            /*[
                                'attribute' => 'materia',
                                'header' => academico::t("Academico", "Subject"),
                                'options' => ['width' => '590'],
                                'value'=>function ($model_detalle) {
                                    return  $model_detalle['asignatura'];
                                },
                            ], */
                            [
                                'attribute' => 'asignatura',
                                'header' => academico::t("Academico", "Subject"),
                                'options' => ['min-width' => '500'],
                                'value' => 'asignatura',
                            ], 
                            [
                                'attribute' => 'jornada',
                                'header' => academico::t("Academico", "Working day"),
                                'value' => 'pes_jornada',
                            ],
                            [
                                'attribute' => 'hora',
                                'header' => academico::t("Academico", "Hour"),
                                'value' => 'hora',
                            ],          
                            [
                                'attribute' => 'bloque',
                                'header' => Yii::t("formulario", "Block"),
                                'value' => 'bloque',
                            ],
                            [
                                'attribute' => 'paralelo',
                                'header' => Yii::t("formulario", "Paralelo"),
                                'value' => 'paralelo',
                            ],
                            [
                                'attribute' => 'modalidad',
                                'header' => Yii::t("formulario", "Mode"),
                                'value' => 'modalidad',
                            ],
                            [
                                'attribute' => 'horario',
                                'header' => academico::t("Academico", "Horario"),
                                'value' => 'horario',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'contentOptions' => ['style' => 'text-align: left;'],
                                'headerOptions' => ['width' => '60'],
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model_detalle) {
                                        return Html::a('<span class="' . Utilities::getIcon('remove') . '"></span>', null, ['href' => 'javascript:', 'onclick' => "deletematestudianteaut( " . $_SESSION['plan_id'] .",". $_SESSION['per_ids'] . ", " . substr($model_detalle['bloque'], -1) . ", " . substr($model_detalle['hora'], -1) .", " . $model_detalle['saca_id'] . ");", "data-toggle" => "tooltip", "title" => Yii::t("accion", "Delete")]);
                                
                                    },
                                ],
                            ],
                            
                        ],
                    ])
                    ?>
                </div>
            </div>

        </div>
        <br>  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span id="lbl_evaluar"><?= Yii::t("formulario", "Nuevas Asignaturas del estudiante") ?></span></h4>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <p class="text-danger" style="font-size: 11px;"> <?= Yii::t("formulario", "Las asignaturas de este cuadro son temporales, se debe gaurdar para reflejar los cambios.") ?> </p>
        </div><br><br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="box-body table-responsive no-padding">
                    <table  id="PbPlanificaestudiantnew" class="table table-hover">
                        <thead>
                            <tr>
                                <th style="display:none; border:none;"><?= Yii::t("formulario", "pla_id") ?></th>
                                <th style="display:none; border:none;"><?= Yii::t("formulario", "per_id") ?></th>
                                <th style="display:none; border:none;"><?= Yii::t("formulario", "mpp_id") ?></th>
                                <th style="width: 590px">
                                    <?= academico::t("","Academico", "Subject") ?></th>
                                <th><?= academico::t("Academico", "Working day") ?></th>
                                <th><?= Yii::t("formulario", "Block") ?></th>                            
                                <th><?= Yii::t("formulario", "Mode") ?></th>
                                <th><?= academico::t("Academico", "Hour") ?></th>
                                <th style="display:none; border:none;"><?= Yii::t("formulario", "Subject") ?></th>                            

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
    
    </form>     
