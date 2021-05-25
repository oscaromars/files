<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;

academico::registerTranslations();
financiero::registerTranslations();
?>
<?= Html::hiddenInput('txth_sins_id', base64_encode($sins_id), ['id' => 'txth_sins_id']); ?>
<?= Html::hiddenInput('txth_per_id', base64_encode($per_id), ['id' => 'txth_per_id']); ?>
<?= Html::hiddenInput('txth_int_id', base64_encode($int_id), ['id' => 'txth_int_id']); ?>
<?= Html::hiddenInput('txth_rsin_id', base64_encode($personaData["rsin_id"]), ['id' => 'txth_rsin_id']); ?>
<?= Html::hiddenInput('txth_emp_id', base64_encode($emp_id), ['id' => 'txth_emp_id']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= Yii::t("solicitud_ins", "See Request") ?></span></h3>
    </div>        
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_numsolicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_solicitud"><?= admision::t("Solicitudes", "Request #") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["num_solicitud"] ?>" id="txt_numsolicitud" disabled="true">                 
                </div>
            </div>
        </div>
    </div>   
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombres" class="col-sm-4 control-label" id="lbl_nombres"><?= Yii::t("formulario", "Names") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["per_nombres"] ?>" id="txt_nombres" disabled="true">                 
                </div>
            </div>
        </div>   

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_apellidos" class="col-sm-4 control-label" id="lbl_apellidos"><?= Yii::t("formulario", "Last Names") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["per_apellidos"] ?>" id="txt_apellidos" disabled="true">                 
                </div>
            </div>
        </div> 
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_correo" class="col-sm-4 control-label" id="lbl_nombres"><?= Yii::t("formulario", "Email") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["per_correo"] ?>" id="txt_nombres" disabled="true">                 
                </div>
            </div>
        </div>   

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-4 control-label" id="lbl_apellidos"><?= Yii::t("formulario", "CellPhone") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["per_celular"] ?>" id="txt_apellidos" disabled="true">                 
                </div>
            </div>
        </div> 
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nivelint" class="col-sm-4 control-label" id="lbl_unidad"><?= academico::t("Academico", "Academic unit") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $personaData["uaca_nombre"] ?>" id="txt_nivelint" disabled="true">                 
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_carrera" class="col-sm-4 control-label" id="lbl_carrera"><?= academico::t("Academico", "Career/Program/Course") ?></label> 
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="<?= $personaData["carrera"] ?>" id="txt_carrera" disabled="true">                 
                </div>
            </div>
        </div>    
    </div>  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_observasoli" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">                
                    <textarea  class="form-control keyupmce" id="txt_observasoli" disabled = "true" rows="3"><?= $personaData["sins_observacion_creasolicitud"] ?></textarea>                  
                </div>
            </div>   
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h4><b><span id="lbl_solicitud"><?= Yii::t("formulario", "Date") ?>:</span></b></h4>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_fecha_subio" class="col-sm-4 control-label" id="lbl_nombres"><?= financiero::t("Pagos", "Payment Upload") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $arr_fecha["fecha_subio"] ?>" id="txt_fecha_subio" disabled="true">                 
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_fecha_aprobado" class="col-sm-4 control-label" id="lbl_apellidos"><?= financiero::t("Pagos", "Payment approved") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $arr_fecha["fecha_aprobacion_pago"] ?>" id="txt_fecha_aprobado" disabled="true">                 
                </div>
            </div>
        </div> 
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_fecha_admitido" class="col-sm-4 control-label" id="lbl_nombres"><?= academico::t("Academico", "Admitted") ?></label> 
                <div class="col-sm-8 ">
                    <input type="text" class="form-control" value="<?= $arr_fecha["fecha_admitido"] ?>" id="txt_fecha_admitido" disabled="true">                 
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_observa" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">                
                    <textarea  class="form-control keyupmce" id="txt_observa" disabled = "true" rows="3"><?= $arr_fecha["icpr_observacion"] ?></textarea>                  
                </div>
            </div>   
        </div> 
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h4><b><span id="lbl_Personeria"><?= Yii::t("formulario", "Attached Files") ?></span></b></h4>    
    </div>
    <?php if ($personaData["uaca_id"] < 3) { ?>   
        <div class="col-md-6 doc_titulo cinteres">
            <div class="form-group">
                <label for="txth_doc_titulo" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Title") ?></label>
                <div class="col-sm-7 ">  
                    <?php
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch1"]) . "' download='" . $arch1 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6  doc_dni cinteres">
            <div class="form-group">
                <label for="txth_doc_dni" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "DNI") ?></label>
                <div class="col-sm-7 ">                
                    <?php
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch2"]) . "' download='" . $arch2 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                    ?>
                </div>
            </div>
        </div>        

        <div class="col-md-6  doc_foto cinteres">
            <div class="form-group">
                <label for="txth_doc_foto" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Photo") ?></label>
                <div class="col-sm-7 ">                
                    <?php
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch4"]) . "' download='" . $arch4 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                    ?>
                </div>
            </div>
        </div>        

        <?php if (($txth_extranjero == "1") or ( empty($txth_extranjero))) { ?>
            <div class="col-md-6  doc_certvota cinteres">
                <div class="form-group">
                    <label for="txth_doc_certvota" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Voting Certificate") ?></label>
                    <div class="col-sm-7 ">                
                        <?php
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch3"]) . "' download='" . $arch3 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($personaData["uaca_id"] == 2) { ?>   
            <div class="col-md-6  doc_foto cinteres">
                <div class="form-group">
                    <label for="txth_doc_certificado" class="col-sm-4  control-label keyupmce"><?= Yii::t("formulario", "Materials Certificate") ?></label>
                    <div class="col-sm-7 ">                
                        <?php
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch6"]) . "' download='" . $arch6 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                        ?>
                    </div>
                </div>
            </div>        

            <div class="col-md-6  doc_certvota cinteres">
                <div class="form-group">
                    <label for="txth_doc_hojavida" class="col-sm-4 control-label keyupmce"><?= Yii::t("formulario", "Curriculum") ?></label>
                    <div class="col-sm-7 ">                
                        <?php
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch7"]) . "' download='" . $arch7 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (!empty($arch5)) { ?>
            <div class="col-md-6  doc_beca cinteres">
                <div class="form-group">
                    <label for="txth_doc_beca" class="col-sm-4 control-label keyupmce"><?= admision::t("Solicitudes", "Scholarship document") ?></label>
                    <div class="col-sm-7 ">                
                        <?php
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch5"]) . "' download='" . $arch5 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>  
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6  doc_foto cinteres">
        <div class="form-group">
            <label for="txth_doc_pago" class="col-sm-4 control-label keyupmce"><?= financiero::t("Pagos", "Payment") ?></label>
            <div class="col-sm-7 ">                
                <?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => "/uploads/documento/" . $per_id . "/" . $img_pago]) . "' download='" . $img_pago . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pago</a>"
                ?>
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <label for="txt_observa" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">                
            <textarea  class="form-control keyupmce" id="txt_observa" disabled = "true" rows="3"><?= $arr_observa['sdoc_observacion'] ?></textarea>                  
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h4><b><span id="lbl_solicitud"><?= admision::t("Solicitudes", "Result Review") ?></span></b></h4>
    </div> 

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_revision" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Result") ?></label>
                <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">

                    <?php if ($personaData["rsin_id"] != 2) { ?> 
                        <?= Html::dropDownList("cmb_revision", 0, $revision, ["class" => "form-control PBvalidation", "id" => "cmb_revision"]) ?> 
                    <?php } else { ?>                
                        <?= Html::dropDownList("cmb_revision", $personaData["rsin_id"], $revision, ["class" => "form-control PBvalidation", "id" => "cmb_revision", "disabled" => "true"]) ?> 
                    <?php } ?>      

                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <label for="txt_observarevi" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">                
                <?php if ($personaData["rsin_id"] != 2) { ?> 
                    <textarea  class="form-control keyupmce" id="txt_observarevi" rows="3"><?= $personaData["sins_observacion_revisa"] ?></textarea>                  
                <?php } else { ?>  
                    <textarea  class="form-control keyupmce" id="txt_observarevi" disabled = "true" rows="3"><?= $personaData["sins_observacion_revisa"] ?></textarea>                  
                <?php } ?>   
            </div>
        </div>
  
    <?php //if (empty($personaData["sins_fecha_reprobacion"])) { ?> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="Divnoaprobado" style="display: none;"> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="chk_titulo" class="col-sm-10 col-md-10 col-xs-10 col-lg-10 control-label"><?= admision::t("Solicitudes", "Does not meet acceptance conditions in title") ?></label>
                <div class="col-sm-1 ">                     
                    <input type="checkbox" class="" id="chk_titulo"  data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("solicitud_ins", "Does not meet acceptance conditions in title") ?>">                      
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="chk_documento" class="col-sm-10 col-md-10 col-xs-10 col-lg-10 control-label"><?= admision::t("Solicitudes", "Does not meet acceptance conditions on identity document") ?></label>
                <div class="col-sm-1 ">                     
                    <input type="checkbox" class="" id="chk_documento" data-type="alfa" data-keydown="true" placeholder="<?= admision::t("Solicitudes", "Does not meet acceptance conditions on identity document") ?>">  
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" id="Divcondtitulo" style="visibility: hidden;" >
            <div class="form-group">               
                <?php
                for ($i = 0; $i < count($arr_condtitulo); $i++) {
                    $chk_contitulo = "chk_contitulo" . $i;
                    ?>  
                    <p for="<?= $chk_contitulo ?>" class="col-sm-10 col-md-10 col-xs-10 col-lg-10 control-label"><?php echo $arr_condtitulo[$i]['name'] ?></p>
                    <div class="col-sm-1 ">    
                        <?= Html::hiddenInput('txth_cond_titulo' . $i, $arr_condtitulo[$i]['id'], ['id' => 'txth_cond_titulo' . $i]); ?>
                        <input type="checkbox" class="" id="<?= $chk_contitulo ?>" data-type="alfa" data-keydown="true" placeholder="<?= $arr_condtitulo[$i]['name'] ?>">  
                    </div>
                <?php } ?>   
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" id="Divconddni"  style="visibility: hidden;" >
            <div class="form-group">            
                <?php
                for ($j = 0; $j < count($arr_conddni); $j++) {
                    $chk_conddni = "chk_conddni" . $j;
                    ?>  
                    <p for="<?= $chk_conddni ?>" class="col-sm-10  col-md-10 col-xs-10 col-lg-10 control-label"><?php echo $arr_conddni[$j]['name'] ?></p>
                    <div class="col-sm-1 ">    
                        <?= Html::hiddenInput('txth_cond_dni' . $j, $arr_conddni[$j]['id'], ['id' => 'txth_cond_dni' . $j]); ?>
                        <input type="checkbox" class="" id="<?= $chk_conddni ?>" data-type="alfa" data-keydown="true" placeholder="<?= $arr_conddni[$j]['name'] ?>">  
                    </div>
                <?php } ?>      
            </div>
        </div>            
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="chk_certificado" class="col-sm-10 col-md-10 col-xs-10 col-lg-10 control-label"><?= admision::t("Solicitudes", "Does not meet acceptance conditions in voting certificate") ?></label>
                    <div class="col-sm-1 ">                     
                        <input type="checkbox" class="" id="chk_certificado"  data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("solicitud_ins", "Does not meet acceptance conditions in title") ?>">                      
                    </div>
                </div>
                <div class="col-md-13 col-sm-13 col-xs-13 col-lg-13" id="Divcondcerti" style="visibility: hidden;" >
                    <div class="form-group">               
                        <?php
                        for ($i = 0; $i < count($arr_certv); $i++) {
                            $chk_concerti = "chk_concerti" . $i;
                            ?>  
                            <p for="<?= $chk_concerti ?>" class="col-sm-10 col-md-10 col-xs-10 col-lg-10 control-label"><?php echo $arr_certv[$i]['name'] ?></p>
                            <div class="col-sm-1 ">    
                                <?= Html::hiddenInput('txth_cond_certi' . $i, $arr_certv[$i]['id'], ['id' => 'txth_cond_certi' . $i]); ?>
                                <input type="checkbox" class="" id="<?= $chk_concerti ?>" data-type="alfa" data-keydown="true" placeholder="<?= $arr_certv[$i]['name'] ?>">  
                            </div>
                        <?php } ?>   
                    </div>
                </div>     
            </div>
        </div>  
    </div>  

    <?php
    //} else {
    if (!empty($resp_rechazo) && $personaData["rsin_id"] != 2) {
        $obs_condicion = "";
        ?>        
        <?php
        for ($r = 0; $r < count($resp_rechazo); $r++) {
            if ($obs_condicion <> $resp_rechazo[$r]['observacion']) {
                $obs_condicion = $resp_rechazo[$r]['observacion'];
                if ($r == 0) {
                    $obs_correo = $obs_correo . "<b>&nbsp;&nbsp;&nbsp;" . $obs_condicion . "</b><br/>" . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No " . $resp_rechazo[$r]['condicion'] . "&nbsp;&nbsp;&nbsp;";
                } else {
                    $obs_correo = $obs_correo . "</br><b>&nbsp;&nbsp;&nbsp;" . $obs_condicion . "</b><br/>" . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No " . $resp_rechazo[$r]['condicion'] . "&nbsp;&nbsp;&nbsp;";
                }
            } else {
                $obs_correo = $obs_correo . "<br/>" . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No " . $resp_rechazo[$r]['condicion'] . "&nbsp;&nbsp;&nbsp;";
            }
        }
        ?>

        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <label for="" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Observations") ?></label>            
            </div> 
            <div style="height:30px">

            </div>
            <?php
            $leyenda = '      
          <div style = "width: 530px;" class="alert alert-info"><span style="font-weight"> </span> '
                    . $obs_correo .
                    '</div>';
            echo $leyenda;
            ?>                   
        <?php } ?>    
    </div> 

</form>
