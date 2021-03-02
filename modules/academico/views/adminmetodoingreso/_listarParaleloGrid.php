<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;

use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as aspirante;

admision::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();
aspirante::registerTranslations();
?>
<div class="col-md-12">    
    <h4><span id="lbl_titulo1"><?= $periodo ?></span></h4><br/>    
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div>        
         <?=
            PbGridView::widget([
                //'dataProvider' => new yii\data\ArrayDataProvider(array()),
                'id' => 'Pbgparalelo',
                //'showExport' => true,
                //'fnExportEXCEL' => "exportExcel",
                //'fnExportPDF' => "exportPdf",
                'dataProvider' => $mod_paralelo,
                'columns' => [            
                    [
                        'attribute' => 'Nombre',
                        'header' => Yii::t("formulario", "Name"),
                        'value' => 'nombre',
                    ],    
                    [
                        'attribute' => 'Descripción',
                        'header' => Yii::t("formulario", "Description"),
                        'value' => 'descripcion',
                    ],
                    [
                        'attribute' => 'Cupo',
                        'header' => academico::t("Academico", "Quota"),
                        'value' => 'cupo',
                    ],                                                   
                ],                                   
            ])
        ?>
    </div>
</form>