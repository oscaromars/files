<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use yii\grid\CheckboxColumn;
academico::registerTranslations();
?>
<br /><br />
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("matriculacion", "Block 1") ?></label>
            <table class="table table-bordered table-subjects">
                <thead>
                    <tr>
                        <th><?= academico::t("matriculacion", "Subject")  ?></th>
                        <th style="width: 5px"><?= academico::t("matriculacion", "Hour")  ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($materias as $key => $value){
                            if($value["Block"] == "B1")
                                echo "<tr><td>".strtoupper($value["Subject"])."</td><td>".$value["Hour"]."</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <label class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label"><?= academico::t("matriculacion", "Block 2") ?></label>
            <table class="table table-bordered table-subjects">
                <thead>
                    <tr>
                        <th><?= academico::t("matriculacion", "Subject")  ?></th>
                        <th style="width: 5px"><?= academico::t("matriculacion", "Hour")  ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($materias as $key => $value){
                            if($value["Block"] == "B2")
                                echo "<tr><td>".strtoupper($value["Subject"])."</td><td>".$value["Hour"]."</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</form>