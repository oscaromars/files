<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;
academico::registerTranslations();

if ($isreg) {

    echo "</br>";
    echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group'>";
    echo "<b style='color:green'>REGISTRO DE CALIFICACIONES ESTARÁ ABIERTO HASTA " . $isreg[0]["fin"] . "</b>";
    echo "</div>";
} Else {
    echo "</br>";
    echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group'>";
    echo "<b style='color:red'>EL PERIODO DE CALIFICACIONES ESTÁ CERRADO</b>";
    echo "</div>";
}

?>
<?= Html::hiddenInput('txth_proid', $pro_id, ['id' => 'txth_proid']); ?>
<input type="hidden" id="frm_arr_grupo" value="<?= $arr_grupos ?>">
<!--div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Qualifications Teacher Record") ?></span></h3>
</div></br></br></br-->
<div>
    <form class="form-horizontal">

        <?=
         

        $this->render('_formregister',[
            'arr_asignatura'  => $arr_asignatura,
            'arr_periodos' => $arr_periodos,
            'arr_ninteres' => $arr_ninteres,
            'arr_modalidad' => $arr_modalidad, 
            'arr_parcial' => $arr_parcial,
            'arr_profesor_all' => $arr_profesor_all,
            'thisperiodo'        => $thisperiodo,
            'thisunidad'        => $thisunidad,
            'thismodalidad'        => $thismodalidad,
            'thisprofesor'        => $thisprofesor,
            'thismateria'        => $thismateria,
            'thisparcial'        => $thisparcial,
        ]);
        ?>
    </form>
</div>
    <?=

    $this->render('register-grid',[
        'model' => $model,
        'arr_asignatura'  => $arr_asignatura,
        'componente' => $componente,
        //'isreg'  => $model['isreg'], 
        'campos' => $campos,
        'unidad' => $unidad,

    ]);
    ?> 
 <div>
   
</div>