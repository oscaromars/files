<?php

//use Yii;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\widgets\PbGridView\PbGridView;

admision::registerTranslations();
academico::registerTranslations();
?>

<?= Html::hiddenInput('txth_idperiodo', $arr_periodoActual['id'], ['id' => 'txth_idperiodo']); ?>
<input type="hidden" name="txth_profid" id="txth_profid" value="<?=$arr_cabecera["pro_id"]?>">
<input type="hidden" name="txth_cabid" id="txth_cabid" value="<?=$arr_cabecera["dcab_id"]?>">

<h3>Período Académico: <span id="lbl_etiqueta"><?= $arr_periodoActual['nombre'] ?></span></h3>
</br>
<form class="form-horizontal">
    <input type="hidden" name="cmb_profesor" id="cmb_profesor" />
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3><label id="lbl_profesor"><?= Yii::t("formulario", "Data Teacher") ?></label></h3>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_periodo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Period") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['periodo'] ?>" id="txt_periodo" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "Period") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "DNI Document") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['nombres'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellidos" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellidos"><?= Yii::t("formulario", "Last Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_cabecera['apellidos'] ?>" id="txt_apellidos" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3><label id="lbl_profesor"><?= Yii::t("formulario", "Asignación") ?></label></h3>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cmb_tipo_asignacion" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Tipo Asignación") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tipo_asignacion", 0,  $arr_tipo_asignacion, ["class" => "form-control", "id" => "cmb_tipo_asignacion", "disabled" => "true"]) ?>
                </div>
                <div id="bloque1" style="display: none">
                    <label for="cmb_unidad_dis" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?= Html::dropDownList("cmb_unidad_dis", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_dis"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div id="bloque2" style="display: none">
                    <label for="cmb_modalidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                    </div>
                </div>
                <div id="bloque2-1" style="display: none">
                    <label for="cmb_periodo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?= Html::dropDownList("cmb_periodo", 0,  $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
                    </div>
                </div>
                <div id="bloque6" style="display: none">
                    <label for="cmb_programa" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Career/Program") ?></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?= Html::dropDownList("cmb_programa", 0,  $arr_programa, ["class" => "form-control", "id" => "cmb_programa"]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bloque3" style="display: none">
            <div class="form-group">
                <label for="cmb_jornada" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_jornada", 0, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornada"]) ?>
                </div>
                <label for="cmb_materia" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Subject") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_materia", 0,  $arr_materias, ["class" => "form-control", "id" => "cmb_materia"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="bloque4" style="display: none">
            <div class="form-group">
                <label for="cmb_horario" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Schedule") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_horario", 0, $arr_horario, ["class" => "form-control", "id" => "cmb_horario"]) ?>
                </div>
                <label for="cmb_paralelo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Paralelo") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_paralelo", 0, $arr_paralelo, ["class" => "form-control", "id" => "cmb_paralelo"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="bloque5" style="display: none">
            <div class="form-group">
                <label for="txt_num_estudiantes" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("distributivoacademico", "Number of students") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control keyupmce" value="" id="txt_num_estudiantes" data-type="number" placeholder="<?= academico::t("distributivoacademico", "Number of students") ?>">
                </div>

            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <button type="button" class="btn btn-primary" onclick="javascript:addAsignacion('new')"><?= Academico::t('profesor', 'Add') ?></button>
                </div>
            </div>
        </div>
    </div>

    <div>
        <?=
        $this->render('view-grid-edit', [
            'arr_detalle' => $arr_detalle,
        ]);
        ?>
    </div>

</form>

<script>
   

    document.getElementById("cmb_tipo_asignacion").disabled = false;
    sessionStorage.removeItem('dts_asignacion_list');

    let detalleAsignacionSinFormato = <?= json_encode($arr_detalle) ?>    

    let detalleAsignacionConFormato = new Array();
    detalleAsignacionSinFormato.allModels.map(function(a) {
        let asignacion = new Object();

        asignacion.indice = a.indice;
        asignacion.daca_id = a.Id;
        asignacion.tasi_id = a.idTipoAsignacion;
        asignacion.tasi_name = a.tipo_asignacion;
        asignacion.uni_id = a.idUnidadAcademica;
        asignacion.mod_id = a.idModalidad;
        asignacion.paca_id = a.idPeriodo;
        asignacion.jor_id = a.idJornada;
        asignacion.asi_id = a.idMateria;
        asignacion.hor_id = a.idHorario;
        asignacion.par_id = a.idParalelo;

        if (asignacion.tasi_id === 1) {
            asignacion.uni_name = a.UnidadAcademica;
            asignacion.mod_name = a.Modalidad;
            asignacion.jor_name = a.jornada;
            asignacion.asi_name = a.Asignatura;
            asignacion.hor_name = a.horario;
            asignacion.num_estudiantes = a.nroEstudiantes;
        } else {
            asignacion.uni_name = 'N/A';
            asignacion.mod_name = 'N/A';
            asignacion.jor_name = 'N/A';
            asignacion.asi_name = 'N/A';
            asignacion.hor_name = 'N/A';
            asignacion.num_estudiantes = 'N/A';
        }

        detalleAsignacionConFormato.push(asignacion);
    })

    sessionStorage.dts_asignacion_list = JSON.stringify(detalleAsignacionConFormato);

    console.log('session', sessionStorage.dts_asignacion_list);
</script>