<style>
    .modCab{
        color: #000000;        
        line-height: 12px;
    }
    .panelUserInfo{
        /*margin: 10px 0px 18px;*/
        margin: 5px 0px 0px;
    }    
    .tcoll_cen {
        width: 50%;
        float: left;
        font-size: 7px;
        text-align: left;
        /*font-family: Arial;*/
    }
    .tcolr_cen {
        width: 50%;
        float: left;
        font-size: 7px;
        text-align: left;
        /*font-family: Arial;*/
    }
    .tcoll_cen2 {
        width: 40%;
        float: left;
        font-size: 7px;
        text-align: left;
        /*font-family: Arial;*/
    }
    .tcolr_cen2 {
        width: 60%;
        float: left;
        font-size: 7px;
        text-align: left;
        /*font-family: Arial;*/
    }
    .tcoll_ad {
        width: 30%;
        float: left;
        font-size: 7px;
        text-align: left;
        /*font-family: Arial;*/
    }
    .tcolr_ad {
        width: 70%;
        float: left;
        font-size: 7px;
        text-align: left;
        /*font-family: Arial;*/
    }
    .divDetalles{
        float: left;
        width: 100%;
        position: absolute;      
        left: 0;
        margin-top: 10px;
    }
    .divDetalleAd{
        float: left;
        width: 65%;
        position: absolute;      
        left: 0;
    }
    .divDetalleTot{  
        width: 35%;
        position: absolute;      
        right: 0;
    }
    .div_modInfoAd{
        float: left;
        width: 70%;
    }
    .div_modInfoVal{
        float: left;
        width: 100%;       
    }
    .div_modInfoDet{
        float: left;
        width: 60%;
    }
    .div_modInfoDet2{
        float: left;
        width: 75%;
    }
    .div_modInfoDet1{
        float: left;
        width: 40%;
    }    
    .bordeDivDet{ 
        border: 1px solid #000000;       
        -moz-border-radius: 7px;
        -webkit-border-radius: 7px;
        padding: 10px;
    }    
    .valorAlign{ 
        text-align: right !important;
    }

    .bold{
        font-weight: bold;
    }
    .blue{
        color:#002060 !important;
        font-size: 12px !important;
    }
    .tabla {       
        color:#002060 !important;
        width: 100%;
        text-align: center;
        vertical-align: top;
        border: 1px solid #002060;
        border-collapse: collapse;
        padding: 0.3em;
        caption-side: bottom;
    }  
    .tcell_cen {
        width: 30%;
        float: left;
        font-size: 14px;
        text-align: left;
        /*font-family: Arial;*/;
    }
    .tr_bor {
        border: 1px solid #002060;
    }

    .table_end {
        border: 1px solid black;
    }
    table, th, td {
      border: 1px solid black;
      font-size: 12px !important;
    }
