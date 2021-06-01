
<?php

use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>
<?php
foreach($arr_body3 as $items => $item){
    echo "<h2>" . $item['name'] . "</h2>";
    $hayInfo1 = false;
?>
<table>
    <thead>
        <tr>
            <th rowspan="2"><?= strtoupper(gpr::t("unidad", "Unity")) ?></th>
            <th rowspan="2"><?= strtoupper(gpr::t("objetivooperativo", "Operative Objective")) ?></th>
            <th rowspan="2"><?= strtoupper(gpr::t("responsablesubunidad", "Responsible")) ?></th>
            <th rowspan="2"><?= strtoupper(gpr::t("indicador", "Indicator")) ?></th>
            <th rowspan="2"><?= strtoupper(gpr::t("indicador", "Indicator Frecuency")) ?></th>
            <th colspan="12" style="text-align: center;"><?= strtoupper(gpr::t("meta", "Goal")) ?></th>
        </tr>
        <tr>
            <th><?= strtoupper(gpr::t("meta", "JAN")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "FEB")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "MAR")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "APR")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "MAY")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "JUN")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "JUL")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "AUG")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "SEP")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "OCT")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "NOV")) ?></th>
            <th><?= strtoupper(gpr::t("meta", "DEC")) ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $border = "border: 1px solid #ccc4c4;";
            $style = "style='$border'";
            foreach($arr_body as $key => $value){
                if($item['id'] === $value['UniId']){
                    $hayInfo1 = true;
                    echo "<tr $style>";
                    echo "<td $style>" . $value['Unidad'] . "</td>";
                    echo "<td $style>" . $value['ObjetivoOperativo'] . "</td>";
                    echo "<td $style>" . $value['Responsable'] . "</td>";
                    echo "<td $style>" . $value['Indicador'] . "</td>";
                    echo "<td $style>" . $value['Frecuencia'] . "</td>";
                    $denominador = 12 / $value['FrecuenciaDen'];
                    $ind_id = $value['Id'];
                    foreach($arr_body2 as $key2 => $value2){
                        if($ind_id === $value2['Id']){
                            $resultado = $value2['Resultado'];
                            if(isset($value2['Resultado']) && $value['Fraccional'] == 1)   $resultado .= '%';
                            echo "<td colspan='".$denominador."' style='text-align: center; $border'>" . $resultado . "</td>";
                        }
                    }
                    echo "</tr>";
                }
            }
            if(!$hayInfo1){
                echo "<tr><td style='text-align: center; $border' colspan='17'>".gpr::t('reporte', 'No Information')."</td></tr>";
            }
        ?>
    </tbody>
</table>
<?php 
    echo "<br />";
}
?>