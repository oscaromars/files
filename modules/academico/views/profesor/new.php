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
<br />
<?php
$this->registerJs(
    "loadSessionCampos('grid_instruccion_list', '', '', '');
    loadSessionCampos('grid_docencia_list', '', '', '');
    loadSessionCampos('grid_experiencia_list', '', '', '');
    loadSessionCampos('grid_idioma_list', '', '', '');
    loadSessionCampos('grid_investigacion_list', '', '', '');
    loadSessionCampos('grid_evento_list', '', '', '');
    loadSessionCampos('grid_conferencia_list', '', '', '');
    loadSessionCampos('grid_publicacion_list', '', '', '');
    loadSessionCampos('grid_coordinacion_list', '', '', '');
    loadSessionCampos('grid_evaluacion_list', '', '', '');
    loadSessionCampos('grid_referencia_list', '', '', '');",
    $this::POS_END);
?>