<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\investigacion\models\LineaInvestigacion */

$this->title = 'Create Linea Investigacion';
$this->params['breadcrumbs'][] = ['label' => 'Linea Investigacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linea-investigacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
