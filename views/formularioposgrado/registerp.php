<style>
    body {
        width: 100%;
        font-family: Arial;
        font-size: 8pt;
        margin: 0;
        padding: 0;
    }

    .marcoDiv {
        border: 1px solid #165480;
        padding: 2mm;
    }

    .marcoCel {
        border: 1px solid #0000;
        padding: 1mm;
    }

     .marcoCel1 {
        border: 1px solid #0000;
        padding: 1mm;
        width: 25%;
        font-size: 10pt;
    }

    .marcoCel3 {
        border: 0px solid #0000;
        word-spacing:2px;
        letter-spacing:0.7px;
        padding: 1mm;
        width: 25%;
        color:#000011;
         font-size: 10pt;
    }
    .bold {
        font-weight: bold;
    }

    .normal {
        border: 1px solid #000;
        border-collapse: collapse;
    }

     .abnormal {
        border: 0px solid #000;
        border-collapse: collapse;
    }

    .normal th,
    .normal td {
        border: 1px solid #000;
    }


    .divTabla {
        display: table;
        width: 100%;
        /*background-color:#eee;
        border:1px solid  #666666;*/
        border: 1px solid #0000;
        padding: 2mm;
        border-spacing: 5px;
        /*cellspacing:poor IE support for  this*/
        /* border-collapse:separate;*/
    }

    .divTable {
        display: table;
        width: 100%;
        /*background-color:#eee;
        border:1px solid  #666666;*/
        /*border:1px solid  #0000;
        padding: 2mm;*/
        border-spacing: 5px;
        /*cellspacing:poor IE support for  this*/
        /* border-collapse:separate;*/
    }

    .fila {
        border-bottom: 1px;
        border-width: 1px;
        border-bottom-style: solid;

    }

    .divRow {
        display: table-row;
        width: auto;
    }

    .divCell {
        float: left;
        /*fix for  buggy browsers*/
        display: table-column;
        /*width:200px;*/
        border: 1px solid #0000;
        /*background-color:#ccc;*/
        padding: 2mm;
    }

    .divCelda {
        float: left;
        /*fix for  buggy browsers*/
        display: table-column;
        border: 1px solid #0000;
        background-color: #ccc;
        padding: 2mm;
    }

     .divCeldag {
        float: left;
        /*fix for  buggy browsers*/
        display: table-column;
        border: 0px solid #0000;
        background-color: #ccc;
        padding: 2mm;
        position:absolute;
        left:0px;
        width:100%;
    }


     .divCeldap {
        float: left;
        /*fix for  buggy browsers*/
        display: table-column;
        border: 0px solid #0000;
        background-color: #ccc;
        padding: 0mm;
        position:absolute;
        left:0px;
        height:7px;
        width:100%;
    }

    .tabDetalle {
        border-spacing: 1;
        border-collapse: collapse;
    }

    .titleDetalle {
        text-align: center;
    }

     .credpic {
        height: 220px;
        position:relative;
        top: -25px;
    }
</style>
   <br><br><br>
     <br><br><br>
     <br><br><br><br>
    <br><br> <br><br>

<div style="text-justify: auto">
    <p style='font-family:helvetica'>
<b>FECHA DE REGISTRO:</b>  &nbsp;<?php echo $persona_model['registro'] ?>
        </p>
    </div>
   <br>
<div class="divCeldap" style="text-align: center">
    </div> 
<br>
 <div style="text-justify: auto">
        <p>
Por favor aseg??rese de contestar correctamente todas las cuestiones planteadas. No se admitir?? ninguna solicitud que no est??
completamente firmada y acompa??ada de todos los documentos solicitados.
        </p>
    </div>
   <br><br>
   <div  style="text-justify: auto">
        <p><h3>
    <b>PROGRAMA DE:</b>&nbsp;<?php echo $persona_model['programa'] ?><br><br>
    <b>MODALIDAD:</b>&nbsp;<?php echo $persona_model['modalidad'] ?><br><br>
    <b>A??O:</b>&nbsp;<?php echo $persona_model['anio'] ?></h3>
  </p>
    </div>
<br>
<div class="divCeldag bold titleDetalle " style="text-align: center">

1. DATOS PERSONALES

    </div>
<br><br>
 <table class="abnormal">
        <tbody>
