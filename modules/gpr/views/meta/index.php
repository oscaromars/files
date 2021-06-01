<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();
?>

<div>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
        <!-- Data -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= gpr::t("indicador", "Indicator Name") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $indicador->ind_nombre ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= gpr::t("indicador", "Base Line Initial") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $indicador->ind_linea_base ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= gpr::t("indicador", "Source File") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $indicador->ind_fuente_informe ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= gpr::t("indicador", "Calculation Method") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $indicador->ind_metodo_calculo ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= gpr::t("meta", "Year") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= date('Y', strtotime($indicador->ind_fecha_inicio)) ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= gpr::t("indicador", "Unit of Measure") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $unidadmedida ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= gpr::t("indicador", "Indicator Behavior") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $comportamiento ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><strong><?= gpr::t("meta", "Advance Goal") ?>: </strong></span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <span><?= $tipometa ?></span>
                </div>                
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <table class="table">
                <tbody><tr>
                  <th><?= gpr::t('umbral','Threshold Name') ?></th>
                  <th><?= gpr::t('umbral', 'Range Threshold') ?></th>
                  <th style="width: 40px"></th>
                </tr>
            <?php 
                foreach($modelUmbrales as $key => $value){
                    $range = ">= " . $value['umb_per_inicio'] . "%   <= " .$value['umb_per_fin']."%";
                    echo '<tr><td>'.$value['umb_nombre'].'</td><td>'.$range.'</td><td><span class="badge" style="background-color: '.$value['umb_color'].';">%</span></td></tr>';
                }
            ?>
                </tbody>
            </table>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
    </div>
</div>
<?=
    ($ind_fraccional == 1)?$this->render('grid-fraccional', ['model' => $model, 'niv_id' => $niv_id, 'isAdmin' => $isAdmin,]):$this->render('grid-numerico', ['model' => $model, 'niv_id' => $niv_id, 'isAdmin' => $isAdmin,])
?>
<input type="hidden" id="frm_id" value="<?= $indicador->ind_id ?>" />
<input type="hidden" id="frm_tipoM" value="<?= $comportamiento . '/' . $tipometa ?>" />
<input type="hidden" id="frm_fraccional" value="<?= $indicador->ind_fraccional ?>" />
<input type="hidden" id="frm_nivel" value="<?= $niv_id ?>" />