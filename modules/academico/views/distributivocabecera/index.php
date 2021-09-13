<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [
            'arr_periodo' => $mod_periodo,
            'arrEstados' => $arrEstados,
            'arr_tipo_distributivo' => $mod_tipo_distributivo,
             'arr_profesor' => $arr_profesor,
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('index-grid', [
        'model' => $model,
        ]);
    ?>
</div>