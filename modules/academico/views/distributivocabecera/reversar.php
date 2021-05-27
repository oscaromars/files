<?php

//use Yii;
use yii\helpers\Html;
use app\modules\academico\Module as academico;

?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('reversar-cab', [ 
            'arr_cabecera' => $arr_cabecera,     
            'arr_estado' => $arr_estado,
          ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('reversar-grid', [
        'arr_detalle' => $arr_detalle, 
        ]);
    ?>
</div>