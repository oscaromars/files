<?php

use app\modules\academico\Module as academico;
use yii\helpers\Url;

$leyenda = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
          <div class="form-group">
          <div style = "width: 1035px;" class="alert alert-info">
          <table WIDTH="110%" class="tg">
            <tr>
              <td colspan="2" class="tg-0pky"><span style="font-weight: bold"> Info: </span>Dear Student, if you have any observations with the
              planning the academic period please contact the secretariat of your faculty, at the following numbers:</br></td>
            </tr>
            <tr>
                <td class="tg-0pky"><span style="font-weight: bold">Contact information:</span></br></td>
            </tr>
            <tr>
              <td class="tg-0pky"> <span style="font-weight: bold"> </span>
                Email: administrative.assistant@mbtu.us</br>
                Phone: 7866426500</td>
            </tr>
          </table>
          </div>
          </div>
          </div>';

?>

<div>
    <h3><?=Academico::t("matriculacion", "Online Registration")?></h3>
    <br>





        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_telefono" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("matriculacion", "ESTADO")?>:  </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                         <span>Ya existe registro de materias de este estudiante, presione CONTINUAR</span>
                    </div>
                </div>
            </div>
        </div>
</div>




<input type="hidden" id="frm_per_id" value="<?=$per_id?>">
<input type="hidden" id="frm_pes_id" value="<?=$pes_id?>">
<input type="hidden" id="frm_num_min" value="<?=$num_min?>">
<input type="hidden" id="frm_num_max" value="<?=$num_max?>">
<input type="hidden" id="frm_modalidad" value="<?=$data_student['mod_nombre']?>">
<input type="hidden" id="frm_carrera" value="<?=$data_student['pes_carrera']?>">


<a href="<?=Url::to(['/academico/matriculacion/registro', 'uper_id' => $per_id])?>" class="btn btn-primary pull-right" style="margin: 0px 5px;"><?=Academico::t("matriculacion", "Continuar")?></a>

