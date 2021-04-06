<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Notas */

$this->title = 'Nuevo Periodo Academico';
$this->params['breadcrumbs'][] = ['label' => 'Periodo Academico', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notas-create">

    <h2 align="center"><?= Html::encode($this->title) ?></h2>
    
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>