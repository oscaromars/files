<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use kartik\grid\GridView;
use kartik\grid\DataColumn;
use kartik\grid\EditableColumn;
use kartik\grid\Editable;
use app\models\Utilities;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;

use app\assets\DatatableAsset;
DatatableAsset::register($this);
academico::registerTranslations();
//$isreg = array($model{'isreg'});
//var_dump($isreg);
?>


<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style=" margin-top: 10px;">
    <div class="spinner" style="display:none">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
    <br>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group' id="periodocalif">
    </div>
    
    
    <div class="container" style="width:auto;" id="tablacontenedor">
        <table id="gridResumen" class="display compact responsive nowrap" style="width:100%">
            <thead id="html_thead">
                <tr>
                    <th class='no-sort'><i class='fa fa-clone' aria-hidden='true'></i></th>
                    <th></th>
                    <th>No.</th>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Materia</th>
                    <th>Parcial</th>
                    <th>Asíncrona</th>
                    <th>Síncrona</th>
                    <th>Autónoma</th>
                    <th>Evaluación</th>
                    <th>Examen</th>
                    <th>Total</th>
                    <th>paca_id</th>
                    <th>est_id</th>
                    <th>pro_id</th>
                    <th>asi_id</th>
                    <th>ecal_id</th>
                    <th>uaca_id</th>
                    <th>ccal_id</th>
                </tr>
            </thead>
        </table>
    </div> 
</div>

<script type="text/javascript">

    $(document).ready(function () {
        //1 para indicarle que viene de document ready
        var editor;
        $('#gridResumen').DataTable({
            responsive: true,
            destroy: true,
            paging: false,
            retrieve: false,
            columnDefs: [      
                    {
                        "targets": [ 13,14,15,16,17,18,19,20 ],
                        "visible": false,
                        "searchable": false
                    },  
                ],
        });
        $('#cmb_profesor_rc').change();
    });
</script>
