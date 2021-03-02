
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use app\modules\gpr\models\Umbral;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

$border = "border: 1px solid #ccc4c4;";

?>

<?php
foreach($arr_body3 as $items => $item){
    $hayInfo1 = false;
?>
<table>
    <thead>
        <tr>
            <th colspan="16" style="text-align: center; font-weight: bold; background-color: #1708f1; color: #FFFFFF; <?= $border?>"><?= strtoupper($item['name']) ?></th>
        </tr>
        <tr><th colspan="16" style="background-color: #FFFFFF;">&nbsp;</th></tr>
        <tr>
            <th colspan="4" style="background-color: #FFFFFF;">&nbsp;</th>
            <th colspan="12" style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "Goal")) ?></th>
        </tr>
        <tr style="<?= $border?>">
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("objetivooperativo", "Operative Objective")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("indicador", "Indicator")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("indicador", "Base Line Initial")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("indicador", "Indicator Frecuency")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "JAN")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "FEB")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "MAR")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "APR")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "MAY")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "JUN")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "JUL")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "AUG")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "SEP")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "OCT")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "NOV")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("meta", "DEC")) ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $style = "style='$border'";
            foreach($arr_body as $key => $value){
                if($item['id'] === $value['UniId']){
                    $hayInfo1 = true;
                    echo "<tr $style>";
                    echo "<td style='$border'>" . $value['ObjetivoOperativo'] . "</td>";
                    echo "<td style='$border'>" . $value['Indicador'] . "</td>";
                    echo "<td style='text-align: center; $border'>" . $value['LineBase'] . "</td>";
                    echo "<td style='text-align: center; $border'>" . $value['Frecuencia'] . "</td>";
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
                echo "<tr><td style='text-align: center; $border' colspan='16'>".gpr::t('reporte', 'No Information')."</td></tr>";
            }
            reset($arr_body);
        ?>
    </tbody>
</table>
<br/>
<?php
$avance = 0;
$contAvance = 0;
$presuesto = 0;
$consumo = 0;
$hayInfo = false;
?>
<table>
    <thead>
        <tr style="<?= $border?>">
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "Project")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("tipoproyecto", "Project Type")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "Initial Date")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "End Date")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("hito", "Milestones")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "Advance")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "Budget")) ?></th>
            <th style="text-align: center; background-color: #9b9b9c; font-weight: bold; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "Budget Consumed")) ?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
            foreach($arr_body4 as $key2 => $value2){
                if($item['id'] === $value2['IdUni']){
                    $avance += $value2['Avance'];
                    $contAvance ++;
                    $presuesto += $value2['Presupuesto'];
                    $consumo += $value2['Consumo'];
                    $hayInfo = true;
                    echo "<tr style='$border'>";
                    echo "<td style='$border'>" . $value2['Objetivo'] . "</td>";
                    echo "<td style='text-align: center;$border'>" . $value2['TipoProyecto'] . "</td>";
                    echo "<td style='text-align: center;$border'>" . date(Yii::$app->params["dateByDefault"], strtotime($value2['FechaInicio'])) . "</td>";
                    echo "<td style='text-align: center;$border'>" . date(Yii::$app->params["dateByDefault"], strtotime($value2['FechaFin'])) . "</td>";
                    echo "<td style='text-align: center;$border'>" . $value2['CantHito'] . "</td>";
                    echo "<td style='text-align: center;$border'>" . $value2['Avance']. "%" . "</td>";
                    echo "<td style='text-align: right;$border'>" . "$".(number_format($value2['Presupuesto'], 2, '.', ',')) . "</td>";
                    echo "<td style='text-align: right;$border'>" . "$".(number_format($value2['Consumo'], 2, '.', ',')) . "</td>";
                    echo "</tr>";
                }
            }
            if(!$hayInfo)
                echo "<tr style='$border'><td colspan='8' style='text-align: center;$border'>".gpr::t('reporte', 'No Information')."</td></tr>";
        ?>
    </tbody>
<?php if($hayInfo): ?>
    <tfoot>
        <tr style='<?= $border ?>'>
            <th style='text-align: center; <?= $border ?>' colspan='5'><?= gpr::t("proyecto", "Total") ?></th>
            <th style='text-align: center; <?= $border ?>'><?= round(($avance / $contAvance), 2). "%" ?></th>
            <th style='text-align: right; <?= $border ?>'><?= "$".(number_format($presuesto, 2, '.', ',')) ?></th>
            <th style='text-align: right; <?= $border ?>'><?= "$".(number_format($consumo, 2, '.', ',')) ?></th>
        </tr>
    </tfoot>
<?php endif; ?>
</table>
<br/><br />
<?php } ?>
<br/>