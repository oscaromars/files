<?php
use yii\helpers\Html;

use app\modules\marketing\Module as marketing;

?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= marketing::t("marketing", "List") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [         
          //'arr_lista' => $arr_lista,
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
