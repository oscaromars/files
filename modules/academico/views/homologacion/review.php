<?php

//use Yii;
use yii\helpers\Html;
use app\modules\academico\Module as academico;

?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('review-cab', [ 
            'arr_cabecera' => $arr_cabecera,     
            'arr_estado' => $arr_estado,
          ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('review-grid', [
        'arr_detalle' => $arr_detalle, 
        ]);
    ?>
</div>