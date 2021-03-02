<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('listarestudiantespagopos_search', [ 
            'arr_unidad' => $mod_unidad,
            'arr_modalidad' => $mod_modalidad,
            'arr_promocion' => $mod_promocion,
            'arr_asignatura' => $mod_asignatura,
            'arr_estado' => $mod_estado,
            'arr_paralelo' => $mod_paralelo,
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('_listarestudiantespagoposgrid', [
        'model' => $model,
        ]);
    ?>
</div>