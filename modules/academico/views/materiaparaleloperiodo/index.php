<?php

//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\academico\Module as academico;
use kartik\mpdf\Pdf;
use kartik\grid\EditableColumn;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
?>

<div>
    <form class="form-horizontal">
          <?php echo $this->render('_searchindex', 
                  [
                  'arr_periodo' => $arr_periodo,
                  'arr_modalidad' => $arr_modalidad,
                  'arr_unidad'=>$arr_unidad,
                  'arr_asignaturas'=>$arr_asignaturas,
                ]); ?>

    </form>
</div>
<div>
    <?=
    $this->render('index-grid', [
        'model' => $model,
        ]);
    ?>
</div>

