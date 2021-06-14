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
            'arr_unieduca' => $arr_unieduca,
            //'arr_jornada' => $mod_jornada,
            ]);
        ?>
    </form>
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