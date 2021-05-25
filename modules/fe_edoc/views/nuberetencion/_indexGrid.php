<?php
/**
 * Este Archivo contiene las vista de Compañias
 * @author Ing. Byron Villacreses <byronvillacreses@gmail.com>
 * @copyright Copyright &copy; SolucionesVillacreses 2014-09-24
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\fe_edoc\models\VSacceso;
?>
<?=

PbGridView::widget([
    //'dataProvider' => new yii\data\ArrayDataProvider(array()),
    'id' => 'TbG_DOCUMENTO',
    'showExport' => true,
    'fnExportEXCEL' => "exportExcel",
    'fnExportPDF' => "exportPdf",
    'dataProvider' => $model,
    'columns' =>
        [
        [
            //'id' => 'chkId',
            'class' => 'app\widgets\PbGridView\PbCheckboxColumn',
            //'cssClassExpression' => '($data["Estado"]=="2")?"disabled":""',
            //'disabled' => '($data["Estado"]=="2")?true:false',
        ],
        [
            'attribute' => 'IdDoc',
            'header' => Yii::t('fe_edoc', 'IdDoc'),
            'value' => 'IdDoc',
            'visible' => '0',
            //'header' => false,
            //'filter' => false,
            //'headerHtmlOptions' => array('style' => 'width:0px; display:none; border:none; textdecoration:none'),
            //'options' => array('style' => 'display:none; border:none;'),
        ],
        
        [
            'attribute' => 'Estado',
            'header' => Yii::t('fe_edoc', 'Status'),
            'value' => function ($data) {
                return VSacceso::estadoAprobacion($data["Estado"]);
            },
        ],
        /*[
            'attribute' => 'DocSustentoERP',
            'header' => Yii::t('fe_edoc', 'Document type'),
            'value' => 'DocSustentoERP',
        ],*/
        [
            'attribute' => 'NumDocumento',
            'header' => Yii::t('fe_edoc', 'Document Number'),
            'options' => array('style' => 'text-align:center'),
            'value' => 'NumDocumento',
        ],
        [
            'attribute' => 'FechaEmision',
            'header' => Yii::t('fe_edoc', 'Issuance date'),
            'value' => function ($data) {
                return date(Yii::$app->params["dateByDefault"], strtotime($data["FechaEmision"]));
            },
            //'value' => 'date(Yii::$app->params["dateByDefault"],strtotime($data["FechaEmision"]))',
        ], 
        [
            'attribute' => 'FechaAutorizacion',
            'header' => Yii::t('fe_edoc', 'Authorization date'),
            //'value' => '($data["FechaAutorizacion"]<>"")?date(Yii::$app->params["dateByDefault"],strtotime($data["FechaAutorizacion"])):"";',
            'value' => function ($data) {
                return ($data["FechaAutorizacion"] <> "") ? date(Yii::$app->params["dateByDefault"], strtotime($data["FechaAutorizacion"])) : "";
            },
        ],
        [
            'attribute' => 'IdentificacionSujetoRetenido',
            'header' => Yii::t('fe_edoc', 'Dni/Ruc'),
            'value' => 'IdentificacionSujetoRetenido',
        ],
        [
            'attribute' => 'RazonSocialComprador',
            'header' => Yii::t('fe_edoc', 'Company name'),
            //'htmlOptions' => array('style' => 'text-align:left', 'width' => '300px'),
            'value' => 'RazonSocialSujetoRetenido',
        ],
        [
            'attribute' => 'TotalRetencion',
            'header' => Yii::t('fe_edoc', 'Total amount'),
            //'value' => '$data["ImporteTotal"]',
            //'value' => 'Yii::$app->format->formatNumber($data["TotalRetencion"])',
            'options' => array('style' => 'text-align:right', 'width' => '8px'),
            'value' => function ($data) {
                return Yii::$app->params["currency"].Yii::$app->formatter->format($data["TotalRetencion"],["decimal", 2]);
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('fe_edoc', 'Error'),
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    //return Html::a('<span>' . substr($model['Mensaje'], 0, 20) . '... </span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['Mensaje']]);
                    return VSacceso::mensajeErrorSri($model['Mensaje']);
                },
            ],
        ],
        [
            'header' => Yii::t('fe_edoc', 'Download'),
            'class' => 'yii\grid\ActionColumn',
            'options' => array('style' => 'text-align:center', 'width' => '85px'),
            'template' => '{pdf} {xml}',
            'buttons' => array(
                'pdf' => function ($url, $model) {
                    return Html::a('<span class="text-danger fa fa-file-pdf-o"></span>', Url::to(['nuberetencion/generarpdf', 'ids' => base64_encode($model['IdDoc'])]), ["data-toggle" => "tooltip", "title" => Yii::t('fe_edoc', 'Download PDF document'), "data-pjax" => 0]);
                },
                'xml' => function ($url, $model) {
                    return Html::a('<span class="text-success fa fa-file-code-o"></span>', Url::to(['nuberetencion/xmlautorizado', 'ids' => base64_encode($model['IdDoc'])]), ["data-toggle" => "tooltip", "title" => Yii::t('fe_edoc', 'Download XML document'), "data-pjax" => 0]);
                },
            ),
        ],
    ],
]);
?>
