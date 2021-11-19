<?php

use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use kartik\grid\GridView;
use kartik\grid\DataColumn;
use kartik\grid\EditableColumn;
use kartik\grid\Editable;
use app\models\Utilities;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use yii\helpers\Html;
use app\modules\academico\Module as academico;

use app\assets\DatatableAsset;

DatatableAsset::register($this);
academico::registerTranslations();
// $status = "disabled";
// print_r($status_i);
// print_r(array_values($model));die();
?>
<style>
    .ubicar{
        text-align: left !important;
    }
</style>
<?= Html::hiddenInput('txth_proid', $pro_id, ['id' => 'txth_proid']); ?>
<?= Html::hiddenInput('txth_materia', $materia, ['id' => 'txth_materia']); ?>
<?= Html::hiddenInput('txth_periodo', $periodo, ['id' => 'txth_periodo']); ?>
<?= Html::hiddenInput('txth_sesion', $sesion, ['id' => 'txth_sesion']); ?>
<?= Html::hiddenInput('txth_unidad', $unidad, ['id' => 'txth_unidad']); ?>
<?= Html::hiddenInput('cantidad_registros', count($model), ['id' => 'cantidad_registros']); ?>
<div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
    <div class="col-sm-10 col-md-10 col-xs-8 col-lg-10"></div>
    <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
        <a id="btn_crearAsistencia" href="javascript:" class="btn btn-default btn-Action"> <i class="glyphicon glyphicon-floppy-disk"></i><?= Yii::t("formulario", "&nbsp;&nbsp; Registrar") ?></a>
    </div>        
