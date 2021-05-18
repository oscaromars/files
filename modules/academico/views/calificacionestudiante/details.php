<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "List grades by period") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formdetails',[
            'arr_ninteres' => $arr_ninteres,
            'arr_modalidad' => $arr_modalidad,
            'arr_carrerra1' => $arr_carrera,
            'arr_periodoActual' => $arr_perioido,
            'arr_asignatura' => $arr_materias,
        ]);
        ?>
    </form>
</div>
    
 <div>
     <?=
    $this->render('details-grid', [
        'model' => $model,
        ]);
    ?>
   
</div>