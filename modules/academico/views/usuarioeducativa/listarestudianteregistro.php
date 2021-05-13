<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('listarestudiantesregistro_search', [ 
            'arr_unidad' => $mod_unidad,
            'arr_modalidad' => $mod_modalidad,
            'arr_periodo' => $mod_periodo,
            'arr_asignatura' => $mod_asignatura,
            'arr_estado' => $mod_estado,
            'arr_curso' => $arr_curso,
            //'arr_jornada' => $mod_jornada,
            ]);
        ?>
    </form>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><b> <?= academico::t("cursoeducativa", "Insert Students") ?> </b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p> <?= academico::t("cursoeducativa", "Do you want to insert the students on Educative Course?") ?> </p>
      </div>
      <div class="modal-footer">
        <a type="button" class="btn btn-primary" id="insert_btn" href="javascript:" data-dismiss="modal"> <?= Yii::t("formulario", "Accept") ?> </a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <?= Yii::t("formulario", "Cancel") ?> </button>
      </div>
    </div>
  </div>
</div>

<!--
<button onclick="insertarEstudiantes()">Insertar Estudiantes</button>
-->

<div>
    <?=
    $this->render('_listarestudiantesregistrogrid', [
        'model' => $model,
        ]);
    ?>
</div>