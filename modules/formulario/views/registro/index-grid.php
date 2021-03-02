<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'PBgrid_personaform',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombres',
                'header' => Yii::t("formulario", "Names"),
                'value' => 'nombres',
            ],
            [
                'attribute' => 'Apellidos',
                'header' => Yii::t("formulario", 'Last Names'),
                'value' => 'apellidos',
            ],
            [
                'attribute' => 'Dni',
                'header' => Yii::t("formulario", "Dni"),
                'value' => 'dni',
            ],
            [
                'attribute' => 'Correo',
                'header' => Yii::t("perfil", 'Email'),
                'value' => 'correo',
            ],
            [
                'attribute' => 'Celular',
                'header' => Yii::t("perfil", 'CellPhone')."/".Yii::t("formulario", 'Phone'),
                'value' => 'celular_telefono',
            ],
            [
                'attribute' => 'Institucion',
                'header' => Yii::t("formulario", 'Institution'),
                'value' => 'institucion',
            ],        
            [
                'attribute' => 'Provincia',
                'header' => Yii::t("general", 'State'),
                'value' => 'provincia',
            ],
            [
                'attribute' => 'Canton',
                'header' => Yii::t("general", 'City'),
                'value' => 'canton',
            ],                                   
            [
                'attribute' => 'Unidad',
                'header' => Yii::t("formulario",'Academic unit'),
                'value' => 'unidad',
            ],
            [
                'attribute' => 'Carrera',
                'header' => academico::t("Academico", "Career/Program"),
                'value' => 'carrera',
            ],  
            [
                'attribute' => 'Fecha',
                'header' => Yii::t("formulario", "Registration Date"),
                'value' => 'fecha_registro',
            ],  
        ],
    ])
?>
