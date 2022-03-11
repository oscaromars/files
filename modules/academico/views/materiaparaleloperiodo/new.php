<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */


?>
<h3><?= Html::encode($this->title) ?></h3>
<div>
    <form class="form-horizontal">
        <?php echo $this->render('_searchnew', 
                  [
                  'arr_periodo'    => $arr_periodo,
                  'arr_modalidad'  => $arr_modalidad,
                  'arr_unidad'     => $arr_unidad,
              ]); ?>

    </form>
</div>

<!--<div class="semestreacademico-create">
-->
<div>
    <?= $this->render('_form', [
        //'searchModel' => $searchModel,
        //'dataProvider' => $dataProvider,
        'model' => $model,
    ]) ?>

</div>