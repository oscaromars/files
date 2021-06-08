<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\academico\Module as academico;

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
          
  //print_r($pes_id); print_r($materias);
  
  //print_r($materias [0]['Subject']);
   // print_r($materias [0]['Code']);
    //  print_r($materias [0]['Cost']);
     //   print_r($materias [0]['Credit']);
     
?>

<div>
    <h3><?= Academico::t("matriculacion", "Registro de Becarios") ?></h3>
  
  <?php echo "<b style='color:red'>Usted tiene una beca con registro de materias activo, Para continuar presione GUARDAR </b>";?>
    <br> <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_periodo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Academic Period") ?>:</label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['pla_periodo_academico'] ?></span>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_estudiante" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Student") ?>:</label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['pes_nombres'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_id" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "ID/Passport") ?>: </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['pes_dni'] ?></span>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_unidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Academic Unit") ?>:</label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span> <?= $data_student['pes_jornada'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
    
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_modalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Modality") ?>:  </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span> <?= $data_student['mod_nombre'] ?></span>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_programa" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Program") ?>: </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['pes_carrera'] ?></span>
                    </div>
                </div>
            </div> 
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="lbl_telefono" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Academico::t("matriculacion", "Phone") ?>:  </label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <span><?= $data_student['per_celular'] ?></span>
                    </div>
                </div>
            </div>              
        </div>                                                             
</div>
 
<?=
    $this->render('indexsch-grid', ['planificacion' => $planificacion,]);
?>

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <div class="form-group">          
        <div class="">
            <table WIDTH="110%" class="tg">
                <tr>
                    <td colspan="2" class="tg-0pky"><span style="font-weight: bold"><?= Academico::t("matriculacion", "Information") ?>: </span></br>
                    <?= Academico::t("matriculacion", "Dear Student, if you have any observations with the planning the academic period please contact the secretariat of your faculty, at the following numbers") ?>:</br></td>
                </tr>
                <tr>
                    <td class="tg-0pky"><span style="font-weight: bold"><?= Academico::t("matriculacion", "Contact information") ?>:</span></br></td>
                </tr>            
                <tr>
                    <td class="tg-0pky"> 
                    <?= Yii::t("formulario", "Email") ?>: <?= Yii::$app->params["correoContacto"] ?> </br>
                    <?= Yii::t("formulario", "Phone") ?>:  <?= Yii::$app->params["telefonoContacto"] ?> </td>              
                </tr>
            </table>
        </div>    
    </div>
</div>
<input type="hidden" id="frm_notscholar" value="<?= $notscholar ?>">
<input type="hidden" id="frm_pes_id" value="<?= $pes_id ?>">
<input type="hidden" id="frm_num_min" value="<?= $num_min ?>">
<input type="hidden" id="frm_num_max" value="<?= $num_max ?>">
<input type="hidden" id="frm_modalidad" value="<?= $data_student['mod_nombre'] ?>">
<input type="hidden" id="frm_carrera" value="<?= $data_student['pes_carrera'] ?>">