<tr>
                <td class="marcoCel1 bold" >
                CI / PASAPORTE:<br><br>
                NOMBRES:<br><br>
                APELLIDOS:<br><br>
                LUGAR DE NACIMIENTO:<br><br>
                FECHA DE NACIMIENTO:<br><br>
                NACIONALIDAD:<br><br>
                ESTADO CIVIL:<br><br>
                PROVINCIA:<br><br>
                CANTON:<br>
                </td>
                <td class="marcoCel3" style='font-family:helvetica'>
                &nbsp;<?php if ($persona_model['cedula'] != Null) {
                   echo $persona_model['cedula'];  } else {echo $persona_model['pasaporte'];  }            
                 ?> 
                 <br><br>
                &nbsp;<?php echo $persona_model['nombres'] ?><br><br>
                &nbsp;<?php echo $persona_model['apellidos'] ?><br><br>
                &nbsp;<?php echo $persona_model['pai_nombre'] ?><br><br>
                &nbsp;<?php echo $persona_model['per_fecha_nacimiento'] ?><br><br>
                &nbsp;<?php echo $persona_model['pai_nacionalidad'] ?><br><br>
                &nbsp;<?php echo $persona_model['eciv_nombre'] ?><br><br>
                &nbsp;<?php echo $persona_model['pro_nombre'] ?><br><br>
                &nbsp;<?php echo $persona_model['can_nombre'] ?><br>
                </td>
                <td class="marcoCel3">
                </td>
                  <td class="marcoCel3">
                     <?php echo yii\helpers\Html::img(
                                    Yii::$app->basePath . $persona_model['ipos_ruta_doc_foto'] , 
                                    array("class" => "credpic", "alt" => Yii::$app->params["copyright"])); ?>
                </td>
            </tr>

        </tbody>
    </table>
    <br><br>
<div class="divCeldag bold titleDetalle " style="text-align: center;">

2. DATOS DE CONTACTO

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>DIRECCI??N DOMICILIARIA:</b> &nbsp;<?php echo $persona_model['domicilio'] ?><br><br>
      <b>CELULAR:</b> &nbsp;<?php echo $persona_model['per_celular'] ?><br><br>
      <b>TEL??FONO ADICIONAL:</b> &nbsp;<?php echo $persona_model['per_domicilio_telefono'] ?><br><br>
    <b>CORREO ELECTR??NICO:</b>&nbsp;<?php echo $persona_model['per_correo'] ?><br><br>
  </p>
    </div>

<div class="divCeldag bold titleDetalle " style="text-align: center">

3. DATOS EN CASO DE EMERGENCIA

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>PERSONA POR CONTACTAR EN CASO DE EMERGENCIA:</b> &nbsp;<?php echo $persona_model['pcon_nombre'] ?><br><br>
     <b>TIPO DE PARENTESCO:</b>&nbsp;<?php echo $persona_model['tpar_nombre'] ?><br><br>
   <b>TEL??FONO DE LA PERSONA A CONTACTAR EN CASO DE EMERGENCIA:</b>&nbsp;<?php echo $persona_model['pcon_celular'] ?><br><br>


  </p>
    </div>
<br>
<div class="divCeldag bold titleDetalle " style="text-align: center">

    4. FORMACION PROFESIONAL 

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>T??TULO DE TERCER NIVEL:</b> &nbsp;<?php echo $persona_model['eins_titulo3ernivel'] ?><br><br>
      <b>UNIVERSIDAD:</b> &nbsp;<?php echo $persona_model['eins_institucion3ernivel'] ?><br><br>
     <b>A??O DE GRADUACI??N:</b> &nbsp;<?php echo $persona_model['eins_aniogrado3ernivel'] ?><br><br>   
        <p class="blue" style='text-align:left'>__________________________________</p>
