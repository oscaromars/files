<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as aspirante;

aspirante::registerTranslations();
admision::registerTranslations();


?>
<?= Html::hiddenInput('txth_per_id', base64_encode($personalData['per_id']), ['id' => 'txth_per_id']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h4><span id="lbl_general"><?= admision::t("Solicitudes", "General Information") ?></span></h4> 
</div>


<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <form class="form-horizontal">
        <?=
        $this->render('_solicitud-search', [           
            'arrEstados' => $arrEstados,
            'arr_forma_pago' => $arr_forma_pago
            ]);
        ?>
    </form>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <?=
    $this->render('_solicitudalumnoGrid', [
        'model' => $model,
        'url' => $url,      
        ]);
    ?>
</div>

<script>
    var AccionTipo = 'Create';
</script>


