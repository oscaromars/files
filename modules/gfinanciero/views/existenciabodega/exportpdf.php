
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$cols = count($arr_head);
$j = 0;
?>
<div class="rptbody">
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
                foreach($arr_body as $key2 => $value2){
                    echo "<tr>";
                    foreach($value2 as $key3 => $value3){
                        if($j < $cols) echo "<td>$value3</td>";
                        $j++;
                    }
                    $j=0;
                    echo "</tr>";
                }
            ?>
        </tbody>
        <!--<tfoot>
            <tr><td></td></tr>
        </tfoot>-->
    </table>
</div>
