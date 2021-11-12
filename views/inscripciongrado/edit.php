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
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <div class="pull-right"><!-- Carga de Acciones -->
        <div class="btn-groups">
            <button type="button" class="btn btn-default btnAccion" data-trigger="hover" onclick="updateaspirantegrado()"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;&nbsp;Actualizar</button>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
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
