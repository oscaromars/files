<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-secondary">
            <div>
            <?=
                PbGridView::widget([
                    'id' => 'grid_discapacidad_list',
                    'showExport' => false,
                    //'fnExportEXCEL' => "exportExcel",
                    //'fnExportPDF' => "exportPdf",
                    'dataProvider' => $model_dis,
                    'pajax' => true,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
                        [
                            'attribute' => 'discapacidad',
                            'header' => Yii::t("inscripcionposgrado", "Discapacidad"),
                            'value' => function($value){
                                if(isset($value['discapacidad']) && $value['discapacidad'] != "" )
                                    return $value['discapacidad'];
                                return "";
                            }
                        ],
                        [
                            'attribute' => 'porcentaje',
                            'header' => Yii::t("inscripcionposgrado", "Porcentaje de Discapacidad"),
                            'value' => function($value){
                                if(isset($value['porcentaje']) && $value['porcentaje'] != "" )
                                    return $value['porcentaje'];
                                return "";
                            }
                        ],
                    ],
                ])
            ?>
            </div>
        </div><br><br></br>
    </div>
</form>