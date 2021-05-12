
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
            $TotExi=0;
            $CanExi=0;
            for ($i = 0; $i < sizeof($arr_body); $i++)  {                
                 $cod_art = isset($arr_body[$i]['COD_ART']) ? trim($arr_body[$i]['COD_ART']) : "";
                 $des_com = isset($arr_body[$i]['DES_COM']) ? trim($arr_body[$i]['DES_COM']) : "";
                
                echo "<tr>";
                    echo "<td>" . $cod_art . "</td>";
                    echo "<td>" . $des_com . "</td>";
                    if(isset($arr_body[$i]['EXI_B01'])){
                        $CanExi=$arr_body[$i]['EXI_B01'];
                        echo "<td>" . $CanExi . "</td>";
                        $TotExi+=$CanExi;
                    }
                    if(isset($arr_body[$i]['EXI_B02'])){
                        $CanExi=$arr_body[$i]['EXI_B02'];
                        echo "<td>" . $CanExi . "</td>";
                        $TotExi+=$CanExi;
                    }
                    if(isset($arr_body[$i]['EXI_B03'])){
                        $CanExi=$arr_body[$i]['EXI_B03'];
                        echo "<td>" . $CanExi . "</td>";
                        $TotExi+=$CanExi;
                    }
                    if(isset($arr_body[$i]['EXI_B04'])){
                        $CanExi=$arr_body[$i]['EXI_B04'];
                        echo "<td>" . $CanExi . "</td>";
                        $TotExi+=$CanExi;
                    }
               
                    echo "<td>" . $TotExi . "</td>";
                    
                echo "</tr>";
                
            }
            
        ?>
        <?php
            /*foreach($arr_body as $key2 => $value2){
                echo "<tr>";
                foreach($value2 as $key3 => $value3){
                    if($j < $cols) echo "<td>$value3</td>";
                    $j++;
                }
                $j=0;
                echo "</tr>";
            }*/
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6"></td>
            <?php
                //echo "<td>" . $sumTotal . "</td>";
            ?>
        </tr>
    </tfoot>
</table>