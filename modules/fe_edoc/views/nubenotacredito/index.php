<?php
/**
 * Este Archivo contiene las vista de Compañias
 * @author Ing. Byron Villacreses <byronvillacreses@gmail.com>
 * @copyright Copyright &copy; SolucionesVillacreses 2014-09-24
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
?>
<div class="col-lg-12">
    <form class="form-horizontal">
        <?= $this->render('_frm_BuscarGrid', array('model' => $model, 'tipoDoc' => $tipoDoc,'tipoApr'=> $tipoApr)); ?>
    </form>
</div>
<div class="col-lg-12">
    <?php echo $this->render('_indexGrid', array('model' => $model)); ?>
</div>
