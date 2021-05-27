<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "List Student Grades") ?></span></h3>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formIndex',[
            'arr_asignatura'  => $arr_asignatura,
            'arr_periodoActual' => $arr_periodoActual,
            'arr_ninteres' => $arr_ninteres,
            'arr_modalidad' => $arr_modalidad,
            'arr_profesor_all'=>$arr_profesor_all,
            'arr_paralelo_clcf' => $arr_paralelo_clcf,
        ]);
        ?>
    </form>
</div>
    <?=

    $this->render('index-grid',[
        'model' => $model
    ]);
    ?>
 <div>
   
</div>