<br>
   <b>T??TULO DE CUARTO NIVEL:</b> &nbsp;<?php echo $persona_model['eins_titulo4tonivel'] ?><br><br>
    <b>UNIVERSIDAD:</b> &nbsp;<?php echo $persona_model['eins_institucion4tonivel'] ?><br><br>
  <b> A??O DE GRADUACI??N:</b> &nbsp;<?php echo $persona_model['eins_aniogrado4tonivel'] ?><br><br>
  </p>
    </div>
    <pagebreak />
    <div class="divCeldag bold titleDetalle " style="text-align: center">
    5. DATOS LABORALES 

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>EMPRESA:</b> &nbsp;<?php echo $persona_model['ilab_empresa'] ?><br><br>
      <b>CARGO:</b> &nbsp;<?php echo $persona_model['ilab_cargo'] ?><br><br>
     <b>TEL??FONOS</b> &nbsp;<?php echo $persona_model['ilab_telefono_emp'] ?><br><br>
   <b>DIRECCI??N</b> &nbsp;<?php echo $persona_model['dirempresa'] ?><br>
   (provincia, cant??n, parroquia, direcci??n exacta)<br><br>
    <b>A??O DE INGRESO:</b> &nbsp;<?php echo $persona_model['ilab_anioingreso_emp'] ?><br><br>
    <b> CORREO ELECTR??NICO:</b> &nbsp;<?php echo $persona_model['ilab_correo_emp'] ?><br><br>
      <b> CATEGOR??A OCUPACIONAL:</b> &nbsp;<?php 

    if ($persona_model['ilab_cat_ocupacional'] == 1 ){
      echo "Empresa P??blica"; 
    }
     if ($persona_model['ilab_cat_ocupacional'] == 2 ){
      echo "Empresa Privada"; 
    }



  ?><br><br>
  </p>
    </div>
    <br>
  <div class="divCeldag bold titleDetalle " style="text-align: center">

    6. HABILIDADES EN EL LENGUAJE 

    </div>
    <br><br>
 <table class="abnormal">
        <tbody>
<tr>
        <?php if (isset($persona_model['idi1_nombre'])) { ?>
              <td class="marcoCel"> 
  <b>IDIOMA:</b> &nbsp;<?php echo $persona_model['idi1_nombre'] ?><br><br>
              </td>
               <?php } ?>   
      <?php if (isset($persona_model['idi_nombre'])) { ?>
                <td class="marcoCel"> 
<b>IDIOMA:</b> &nbsp;<?php echo $persona_model['idi_nombre'] ?><br><br>
                </td>
                  <?php } ?>  
            </tr>
                  
<tr>
        <?php if (isset($persona_model['idi1_nombre'])) { ?>
              <td class="marcoCel"> 
       <b>NIVEL:</b> &nbsp;<?php echo $persona_model['nidi1_descripcion'] ?><br><br> 
             </td>
              <?php } ?>   
      <?php if (isset($persona_model['idi_nombre'])) { ?>
                <td class="marcoCel"> 
     <b>NIVEL:</b> &nbsp;<?php echo $persona_model['nidi_descripcion'] ?><br><br> 
                </td>
                  <?php } ?>  
            </tr>


        </tbody>
    </table>

    <br>

 <div class="divCeldag bold titleDetalle " style="text-align: center">

    7. OTRA INFORMACION 

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>DISCAPACIDAD</b> &nbsp;<?php echo $persona_model['ipdi_discapacidad'] ?><br><br>
       <b>TIPO DE DISCAPACIDAD:</b> &nbsp;<?php echo $persona_model['tdis_nombre'] ?><br><br>
      <b>PORCENTAJE DE DISCAPACIDAD:</b> &nbsp;<?php echo $persona_model['ides_porcentaje']; ?><br><br>
        <p class="blue" style='text-align:left'>__________________________________</p>
<br>
     <b>DOCENCIA</b> &nbsp;<br><br>
   <b>A??OS DE DOCENCIA:</b> &nbsp;<?php echo $persona_model['ides_anio_docencia'];  ?><br><br>
    <b>??REA DE DOCENCIA:</b> &nbsp;<?php echo $persona_model['ides_area_docencia'] ?><br><br>
      <p class="blue" style='text-align:left'>__________________________________</p>
<br>
  <b> INVESTIGACI??N</b> &nbsp;<br><br>
    <b>N??MERO DE ??RTICULOS PUBLICADOS:</b> &nbsp;<?php echo $persona_model['iein_articulos_investigacion'];  ?><br><br>
      <b>??REA DE INVESTIGACI??N:</b> &nbsp;<?php echo $persona_model['iein_area_investigacion'] ?><br><br>
  </p>
    </div>
    <br>



<div class="divCeldag bold titleDetalle " style="text-align: center">

    8. FINANCIAMIENTO

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>FINANCIAMIENTO:</b><br><br>
  </p>
    </div>

 <table class="abnormal">
        <tbody>
<tr>
              <td class="marcoCel"><?php 
If ($persona_model['ipos_tipo_financiamiento'] == 'Cr??dito Directo')
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Cr??dito Directo </td>
            </tr>
                  <tr>
                <td class="marcoCel"><?php 
If ($persona_model['ipos_tipo_financiamiento'] == 'Cr??dito Bancario')
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='2' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Cr??dito Bancario</td>
            </tr>
 <tr>
                <td class="marcoCel"><?php 
