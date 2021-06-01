<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 500px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 15 KB máximo y tipo xlsx o csv, seprador  ","</div>
          </div>
          </div>
          </div>';
?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_form_Buscar', [   
            //'arrEstados' => $arrEstados
            'arr_empresa' => $arr_empresa,
            ]);
        ?>
    </form>
</div>