<?php

//use Yii;
use yii\helpers\Html;
use app\modules\academico\Module as academico;

?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('view-cab', [
            'nombres' => $nombres,
            'matricula' => $matricula,
            'unidad' => $unidad,
            'modalidad' => $modalidad,
            'asignatura' => $asignatura,
            'periodo' => $periodo,
            'promedio_final' => $promedio_final,
            'programa' => $programa
          ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('view-grid', [
        'notas_estudiante' => $notas_estudiante,
        'supletorio' => $supletorio
        ]);
    ?>
</div>