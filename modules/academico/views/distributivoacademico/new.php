<?php

//use Yii;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

admision::registerTranslations();
academico::registerTranslations();
?>
<?=Html::hiddenInput('txth_tipo', 'new', ['id' => 'txth_periodoid']);?>
<?=Html::hiddenInput('txth_idperiodo', $arr_periodoActual['id'], ['id' => 'txth_idperiodo']);?>

</br>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?=Yii::t("formulario", "Fields with * are required")?> </p>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
                <div style = "width: 500px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Si al señalar paralelo no muestra horario, es porque no ha sido asignado.</div>
                </div>
            </div>
        </div>
     <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cmb_profesor" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Teacher")?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=Html::dropDownList("cmb_profesor", 0, $arr_profesor, ["class" => "form-control", "id" => "cmb_profesor"])?>
                </div>
                <div>
                    <label for="cmb_periodo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=Yii::t("formulario", "Period")?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?=Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"])?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="form-group">
                <label for="cmb_tipo_asignacion" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Tipo Asignación")?><span class="text-danger">*</span></label>
                <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=Html::dropDownList("cmb_tipo_asignacion", 0, $arr_tipo_asignacion, ["class" => "form-control", "id" => "cmb_tipo_asignacion", "disabled" => "true"])?>
                </div>
                <div id="bloque1" style="display: none">
                    <label for="cmb_unidad_dis" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=Yii::t("formulario", "Academic unit")?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?=Html::dropDownList("cmb_unidad_dis", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_dis"])?>
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
                    <label for="txt_horas_otros" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("distributivoacademico", "Número Horas")?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <input type="text" class="form-control keyupmce" value="" id="txt_horas_otros"  data-type="number" placeholder="<?=academico::t("distributivoacademico", "Número Horas")?>">
                    </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div id="bloque2" style="display: none">
                    <label for="cmb_modalidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=Yii::t("formulario", "Mode")?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?=Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"])?>
                    </div>
                </div>
                <div id="bloque6" style="display: none">
                    <label for="cmb_programa" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Career/Program")?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <?=Html::dropDownList("cmb_programa", 0, $arr_programa, ["class" => "form-control", "id" => "cmb_programa"])?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bloque3" style="display: none">
            <div class="form-group">
                <div class="row">
                    <div id="bloque_j" style="display: none">
                        <label for="cmb_jornada" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Working day")?><span class="text-danger">*</span></label>
                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                            <?=Html::dropDownList("cmb_jornada", 0, $arr_jornada, ["class" => "form-control", "id" => "cmb_jornada"])?>
                        </div>
                    </div>
                    <label for="cmb_materia" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=Yii::t("formulario", "Subject")?><span class="text-danger">*</span></label>
                    <div class="col-sm-4 col-xs-3 col-md-3 col-lg-3">
                        <?=Html::dropDownList("cmb_materia", empty($arr_materias) ? 0 : $arr_materias, ArrayHelper::map($arr_materias, "id", "name"), ["class" => "form-control", "id" => "cmb_materia", "name" => "cmb_materia[]", "multiple" => ""])?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="bloque4" style="display: none">
            <div class="form-group">
                <div class="row">
                    <div id="bloque_p" style="display: none">
                    <label for="cmb_paralelo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=Yii::t("formulario", "Paralelo")?><span class="text-danger">*</span></label>
                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                            <?=Html::dropDownList("cmb_paralelo", 0, $arr_paralelo, ["class" => "form-control", "id" => "cmb_paralelo"])?>
                        </div>
                        <label for="cmb_horario" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=Yii::t("formulario", "Schedule")?><span class="text-danger">*</span></label>
                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                            <?=Html::dropDownList("cmb_horario", 0, $arr_horario, ["class" => "form-control", "id" => "cmb_horario"])?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div id="bloque_n" style="display: none">
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" >
                <div class="form-group">
                    <label for="txt_num_estudiantes" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("distributivoacademico", "Number of students")?><span class="text-danger">*</span></label>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <input type="text" class="form-control keyupmce" value="" id="txt_num_estudiantes"  data-type="number" placeholder="<?=academico::t("distributivoacademico", "Number of students")?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12"  id="bloque7" style="display: none">
            <div class="form-group">
                <label for="lbl_inicio" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?=Yii::t("formulario", "Start date")?><span class="text-danger">*</span></label>
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
                <label for="lbl_fin" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?=Yii::t("formulario", "End date")?><span class="text-danger">*</span></label>
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
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <button type="button" class="btn btn-primary" onclick="javascript:addAsignacion('new')"><?=Academico::t('profesor', 'Add')?></button>
                </div>
            </div>
        </div>
     </div>
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="box-body table-responsive no-padding">
                <table  id="TbG_Data" class="table table-hover">
                    <thead>
                        <tr>
                            <th style="display:none; border:none;"><?=Yii::t("formulario", "Indice")?></th>
                            <th style="display:none; border:none;"><?=Yii::t("formulario", "Ids")?></th>
                            <th><?=academico::t("Academico", "Assignment Type")?></th>
                            <th><?=academico::t("Academico", "Subject")?></th>
                            <th><?=academico::t("Academico", "Academic unit")?></th>
                            <th><?=academico::t("Academico", "Area Conocimiento")?></th>
                            <th style="display:none; border:none;"></th>
                            <th><?=academico::t("Academico", "Modality")?></th>
                            <th><?=academico::t("distributivoacademico", "Number of students")?></th>
                            <th style="display:none; border:none;"></th>
                            <th><?=academico::t("Academico", "Working day")?></th>
                            <th><?=academico::t("Academico", "Schedule")?></th>
                            <th><?=academico::t("Academico", "Fecha Inicio Posgrado")?></th>
                            <th><?=academico::t("Academico", "Fecha Fin Posgrado")?></th>
                             <th><?=academico::t("Academico", "# Horas Otros")?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<script>
    sessionStorage.removeItem('dts_asignacion_list');
</script>