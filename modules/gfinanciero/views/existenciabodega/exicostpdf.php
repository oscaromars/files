
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$cols = count($arr_head);
$j = 0;
?>
 
<table>
    <thead>
        <tr>
        <?php
            foreach($arr_head as $key1 => $value1){
                echo "<th>" . strtoupper($value1) . "</th>";
            }
        ?>
        </tr>
    </thead>
    <tbody>
        <?php
            $sumTotal=0;
            for ($i = 0; $i < sizeof($arr_body); $i++)  {                
                 $cod_art = isset($arr_body[$i]['COD_ART']) ? trim($arr_body[$i]['COD_ART']) : "";
                 $des_com = isset($arr_body[$i]['DES_COM']) ? trim($arr_body[$i]['DES_COM']) : "";
                 $exi_tot = isset($arr_body[$i]['EXI_TOT']) ? trim($arr_body[$i]['EXI_TOT']) : "0";
                 $p_prome = isset($arr_body[$i]['P_PROME']) ? trim($arr_body[$i]['P_PROME']) : "0";
                 $p_costo = isset($arr_body[$i]['P_COSTO']) ? trim($arr_body[$i]['P_COSTO']) : "0";
                 $t_costo=0;
                 if($p_costo <>"0"){
                     $t_costo=$p_costo*$exi_tot;
                 }
                 $sumTotal+=$t_costo;
                 $v_porce=0;
                 if($p_prome <>"0"){
                     $v_porce=(($p_costo-$p_prome)*100)/$p_prome;
                 }
                 
                echo "<tr>";
                    echo "<td>" . $cod_art . "</td>";
                    echo "<td>" . $des_com . "</td>";
                    echo "<td>" . $exi_tot . "</td>";
                    echo "<td>" . $p_prome . "</td>";
                    echo "<td>" . $p_costo . "</td>";
                    echo "<td>" . $v_porce . "</td>";
                    echo "<td>" . $t_costo . "</td>";
                echo "</tr>";
                
            }
            
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6"></td>
            <?php
                echo "<td>" . $sumTotal . "</td>";
            ?>
        </tr>
    </tfoot>
</table>