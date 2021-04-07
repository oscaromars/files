<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Rol */

$this->title = 'Lista de paralelos';
$this->params['breadcrumbs'][] = ['label' => 'Semestre', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<h3 align="center" ><?= Html::encode($this->title) ?></h3>
<?=
GridView::widget([
    'dataProvider' => $data,
    'columns' => [
        // Simple columns defined by the data contained in $dataProvider.
        // Data from the model's column will be used.
        'dhpa_id',
        'dhpa_paralelo',
        'daho.daho_descripcion',
        'daho.daho_horario',
    // More complex one.
    ],
])
?>