</div> 
<!-- <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6 text-right  " >
            <a  id="btn_crearAsistencia" class="btn btn-default glyphicon glyphicon-floppy-disk btn-block"
            style="text-decoration: none !important;" href="javascript:"> 
            <?= Yii::t("formulario", "Registrar") ?></a>
        </div>  -->
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Marcar Asistencia") ?></span></h3>
</div></br></br></br>
<div>
    <form class="form-horizontal">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 30px;">
        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
            <div class="form-group">                 
                <label for="cmb_periodo" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label ubicar"><?= academico::t("Academico", "Period") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">  
                        <?= Html::dropDownList("cmb_periodo",0,$arr_periodoActual, ["class" => "form-control", "id" => "cmb_periodo"]) ?>              
                </div>
            </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
            <div class="form-group"> 
                <label for="cmb_profesor_asis" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label ubicar"><?= academico::t("Academico", "Teacher") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <?= Html::dropDownList("cmb_profesor_asis", 0,$arr_profesor_all, ["class" => "form-control", "id" => "cmb_profesor_asis","disabled"=>"true"]) ?>
                </div>
            </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
            <div class="form-group"> 
                <label for="cmb_modalidad" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label ubicar"><?= academico::t("Academico", "Modality") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", 0,$arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
            <div class="form-group">                 
                <label for="cmb_unidad" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label ubicar"><?= Yii::t("formulario", "Academic unit") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <?= Html::dropDownList("cmb_unidad_est", 0, $arr_ninteres,["class" => "form-control", "id" => "cmb_unidad_est"]) ?>
                </div>
            </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
            <div class="form-group"> 
                <label for="cmb_materia" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label ubicar">
                    <?= Yii::t("formulario", "Subject") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <?= Html::dropDownList("cmb_materia", 0,$arr_asignatura,["class" => "form-control", "id" => "cmb_materia","disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
            <div class="form-group"> 
                <label for="cmb_parcial" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label ubicar"><?= academico::t("Academico", "Paralelo") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <?= Html::dropDownList("cmb_parcial", 0,$arr_paralelo, ["class" => "form-control", "id" => "cmb_parcial","disabled"=> "true"]) ?>
                </div>
            </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
            <div class="form-group">                 
                <label for="cmb_sesion" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label ubicar"><?= Yii::t("formulario", "Sesión") ?></label>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <?= Html::dropDownList("cmb_sesion", 0, $arr_sesion,["class" => "form-control", "id" => "cmb_sesion", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 "></div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 text-right">                
                <a id="btn_buscarDataregistroM" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>
    </div>
    <!-- <div class="noBar" style="overflow: scroll; /*border: 1px solid;*/ scrollbar-width: none;margin-left: 0 !important;"> -->
    
</form>
</div>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    /* .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td {
    border-top: 0;
    vertical-align: top;
    
    -ms-overflow-style: none; /* for Internet Explorer, Edge */
    /*scrollbar-width: none; /* for Firefox */
    /*} */
    .noBar{
        -ms-overflow-style: none !important; /* for Internet Explorer, Edge */
          scrollbar-width: none !important; /* for Firefox */
          overflow-x: scroll !important; 	
    }
    .nopBar::-webkit-scrollbar {
        display: none !important; /* for Chrome, Safari, and Opera */
    }
    .separate{
        margin-top: 5%;
    }
    input[type="checkbox"][readonly] {
        pointer-events: none;
    }
    </style>
    <br>
    
<div style="width: 100%;"class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row " style="padding-bottom: 2% !important;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 container" >
        <div class="alert alert-info" style="opacity:0.7"><span style="font-weight: bold"> Nota: </span> Registro <?= $marcado ?> de clases
            </div>
        </div>
 
    </div>
    <div class="row" style="overflow: scroll !important; margin-left: -10px;" >
        <div>
            <input type="checkbox" class="form-check-input" id="checkAll">
            <label id="text_seleccion" class="form-check-label" for="checkAll">Seleccionar todos los estudiantes</label>
            <br>Mostando&nbsp;<label style=""> <?= count($model)?> </label>&nbsp;registros.
        </div>
        <table id="table_asistencia" class=" text-center table thead-light table-responsive table-bordered" >
            <tr>
                <th style="width: 60px;" hidden>id</th>
                <th style="width: 60px;" hidden>asi_id</th>
                <th style="width: 60px;" hidden>paca_id</th>
                <th style="width: 60px;" hidden>mod_id</th>
                <th style="width: 60px;" hidden>pro_id</th>
                <th style="width: 60px;" hidden>est_id</th>
                <th style="width: 60px;" hidden>daho_id</th>
                <th style="width: 60px;" hidden>rmtm_id</th>
                <th style="width: 60px;" hidden>parcial</th>
                <th style="width: 10px;">#</th>
                <th style="width: 200px;">Alumno</th>
                <th style="width: 100px;">Matricula</th>
                <th style="width: 200px;">Materia</th>
                <th style="width: 100px;">Hora Inicio</th>
                <th style="width: 50px;">Atraso &nbsp;
                    <a  href="#" 
                        style="text-decoration: none;"
                        data-toggle="tooltip"
                        title="Ingrese con cuantos minutos de atraso llego el estudiante." 
                        class="glyphicon glyphicon-info-sign"
                        data-placement="top"></a></th>
                <th style="width: 100px;">Hora Fin</th>
                <th style="width: 50px;">Salida &nbsp;
                    <a  href="#" 
                        style="text-decoration: none;"
                        data-toggle="tooltip"
                        title="Ingrese con cuantos minutos de anticipación se retiró el estudiante." 
                        class="glyphicon glyphicon-info-sign"
                        data-placement="top"></a></th>
            </tr>
            <?php
            $contenido = '<td colspan="7"><p class="text-left">No se encontraron resultados.</p></td>';
            $c = 1;
            if(count($model) == 0){  
                echo $contenido; 
            }
            foreach($model as $key => $value){
                $contenido = '  <tr >
                <td id = "id_'.$c.'" hidden>'.$value['id'].'</td>
                <td id = "asi_id_'.$c.'" hidden>'.$value['asi_id'].'</td>
                <td id = "paca_id_'.$c.'" hidden>'.$value['paca_id'].'</td>
                <td id = "mod_id_'.$c.'" hidden>'.$value['mod_id'].'</td>
                <td id = "pro_id_'.$c.'" hidden>'.$value['pro_id'].'</td>
                <td id = "est_id_'.$c.'" hidden>'.$value['est_id'].'</td>
                <td id = "daho_id_'.$c.'" hidden>'.$value['daho_id'].'</td>
                <td id = "rmtm_id'.$c.'" hidden>'.$value['sesion'].'</td>
                <td id = "parcial_'.$c.'" hidden>'.$value['parcial'].'</td>
                <td>'.$c.'</td>
                <td style="text-align: left;">'.$value['nombre'].'</td>
                <td>'.$value['matricula'].'</td>
                <td>'.$value['materia'].'</td>
                <td><input type="checkbox" class="keyupmce" data-type="number" id="txt_inicio_'.$c.'" name="txt_alumno_'.$c.'" value="" '.$value['stado'].' ></td>
                <td ><input type="text" id="entrada_'.$c.'"  style="width: 100%; text-align: center;" placeholder="0" ></td>
                <td><input type="checkbox" id="txt_fin_'.$c.'" name="txt_alumno_'.$c.'" value=""  ></td>
                <td ><input type="text" id="salida_'.$c.'" style="width: 100%; text-align: center;" placeholder="0"  ></td>
                </tr>';
                $c++;
                echo $contenido; 
            } ?>
        </table>
    </div>
</div>
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.12.4.min.js" ></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
<script src="bootstrap-number-input.js" ></script> -->
<!-- <script>
    $('#after').bootstrapNumber();
</script> -->
<div >
<!--scriptZbGridView::widget([
// 	'id' => 'grid_marcacion_list',
// 	'showExport' => false,
// 	//'fnExportEXCEL' => "exportExcel",
// 	//'fnExportPDF' => "exportPdf",
// 	/* 'dataProvider' => $model, */
// 	'dataProvider' => $model,
// 	'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
// 	'pajax' => true,
// 	'columns' => [
// 		['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10','border' => '1px solid !important']],
// 		[
// 			'attribute' => 'id',
// 			'header' => Academico::t("matriculacion", "id"),
// 			'value' => 'id',
// 		],
// 		[
// 			'attribute' => 'nombre',
// 			'header' => Academico::t("matriculacion", "nombre"),
// 			'value' => 'nombre',
// 		],
// 		[
// 			'attribute' => 'matricula',
// 			'header' => Academico::t("matriculacion", "matricula"),
// 			'value' => 'matricula',
// 		],
// 		// [
// 		// 	'attribute' => 'Hour',
// 		// 	'header' => Academico::t("matriculacion", "Hour"),
// 		// 	'contentOptions' => ['class' => 'text-center'],
// 		// 	'headerOptions' => ['class' => 'text-center'],
// 		// 	//'visible' => false,
// 		// 	'value' => 'Hour',
// 		// ],
// 		// [
// 		// 	'attribute' => 'Parallel',
// 		// 	'header' => Academico::t("matriculacion", "Paralelo"),
// 		// 	'contentOptions' => ['class' => 'text-center'],
// 		// 	'headerOptions' => ['class' => 'text-center'],
// 		// 	'value' => 'Parallel',
// 		// ],
// 		// [
// 		// 	'attribute' => 'Credit',
// 		// 	'header' => Academico::t("matriculacion", "Credit"),
// 		// 	'contentOptions' => ['class' => 'text-center'],
// 		// 	'headerOptions' => ['class' => 'text-center'],
// 		// 	'visible' => false,
// 		// 	'value' => 'Credit',
// 		// ],
// 		[
// 			'class' => 'yii\grid\ActionColumn',
// 			'header' => Academico::t("matriculacion", "Hora Inicio"),
// 			'contentOptions' => ['style' => 'text-align: center;'],
// 			'headerOptions' => ['width' => '60'],
// 			'template' => '{select}',
// 			'buttons' => ['select' => function ($url, $planificacion) {
//                     return Html::checkbox($planificacion['Code'], false, ["value" => $planificacion['Subject']]);
//                     /* return Html::checkbox("", false, ["value" => $planificacion['Subject'], "onchange" => "handleChange(this)"]); */
//                 },                    
//             ],
// 		],
//         [
// 			'attribute' => 'Atraso',
// 			'header' => Academico::t("matriculacion", "Atraso"),
// 			'contentOptions' => ['name' => "txt_m_"],
//             'format' => 'html',
//             // 'template' => '{input}',
// 			'value' => function($model){
//                     // return '';            
//                    return 'echo "<td><input type="text" id="txt_m_'.$model['id'].'" class="form-control" value=""  name="txt_m_'.$model['id'].'"  spellcheck="false" data-ms-editor="true" style="border:1px !important"></td>"';            
//                 //     return Html::input("text", "txt_m_".$model['id'], '', ["class" => "form-control", "id" => "txt_m_".$model['id']]) ;+
//                     // return  Html::input("text", "txt_m_".$model['id'], "", ["class" => "form-control", "id" => "txt_m_".$model['id']]) ;
//                 },
            
// 		],
// 	],
// ])
-->
</div>


<!-- <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style=" margin-top: 10px;">
    <div class="spinner" style="display:none">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
    <div class="container" style="width:auto;">
        <table id="gridResumen" class="display compact responsive nowrap" style="width:100%">
            <thead id="html_thead">
              
                <tr>
                    <th rowspan="2"></th>
                    <th rowspan="2"></th>
                    <th rowspan="2">No.</th>
                    <th rowspan="2">Nombre</th>
                    <th rowspan="2">Matricula</th>
                    <th rowspan="2">Materia</th>
                    <th colspan="2">Asistencia</th>
                 
            
                </tr>
                <tr>
                    <th>P1</th>
                    <th>P2</th>
                    <th>paca_id</th>
                    <th>est_id</th>
                    <th>pro_id</th>
                    <th>asi_id</th>
                    <th>uaca_id</th>
                    <th>mod_id</th>
                    <th>daes_id</th>
                    <th>daho_total_horas</th>
                </tr>
            </thead>
        </table>
    </div> 
</div> -->

<!-- <script type="text/javascript">
        //envio de los datos
        /*
        function cargarArchivo(){
            console.log("cargarArchivo");
            //$("#frm_carga").on('submit', function(e){
            //e.preventDefault();
            var myUrl = "{{ url('ajax_archivo') }}";
            var formData = new FormData(document.getElementById('frm_carga'));

            $.ajax({
                type       : "POST",
                url        : myUrl,
                data       : formData,
                dataType   : 'json',
                cache      : false,
                contentType: false,
                processData: false
            }).done(function(resp){
                console.log(resp);
                if(resp.status == 'success') {
                    $.alert({
                        title:'Atención',
                        content: '<strong>Archivo Procesado</strong>',
                        icon: 'fa fa-check-circle-o',
                        theme: 'modern',
                        //closeIcon: true,
                        animation: 'scale',
                        type: 'green',
                    });
                }
                else {
                    $.alert({
                        title:'Atención',
                        content: '<strong>'+resp.message+'</strong>',
                        icon: 'fa fa-exclamation-circle',
                        theme: 'modern',
                        //closeIcon: true,
                        animation: 'scale',
                        type: 'orange',
                    });
                }

                $('#boton_cerrar').trigger('click');
            });
        };
        */
    $(document).ready(function () {
        //1 para indicarle que viene de document ready
        var editor;
        $('#cmb_profesor_asis').change();
        //actualizarGridRegistro(1);
        $('#gridResumen').DataTable({
            responsive: true,
            columnDefs: [   
                { targets: "no-sort", "orderable": false, "order": [1,3,2,4],},
                { targets: [ 1,3,2,4 ], responsivePriority: 1},     
                {
                    "targets": [ 8,9,10,11,12,13,14,15 ],
                    "visible": false,
                    "searchable": false
                },
            ],
        });
        //$('#cmb_profesor_asis').change();
        
    });
</script|> -->
 <div>
   
</div>