If ($persona_model['ipos_tipo_financiamiento'] == 'Pago Al Contado')
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='3' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?>
</td>
                <td class="marcoCel">Pago al contado&nbsp;</td>
            </tr>
        </tbody>
    </table>
<br>


<div style="text-align: center;background-color:#cccccc;height:32px;"><br>
<b>9. DOCUMENTACI??N (este punto corresponde al ??rea de admisiones) NO LLENAR </b>
</div>
<br><br>
<table style="width:100%" class="divTabla">
        <tbody>
            <tr>
                <td class=" bold titleDetalle  ">
                    <span>Documentos B??sicos</span>
                </td>
                 <td class=" bold titleDetalle">
                <span>Digital</span> </td>
                <td class=" bold titleDetalle">
                    <span>Si</span>
                </td>
                <td class=" bold titleDetalle">
                    <span>No</span>
                </td>
                 <td class=" bold titleDetalle"  style="width:30%;">
                    <span>Observaci??n</span>
                </td>
            </tr><br><br>
                <tr class="fila">
                    <td class="marcoCel">Copia de c??dula o pasaporte a color</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Copia de certificado de votaci??n a color</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Copia a color de t??tulo de tercer nivel de grado notarizada</td>
                     <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>         
                 <tr class="fila">
                    <td class="marcoCel">Formulario de inscripci??n</td>
                    <td class="marcoCel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;</td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Comprobante de dep??sito o transferencia de pago de matr??cula</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_doc_comprobantepago'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Registro de Senescyt a color</td>
                     <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Hoja de vida a color</td>
                     <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Certificado laboral</td>
                     <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Certificado de suficiencia en ingl??s</td>
                     <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
        </tbody>
    </table>

