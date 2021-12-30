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
                    'id' => 'grid_idiomas_list',
                    'showExport' => false,
                    //'fnExportEXCEL' => "exportExcel",
                    //'fnExportPDF' => "exportPdf",
                    'dataProvider' => $model,
                    'pajax' => true,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
                        [
                            'attribute' => 'idioma',
                            'header' => Yii::t("inscripcionposgrado", "Idioma"),
                            'value' => function($value){
                                if($value['idi'] == 3 ){
                                    return $value['idioma'];
                                }else if($value['idi'] != 3 ){
                                    return $value['nombre_idioma'];
                                }
                            }
                        ],
                        [
                            'attribute' => 'nivel_idioma',
                            'header' => Yii::t("inscripcionposgrado", "Nivel de Idioma"),
                            'value' => function($value){
                                if(isset($value['nivel_idioma']) && $value['nivel_idioma'] != "" )
                                    return $value['nivel_idioma'];
                                return "";
                            }
                        ],
                    ],
                ])
            ?>
            </div>
        </div>
    </div>
</form>