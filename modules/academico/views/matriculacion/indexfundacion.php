 <?php

use yii\helpers\Html;
use app\modules\academico\Module as academico;

academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Registro de Asignaturas de fundacion por Carrera") ?></span></h3><br><br>
</div>
<div>
    <form class="form-horizontal">
        <?=
        $this->render('_formfund', [
            'arr_ninteres' => $arr_ninteres,   
            'arr_modalidad' => $arr_modalidad,
            'arr_carrerra1' => $arr_carrerra1,  
        ]);
        ?>
    </form>
</div>
 <div>
    <?=
    $this->render('indexfund-grid', [
        'model' => $model,
    ]);
    ?>
</div>