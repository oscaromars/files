<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
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
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?=
    $this->render('index-grid', [
        'model' => $model,
        'model_posgrado' => $model_posgrado,
        
        ]);
    ?>
</div>