<?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
Academico::registerTranslations();

?>

<br></br>
<br></br>
<?=
    $this->render('exportpdfclfc-grid', ['model' => $model]);
?>