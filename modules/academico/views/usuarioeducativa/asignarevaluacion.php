<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_asignarevaluacion-search', [ 
            'arr_modalidad' => $arr_modalidad,
            'arr_periodo' => $arr_periodo,
            'arr_aula' => $arr_aula,
            'arr_unidadeduc' => $arr_unidadeduc,
            'arr_evaluacion' => $arr_evaluacion
            ]);
        ?>
    </form>
</div>

<div>
    <?=
    $this->render('_asignarevaluacion-grid', [
        'model' => $model,
        ]);
    ?>
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
        <a type="button" class="btn btn-primary" id="insert_btn" href="javascript:" data-dismiss="modal">Aceptar</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>