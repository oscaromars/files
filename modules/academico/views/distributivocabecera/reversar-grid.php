<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();

?>

<div>

    <?=

    PbGridView::widget([
        'id' => 'Tbg_DistribAca_Profesor',
        //'showExport' => true,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $arr_detalle,
        //'pajax' => false,
        'columns' =>
        [            
            [
                'attribute' => 'Tipo Asignación',
                'header' => academico::t("Academico", "Assignment Type"),
                'value' => 'tipo_asignacion',
            ],  
            [
                'attribute' => 'UnidadAcademica',
                'header' => Yii::t("formulario", "Academic unit"),
                'value' => 'UnidadAcademica',
            ],   
            [
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Mode"),
                'value' => 'Modalidad',
            ],        
            [
                'attribute' => 'Asignatura',
                'header' => Yii::t("formulario", "Subject"),
                'value' => 'Asignatura',
            ],  
                                                                      
            [
                'attribute' => 'Horario',
                'header' => academico::t("Academico", "Schedule"),
                'value' => 'horario',
            ],                                               
        ],
    ])
    ?>
</div>