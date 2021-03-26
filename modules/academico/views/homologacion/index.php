<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

$this->registerJsFile("https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js",['depends' => [\yii\web\YiiAsset::className()]]);
$this->registerJsFile("https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js",['depends' => [\yii\web\YiiAsset::className()]]);

//$this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js",['depends' => [\yii\web\YiiAsset::className()]]);

//$session = Yii::$app->session; print_r($session);
?>

<link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css">

<div>

    <form class="form-horizontal">
        <?=
        $this->render('index-search', [                
            'per_id' => $per_id,      
            'usu_id' => $usu_id,
            'rol' => $rol,
            'cedula' => $cedula,
            ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('index-grid2', [
        'model' => $model,
        ]);
    ?>
</div>
<?= Html::hiddenInput('txth_doc_pago', '', ['id' => 'txth_doc_pago']); ?>