</style>
<div class ="table_end">
    <div class="blue">
        <table class="tabla">
            <tr class="tr tr_bor">
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">PERIODO ACADEMICO: </span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= strtoupper($data_student['pla_periodo_academico']) ?></span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">NUM DOC PED:</span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= $ron_id ?></span></td>
            </tr>
            <tr class="tr tr_bor">
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">ALUMNO: </span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= strtoupper($data_student['pes_nombres']) ?></span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">TELEFONO:</span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= $data_student['per_celular'] ?></span></td>
            </tr>
            <tr class="tr tr_bor">
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">CÉDULA: </span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= $data_student['pes_dni'] ?></span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">DIRECCION:</span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= strtoupper($direccion) ?></span></td>
            </tr>
            <tr class="tr tr_bor">
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">UNIDAD ACADEMICA: </span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= strtoupper($data_student['mod_nombre']) ?></span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">CARRERA:</span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= strtoupper($data_student['pes_carrera']) ?></span></td>
            </tr>
            <tr class="tr tr_bor" >
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">FLUJO: </span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= strtoupper($maca_nombre) ?></span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span class="bold">USUARIO:</span></td>
                <td class="tcell_cen tr_bor" colspan="1" ><span ><?= $matricula ?></span></td>
            </tr>
        </table>
    </div>
    <br>
    <div class="blue">
        <?php
        echo '<table style="margin:0" class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;border: 1px solid #002060;"><th>CODIGO</th><th>MATERIA</th><th>HORARIO</th><th>COSTO UNITARIO</th></tr>';
        for ($i = 0; $i < count($dataPlanificacion); $i++) {
            $total_costo = $total_costo + $dataPlanificacion[$i]['Cost'];
            echo '<tr style="border: 1px solid #002060;"><td>' . $dataPlanificacion[$i]['Code'] . '</td><td style="text-align:left">' . strtoupper($dataPlanificacion[$i]['Subject']) . '</td><td>' . $dataPlanificacion[$i]['Hour'] . ' - '  . $dataPlanificacion[$i]['Block'] .'</td><td>' . $dataPlanificacion[$i]['Cost'] . '</td><td> ';
        }
        /*echo '<tr style="border: 1px solid #002060;"> 
                <td> </td>
                <td> ASOCIACION DE ESTUDIANTES</td>
                <td> </td>
                <td>' . $ron_valor_aso_estudiante . '</td> 
             </tr>';*/
         echo '<tr style="border: 1px solid #002060;"> 
                <td> </td>
                <td> GASTOS ADMINISTRATIVOS</td>
                <td> </td>
                <td>' . $valor_gasto_adm . '</td> 
             </tr>';
        $total = $total_costo + $ron_valor_aso_estudiante + $valor_gasto_adm;
        echo "<tr><td colspan='3'></td><td><b>TOTAL US$ " . number_format($total,2 ) . "</b></td></tr>";
        if (empty(count($dataPlanificacion))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?>
        <br>
    </div>

    <div class="blue">
        <?php
        echo '<table class="tabla"><tbody>';
        echo "<tr><td colspan='5'> <b>CREDITO DIRECTO (" . $cant_cuota . " Pagos</b>) </td></tr>";
        echo '</tbody> </table>';

        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;border: 1px solid #002060;"><th>NO.</th><th>PAGO</th><th>VENCIMIENTO</th><th>PORCENTAJE</th><th>PAGO ACTUAL</th></tr>';
        for ($i = 0; $i < count($detallePagos); $i++) {
            $total_valor_cuota  = $total_valor_cuota + $detallePagos[$i]['valor_cuota'];
            echo '<tr style="border: 1px solid #002060;"><td>' . $detallePagos[$i]['NO'] . '</td><td>' . $detallePagos[$i]['pago'] . '</td><td>' . $detallePagos[$i]['fecha_vencimiento'] . '</td><td>' . $detallePagos[$i]['porcentaje'] . '</td><td> ' . $detallePagos[$i]['valor_cuota'] . ' </td></tr>';
        }

        echo "<tr><td colspan='4'> </td><td><b>TOTAL US$ " . number_format($detallePagos[0]['valor_factura'],2 ) . "</b></td></tr>";

        if (empty(count($detallePagos))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>

    <div class="blue" style="text-justify: auto">
        <p><b>Cláusulas:</b></p><br> 
        <p>    
            <ul style= "list-style-type: disc; padding-left: 5%;">
                <li>Esta pre-inscripción está sujeta de acuerdo a la disponibilidad de cupos en las materias seleccionadas, en caso de solicitar activación deberá comunicarse con la secretaria de su Facultad.</li>
                <li>Los valores correspondientes a cada mensualidad deberán cancelarse en las fechas establecidas.</li>
                <li>Todos los pagos deben realizarse directamente en las cuentas institucionales indicadas por la UTEG</li>
                <li>El comprobante de depósito deberá ser enviado al correo de colecturia@uteg.edu.ec dentro de las 24 horas posteriores para su registro en sistema y emisión de la factura correspondiente.</li>
                <li>El estudiante acepta acoger las disposiciones académicas y reglamentarias de la Universidad Tecnológica Empresarial de Guayaquil.</li>
                <li>La confirmación mediante correo electrónico por parte del Estudiante constituye la aceptación de la hoja de inscripción.</li>
            </ul>
        </p><br>    
    </div>
    <hr>
    <div class="blue">
        <p style='text-align:center'><b><i>Compromiso de Pago - Crédito Universitario Directo</i></b></p>
            <p>Yo, alumno(a) <?= strtoupper($data_student['pes_nombres']) ?> con C.I. No. <?= $data_student['pes_dni'] ?>, perteneciente a la facultad de MODALIDAD <?= strtoupper($data_student['mod_nombre']) ?>, carrera de <?= strtoupper($data_student['pes_carrera']) ?>, me comprometo a cancelar
            <u>puntualmente</u> las cuotas señaladas anteriormente, hasta el día dos de cada mes, durante el periodo de <?= strtoupper($data_student['pla_periodo_academico']) ?>. Por el incumplimiento de lo antes señalado, me sujeto a cumplir las disposiciones que establece la universidad.
            </p>
    </div>
    <br><br>
    <div class="blue">
        <p style='text-align:center'>__________________________________</p>
        <p style='text-align:center'>              Firma Alumno (a)    </p>
    </div>  
</div>

