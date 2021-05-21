<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_proid', $pro_id, ['id' => 'txth_proid']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Registro de Asistencia") ?></span></h3>
</div></br></br></br>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formregister',[
            'arr_asignatura'  => $arr_asignatura,
            'arr_periodoActual' => $arr_periodoActual,
            'arr_ninteres' => $arr_ninteres,
            'arr_modalidad' => $arr_modalidad, 
            'arr_parcial' => $arr_parcial,  
            'arr_profesor_all' => $arr_profesor_all,      
        ]);
        ?>
    </form>
</div>
    <?=

    $this->render('register-grid',[
        'model' => $model,
        'arr_asignatura'  => $arr_asignatura,
        'componente' => $componente,
        'campos' => $campos,
        'unidad' => $unidad,

    ]);
    ?> 
 <div>
   
</div>