<?php

use yii\helpers\Html;
?>
<?=Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']);?>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3><span id="lbl_planear"> <i class='fas fa-user-tie'></i> <?=academico::t("matriculacion", "Registro Online - Administrativo")?></span></h3>
        </div><br><br>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4><span id="lbl_planear"><i class="far fa-address-book"></i> <?=academico::t("matriculacion", "Listado de Estudiante a matricular")?></span></h4>
        </div><br><br>
    </div>

<div>
    <form class="form-horizontal">
        <?=
$this->render('newfund-search', [
	'arr_periodo' => $mod_periodo,

]);
?>
    </form>
</div>

<div>
    <?=
$this->render('newfund-grid', [
	'model' => $model,
]);
?>
</div>