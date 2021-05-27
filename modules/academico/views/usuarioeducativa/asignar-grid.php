<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\models\CursoEducativa;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
/*use kartik\grid\GridView;
use kartik\grid\DataColumn;
use kartik\grid\EditableColumn;
use kartik\grid\Editable;*/
use yii\helpers\ArrayHelper;
//print_r($model);
//print_r($arr_curso);
admision::registerTranslations();
academico::registerTranslations();
?>
<div>
    <?=
    PbGridView::widget([
        'id' => 'Tbg_Asigdistributivo',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelasigd",
        'fnExportPDF' => "exportPdfasigd",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [
            [ 
                'attribute' => '#',
                'header' => academico::t("Academico", "#"),
                'value' => 'pro_id',
            ],

            [ 
                'attribute' => 'Nombres',
                'header' => academico::t("Academico", "Teacher"),
                'value' => 'Nombres',
            ],
            [ 
                'attribute' => 'Cedula',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'Cedula',
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
                'attribute' => 'Periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'Periodo',
            ],
            [
                'attribute' => 'Asignatura',
                'header' => Yii::t("formulario", "Subject"),
                'value' => 'Asignatura',
            ],  
            [
                'attribute' => 'Jornada',
                'header' => academico::t("Academico", "Working day"),
                'value' => 'Jornada',
            ],   
            [
                'attribute' => 'cursos',
                'header' => academico::t("Academico", "Aulas"),
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => 'Select'
                ],
                'format' => 'raw',
                'value'  => function ($model) {
                    return Html::dropDownList('cursos', empty($model['id'])?0:$model['id'], ArrayHelper::map($model['cursos'] , "id", "name"), ["class" => "form-control", "id" => "curso_".$model['id'] ]);                                        
                    }
            ],      
        ],
    ])
    ?>
</div>