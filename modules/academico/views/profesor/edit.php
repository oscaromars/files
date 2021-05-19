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
<input type="hidden" id="frm_per_id" value="<?= $persona_model->per_id ?>">
<input type="hidden" id="frm_pro_id" value="<?= $pro_id ?>">
<?php

$this->registerJs(
    "loadSessionCampos('grid_instruccion_list', ".json_encode($storage_instruccion[0]).", ".json_encode($storage_instruccion[2]).", ".json_encode($storage_instruccion[1]).");
    loadSessionCampos('grid_docencia_list', ".json_encode($storage_docencia[0]).", ".json_encode($storage_docencia[2]).", ".json_encode($storage_docencia[1]).");
    loadSessionCampos('grid_experiencia_list', ".json_encode($storage_experiencia[0]).", ".json_encode($storage_experiencia[2]).", ".json_encode($storage_experiencia[1]).");
    loadSessionCampos('grid_idioma_list', ".json_encode($storage_idioma[0]).", ".json_encode($storage_idioma[2]).", ".json_encode($storage_idioma[1]).");
    loadSessionCampos('grid_investigacion_list', ".json_encode($storage_investigacion[0]).", ".json_encode($storage_investigacion[2]).", ".json_encode($storage_investigacion[1]).");
    loadSessionCampos('grid_evento_list', ".json_encode($storage_capacitacion[0]).", ".json_encode($storage_capacitacion[2]).", ".json_encode($storage_capacitacion[1]).");
    loadSessionCampos('grid_conferencia_list', ".json_encode($storage_conferencia[0]).", ".json_encode($storage_conferencia[2]).", ".json_encode($storage_conferencia[1]).");
    loadSessionCampos('grid_publicacion_list', ".json_encode($storage_publicacion[0]).", ".json_encode($storage_publicacion[2]).", ".json_encode($storage_publicacion[1]).");
    loadSessionCampos('grid_coordinacion_list', ".json_encode($storage_coordinacion[0]).", ".json_encode($storage_coordinacion[2]).", ".json_encode($storage_coordinacion[1]).");
    loadSessionCampos('grid_evaluacion_list', ".json_encode($storage_evaluacion[0]).", ".json_encode($storage_evaluacion[2]).", ".json_encode($storage_evaluacion[1]).");
    loadSessionCampos('grid_referencia_list', ".json_encode($storage_referencia[0]).", ".json_encode($storage_referencia[2]).", ".json_encode($storage_referencia[1]).");",
    $this::POS_END);
?>