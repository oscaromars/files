<?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

academico::registerTranslations();
print_r("GRUPO ROL ".$model[0]['id']);  echo "<BR>";
print_r("PACA ID ".$model1[0]['id']);  echo "<BR>";
print_r("PACA NOMBRE ".$model1[0]['name']); echo "<BR>";
print_r("USER ".$model2); echo "<BR>";
print_r("UACA ID ".$model3[0]['id']);  echo "<BR>";
print_r("UACA NOMBRE ".$model3[0]['name']); echo "<BR>";
print_r("EACA ID ".$model4[0]['id']);  echo "<BR>";
print_r("EACA NOMBRE ".$model4[0]['name']); echo "<BR>";
print_r($model6);
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>