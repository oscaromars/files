<?php

//use Yii;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
  
<div>
    <form class="form-horizontal">
        <?=
        $this->render('view-cab', [ 
            'arr_cabecera' => $arr_cabecera, 
            'arr_estados' => $arr_estados, 
            'arr_profesor'=>$arr_profesor,            
            'arr_unidad' => $arr_unidad,
            'arr_modalidad' => $arr_modalidad,
            'arr_periodo' => $arr_periodo,
            'arr_materias' => $arr_materias,
            'arr_jornada' => $arr_jornada,
            'arr_horario' => $arr_horario,
            'arr_tipo_asignacion' => $arr_tipo_asignacion,
            'arr_paralelo' => $arr_paralelo,            
            'arr_periodoActual' => $arr_periodoActual,
            'arr_programa'=>$arr_programa
          ]);
        ?>
    </form>
</div>
<div>
    <?=
    $this->render('view-grid', [
        'arr_detalle' => $arr_detalle, 
        ]);
    ?>
</div>

<input type="hidden" name="txth_cabid" id="txth_cabid" value="<?=$arr_cabecera["dcab_id"]?>">

