<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\academico\Module as academico;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    console.log("per_id: <?= $per_id; ?>");
    console.log("usu_id: <?= $usu_id; ?>");
    console.log("rol: <?= $rol; ?>");
    console.log("cedula: <?= $cedula; ?>");

    document.getElementById("input_cedula").value("<?= $cedula; ?>");
</script>
<style type="text/css">

    @media only screen and (min-width: 768px) {
        .checkonline{
            display: inline-flex;
            justify-content: flex-end;
        }
    }
</style>
<input type="hidden" value="<?= $cedula; ?>" id="cedula/>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label" for="txt_buscarData" ><?= Yii::t("homologacion", "Dni") ?></label>
                <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <input class="form-control" type="text" value="" id="input_cedula" placeholder="<?= Yii::t("homologacion", "Search by Dni") ?>">
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 checkonline">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="flexCheckDefault">
                  <label class="form-check-label" for="flexCheckDefault">&nbsp;&nbsp; Estudiante Online</label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <a id="btn_cedula" href="javascript:traer_estudiante()" class="btn btn-primary" style="width: 100%">
                        <i class="fa fa-search" aria-hidden="true"></i>&nbsp;<?= Yii::t("formulario", "Search") ?>
                    </a> 
                </div>
                <?php if($rol!=37){  ?>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <a id="btn_iniciar" href="javascript:iniciarHomologacion()" class="btn btn-success" style="width: 100%">
                            <i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;
                            <?= Yii::t("formulario", "Iniciar Reconocimiento") ?>
                        </a> 
                    </div>
                <?php } ?>
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4  control-label" for="txt_buscarData"><?= Yii::t("homologacion", "Student") ?></label>
                <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_estudiante" readonly/>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label" for="txt_buscarData" ><?= Yii::t("homologacion", "School period") ?></label>
                <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <?= Html::dropDownList("sel_periodo", 0, array('0'=>'Elija un Periodo'), ["class" => "form-control", "id" => "sel_periodo"]) ?> 
                    <!--select type="text" class="form-control" value="" id="sel_periodo"></select-->
                </div>
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label" for="txt_buscarData" ><?= Yii::t("homologacion", "Previous flow") ?></label>
                <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <select type="text" class="form-control" value="" id="sel_pflow"></select>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label" for="txt_buscarData" ><?= Yii::t("homologacion", "New Flow") ?></label>
                <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <select type="text" class="form-control" value="" id="sel_nflow"></select>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label class="col-xs-10 col-sm-10 col-md-3 col-lg-3 control-label" for="txt_buscarData" >
                    <?= Yii::t("homologacion", "Materias Aprobadas Vieja Malla") ?><br>
                </label>
                <div   class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
                    <input type="text" class="form-control" value="" id="input_aprobadas" readonly/>
                </div>
                <!--label class="col-xs-10 col-sm-10 col-md-2 col-lg-2 control-label" for="txt_buscarData" >
                    <?= Yii::t("homologacion", "Materias por Aprobar") ?><br>
                    <?= Yii::t("homologacion", "(Nueva Malla)") ?>          
                </label>
                <div   class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
                    <input type="text" class="form-control" value="" id="txt_test" readonly/>
                </div-->
                <label class="col-xs-10 col-sm-10 col-md-3 col-lg-3  control-label" for="txt_buscarData" >
                    <?= Yii::t("homologacion", "Numero de Materias Cursando") ?>           
                </label>
                <div   class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
                    <input type="text" class="form-control" value="" id="input_cursando" readonly/>
                </div>
                <label class="col-xs-10 col-sm-10 col-md-3 col-lg-3  control-label" for="txt_buscarData" >
                    <?= Yii::t("homologacion", "Descargar Malla") ?>           
                </label>
                <div   class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
                    <!--input type="button" class="form-control" value="Malla" id="txt_estudiante" readonly/-->
                    <a id="txt_estudiante" href="javascript:" class="btn btn-primary">
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>        
</div>

