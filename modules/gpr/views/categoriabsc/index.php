<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

?>

<div class="row">
    <div class="col-md-6">
        <?= 
            PbSearchBox::widget([
                'boxId' => 'boxgrid',
                'type' => 'searchBox',
                'placeHolder' => Yii::t("accion","Search"),
                'controller' => '',
                'callbackListSource' => 'searchModules',
                'callbackListSourceParams' => ["'boxgrid'","'grid_bsc_list'"],
            ]);
        ?>
    </div>
</div>
<br />
<?=
    $this->render('index-grid', ['model' => $model,]);
?>