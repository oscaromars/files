
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<style>
    .bold{
        font-weight: bold;
    }

    .tr{
        border: 0.5px solid #FFFFFF;
        text-align: center;
    }

    .th{
        border: 0.5px solid #FFFFFF;
        text-align: center;
    }
    .td{
        border: 0.5px solid #FFFFFF;
        padding: 10px;
        text-align: center;
    }
    
</style>

<div>
    <div style="text-align: center">
        <p><span class="bold">UNIVERSIDAD TECNOLÓGICA EMPRESARIAL DE GUAYAQUIL</span></p>
        <br>
    </div>
    <div style="text-align: center">
        <p><span class="bold">FACULTAD DE POSGRADOS E INVESTIGACIÓN</span></p>
        <br>
    </div>
    <div style="text-align: center">
        <p><span class="bold">DISTRIBUTIVO DE PERSONAL DOCENTE </span></p>
        <br>
    </div>
    <div style="text-align: center">
        <p><span class="bold"><?php echo strtoupper($baca['baca_descripcion']).' '.$baca['baca_anio'].' '.'('. strtoupper($pame['pame_mes']).')'?> </span></p>
        <br><br>
    </div>
    


    <table >
        <thead>
            <tr class="tr">
                <td class="td"><span class="bold">PROFESOR</span></td>
                <td class="td"><span class="bold">3er. Nivel</span></td>
                <td class="td"><span class="bold">4to Nivel</span></td>
                <td class="td"><span class="bold">Maestría</span></td>
                <td class="td"><span class="bold">Grupo Paralelo</span></td>
                <td class="td"><span class="bold">Materias</span></td>
                <td class="td"><span class="bold">Días</span></td>
                <td class="td"><span class="bold">Hora</span></td>
                <td class="td"><span class="bold">Estudiantes</span></td>
                <td class="td"><span class="bold">Total Horas</span></td>
                <td class="td"><span class="bold">Modalidad</span></td>
                <td class="td"><span class="bold">Total crédito</span></td>
            </tr>

        </thead>
        <tbody>
            <?php
            foreach ($arr_body as $key2 => $value2) {
              echo "<tr style='border: 0.5px solid #FFFFFF;'>";
                echo "<td class='td' style='text-align: center;'>".$value2['docente']."</td>
                          <td class='td' style='text-align: center;'>".$value2['titulo_tercel_nivel']."</td>
                          <td class='td' style='text-align: center;'>".$value2['titulo_cuarto_nivel']."</td>
                          <td class='td' style='text-align: center;'>".$value2['maestria']."</td>
                          <td class='td' style='text-align: center;'>".$value2['paralelo']."</td>
                          <td class='td' style='text-align: center;'>".$value2['materia']."</td>
                          <td class='td' style='text-align: center;'>".$value2['dias']."</td>
                          <td class='td' style='text-align: center;'>".$value2['hora']."</td>
                          <td class='td' style='text-align: center;'>".$value2['num_est']."</td>
                          <td class='td' style='text-align: center;'>".$value2['total_horas_dictar']."</td>
                          <td class='td' style='text-align: center;'>".$value2['modalidad']."</td>
                          <td class='td' style='text-align: center;'>".$value2['credito']."</td>";

              
              echo "</tr>";
            }
            ?>
        </tbody>
        <!--<tfoot>
            <tr><td></td></tr>
        </tfoot>-->
    </table>
</div>
<br><br><br><br><br>
<footer>
<div>
    <table style="background: #FFFFFF;">
        <thead>
            <tr >
                <td style="text-align: center"><span class="bold">Elaborado por:</span></td>
                <td style="text-align: center"><span class="bold">Revisado por:</span></td>
                <td style="text-align: center"><span class="bold">Revisado por:</span></td>
                <td style="text-align: center"><span class="bold">Aprobado por:</span></td>
                
            </tr>

        </thead>
        <tbody>
            <tr >
                <td style='text-align: center;'>
                    <div class="col-md-3 col-xs-3 col-lg-3 col-sm-3">
                        <div style="text-align: center">
                            <br><br>
                            <p><span class="bold">________________________________</span></p>
                        </div>
                        <div style="text-align: center">
                            <p><span class="bold">MSc. Dayse Cevallos Villegas</span></p>
                        </div>
                        <div style="text-align: center">
                            <p><span class="bold">COORDINADORA ADMINISTRATIVA DE POSGRADO</span></p>
                        </div>
                    </div>
                </td>
                <td style='text-align: center;'>
                    <div style="text-align: center">
                        <br><br>
                        <p><span class="bold">________________________________</span></p>
                    </div>
                    <div style="text-align: center">
                        <p><span class="bold">MSc. Karina Alvarado Quito</span></p>
                    </div>
                    <div style="text-align: center">
                        <p><span class="bold">DECANA DE POSGRADO</span></p>
                    </div>
                    
                </td>
                <td style='text-align: center;'>
                    <div style="text-align: center">
                        <br><br>
                        <p><span class="bold">________________________________</span></p>
                    </div>
                    <div style="text-align: center">
                        <p><span class="bold">PhD. Mercedes Conforme</span></p>
                    </div>
                    <div style="text-align: center">
                        <p><span class="bold">VICERRECTORA ACADÉMICA</span></p>
                    </div>
                </td>
                <td style='text-align: center;'>
                    <div style="text-align: center">
                        <br><br>
                        <p><span class="bold">________________________________</span></p>
                    </div>
                    <div style="text-align: center">
                        <p><span class="bold">PhD. Mara Cabanilla Guerra</span></p>
                    </div>
                    <div style="text-align: center">
                        <p><span class="bold">RECTORA</span></p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</div>
</footer>

