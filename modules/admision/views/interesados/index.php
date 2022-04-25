<?php
use yii\helpers\Html;
use app\modules\academico\Module as aspirante;

aspirante::registerTranslations();
?>

<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= aspirante::t("Aspirante", "Aspirants") ?></span></h3>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [
            'arr_empresa' => $arr_empresa,
            'arr_unidad' => $arr_unidad]);
        ?>
    </form>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?=
        $this->render('index-grid', [
            'model' => $model,
            'url' => $url
          ]
        );
    ?>
</div>

