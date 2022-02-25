<?php

//use Yii;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\widgets\PbGridView\PbGridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

admision::registerTranslations();
academico::registerTranslations();
?>

<?= Html::hiddenInput('txth_idperiodo', $arr_cabecera["paca_id"], ['id' => 'txth_idperiodo']); ?>
<?= Html::hiddenInput('txth_cabid',     $arr_cabecera["dcab_id"], ['id' => 'txth_cabid']); ?>
<?= Html::hiddenInput('txth_proid',     $arr_cabecera["pro_id"], ['id' => 'txth_proid']); ?>

<h3>Período Académico: <span id="lbl_etiqueta"><?= $arr_periodoActual['nombre'] ?></span></h3>
</br>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
                <div style = "width: 500px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Si al señalar paralelo no muestra horario, es porque no ha sido asignado.</div>
                </div>
            </div>
        </div>
     <input type="hidden" name="cmb_profesor" id="cmb_profesor" value="<?= $arr_cabecera["pro_id"] ?>"  />
     <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3><label id="lbl_profesor"><?= Yii::t("formulario", "Data Teacher") ?></label></h3>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_periodo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Period") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_periodo", $arr_cabecera["paca_id"],  $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo","disabled"=>"true"]) ?>
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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cmb_tipo_asignacion" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Tipo Asignación") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_tipo_asignacion", 0,  $arr_tipo_asignacion, ["class" => "form-control", "id" => "cmb_tipo_asignacion", "disabled" => "true"]) ?>
                </div>
                <div id="bloque1" style="display: none">
                    <label for="cmb_unidad_dis" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?= Html::dropDownList("cmb_unidad_dis", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_dis"]) ?>
                    </div>
                </div>
            </div>
        </div>
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bloque8" style="display: none">
            <div class="form-group">
                <label for="cmb_periodo_mensualizado" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Periodo Mensualizado")?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?=Html::dropDownList("cmb_periodo_mensualizado", 0, $arr_periodomensualizado, ["class" => "form-control", "id" => "cmb_periodo_mensualizado"])?>
                    </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bloque9" style="display: none">
            <div class="form-group">
                <label for="cmb_campo_amplio" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Campo Amplio")?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?=Html::dropDownList("cmb_campo_amplio", 0, $arr_areaconocimiento, ["class" => "form-control", "id" => "cmb_campo_amplio"])?>
                    </div>
            </div>
        </div>
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="bloque_h_otros" style="display: none" class="form-group">
                    <label for="txt_horas_otros" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("distributivoacademico", "Número Horas") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <input type="text" class="form-control keyupmce" value="" id="txt_horas_otros"  data-type="number" placeholder="<?= academico::t("distributivoacademico", "Número Horas") ?>">
                    </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div id="bloque2" style="display: none">
                    <label for="cmb_modalidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                    </div>
                </div>
                <div id="bloque6" style="display: none">
                    <label for="cmb_programa" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Career/Program") ?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?= Html::dropDownList("cmb_programa", 0,  $arr_programa, ["class" => "form-control", "id" => "cmb_programa"]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bloque3"  style="display: none" >
            <div class="form-group">
                <div id="bloque_j" style="display: none">
                <label for="cmb_jornada" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_jornada", 0, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornada"]) ?>
                </div>
                </div>
                <label for="cmb_materia" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Subject") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_materia",empty($arr_materias)?0:$arr_materias, ArrayHelper::map($arr_materias , "id", "name"), ["class" => "form-control", "id" => "cmb_materia", "name" => "cmb_materia[]", "multiple" => ""]) ?>
                </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="bloque4" style="display: none">
            <div class="form-group">
            <label for="cmb_paralelo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Paralelo") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_paralelo", 0, $arr_paralelo, ["class" => "form-control", "id" => "cmb_paralelo"]) ?>
                </div>
                <label for="cmb_horario" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Schedule") ?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_horario", 0, $arr_horario, ["class" => "form-control", "id" => "cmb_horario"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="bloque_n" style="display: none">
            <div class="form-group">
                <label for="txt_num_estudiantes" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("distributivoacademico", "Number of students") ?></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control keyupmce" value="" id="txt_num_estudiantes" data-type="number" placeholder="<?= academico::t("distributivoacademico", "Number of students") ?>">
                </div>
            </div>
        </div>
         <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12"  id="bloque7" style="display: none">
            <div class="form-group">
                <label for="lbl_inicio" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Start date") ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_ini',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_ini", "placeholder" => Yii::t("formulario", "Start date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
                <label for="lbl_fin" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "End date") ?></label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_fin',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_fin", "placeholder" => Yii::t("formulario", "End date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
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
  //  console.log('session', sessionStorage.dts_asignacion_list);
</script>