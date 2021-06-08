<style>
    .modCab{
        color: #000000;        
        line-height: 16px;
    }
    .panelUserInfo{
        /*margin: 10px 0px 18px;*/
        margin: 5px 0px 0px;
    }    
    .tcoll_cen {
        widtd: 50%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcolr_cen {
        widtd: 50%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcoll_cen2 {
        widtd: 40%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcolr_cen2 {
        widtd: 60%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcoll_ad {
        widtd: 30%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .tcolr_ad {
        widtd: 70%;
        float: left;
        font-size: 9px;
        text-align: left;
        font-family: Arial;
    }
    .divDetalles{
        float: left;
        widtd: 100%;
        position: absolute;      
        left: 0;
        margin-top: 10px;
    }
    .divDetalleAd{
        float: left;
        widtd: 65%;
        position: absolute;      
        left: 0;
    }
    .divDetalleTot{  
        widtd: 35%;
        position: absolute;      
        right: 0;
    }
    .div_modInfoAd{
        float: left;
        widtd: 70%;
    }
    .div_modInfoVal{
        float: left;
        widtd: 100%;       
    }
    .div_modInfoDet{
        float: left;
        widtd: 60%;
    }
    .div_modInfoDet2{
        float: left;
        widtd: 75%;
    }
    .div_modInfoDet1{
        float: left;
        widtd: 40%;
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

</style>
<div>
    <div class="blue">
        <table class="default">
            <tr class="tr">
                <td colspan="1" class="aleft td"><span class="bold">PERIODO ACADEMICO: </span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $data_student['pla_periodo_academico'] ?></span></td>
                <td colspan="1" class="aleft td"><span class="bold">NUM DOC PED:</span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $data_student['pla_periodo_academico'] ?></span></td>
            </tr>
            <tr class="tr">
                <td colspan="1" class="aleft td"><span class="bold">ALUMNO: </span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $data_student['pes_nombres'] ?></span></td>
                <td colspan="1" class="aleft td"><span class="bold">TELEFONO:</span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $data_student['per_celular'] ?></span></td>
            </tr>
            <tr class="tr">
                <td colspan="1" class="aleft td"><span class="bold">CÉDULA: </span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $data_student['pes_dni'] ?></span></td>
                <td colspan="1" class="aleft td"><span class="bold">DIRECCION:</span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $direccion ?></span></td>
            </tr>
            <tr class="tr">
                <td colspan="1" class="aleft td"><span class="bold">UNIDAD ACADEMICA: </span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $data_student['mod_nombre'] ?></span></td>
                <td colspan="1" class="aleft td"><span class="bold">CARRERA:</span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $data_student['pes_carrera'] ?></span></td>
            </tr>
            <tr class="tr">
                <td colspan="1" class="aleft td"><span class="bold">FLUJO: </span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $matricula ?></span></td>
                <td colspan="1" class="aleft td"><span class="bold">USUARIO:</span></td>
                <td colspan="1" class="aleft td"><span class="bold"><?= $matricula ?></span></td>
            </tr>
        </table>
    </div>

    <div class="blue">
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;border: 1px solid #002060;"><th>CODIGO</th><th>MATERIA</th><th>HORARIO</th><th>COSTO UNITARIO</th></tr>';
        for ($i = 0; $i < count($dataPlanificacion); $i++) {
            $total_costo = $total_costo + $dataPlanificacion[$i]['Cost'];
            echo '<tr style="border: 1px solid #002060;"><td>' . $dataPlanificacion[$i]['Code'] . '</td><td>' . $dataPlanificacion[$i]['Subject'] . '</td><td>' . $dataPlanificacion[$i]['Hour'] . ' - '  . $dataPlanificacion[$i]['Block'] .'</td><td>' . $dataPlanificacion[$i]['Cost'] . '</td><td> ';
        }
        echo '<tr> 
                <td> </td>
                <td> ASOCIACION DE ESTUDIANTES</td>
                <td> </td>
                <td> </td>
                <td> </td>' . $ron_valor_aso_estudiante .
             '</tr>';
         echo '<tr> 
                <td> </td>
                <td> VARIOS ADMINISTRATIVOS</td>
                <td> </td>
                <td> </td>
                <td> </td>' . $ron_valor_gastos_adm .
             '</tr>';
        
        
        echo "<tr><td colspan='4'> </td><td>TOTAL US$ " . $total_costo . "</td></tr>";
        if (empty(count($dataPlanificacion))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>

    <div class="blue">
       <p><u><b><?php echo "CREDITO DIRECTO (" .$cant_cuota . " Pagos)" ?></b><br></u></p><br><br>
        <?php
        echo '<table class="tabla"><tbody>';
        echo '<tr style="background: #AED6F1;border: 1px solid #002060;"><th>NO.</th><th>PAGO</th><th>VENCIMIENTO</th><th>PORCENTAJE</th><th>PAGO ACTUAL</th></tr>';
        for ($i = 0; $i < count($detallePagos); $i++) {
            $total_valor_cuota  = $total_valor_cuota + $detallePagos[$i]['valor_cuota'];
            echo '<tr style="border: 1px solid #002060;"><td>' . $detallePagos[$i]['NO'] . '</td><td>' . $detallePagos[$i]['pago'] . '</td><td>' . $detallePagos[$i]['fecha_vencimiento'] . '</td><td>' . $detallePagos[$i]['porcentaje'] . '</td><td> ' . $detallePagos[$i]['valor_cuota'] . ' </td></tr>';
        }

        echo "<tr><td colspan='4'> </td><td>TOTAL US$ " . $total_valor_cuota . "</td></tr>";

        if (empty(count($detallePagos))) {
            echo '<tr style="border: 1px solid #002060;"><td><br></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '</tbody> </table>';
        ?><br><br>
    </div>

    <div class="blue">
        <p><b>Cláusulas:</b></p> 
            <p>
                <ul>
                    <li>Esta pre-inscripción está sujeta de acuerdo a la disponibilidad de cupos en las materias seleccionadas, en caso de solicitar activación deberá comunicarse con la secretaria de su Facultad.</li>
                    <li>Los valores correspondientes a cada mensualidad deberán cancelarse en las fechas establecidas.</li>
                    <li>Todos los pagos deben realizarse directamente en las cuentas institucionales indicadas por la UTEG</li>
                    <li>El comprobante de depósito deberá ser enviado al correo de colecturia@uteg.edu.ec dentro de las 24 horas posteriores para su registro en sistema y emisión de la factura correspondiente.</li>
                    <li>El estudiante acepta acoger las disposiciones académicas y reglamentarias de la Universidad Tecnológica Empresarial de Guayaquil.</li>
                    <li>La confirmación mediante correo electrónico por parte del Estudiante constituye la aceptación de la hoja de inscripción.</li>
                </ul>
            </p> 
    </div>
    <hr>
    <div class="blue">
        <p style='text-align:center'><b>Compromiso de Pago - Crédito Universitario Directo</b></p>
            <p>Yo, alumno(a) <?= $data_student['pes_nombres'] ?> con C.I. No. <?= $data_student['pes_dni'] ?>, perteneciente a la facultad de MODALIDAD <?= $data_student['mod_nombre'] ?>, carrera de <?= $data_student['pes_carrera'] ?>, me comprometo a cancelar
            puntualmente las cuotas señaladas anteriormente, hasta el día dos de cada mes, durante el periodo de <?= $data_student['pla_periodo_academico'] ?>. Por el incumplimiento de lo antes señalado, me sujeto a cumplir las disposiciones que establece la universidad.
        </p>
    </div>
    <br><br>
    <div class="blue">
        <p style='text-align:center'>__________________________________</p>
        <p style='text-align:center'>              Firma Alumno (a)    </p>
    </div>  
</div>
