<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use kartik\tabs\TabsX;

?>

<div class="row">
    <div class="col-md-12">
        <?= 
            TabsX::widget([
                'items'=>$items,
                'position'=>TabsX::POS_LEFT,
                'encodeLabels'=>false
            ]);
        ?>
    </div>
</div>
<input type="hidden" id="frm_per_id" value="<?= $persona_model->per_id ?>">
<input type="hidden" id="frm_pcon_id" value="<?= $contacto_model->pcon_id ?>">
<input type="hidden" id="frm_pro_id" value="<?= $pro_id ?>">