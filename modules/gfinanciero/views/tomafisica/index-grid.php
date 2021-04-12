<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gfinanciero\Module as financiero;
financiero::registerTranslations();

?>

<div>
<?=
    PbGridView::widget([
        'id' => 'grid_list',
        'showExport' => false,
        'summary' => '',
        'fnExportEXCEL' => "",
        'fnExportPDF' => "",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Code',
                'header' => financiero::t("articulo", "Code"),
                'value' => 'Code',
            ],
            [
                'attribute' => 'Name',
                'header' => financiero::t("articulo", "Article Name"),
                'value' => 'Name',
            ],
            [
                'attribute' => 'EFisica',
                'header' => financiero::t("tomafisica", "Physical Count"),
                'value' => 'EFisica',
            ],
            [
                'attribute' => 'ETotal',
                'header' => financiero::t("tomafisica", "Current Stock"),
                'value' => 'ETotal',
            ],
            [
                'attribute' => 'Estado',
                'header' => financiero::t("tomafisica", "Status"),
                'value' => 'Estado',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{edit}',
                'buttons' => [
                    'edit' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('edit').'"></span>', null, ['href' => 'javascript:editTomaFisica("'.$model['Code'].'");', "data-toggle" => "tooltip", "title" => Yii::t("accion","Edit")]);
                    },
                ],
            ],
        ],
    ])
?>
</div>
<br/>
<div><span style="font-weight: bold;"><?= financiero::t("tomafisica", "Number of Items") ?>: </span><span id="citems"><?= number_format($model->getCount(), 0, '.', ',') ?></span></div>
<br/>