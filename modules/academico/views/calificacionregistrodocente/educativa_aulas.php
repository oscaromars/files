<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
//print_r($arr_periodoActual);
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Transferencia Calificaciones por Aula") ?></span></h3>
</div>
<p style="color:#aa0000">Actualmente los usuarios Educativa disponibles son online.</p>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formEducativa_aulas',[
            'arr_periodos' => $arr_periodos,
            'arr_unidad' => $arr_unidad,
            'arr_modalidad' => $arr_modalidad,
            'arr_aula' => $arr_aula,
            'arr_parcial' => $arr_parcial,
            'paca' => $paca, 
            'unidad' => $unidad, 
            'modalidad' => $modalidad, 
            'aula' => $aula , 
            'parcial' => $parcial, 
            
        ]);
        ?>
    </form>
</div>

  
 <div>
   
   <?=

    $this->render('aulas-grid',[
        'model' => $model,
    ]);
    ?>
</div>