<?php

use yii\helpers\Html;
use app\modules\marketing\Module as marketing;
?>
<?= Html::hiddenInput('txth_ids', base64_encode($arr_lista['lis_id']), ['id' => 'txth_ids']); ?>
<!--<? Html::hiddenInput('txth_lst', $arr_lista['lis_id'], ['id' => 'txth_lst']); ?>-->
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= marketing::t("marketing", "Subscriber Allocation") ?></span></h3>
</div>
<div class="clear">
    <br/><br/>
</div>    
<div>    
    <form class="form-horizontal">
        <?=
        $this->render('asignar-search', [
            'arr_estado' => $arr_estado,
            'arr_lista' =>$arr_lista,
            'noescritos' => $noescritos,
            'num_suscr' => $num_suscr,
            'num_suscr_chimp' => $num_suscr_chimp,
        ]);
        ?>    
    </form>
</div>
<div>    
    <?=
    $this->render('asignar_grid', [
        'model' => $model,
    ]);
    ?>    
</div>
