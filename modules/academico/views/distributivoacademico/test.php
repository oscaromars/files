<?php
     use kartik\dynagrid\DynaGrid;
 use kartik\grid\GridView;
  use yii\helpers\ArrayHelper;   
use yii\helpers\Html;


$columns = 
[
    'daca_id',
    'pro_id'
    
      
    
    
];

$dynagrid = DynaGrid::begin([
    'columns' => $columns,
    'theme'=>'panel-info',
    'showPersonalize'=>true,
    'storage' => 'session',
    'gridOptions'=>[
        'dataProvider'=>$dataProvider,
        //'filterModel'=>$searchModel,
        'showPageSummary'=>true,
        'floatHeader'=>true,
        'pjax'=>true,
        'responsiveWrap'=>false,
         'exportConfig' => [
        GridView::HTML => ['label' => 'HTML'],
        // GridView::CSV => ['label' => 'CSV'],
        // GridView::TEXT  => ['label' => 'Text'],
        GridView::EXCEL => ['label' => 'Excel'],
         GridView::PDF => ['label' => 'PDF'],
        // GridView::JSON => ['label' => 'JSON'],
    ],
        'panel'=>[
            'heading'=>'<h3 class="panel-title"><i class="fas fa-book"></i>  Distributivo</h3>',
            'before' =>  '<div style="padding-top: 7px;"><em>* The table header sticks to the top in this demo as you scroll</em></div>',
            'after' => false
        ],
        'toolbar' =>  [
            ['content'=>
                Html::button('<i class="fas fa-plus"></i>', ['type'=>'button', 'title'=>'Add Book', 'class'=>'btn btn-success', 'onclick'=>'test();']) . ' '.
                Html::a('<i class="fas fa-repeat"></i>', ['dynagrid-demo'], ['data-pjax'=>0, 'class' => 'btn btn-outline-secondary', 'title'=>'Reset Grid'])
            ],
            ['content'=>'{dynagridFilter}{dynagridSort}{dynagrid}'],
            '{export}',
            
        ]
    ],
    'options'=>['id'=>'daca_id'] // a unique identifier is important
]);
if (substr($dynagrid->theme, 0, 6) == 'simple') {
    $dynagrid->gridOptions['panel'] = false;
}  
DynaGrid::end();
 ?>       
  

