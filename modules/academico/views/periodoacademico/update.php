<?php

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

?>
<div class="rol-update">


    <?=$this->render('_form', [
	'model' => $model,
])?>

</div>
 <input type="hidden" id="frm_paca_id" value="<?=$model->paca_id?>">


