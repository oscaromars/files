<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

?>
<?= Html::hiddenInput('txth_pids', base64_decode($_GET['per_id']), ['id' => 'txth_pids']); ?>
<?= Html::hiddenInput('txth_perids', $_GET['per_id'], ['id' => 'txth_perids']); ?>
<?= Html::hiddenInput('txth_per_id', $per_id, ['id' => 'txth_per_id']); ?>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formIndex', [
            
            'arr_ninteres' => $arr_ninteres,   
            'arr_modalidad' => $arr_modalidad,
            'arr_carrerra1' => $arr_carrerra1,
            
        ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('index-grid', [
        'model' => $model,
    ]);
    ?>
</div> 