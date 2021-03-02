
<?php

use app\modules\gpr\models\Umbral;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

$border = "border: 1px solid #ccc4c4;";
?>

<table>
    <tbody>
        <tr style="background-color: #FFFFFF;">
            <td style='font-weight: bold; width: 200px;'><?= strtoupper($arrHeader['TipoLabel']) ?>:</td>
            <td><?= $arrHeader['Tipo'] ?></td>
        </tr>
        <tr style="background-color: #FFFFFF;">
            <td style='font-weight: bold;'><?= strtoupper(gpr::t('indicador', "Number of Indicators"))?>:</td>
            <td><?= count($arr_body) ?></td>
        </tr>
    </tbody>
</table>
<br />
<br />
<?php
foreach($arr_body3 as $items => $item){
    echo "<h2>" . $item['name'] . "</h2>";
    $hayInfo1 = false;
?>
<table>
    <thead>
        <tr>
            <th><?= strtoupper(gpr::t("indicador", "Indicator")) ?></th>
            <th><?= strtoupper(gpr::t("unidad", "Unity")) ?></th>
            <th><?= strtoupper(gpr::t("responsablesubunidad", "Responsible")) ?></th>
            <th><?= strtoupper(gpr::t("indicador", "Indicator Frecuency")) ?></th>
            <th><?= strtoupper(gpr::t("indicador", "Indicator Behavior")) ?></th>
            <th><?= strtoupper(gpr::t("indicador", "Hierarchy")) ?></th>
            <th><?= strtoupper(gpr::t("indicador", "Base Line Initial")) ?></th>
            <th><?= strtoupper(gpr::t("indicador", "Grouping of Indicators")) ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $style = "style='$border'";
            foreach($arr_body as $key => $value){
                if($item['id'] === $value['OOpId']){
                    $hayInfo1 = true;
                    echo "<tr>";
                    echo "<td>" . $value['Indicador'] . "</td>";
                    echo "<td>" . $value['Unidad'] . "</td>";
                    echo "<td>" . $value['Responsable'] . "</td>";
                    echo "<td>" . $value['Frecuencia'] . "</td>";
                    echo "<td>" . $value['Comportamiento'] . "</td>";
                    echo "<td>" . $value['Jerarquia'] . "</td>";
                    echo "<td>" . $value['LineBase'] . "</td>";
                    echo "<td>" . (($value['Agrupado'] == 1)?(gpr::t("indicador", "Yes")):(gpr::t("indicador", "No"))) . "</td>";
                    echo "</tr>";
                }
            }
            reset($arr_body);
            if(!$hayInfo1){
                echo "<tr><td style='text-align: center; $border' colspan='8'>".gpr::t('reporte', 'No Information')."</td></tr>";
            }
        ?>
    </tbody>
</table>
<br/>
<table>
    <tbody>
        <?php
        $hayInfo2 = false;
        foreach($arr_body as $key => $value){
            if($item['id'] === $value['OOpId']){
                $hayInfo2 = true;
                echo "<tr style='background-color: #dcdce1;$border'><td colspan='13' style='text-align: center; font-weight: bold;'>" . strtoupper(gpr::t("indicador", "Indicator Detail")) ."</tr>";
                echo "<tr style='background-color: #FFFFFF;$border'>";
                echo "<td style='font-weight: bold; $border'>" . strtoupper(gpr::t("indicador", "Indicator")) . "</td>";
                echo "<td colspan='12' style='$border'>" . $value['Indicador'] . "</td>";
                echo "</tr>";
                echo "<tr style='background-color: #FFFFFF;$border'>";
                echo "<td style='font-weight: bold; $border'>" . strtoupper(gpr::t("indicador", "Indicator Description")) . "</td>";
                echo "<td colspan='12' style='$border'>" . $value['Descripcion']. "</td>";
                echo "</tr>";
                echo "<tr style='background-color: #FFFFFF;$border'>";
                echo "<td style='font-weight: bold; $border'>" . strtoupper(gpr::t("indicador", "Calculation Method")) . "</td>";
                echo "<td colspan='12' style='$border'>" . $value['MCalculo']. "</td>";
                echo "</tr>";
                echo "<tr style='background-color: #FFFFFF;$border'>";
                echo "<td style='font-weight: bold; $border'>" . strtoupper(gpr::t("indicador", "Período del Indicador")) . "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "JAN")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "FEB")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "MAR")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "APR")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "MAY")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "JUN")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "JUL")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "AUG")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "SEP")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "OCT")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "NOV")). "</td>";
                echo "<td style='text-align: center; $border'>" . strtoupper(gpr::t("meta", "DEC")). "</td>";
                echo "</tr>";
                echo "<tr style='background-color: #FFFFFF;$border'>";
                echo "<td style='font-weight: bold; $border'>" . strtoupper(gpr::t("meta", "Goal")) . "</td>";
                $denominador = 12 / $value['FrecuenciaDen'];
                $ind_id = $value['Id'];
                $ind_fraccional = $value['Fraccional'];
                foreach($arr_body2 as $key2 => $value2){
                    if($ind_id === $value2['Id']){
                        $meta = $value2['Meta'];
                        if(isset($value2['Meta']) && $value['Fraccional'] == 1)   $meta .= '%';
                        echo "<td colspan='".$denominador."' style='text-align: center; $border'>" . $meta . "</td>";
                    }
                }
                echo "</tr>";
                echo "<tr style='background-color: #FFFFFF;$border'>";
                echo "<td style='font-weight: bold; $border'>" . strtoupper(gpr::t("meta", "Result")) . "</td>";
                foreach($arr_body2 as $key2 => $value2){
                    if($ind_id === $value2['Id']){
                        $resultado = $value2['Resultado'];
                        if(isset($value2['Resultado']) && $value['Fraccional'] == 1)   $resultado .= '%';
                        echo "<td colspan='".$denominador."' style='text-align: center; $border'>" . $resultado . "</td>";
                    }
                }
                echo "</tr>";
                echo "<tr style='background-color: #FFFFFF;$border'>";
                echo "<td style='font-weight: bold; $border'>" . strtoupper(gpr::t("meta", "Goal Status")) . "</td>";
                foreach($arr_body2 as $key2 => $value2){
                    if($ind_id === $value2['Id']){
                        $parameter = $value2["Resultado"];
                        $meta = $value2["Meta"];
                        if($ind_fraccional == 0){
                            $parameter = ($parameter/$meta)*100;
                        }
                        $arr_data = Umbral::getUmbralByParameter($parameter);
                        $per = round($parameter, 0);
                        $color = $arr_data['Color'];
                        if($per >= 100){
                            $color = "#3d754c";
                        }
                        if($per == 0){
                            $color = "#ff0000";
                        }
                        echo "<td colspan='".$denominador."' style='text-align: center; $border'>" . '<span style="background-color: '.$color.'; color: #FFFFFF; font-weight: bolder;">'.$per.'%</span>' . "</td>";
                    }
                }
                echo "</tr>";
                echo "<tr style='background-color: #FFFFFF;'><td colspan='13'>&nbsp;</td></tr>";
            }
        }
        if(!$hayInfo2){
            echo "<tr><td style='text-align: center; $border' colspan='13'>".gpr::t('reporte', 'No Information')."</td></tr>";
        }
        ?>
    </tbody>
</table>
<?php 
    echo "<br />";
}
?>