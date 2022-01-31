<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use app\modules\academico\models\DistributivoAcademico;
$molde_distri = new DistributivoAcademico();
$result = $molde_distri->getDestalleDistributivo($model['dcab_id'], true);
?>

<div class="kv-detail-content">


    <div class="row">

        <table class="table table-bordered table-condensed table-hover small kv-table">
            <tbody>
                <tr class="danger">
                    <th class="text-center text-danger" colspan="3"><?php echo $detalle ?></th>
                </tr>
                <tr class="active">
                    <th class="text-center" >#</th>
                    <th>Tipo Distributivo</th>
                    <th>Unidad Académica</th>
                    <th>Periodo Academico</th>
                    <th>Materias</th>
                     <th>Horas</th>
                     <th>Promedio</th>
                </tr>
                <?php for ($i = 0; $i < sizeof($result); $i++) {?>
                    <tr>
                         <th class="text-center" ><?php echo $i + 1; ?></th>
                          <th><?php echo $result[$i]['tipo_asignacion'] ?></th>
                          <th><?php echo $result[$i]['UnidadAcademica'] ?></th>
                          <th><?php echo $result[$i]['periodo_academico'] ?></th>
                          <th><?php echo $result[$i]['Asignatura'] ?></th>
                          <th><?php echo $result[$i]['total_horas'] ?></th>
                          <th><?php echo $result[$i]['promedio'] ?></th>
                    </tr>
                    <?php }?>
            </tbody>
        </table>

    </div>
</div>