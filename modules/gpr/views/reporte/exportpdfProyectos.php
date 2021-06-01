
<?php

use app\modules\gpr\models\Umbral;
use app\models\Utilities;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

$border = "border: 1px solid #ccc4c4;";

?>

<table>
    <thead>
        <tr>
            <th style="<?= $border?>"><?= strtoupper(gpr::t("proyecto", "Project")) ?></th>
            <th style="text-align: center; <?= $border?>"><?= strtoupper(gpr::t("tipoproyecto", "Project Type")) ?></th>
            <th style="<?= $border?>"><?= strtoupper(gpr::t("unidad", "Unity")) ?></th>
            <th style="text-align: center; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "Initial Date")) ?></th>
            <th style="text-align: center; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "End Date")) ?></th>
            <th style="<?= $border?>"><?= strtoupper(gpr::t("hito", "Milestone")) ?></th>
            <th style="text-align: center; <?= $border?>"><?= strtoupper(gpr::t("hito", "Deliver Date")) ?></th>
            <th style="text-align: center; <?= $border?>"><?= strtoupper(gpr::t("hito", "Actual Date")) ?></th>
            <th style="text-align: center; <?= $border?>"><?= strtoupper(gpr::t("hito", "Milestone Status")) ?></th>
            <th style="text-align: center; <?= $border?>"><?= strtoupper(gpr::t("hito", "Milestone Accomplished")) ?></th>
            <th style="text-align: center; <?= $border?>"><?= strtoupper(gpr::t("hito", "Progress")) ?></th>
            <th style="text-align: right; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "Budget")) ?></th>
            <th style="text-align: right; <?= $border?>"><?= strtoupper(gpr::t("proyecto", "Budget Consumed")) ?></th>
        </tr>
    </thead>
    <tbody>
    <?php 
        $hayInfo1 = false;
        $avance = 0;
        $contAvance = 0;
        $presuesto = 0;
        $consumo = 0;
        foreach($arr_body as $items => $item){
            $hayInfo1 = true;
            $rowSpan = $item['CantHito'];
            $cont = 0;
            echo "<tr style='$border'>";
            echo "<td style='$border' rowspan='$rowSpan'>". $item['Nombre'] ."</td>";
            echo "<td style='text-align: center; $border' rowspan='$rowSpan'>". $item['TipoProyecto'] ."</td>";
            echo "<td style='$border' rowspan='$rowSpan'>". $item['Unidad'] ."</td>";
            echo "<td style='text-align: center; $border' rowspan='$rowSpan'>". date(Yii::$app->params["dateByDefault"], strtotime($item['FechaInicio'])) ."</td>";
            echo "<td style='text-align: center; $border' rowspan='$rowSpan'>". date(Yii::$app->params["dateByDefault"], strtotime($item['FechaFin'])) ."</td>";
            //echo "<td colspan='8'>". $item['FechaInicio'] ."</td>";
            foreach($arr_body2 as $key => $value){
                if($value['IdPro'] === $item['id']){
                    $avance += $value['Avance'];
                    $contAvance ++;
                    $presuesto += $value['Presupuesto'];
                    $consumo += $value['Consumo'];

                    if($cont != 0) echo "<tr>";
                    $cumplido = $value['HitoCumplido'];
                    $fechaEstimada = $value['FechaCompromiso'];
                    $fechaReal = $value['FechaReal'];
                    $proyectoIni = $value['ProyectoInicio'];
                    $current = date(Yii::$app->params["dateTimeByDefault"]);
                    $diffDias = Utilities::getDiffDate($proyectoIni, $fechaEstimada);
                    $promDias = $diffDias / 2;
                    $currentDias = Utilities::getDiffDate($proyectoIni, $current);
                    $colorOnTime = "";
                    $labelOnTime = "";
                    if($cumplido == 1){
                        $diffDiasFin = Utilities::getDiffDate($proyectoIni, $fechaReal);
                        if($diffDiasFin <= $diffDias){
                            $colorOnTime = "#00a65a";
                            $labelOnTime = gpr::t("hito", "Finished");
                        }
                        $colorOnTime = "#dd4b39";
                        $labelOnTime = gpr::t("hito", "Finished");
                    }
                    if($promDias > $currentDias){
                        $colorOnTime = "#00a65a";
                        $labelOnTime = gpr::t("hito", "On Time");
                    }
                    elseif($currentDias > $diffDias){
                        $colorOnTime = "#dd4b39";
                        $labelOnTime = gpr::t("hito", "Unfinished");
                    }
                    else{
                        $colorOnTime = "#f39c12";
                        $labelOnTime = gpr::t("hito", "Delay");
                    }
                    echo "<td style='$border'>" . $value['Nombre'] . "</td>";
                    echo "<td style='text-align: center; $border'>" . date(Yii::$app->params["dateByDefault"], strtotime($value['FechaCompromiso'])) . "</td>";
                    echo "<td style='text-align: center; $border'>" . ((isset($value['FechaReal']) && $value['FechaReal'] != '')?date(Yii::$app->params["dateByDefault"], strtotime($value['FechaReal'])):"-")  . "</td>"; 
                    echo "<td style='text-align: center; $border'>" . '<span style="background-color: '.$colorOnTime.'; color: #FFFFFF; font-weight: bolder;">'.strtoupper($labelOnTime).'</span>' . "</td>";
                    echo "<td style='text-align: center; $border'>" . (($value['HitoCumplido']==1)?(gpr::t('hito', 'Yes')):(gpr::t('hito', 'No'))) . "</td>";
                    echo "<td style='text-align: center; $border'>" . $value['Avance'] . '%' ."</td>";
                    echo "<td style='text-align: right; $border'>" . "$".(number_format($value['Presupuesto'], 2, '.', ',')) . "</td>";
                    echo "<td style='text-align: right; $border'>" . "$".(number_format($value['Consumo'], 2, '.', ',')) . "</td>";
                    if($cont != 0) echo "</tr>";
                    if($cont == 0) echo "</tr>";
                    $cont++;
                }
            }
            if($cont == 0)  echo "<td colspan='8' style='text-align: center;$border'>".gpr::t('reporte', 'No Information')."</td></tr>";
            //reset($arr_body2);
        }
        if(!$hayInfo1){
            echo "<tr style='$border'><td colspan='13' style='text-align: center;$border'>".gpr::t('reporte', 'No Information')."</td></tr>";
        }
    ?>
    </tbody>
<?php if($hayInfo1): ?>
    <tfoot>
        <tr style='<?= $border ?>'>
            <th style='text-align: center; <?= $border ?>' colspan='10'><?= gpr::t("proyecto", "Total") ?></th>
            <th style='text-align: center; <?= $border ?>'><?= round(($avance / $contAvance), 2). "%" ?></th>
            <th style='text-align: right; <?= $border ?>'><?= "$".(number_format($presuesto, 2, '.', ',')) ?></th>
            <th style='text-align: right; <?= $border ?>'><?= "$".(number_format($consumo, 2, '.', ',')) ?></th>
        </tr>
    </tfoot>
<?php endif; ?>
</table>

<br/>