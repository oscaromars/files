<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
//print_r($arr_periodoActual);
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Planificar Carga de Calificaciones Educativa") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formEducativa',[
            'arr_periodos' => $arr_periodos,
            'arr_unidad' => $arr_unidad,
            'arr_modalidad' => $arr_modalidad,
        ]);
        ?>
    </form>
</div>
<?php if ($model != Null): ?>

 <?=

    $this->render('educativa-grid',[
        'model' => $model,
        'modeldata' => $modeldata,
        'arr_periodos' => $arr_periodos,
        'modalidades' => $modalidades,

    ]);
    ?>

<?php else: ?>



<?php endif; ?>

  
 <div>
   
</div>