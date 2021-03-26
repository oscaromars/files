<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;

?>

<div>
    <form class="form-horizontal">
        <?=
        $this->render('_form_Buscarcartera', [   
            'arrEstados' => $arrEstados           
            ]);
        ?>
    </form>
</div>