<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

$this->registerJsFile("https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js",['depends' => [\yii\web\YiiAsset::className()]]);
$this->registerJsFile("https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js",['depends' => [\yii\web\YiiAsset::className()]]);

$this->registerCssFile("https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css");
$this->registerCssFile("https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css");

?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "List Subscribers") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('index-search', [         
            'arr_ninteres' => $arr_ninteres,
            'arr_modalidad' => $arr_modalidad,
            'arr_carrerra1' => $arr_carrerra1]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('index-grid', [
        'model' => $model,
        'url' => $url]);
    ?>
</div>