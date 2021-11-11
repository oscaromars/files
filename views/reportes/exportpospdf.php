
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
        border: 0.5px solid #000000;
        text-align: center;
    }

    .th{
        border: 0.5px solid #000000;
        text-align: center;
    }
    .td{
        border: 0.5px solid #000000;
        padding: 10px;
        text-align: center;
    }

</style>


<table style='border: 0.5px solid;'>
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
	echo "<tr style='border: 0.5px solid #002060;'>";
	echo "<td style='text-align: center;'>" . $value2['docente'] . "</td>
                      <td style='text-align: center;'>" . $value2['titulo_tercel_nivel'] . "</td>
                      <td style='text-align: center;'>" . $value2['titulo_cuarto_nivel'] . "</td>
                      <td style='text-align: center;'>" . $value2['maestria'] . "</td>
                      <td style='text-align: center;'>" . $value2['paralelo'] . "</td>
                      <td style='text-align: center;'>" . $value2['materia'] . "</td>
                      <td style='text-align: center;'>" . $value2['dias'] . "</td>
                      <td style='text-align: center;'>" . $value2['hora'] . "</td>
                      <td style='text-align: center;'>" . $value2['num_est'] . "</td>
                      <td style='text-align: center;'>" . $value2['total_horas_dictar'] . "</td>
                      <td style='text-align: center;'>" . $value2['modalidad'] . "</td>
                      <td style='text-align: center;'>" . $value2['credito'] . "</td>";

	echo "</tr>";
}
?>
    </tbody>
    <!--<tfoot>
        <tr><td></td></tr>
    </tfoot>-->
</table>