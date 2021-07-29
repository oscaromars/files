<?php

use yii\helpers\Html;
use app\modules\bienestar\Module as bienestar;
use yii\helpers\Url;
use kartik\tabs\TabsX;

?>

<div class="row">
    <div class="col-md-12">
        <?= 
            TabsX::widget([
                'items'=>$items,
                'position'=>TabsX::POS_LEFT,
                'bordered'=>true,
                'encodeLabels'=>false,
            ]);
        ?>
    </div>
</div>