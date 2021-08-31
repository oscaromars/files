<?php

//use Yii;
use yii\helpers\Html;
use app\modules\academico\Module as academico;

?>
<div>

    <form class="form-horizontal">
        <?=
        $this->render('review_search', [
          'arr_profesor'=> $arr_profesor,
          'arr_periodo' => $mod_periodo,
          'resCab' => $resCab,
          'arr_estado' => $arr_estado,
          ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('review-grid', [
        'arr_detalle' => $arr_detalle,
        'resCab' => $resCab,
        'promajustado' => $promajustado,
        ]);
    ?>
</div>