<?php

use app\modules\academico\Module as academico;
use yii\helpers\Html;
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <form class="form-horizontal">
        <?=
$this->render('index-search', [
	'arr_unidad' => $mod_unidad,
	'arr_modalidad' => $mod_modalidad,
	'arr_periodo' => $mod_periodo,
	'arr_materias' => $mod_materias,
	'arr_jornada' => $mod_jornada,
]);
?>
    </form>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><b> <?=academico::t("distributivoacademico", "Asignación Estudiantes")?> </b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <h4 style="center"> <?=academico::t("distributivoacademico", "Deseas asignar estudiantes a los docentes?")?> </h4>
          <p> <?=academico::t("distributivoacademico", "Escoger el periodo antes de asignar.")?> </p>
          <br>
          <div class="form-group">
              <label for="cmb_periodo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=Yii::t("formulario", "Period")?></label>
              <div class="col-sm-5 col-xs-5 col-md-5 col-lg-5">
                    <?=Html::dropDownList("cmb_periodo", 0, $periodo, ["class" => "form-control", "id" => "cmb_periodo"])?>
              </div>
          </div>
      </div>

      <div class="modal-footer">
        <a type="button" class="btn btn-primary" id="btn_asignar_estudiante" href="javascript:" data-dismiss="modal"> <?=Yii::t("formulario", "Asignar Estudiante")?> </a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <?=Yii::t("formulario", "Cancel")?> </button>
      </div>
    </div>
  </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?=
$this->render('index-grid', [
	'model' => $model,
	//'model_posgrado' => $model_posgrado,

]);
?>
</div>