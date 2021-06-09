<?php

use yii\helpers\Html;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;

//print_r($model_detalle);
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_pla_id', $_GET['pla_id'], ['id' => 'txth_pla_id']); ?>
<?= Html::hiddenInput('txth_per_id', $_GET['per_id'], ['id' => 'txth_per_id']); ?>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_unidadest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Academic Unit"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidadest", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidadest", "Disabled" => "disabled"]) ?>
                </div> 
                <!-- <label for="lbl_jornadaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_jornadaest", $valorjornada, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornadaest", "disabled" => "true"]) ?>
                </div> -->                
            </div>        
        </div>  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="lbl_carreraest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("crm", "Carrera"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <!-- <? Html::dropDownList("cmb_carreraest",$arr_idcarrera["eaca_id"], $arr_carrera, ["class" => "form-control", "id" => "cmb_carreraest", "Disabled" => "disabled"]) ?>-->
                    <input type="text" class="form-control" value="<?= $arr_idcarrera["pes_carrera"] ?>" disabled ="true" id="txt_carrera" placeholder="<?= Yii::t("crm", "Carrera") ?>">
                </div> 
                <label for="lbl_modalidadest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidadest", $arr_cabecera["mod_id"], $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadest", "Disabled" => "disabled"]) ?>
                </div>                       
            </div>        
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">           
                <label for="lbl_mallaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Academic Mesh"); ?> </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <input type="text" class="form-control" value="<?= $arr_idcarrera["malla"] ?>" id="txt_malla" disabled = "true" placeholder="<?= Yii::t("formulario", "Search by Names") ?>">    
                </div>  
                <label for="lbl_periodoest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_periodoest", $arr_cabecera["pla_periodo_academico"], $arr_periodo, ["class" => "form-control", "id" => "cmb_periodoest", "Disabled" => "disabled"]) ?>
                </div>                  
            </div>        
        </div> 
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="txt_buscarest" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Student") ?> </label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="<?= $arr_idcarrera["pes_nombres"] ?>" id="txt_buscarest" disabled = "true" placeholder="<?= Yii::t("formulario", "Search by Names") ?>">
                </div>
            </div>
        </div> 
    </div>
    <div>      
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span id="lbl_evaluar"><?= Yii::t("formulario", "Detalle Planificación Estudiante") ?></span></h4>
        </div><br><br><br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="lbl_asignaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Subject"); ?> <span class="text-danger">*</span>  </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?= Html::dropDownList("cmb_asignaest", 0, $arr_materia, ["class" => "form-control", "id" => "cmb_asignaest"]) ?>
                    </div>   
                    <label for="lbl_jornadaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?></label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?=  Html::dropDownList("cmb_jornadaest", 0, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornadaest"]) ?>
                    </div>                    
                </div>        
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label for="lbl_bloqueest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Block"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?= Html::dropDownList("cmb_bloqueest", 0, $arr_bloque, ["class" => "form-control", "id" => "cmb_bloqueest"]) ?>
                    </div>                     
                    <label for="lbl_modalidadesth" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?= Html::dropDownList("cmb_modalidadesth", 0, $arr_modalidadh, ["class" => "form-control", "id" => "cmb_modalidadesth"]) ?>
                    </div>                      
                </div>        
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">                     
                    <label for="lbl_horaest" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Hour"); ?> <span class="text-danger">*</span> </label>
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                        <?= Html::dropDownList("cmb_horaest", 0, $arr_hora, ["class" => "form-control", "id" => "cmb_horaest"]) ?>
                    </div> 
                </div>        
            </div>
         </div>        
        <!-- <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <button type="button" class="btn btn-primary" onclick="javascript:addMaterias()"><?= Academico::t('profesor', 'Add') ?></button>
                <a id="btn_EditItemat" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Add") ?></a>
                
            </div>
        </div> -->
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2 text-center">
                    <a id="btn_AgregarItemat" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Agregar") ?></a>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span id="lbl_evaluar"><?= Yii::t("formulario", "Asignaturas Planificadas del estudiante") ?></span></h4>
        </div><br>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <p class="text-danger"> <?= Yii::t("formulario", "Al eliminar la asignatura de este cuadro es el registro guardado anteriormente y es permanente") ?> </p>
        </div><br>
        <?=
        PbGridView::widget([
            'id' => 'PbPlanificaestudiantedit',
            'dataProvider' => $model_detalle,
            'pajax' => true,
            'summary' => false,
            'columns' => [
                [
                    'attribute' => 'asignatura',
                    'header' => academico::t("Academico", "Subject"),
                    'options' => ['width' => '590'],
                    'value'=>function ($model_detalle) {
                        return $model_detalle['cod_asignatura']  . ' - ' . $model_detalle['asignatura'];
                    },
                ], 
                [
                    'attribute' => 'jornada',
                    'header' => academico::t("Academico", "Working day"),
                    'value' => 'jor_materia',
                ],          
                [
                    'attribute' => 'bloque',
                    'header' => Yii::t("formulario", "Block"),
                    'value' => 'Bloque 1',
                ],
                [
                    'attribute' => 'modalidad',
                    'header' => Yii::t("formulario", "Mode"),
                    'value' => 'modalidad',
                ],
                [
                    'attribute' => 'hora',
                    'header' => academico::t("Academico", "Hour"),
                    'value' => 'Hora 1',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['style' => 'text-align: left;'],
                    'headerOptions' => ['width' => '60'],
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model_detalle) {
                            return Html::a('<span class="' . Utilities::getIcon('remove') . '"></span>', null, ['href' => 'javascript:', 'onclick' => "deletematestudiante(" . $_GET['pla_id'] . ", " . $_GET['per_id'] . ", " . substr($model_detalle['Bloque 1'], -1) . ", " . substr($model_detalle['Hora 1'], -1) . ");", "data-toggle" => "tooltip", "title" => Yii::t("accion", "Delete")]);
                            //return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => "javascript:removeItemPlanificacion(this, " . $_GET['pla_id'] . ", " . $_GET['per_id'] . ", " . substr($model_detalle['Bloque 1'], -1) . ", " . substr($model_detalle['Hora 1'], -1) . ");", "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);                         
                            //return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:', 'onclick' => "javascript:removeItemPlanificacion(3);", "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);                         
                        },
                    ],
                ],
            ],
        ])
        ?> <br>  
        </div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span id="lbl_evaluar"><?= Yii::t("formulario", "Nuevas Asignaturas del estudiante") ?></span></h4>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <p class="text-danger"> <?= Yii::t("formulario", "Al eliminar la asignatura de este cuadro es temporal, antes de actualizar los datos") ?> </p>
        </div><br><br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="box-body table-responsive no-padding">
                    <table  id="PbPlanificaestudiantnew" class="table table-hover">
                        <thead>
                            <tr>
                                <th style="display:none; border:none;"><?= Yii::t("formulario", "pla_id") ?></th>
                                <th style="display:none; border:none;"><?= Yii::t("formulario", "per_id") ?></th>
                                <th style="width:52.5%"><?= academico::t("Academico", "Subject") ?></th>
                                <th><?= academico::t("Academico", "Working day") ?></th>
                                <th><?= Yii::t("formulario", "Block") ?></th>                            
                                <th><?= Yii::t("formulario", "Mode") ?></th>
                                <th><?= academico::t("Academico", "Hour") ?></th>                            

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       </div>  
        <!-- <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
            <div class="col-sm-10 col-md-10 col-xs-8 col-lg-10"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_modificarplanificacion" href="javascript:" class="btn btn-primary btn-block"> <? Yii::t("formulario", "Update") ?></a>
            </div>        
        </div> -->
    </div>
    </div>  
</form> 