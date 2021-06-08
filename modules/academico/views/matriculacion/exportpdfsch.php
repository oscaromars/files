 <?php

use yii\helpers\Html;
use app\modules\academico\Module as Academico;
Academico::registerTranslations();

?>
   
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="margin-bottom: 5px;">
        <div style="text-align: center;"><img alt="banner" src="<?= $pathImg . "/" . $bannerImg ?>" style="border-radius:4px;height:150px;"></div>
    </div>
    <!-- Data -->
    <br><br>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        
        <table>
            <tbody>
                <tr>
                    <td width="40%"><strong><?= Academico::t("matriculacion", "Academic Period") ?>: </strong></td>
                    <td width="60%"><?= $data_student['pla_periodo_academico'] ?></td>
                </tr>
                <tr>
                    <td><strong><?= Academico::t("matriculacion", "Student") ?>: </strong></td>
                    <td><?= $data_student['pes_nombres'] ?></td>
                </tr>
                <tr>
                    <td><strong><?= Academico::t("matriculacion", "SSN/Passport") ?>: </strong></td>
                    <td><?= $data_student['pes_dni'] ?></td>
                </tr>
                <tr>
                    <td><strong><?= Academico::t("matriculacion", "Academic Unit") ?>: </strong></td>
                    <td><?= $data_student['pes_jornada'] ?></td>
                </tr>
                <tr>
                    <td><strong><?= Academico::t("matriculacion", "Modality") ?>: </strong></td>
                    <td><?= $data_student['mod_nombre'] ?></td>
                </tr>
                <tr>
                    <td><strong><?= Academico::t("matriculacion", "Career") ?>: </strong></td>
                     <td><?= $data_student['pes_carrera'] ?></td>
                </tr>
                <tr>
                     <td><strong><?= Academico::t("matriculacion", "Phone") ?>: </strong></td>
                     <td><?= $data_student['per_celular'] ?></td>
                </tr>
            </tbody>
        </table>      
    </div>
</div>
<br><br><br>
<?=
    $this->render('exportpdfsch-grid', ['planificacion' => $planificacion, 'materiasxEstudiante' => $materiasxEstudiante,]);
?>