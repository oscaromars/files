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
    }

    .marcoCel3 {
        border: 0px solid #0000;
        word-spacing:2px;
        letter-spacing:0.7px;
        padding: 1mm;
        width: 25%;
        color:#000011;
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
Por favor asegúrese de contestar correctamente todas las cuestiones planteadas. No se admitirá ninguna solicitud que no esté
completamente firmada y acompañada de todos los documentos solicitados.
        </p>
    </div>
   <br><br>
   <div  style="text-justify: auto">
        <p>
    <b>CARRERA:</b>&nbsp;<?php echo $persona_model['carrera'] ?><br><br>
    <b>MODALIDAD:</b>&nbsp;<?php echo $persona_model['modalidad'] ?><br><br>
    <b>PERIODO:</b>&nbsp;<?php echo $persona_model['periodo'] ?>
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
                <td class="marcoCel1 bold" >TIPO DE IDENTIFICACIÓN:<br><br>
                NÚMERO DE IDENTIFICACIÓN:<br><br>
                NOMBRES:<br><br>
                APELLIDOS:<br><br>
                LUGAR DE NACIMIENTO:<br><br>
                FECHA DE NACIMIENTO:<br><br>
                NACIONALIDAD:<br><br>
                ESTADO CIVIL:<br><br>
                </td>
                <td class="marcoCel3" style='font-family:helvetica'>
                 &nbsp;<?php if ($persona_model['cedula'] != Null) {
                   echo "Cédula";  } else {echo "Pasaporte";  }            
                 ?> <br><br>
                 &nbsp;<?php if ($persona_model['cedula'] != Null) {
                   echo $persona_model['cedula'];  } else {echo $persona_model['pasaporte'];  }            
                 ?> <br><br>
                &nbsp;<?php echo $persona_model['nombres'] ?><br><br>
                &nbsp;<?php echo $persona_model['apellidos'] ?><br><br>
                &nbsp;<?php echo $persona_model['pai_nombre'] ?><br><br>
                &nbsp;<?php echo $persona_model['per_fecha_nacimiento'] ?><br><br>
                &nbsp;<?php echo $persona_model['pai_nombre'] ?><br><br>
                &nbsp;<?php echo $persona_model['eciv_nombre'] ?><br><br>
                </td>
                <td class="marcoCel3">
                </td>
                  <td class="marcoCel3">
                     <?php echo yii\helpers\Html::img(
                                    Yii::$app->basePath . $persona_model['igra_ruta_doc_foto'] , 
                                    array("class" => "credpic", "alt" => Yii::$app->params["copyright"])); ?>
                </td>
            </tr>

        </tbody>
    </table>
    <br><br>
<div class="divCeldag bold titleDetalle " style="text-align: center">

2. DATOS DE CONTACTO

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>DIRECCIÓN DOMICILIARIA:</b> &nbsp;<?php echo $persona_model['domicilio'] ?><br><br>
      <b>CELULAR:</b> &nbsp;<?php echo $persona_model['per_celular'] ?><br><br>
      <b>TELÉFONO ADICIONAL:</b> &nbsp;<?php echo $persona_model['per_domicilio_telefono'] ?><br><br>
    <b>CORREO ELECTRÓNICO:</b>&nbsp;<?php echo $persona_model['per_correo'] ?><br><br>
  </p>
    </div>

<div class="divCeldag bold titleDetalle " style="text-align: center">

3. DATOS EN CASO DE EMERGENCIA

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>DIRECCIÓN DE TRABAJO:</b> &nbsp;<?php echo $persona_model['trabajo'] ?><br><br>
      <b>PERSONA POR CONTACTAR EN CASO DE EMERGENCIA:</b> &nbsp;<?php echo $persona_model['pcon_nombre'] ?><br><br>
     <b>TIPO DE PARENTESCO:</b> &nbsp;<?php echo $persona_model['tpar_nombre'] ?><br><br>
   <b>TELÉFONO DE LA PERSONA A CONTACTAR EN CASO DE EMERGENCIA:</b> &nbsp;<?php echo $persona_model['pcon_telefono'] ?><br><br>
    <b>DIRECCIÓN DE LA PERSONA A CONTACTAR EN CASO DE EMERGENCIA:</b> &nbsp;<?php echo $persona_model['pcon_direccion'] ?><br><br>

  </p>
    </div>


<div class="divCeldag bold titleDetalle " style="text-align: center">

    4. DATOS ACADÉMICOS

    </div>
<br><br>
<div  style="text-justify: auto">
        <p>
      <b>MALLA CURRICULAR:</b>&nbsp;<?php echo $persona_model['maca_nombre'] ?><br><br>
      <b>CATEGORIA:</b>&nbsp;<?php echo $persona_model['categoria'] ?><br><br>
      <b>FINANCIAMIENTO:</b><br><br>
  </p>
    </div>

 <table class="abnormal">
        <tbody>
            <tr>
              <td class="marcoCel"><?php 
If ($persona_model['igra_financiamiento'] == 1)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Crédito Directo </td>
            </tr>
            <tr>
                <td class="marcoCel"><?php 
If ($persona_model['igra_financiamiento'] == 2)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='2' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Crédito Bancario</td>
            </tr>
 <tr>
                <td class="marcoCel"><?php 
If ($persona_model['igra_financiamiento'] == 3)
{ 
echo "<input type='checkbox' style='width:140%; height:140%' name='f'  checked='checked'  value='3' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?>
<?php 
If ($persona_model['igra_financiamiento'] == 3)
{ 
echo $persona_model['igra_institucion_beca']; 
}
?>  
</td>
                <td class="marcoCel">Beca</td>
            </tr>
        </tbody>
    </table>
<pagebreak />
 <div class="divCeldag bold titleDetalle " style="text-align: center">

      5. DOCUMENTACIÓN (este punto corresponde al área de admisiones) NO LLENAR

    </div>
<br><br>

<table style="width:100%" class="divTabla">
        <tbody>
            <tr>
                <td class="divCelda bold titleDetalle  ">
                    <span>Documentos Básicos</span>
                </td>
                <td class="divCelda bold titleDetalle">
                    <span>Recibido</span>
                </td>
                <td class="divCelda bold titleDetalle">
                    <span>Si</span>
                </td>
                <td class="divCelda bold titleDetalle">
                    <span>No</span>
                </td>
                <td class="divCelda bold titleDetalle"  style="width:30%">
                    <span>Observación</span>
                </td>
            </tr>
                <tr class="fila">
                    <td class="marcoCel">Copia de cédula a color</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['igra_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Copia de certificado de votación a color</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['igra_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Copia a color de título bachiller notarizada</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['igra_ruta_documento'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>         
                 <tr class="fila">
                    <td class="marcoCel">Formulario de inscripción</td>
                    <td class="marcoCel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;</td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
                  <tr class="fila">
                    <td class="marcoCel">Comprobante de depósito o transferencia de pago de matrícula</td>
                   <td class="marcoCel">
                         <?php if ($persona_model['igra_ruta_doc_comprobantepago'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
        </tbody>
    </table>
  <br><br>
    <table style="width:100%" class="divTabla">
        <tbody>
            <tr>
                <td class="divCelda bold titleDetalle  ">
                    <span>Documentos adicionales por homologación</span>
                </td>
                 <td class="divCelda bold titleDetalle">
                    <span>Recibido</span>
                </td>
                <td class="divCelda bold titleDetalle">
                    <span>Si</span>
                </td>
                <td class="divCelda bold titleDetalle">
                    <span>No</span>
                </td>
                <td class="divCelda bold titleDetalle" style="width:30%">
                    <span>Observación</span>
                </td>
            </tr>
                <tr class="fila">
                    <td class="marcoCel">Récord académico</td>
                     <td class="marcoCel">
                         <?php if ($persona_model['igra_ruta_doc_recordacademico'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

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
                         <?php if ($persona_model['igra_ruta_doc_certificado'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

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
                         <?php if ($persona_model['igra_ruta_doc_syllabus'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>         
                 <tr class="fila">
                    <td class="marcoCel">Especie valorada por homologación</td>
                    <td class="marcoCel">
                         <?php if ($persona_model['igra_ruta_doc_homologacion'] != Null) {
                   ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10004;  <?php 

                    } else { }            
                 ?>
                </td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                    <td class="marcoCel"></td>
                </tr>
        </tbody>
    </table>
<br><br>
 
    <div class="divCeldag bold titleDetalle " style="text-align: center">

      TÉRMINOS Y CONDICIONES DE ADQUISICIÓN DE SERVICIO EDUCATIVO

    </div>
<br><br>
 <div style="text-justify: auto">
        <p>
     El presente acuerdo de términos y condiciones de adquisición de servicios educativos se suscribe con la Universidad Tecnológica Empresarial de
Guayaquil. Por favor, lea el siguiente acuerdo con detenimiento pues, constituye un marco vinculante entre Usted y UTEG.
        </p>
    </div>
<br><br>
 <div  style="text-justify: auto">
        <p>
      <b>DOCUMENTACION</b><br>
      <b>Marca con un check</b>
  </p>
    </div>
 <div class="blue">
        <p style='text-align:left'>__________________________________</p>
    </div>  
<br><br>
 <div  style="text-justify: auto">
        <p>
      <b>Conozco y acepto los siguientes términos:</b>
  </p>
    </div>
<br><br>
 <table class="normal">
         <tbody>
            <tr>
              <td class="marcoCel"><?php 
If ($persona_model['igra_financiamiento'] == 1)
{ 
echo "<input type='checkbox' name='f'  checked  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel"><b>Sobre la entrega de documentos:</b> La documentación requerida para iniciar el semestre será entregada al área de Admisiones, como
fecha máxima al quinto día hábil de haber realizado la inscripción, bajo las condiciones establecidas por el área responsable.</td>
            </tr>
            <tr>
                <td class="marcoCel"><?php 
If ($persona_model['igra_financiamiento'] == 2)
{ 
echo "<input type='checkbox' name='f'  checked  value='1' />" ;
}else
{
echo "<input type='checkbox' name='f'  value='1' />" ;
}
?></td>
                <td class="marcoCel"><b>Sobre la devolución de documentos:</b> en caso de que la documentación entregada sea copias, estas no me serán devueltas bajo ningún
concepto.</td>
            </tr>
 <tr>
                <td class="marcoCel"><?php 
If ($persona_model['igra_financiamiento'] == 3)
{ 
echo "<input type='checkbox' name='pcu'  checked  value='1' />" ;
}else
{
echo "<input type='checkbox' name='pcu'  value='1' />" ;
}
?>
<?php 
If ($persona_model['igra_financiamiento'] ==3)
{ 
echo $persona_model['igra_institucion_beca'] ;
} ?>
    
</td>
                <td class="marcoCel"><b>Sobre la devolución de documentos:</b> en caso de que la documentación entregada sean documentos originales, podré solicitar la
devolución de estos en un plazo máximo de 6 meses, a partir de la fecha de entrega de los documentos.</td>
            </tr>
        </tbody>
    </table>
    <br><br>



<div  style="text-justify: auto">
        <p>
      <b>PAGOS, AUTORIZACIÓN, REEMBOLSOS Y TERMINACIÓN</b><br>
      <b>Marca con un check</b>
  </p>
    </div>
 <div class="blue">
        <p style='text-align:left'>__________________________________</p>
    </div>  
<br><br>
 <div  style="text-justify: auto">
        <p>
      <b>Acepto los siguientes términos:</b>
  </p>
    </div>
<br><br>
 <table class="normal">
        <tbody>
            <tr>
              <td class="marcoCel"><?php 
$fieldvalue =1;
If($fieldvalue==1)
{ 
echo "<input type='checkbox' name='pcu'  checked  value='1' />" ;
}else
{
echo "<input type='checkbox' name='pcu'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Ningún importe es reembolsable, si paga en cuotas mensuales, todos los importes se adeudan por adelantado, no son reembolsables
y se cobrarán automáticamente a su instrumento de pago, empezando con el primer pago adeudado cuando acepte estos Términos
de Adquisición de Servicio. Si su Instrumento de Pago es “Alternativo”, su pago debe realizarse a través de los sistemas de la
Universidad y sus centros de cobro autorizados.</td>
            </tr>
            <tr>
                <td class="marcoCel"><?php 
$fieldvalue =1;
If($fieldvalue==1)
{ 
echo "<input type='checkbox' name='pcu'  checked  value='1' />" ;
}else
{
echo "<input type='checkbox' name='pcu'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Ud. garantiza que legalmente es el titular del referido instrumento de pago y que está autorizado para realizar el pago del Precio de
Adquisición de Servicio y también para crear este compromiso de pago con la Universidad. Una vez que el alumno ha registrado sus
materias del semestre, cualquier materia se tomará como adicional y se dividirá entre el número de cuotas que faltan para terminar el
bloque o semestre.</td>
            </tr>
 <tr>
                <td class="marcoCel"><?php 
$fieldvalue =1;
If($fieldvalue==1)
{ 
echo "<input type='checkbox' name='pcu'  checked  value='1' />" ;
}else
{
echo "<input type='checkbox' name='pcu'  value='1' />" ;
}
?></td>
                <td class="marcoCel">Los datos arriba declarados tienen carácter de DECLARACIÓN JURADA, aceptando en su totalidad las condiciones establecidas.</td>
            </tr>
        </tbody>
    </table>
    <br><br>



 <div style="text-justify: auto">
        <p>
  La información registrada en el presente formulario es para USO INTERNO, exclusivo de la Universidad Tecnológica Empresarial de
Guayaquil, garantizando la confidencialidad de los datos y soportes suministrados.
        </p>
    </div>
   <br>
     
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
