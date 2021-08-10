
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

?>
<div class="rol-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_formupdateschedule', [
        'model' => $model,
        'paralelohorario' => $paralelohorario,
    ]) ?>

</div>

