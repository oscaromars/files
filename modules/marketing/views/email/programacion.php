<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\marketing\Module;

$dia_marcados = explode(",", $arr_ingreso["dia_programa"]);
for ($m = 0; $m < count($dia_marcados); $m++) {

    $keys[$dia_marcados[$m]] = $dia_marcados[$m];
}
?>
<?= Html::hiddenInput('txth_list', isset($_GET["lisid"])?$_GET["lisid"]:base64_encode(0), ['id' => 'txth_list']); ?>
<?= Html::hiddenInput('txth_muestra', $muestra, ['id' => 'txth_muestra']); ?>
<form class="form-horizontal" enctype="multipart/form-data" > 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Yii::t("formulario", "Datos de la Lista") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombre" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre"><?= Yii::t("formulario", "Name") ?></label>
                    <span for="txt_nombre" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_nombre"><?= $arr_lista['lis_nombre'] ?> </span> 
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_no_subs" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="txt_no_subs"><?= Module::t("marketing", "No. Subscribers in Mailchimp") ?></label>
                    <span for="txt_no_subs" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="txt_no_subs"><?= $arr_lista['num_suscr_mailchimp'] ?> </span> 
                </div>
            </div> 
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="cmb_pla_id" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre"><?= Module::t("marketing", "Template") ?></label>
                    <div class="col-sm-9">
                        <?= Html::dropDownList("cmb_pla_id", 1, $arr_templates, ["class" => "form-control", "id" => "cmb_pla_id"]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6"></div>
        </div>
    </div> 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">    
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Module::t("marketing", "Days Schedule") ?></span></h4> 
            </div>
        </div> 
        <div class="col-md-12"> 
            <!-- AQUI EMPIEZA FOR-->  
            <table class="table table-bordered">
                <thead>
                    <tr align="center" style="font-weight: bold;"> 
                        <td><?= Yii::t("formulario", "Monday") ?></td>
                        <td><?= Yii::t("formulario", "Tuesday") ?></td>
                        <td><?= Yii::t("formulario", "Wednesday") ?></td>
                        <td><?= Yii::t("formulario", "Thursday") ?></td>
                        <td><?= Yii::t("formulario", "Friday") ?></td>
                        <td><?= Yii::t("formulario", "Saturday") ?></td>
                        <td><?= Yii::t("formulario", "Sunday") ?></td>
                    </tr>
                </thead>              
                <?php for ($i = 1; $i < 2; $i++) { ?>
                    <tr align="center">                           
                        <?php
                        for ($j = 1; $j < 8; $j++) {
                            ?>                                    
                            <td><input type="checkbox" class="check_dias" <?php echo $deshabilita; ?>  <?php
                    if ($keys[$j] == $j) {
                        echo 'checked="checked"';
                    }
                            ?> name="<?php echo 'check_dia_' . $j; ?>"  id="<?php echo 'check_dia_' . $j; ?>" value="<?php echo $j; ?>"> </td> 
                                       <?php
                            }
                            ?>  
                    </tr>                      
                <?php } ?>
            </table> 
            <!-- AQUI TERMINA FOR-->
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">          
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_fecha_inicio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5  control-label"><?= Yii::t("formulario", "Start date") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?=
                        DatePicker::widget([
                            'name' => 'txt_fecha_inicio',
                            'value' => $arr_ingreso["fecha_desde"],
                            'disabled' => $habilita,
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_inicio", "data-type" => "", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Start date")],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => Yii::$app->params["dateByDatePicker"],
                            ]
                        ]);
                        ?>
                    </div>               
                </div>                    
            </div>     
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_fecha_fin" class="col-sm-5 col-md-5 col-xs-5 col-lg-5  control-label"><?= Yii::t("formulario", "End date") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7 ">
                        <?=
                        DatePicker::widget([
                            'name' => 'txt_fecha_fin',
                            'value' => $arr_ingreso["fecha_hasta"],
                            'disabled' => $habilita,
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_fin", "data-type" => "fecha_fin", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "End date")],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => Yii::$app->params["dateByDatePicker"],
                            ]
                        ]);
                        ?>
                    </div>                    
                </div>                    
            </div>                 
        </div>  
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txthoraenvio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Module::t("marketing", "Shipping Time") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input id="txthoraenvio" type="text" class="form-control PBvalidation keyupmce input-small" <?php echo $deshabilita; ?> value="<?= $arr_ingreso["hora_envio"] ?>" data-type="tiempo" data-keydown="true" placeholder="<?= Yii::t('formulario', 'HH:MM') ?>">
                        </div>
                    </div>
                </div>
            </div>         
        </div>        
    </div>    
</form>
