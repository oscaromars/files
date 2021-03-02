<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\widgets\PbSearchBox\PbSearchBox;
?>

<div class="row">
    <div class="col-md-6">
        <?= 
            PbSearchBox::widget([
                'boxId' => 'boxgrid',
                'type' => 'searchBox',
                'boxLabel' => Yii::t("accion","Search"),
                'placeHolder' => Yii::t("accion","Search").": ".Yii::t("formulario", "Names").", ".Yii::t("formulario", 'Last Names').", ".Yii::t("formulario", "Dni").", ".Yii::t("perfil", 'Email'),
                'controller' => '',
                'callbackListSource' => 'searchModules',
                'callbackListSourceParams' => ["'boxgrid'","'grid_personaext_list'"],
            ]);
        ?>
    </div>
</div>
<br />
<?=
    $this->render('index-grid', ['model' => $model, 'dataInteres' => $dataInteres]);
?>