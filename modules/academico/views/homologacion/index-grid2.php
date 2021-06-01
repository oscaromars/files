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
    <table class="table responsive table-striped table-bordered dataTable" id="tabla_materias" width="100%">
        <thead>
            <tr>
                <th>CARRERA MALLA 2017</th>
                <th>MATERIA MALLA 2017</th>
                <th>CARRERA MALLA 2020</th>
                <th>MATERIA MALLA 2020</th>
                <th>NOTA</th>
                <th>PERIODO</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>