<pagebreak />
<br><br>
    <table style="width:100%" class="divTabla">
        <tbody>
            <tr>
                <td class=" bold titleDetalle  ">
                    <span>Documentos adicionales por homologaci??n</span>
                </td>
                 <td class=" bold titleDetalle">
                    <span>Digital</span> </td>
                <td class=" bold titleDetalle">
                    <span>Si</span>
                </td>
                <td class=" bold titleDetalle">
                    <span>No</span>
                </td>
                 <td class=" bold titleDetalle"  style="width:30%">
                    <span>Observaci??n</span>
                </td>
            </tr><br><br>
                <tr class="fila">
                    <td class="marcoCel">R??cord acad??mico</td>
                     <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_doc_recordacademico'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Certificado de no haber sido sancionado (firma y sello original)</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_doc_certificadonosancion'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Syllabus de materias aprobadas (firma y sello original)</td>
                      <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_doc_syllabus'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>         
                 <tr class="fila">
                    <td class="marcoCel">Especie valorada por homologaci??n</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['ipos_ruta_doc_homologacion'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
        </tbody>
    </table>
<br><br><br><br>
 
    <div class="divCeldag bold titleDetalle " style="text-align: center;color:#0055aa;">

      T??RMINOS Y CONDICIONES DE ADQUISICI??N DE SERVICIO EDUCATIVO

    </div>
<br><br>
 <div style="text-justify: auto">
        <p>
     El presente acuerdo de t??rminos y condiciones de adquisici??n de servicios educativos se suscribe con la Universidad Tecnol??gica Empresarial de
Guayaquil. Por favor, lea el siguiente acuerdo con detenimiento pues, constituye un marco vinculante entre Usted y UTEG.
        </p>
    </div>
<br><br>
 <div  style="text-justify: auto">
        <p>
      <b style="color:#0055aa;">DOCUMENTACION</b><br>
      <b>Marca con un check</b>
  </p>
    </div>
 <div class="blue">
        <p style='text-align:left'>__________________________________</p>
    </div>  
<br><br><br>
 <div  style="text-justify: auto">
        <p>
      <b>Conozco y acepto los siguientes t??rminos:</b>
  </p>
    </div>
<br><br>
 <table class="normal">
         <tbody>
            <tr>
              <td class="marcoCel"><?php 

$fieldvalue= 1; If($fieldvalue==1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel"><b>Sobre la entrega de documentos:</b> La documentaci??n requerida para iniciar el semestre ser?? entregada al ??rea de Admisiones, como
fecha m??xima al quinto d??a h??bil de haber realizado la inscripci??n, bajo las condiciones establecidas por el ??rea responsable.</td>
            </tr>
            <tr>
                <td class="marcoCel"><?php 

$fieldvalue= 1; If($fieldvalue==1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel"><b>Sobre la devoluci??n de documentos:</b> en caso de que la documentaci??n entregada sea copias, estas no me ser??n devueltas bajo ning??n
concepto.</td>
            </tr>
 <tr>
                <td class="marcoCel"><?php 

$fieldvalue= 1; If($fieldvalue==1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel"><b>Sobre la devoluci??n de documentos:</b> en caso de que la documentaci??n entregada sean documentos originales, podr?? solicitar la
devoluci??n de estos en un plazo m??ximo de 6 meses, a partir de la fecha de entrega de los documentos.</td>
            </tr>
        </tbody>
    </table>
    <br><br>
<div  style="text-justify: auto">
        <p>
     <b style="color:#0055aa;">PAGOS, AUTORIZACI??N, REEMBOLSOS Y TERMINACI??N</b><br>
      <b>Marca con un check</b>
  </p>
    </div>
 <div class="blue">
        <p style='text-align:left'>__________________________________</p>
    </div>  
<br><br><br>
 <div  style="text-justify: auto">
        <p>
      <b>Acepto los siguientes t??rminos:</b>
  </p>
    </div>
<br><br>
 <table class="normal">
        <tbody>
            <tr>
              <td class="marcoCel"><?php 

$fieldvalue= 1; If($fieldvalue==1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Ning??n importe es reembolsable, si paga en cuotas mensuales, todos los importes se adeudan por adelantado, no son reembolsables
y se cobrar??n autom??ticamente a su instrumento de pago, empezando con el primer pago adeudado cuando acepte estos T??rminos
de Adquisici??n de Servicio. Si su Instrumento de Pago es ???Alternativo???, su pago debe realizarse a trav??s de los sistemas de la
Universidad y sus centros de cobro autorizados.</td>
            </tr>
            <tr>
                <td class="marcoCel"><?php 

$fieldvalue= 1; If($fieldvalue==1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Ud. garantiza que legalmente es el titular del referido instrumento de pago y que est?? autorizado para realizar el pago del Precio de
Adquisici??n de Servicio y tambi??n para crear este compromiso de pago con la Universidad. Una vez que el alumno ha registrado sus
materias del semestre, cualquier materia se tomar?? como adicional y se dividir?? entre el n??mero de cuotas que faltan para terminar
el bloque o semestre.</td>
            </tr>
     <tr>
              <td class="marcoCel"><?php 

$fieldvalue= 1; If($fieldvalue==1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Los datos arriba declarados tienen car??cter de DECLARACI??N JURADA, aceptando en su totalidad las condiciones establecidas.</td>
            </tr>
     <tr>
              <td class="marcoCel"><?php 

$fieldvalue= 1; If($fieldvalue==1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">La Universidad se reserva el derecho de apertura, aplazamiento o suspensi??n de los cursos en caso de no contar con el n??mero
m??nimo de inscriptos. Se deber?? realizar el pago de matr??cula antes del inicio de este.</td>
            </tr>
 <tr>
                <td class="marcoCel"><?php 

$fieldvalue= 1; If($fieldvalue==1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">A partir de la recepci??n de la presente inscripci??n, el alumno se compromete a realizar el pago de sus cuotas, a excepci??n que con
anticipaci??n solicite la baja con car??cter voluntario, quedando as?? obligado al pago de las cuotas vencidas a la fecha de dicha solicitud.</td>
            </tr>
        </tbody>
    </table>
    <br><br>
 <div style="text-justify: auto">
        <p>
  La informaci??n registrada en el presente formulario es para USO INTERNO, exclusivo de la Universidad Tecnol??gica Empresarial de
Guayaquil, garantizando la confidencialidad de los datos y soportes suministrados.
        </p>
    </div>
   <br><br><br><br>
     
 <table class="abnormal">
        <tbody>
<tr>
              <td class="marcoCel"> <br><br><br> <br><br><br></td>
              <td class="marcoCel"> <br><br><br> <br><br><br></td>
              <td class="marcoCel"> <br><br><br> <br><br><br></td>
            </tr>
            <tr>
                <td class="marcoCel">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;FIRMA DEL ESTUDIANTE&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
                <td class="marcoCel">
             &nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;FIRMA DEL ASESOR DE ADMISIONES
             &nbsp;&nbsp;&nbsp;&nbsp;
         </td>
                <td class="marcoCel">
             &nbsp;&nbsp;&nbsp;&nbsp;
                FIRMA DEL COORDINADOR DE ADMISIONES
             &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            </tr>
        </tbody>
    </table>