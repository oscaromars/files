<?php
include('NubeFactura.php');//para HTTP
$obj = new NubeFactura();
$res=$obj->enviarDocRecepcion();
//echo "llego";
?>