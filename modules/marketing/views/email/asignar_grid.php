<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\marketing\Module as marketing;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as crm;

academico::registerTranslations();
crm::registerTranslations();
?>
    <?=

    PbGridView::widget([
        //'dataProvider' => new yii\data\ArrayDataProvider(array()),
        'id' => 'Tbg_SubsLista',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'columns' =>
        [
            [
                'attribute' => 'Contacto',
                'header' => crm::t("crm", "Contact"),
                'value' => 'contacto',
            ],
            [
                'attribute' => 'Carrera',
                'header' => academico::t("Academico", "Career/Program"),
                'value' => 'carrera',
            ],
            [
                'attribute' => 'Correo',
                'header' => marketing::t("marketing", "Email"),
                'value' => 'per_correo',
            ],
            [
                'attribute' => 'Subscriptor',
                'header' => marketing::t("marketing", "Estado"),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["estado_mailchimp"] == 1)
                        return '<small class="label label-warning">Mailchip</small>';
                    else if ($model["estado"] == 1)
                        return '<small class="label label-success">Suscrito</small>';
                    else
                        return '<small class="label label-danger">No suscrito</small>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{addsubs} {addsublistinte} {rmsubs}',
                'buttons' => [
                    'addsubs' => function ($url, $model) {
                        if ($model["estado_mailchimp"] == 0){
                            if (($model["estado"] == 1)) {
                                return '<span class="glyphicon glyphicon-plus"></span>';
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-plus"></span>', "#", ["onclick" => "preguntasuscribirContacto(" . $model['per_id'] . "," . $model['id_pges'] . "," . $model['lis_id'] .");", "data-toggle" => "tooltip", "title" => "Suscribirse a la lista", "data-pjax" => 0]);
                            }
                        }else{
                            return '<span class="glyphicon glyphicon-plus"></span>';
                        }
                            
                    },
                    'rmsubs' => function ($url, $model) {
                        if ($model["estado_mailchimp"] == 0){
                            if ($model["estado"] == 1) {
                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', "#", ["onclick" => "RemoverSuscritor(" . $model['per_id'] . "," . $model['id_pges'] . "," . $model['lis_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Suscritor", "data-pjax" => 0]);
                            } else {
                                return "<span class = 'glyphicon glyphicon-remove' data-toggle = 'tooltip' title ='No se puede remover un contacto que no se haya suscrito'  data-pjax = 0></span>";
                            }
                        }else{
                            return "<span class = 'glyphicon glyphicon-remove' data-toggle = 'tooltip' title ='No se puede remover un contacto que ya se ha cargado a Mailchimp'  data-pjax = 0></span>";
                        }
                    },
                ],
            ],
        ],
    ])
    ?>