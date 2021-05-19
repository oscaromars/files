<?php
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\academico\Module as academico;
$semana = array(1 => Yii::t("formulario", "Monday"), 2 => Yii::t("formulario", "Tuesday"), 3 => Yii::t("formulario", "Wednesday"), 4 => Yii::t("formulario", "Thursday"), 5 => Yii::t("formulario", "Friday"), 6 => Yii::t("formulario", "Saturday"), 7 => Yii::t("formulario", "Sunday"),);
$dia = date("w", strtotime(date("Y-m-d")));
?>

<form class="form-horizontal" enctype="multipart/form-data" > 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">   
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <h4><span id="lbl_general"><?= academico::t("Academico", "Teacher Dialing") ?></span></h4> 
                </div>
            </div>            
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">   
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">                    
                    <table width="300px">
                        <thead>                            
                            <tr align="center" style="font-weight: bold;"> 
                                <td style="height:100px"></td>
                                <td>
                                    <div><h4><span id="lbl_general"><b><?= $periodo[0]['periodo']  ?></b></span></h4></div>   
                                </td>  
                            </tr>                           
                        </thead>  
                    </table>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <table width="475px">
                    <thead>
                        <tr align="right" style="font-weight: bold;"> 
                            <td>
                                <div class="cleanslate w24tz-current-time w24tz-middle" style="display: inline-block !important; visibility: hidden !important; min-width:300px !important; min-height:145px !important;"><p>
                                        <a href="//24timezones.com/es_husohorario/guayaquil_hora_actual.php" style="text-decoration: none" class="clock24" id="tz24-1549664993-c193-eyJob3VydHlwZSI6MTIsInNob3dkYXRlIjoiMSIsInNob3dzZWNvbmRzIjoiMCIsImNvbnRhaW5lcl9pZCI6ImNsb2NrX2Jsb2NrX2NiNWM1ZTAyZTE4YWFlNyIsInR5cGUiOiJkYiIsImxhbmciOiJlcyJ9" rel="nofollow"></a></p>
                                    <div id="clock_block_cb5c5e02e18aae7"></div></div>
                                <script type="text/javascript" src="//w.24timezones.com/l.js" async></script>    
                            </td>  
                        </tr>
                    </thead>  
                </table> 
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">            
            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 "> 
                <div id="gridmateria" style="display: block;">                    
                    <?=
                    $this->render('materia-grid.php', [
                        'model' => $model,
                    ]);
                    ?> 
                </div>
            </div>      
        </div> 
    </div> 
</form>
