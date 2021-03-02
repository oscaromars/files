<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\piensaecuador\models\PersonaExterna;
use app\models\Utilities;
use app\modules\piensaecuador\Module as piensaecuador;
piensaecuador::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_personaext_list',
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
                'value' => 'Nombres',
            ],
            [
                'attribute' => 'Apellidos',
                'header' => Yii::t("formulario", 'Last Names'),
                'value' => 'Apellidos',
            ],
            [
                'attribute' => 'Dni',
                'header' => Yii::t("formulario", "Dni"),
                'value' => 'Dni',
            ],
            [
                'attribute' => 'Correo',
                'header' => Yii::t("perfil", 'Email'),
                'value' => 'Correo',
            ],
            [
                'attribute' => 'Celular',
                'header' => Yii::t("perfil", 'CellPhone'),
                'value' => 'Celular',
            ],
            [
                'attribute' => 'Telefono',
                'header' => Yii::t("perfil", 'Phone'),
                'value' => 'Telefono',
            ],
            /*[
                'attribute' => 'Genero',
                'header' => Yii::t("perfil", 'Sex'),
                'value' => function($data){
                    //$arr_genero = array("1" => Yii::t("formulario", "Female"), "2" => Yii::t("formulario", "Male"));
                    //$arr_genero = array("F" => Yii::t("formulario", "Female"), "M" => Yii::t("formulario", "Male"));
                    return $data['Genero'];
                    //return $arr_genero[$data['Genero']];
                },
            ],
            [
                'attribute' => 'FechaNacimiento',
                'header' => Yii::t("perfil", 'Birth Date'),
                'value' => 'FechaNacimiento',
            ],*/
            [
                'attribute' => 'Provincia',
                'header' => Yii::t("general", 'State'),
                'value' => 'Provincia',
            ],
            [
                'attribute' => 'Canton',
                'header' => Yii::t("general", 'City'),
                'value' => 'Canton',
            ],            
            [
                'attribute' => 'NivelInstruccion',
                'header' => piensaecuador::t("interes", 'Instruction Level'),
                'value' => 'NivelInstruccion',
            ],
            [
                'attribute' => 'NivelInteres',
                'header' => piensaecuador::t("interes", 'Activity'),
                'value' => function($data) use ($dataInteres) {
                    /*$model = new PersonaExterna();
                    $queryData = $model->getPersonaExtInteres($data["id"]);
                    $result = "";
                    $cont = 0;
                    foreach($queryData as $key => $value){
                        $result .= $value['interes'];
                        $cont++;
                        if(count($queryData) > $cont)
                            $result .= " | ";
                    }
                    return $result;*/
                    $pext_id = $data['id'];
                    $keys = array_keys(array_column($dataInteres, 'id'), $pext_id);
                    
                    $cont = 0;
                    $newValue = "";
                    foreach($keys as $key2 => $value2){
                        $id = $value2;
                        $newValue .= $dataInteres[$id]['interes'];
                        $cont++;
                        if(count($keys) > $cont)
                            $newValue .= " | ";
                    }
                    return $newValue;
                },
            ],
            [
                'attribute' => 'FechaRegistro',
                'header' => piensaecuador::t("interes",'Registry Date'),
                'value' => 'FechaRegistro',
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => Yii::t("general", "Status"),
                'value' => function($data){
                    if($data["Estado"] == Yii::t("general", "Enabled"))
                        return '<small class="label label-success">'.Yii::t("general", "Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.Yii::t("general", "Disabled").'</small>';
                },
            ],
        ],
    ])
?>
