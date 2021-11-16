<?php
   $logFile   = dirname(__FILE__) . "/../../../runtime/logs/webservice.log";
   $dataDB   = include_once(dirname(__FILE__) . "/../config/mod.php");
   $dbname   = 'db_academico';
   $dbuser   = $dataDB["academico"]["db_academico"]["username"];
   $dbpass   = $dataDB["academico"]["db_academico"]["password"];
   $port     = "443";
   $dbserver = "127.0.0.1"; 
   $dbport   = 3306;
   $dsn      = "mysql:host=$dbserver;dbname=$dbname;port=$dbport";

function putMessageLogFile($message) {
       global $logFile;
       if (is_array($message))
           $message = json_encode($message);
       $message = date("Y-m-d H:i:s") . " " . $message . "\r\n";
       if ((filesize($logFile) / pow(1024, 2)) > 100) { 
           file_put_contents($logFile, $message, LOCK_EX);
       } else {
           file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
       }
   } 

function getallcode($i,$j,$k) {

$b1= '$arraydata'; $b2 = '[$grades]';$b3 = "['id_categoria'] = ";$b4 = '$arraycat';

if ($i == -1) { $v1 = Null; } else { $v1 = '['; $v1 .= $i; $v1 .= ']'; }

$alld  = $b1.'1'.$b2."['id_categoria'] = ".$b4.$v1."['id_categoria']".';';
$alld .= $b1.'1'.$b2."['nombre'] = ".$b4.$v1."['nombre']".';';
$alld .= $b1.'1'.$b2."['descripcion'] = ".$b4.$v1."['descripcion']".';';
$alld .= $b1.'1'.$b2."['estado'] = ".$b4.$v1."['estado']".';';
$alld .= $b1.'1'.$b2."['id_modulo'] = ".$b4.$v1."['id_modulo']".';';
$alld .= $b1.'1'.$b2."['id_grupo'] = ".$b4.$v1."['id_grupo']".';';

if ($j == -1) { $v2 = Null; } else { $v2 = '['; $v2 .= $j; $v2 .= ']'; }

        $alld .= $b1.'2'.$b2."['id_calificacion'] = ".$b4.$v1."['calificaciones']".$v2."['id_calificacion']".';';
        $alld .= $b1.'2'.$b2."['nombre'] = ".$b4.$v1."['calificaciones']".$v2."['nombre']".';';
                //$b1.'2'.$b2."['descripcion'] = ".$b4.$v1."['calificaciones']".$v2."['descripcion']".';';
        $alld .= $b1.'2'.$b2."['id_docente'] = ".$b4.$v1."['calificaciones']".$v2."['id_docente']".';';
        $alld .= $b1.'2'.$b2."['fecha'] = ".$b4.$v1."['calificaciones']".$v2."['fecha']".';';
        $alld .= $b1.'2'.$b2."['rango_usuarios'] = ".$b4.$v1."['calificaciones']".$v2."['rango_usuarios']".';';
        $alld .= $b1.'2'.$b2."['tipo_calificacion'] = ".$b4.$v1."['calificaciones']".$v2."['tipo_calificacion']".';';
               //$b1.'2'.$b2."['id_evaluacion'] = ".$b4.$v1."['calificaciones']".$v2."['id_evaluacion']".';';

if ($k == -1) { $v3 = Null; } else { $v3 = '['; $v3 .= $k; $v3 .= ']'; }

                  $alld .= $b1.'3'.$b2."['id_nota'] = ".$b4.$v1."['calificaciones']".$v2."['notas']".$v3."['id_nota']".';';
                  $alld .= $b1.'3'.$b2."['id_usuario'] = ".$b4.$v1."['calificaciones']".$v2."['notas']".$v3."['id_usuario']".';';
                  $alld .= $b1.'3'.$b2."['fecha'] = ".$b4.$v1."['calificaciones']".$v2."['notas']".$v3."['fecha']".';';
                  $alld .= $b1.'3'.$b2."['nota'] = ".$b4.$v1."['calificaciones']".$v2."['notas']".$v3."['nota']".';';
                  $alld .= $b1.'3'.$b2."['observaciones'] = ".$b4.$v1."['calificaciones']".$v2."['notas']".$v3."['observaciones']".';';
                  $alld .= $b1.'3'.$b2."['detalles'] = ".$b4.$v1."['calificaciones']".$v2."['notas']".$v3."['detalles']".';';

return $alld;

}
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $con = new \PDO($dsn, $dbuser, $dbpass);
    $isrunning = "Select * from db_academico.cron_estudiantes_educativa
    WHERE croe_exec = '3';";
     $comando = $con->prepare($isrunning);
    $comando->execute();
    $running = $comando->fetchAll(\PDO::FETCH_ASSOC);
    if (count($running) >= 1 ){ die; } else { getconfig(); }
  


   function getconfig() {
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $con = new \PDO($dsn, $dbuser, $dbpass);

   $configura=
   "SELECT croe_id, croe_mod_id, croe_paca_id, croe_uaca_id,croe_parcial
    FROM db_academico.cron_estudiantes_educativa
    WHERE now() >= croe_fecha_ejecucion 
    AND croe_exec = '1' 
    ;";
    $comando = $con->prepare($configura);
    $comando->execute();
    $confi = $comando->fetchAll(\PDO::FETCH_ASSOC);

    if (count($confi) >= 1 ) {
   for ($f = 0; $f < count($confi); $f++) {
   var_dump($confi[$f]["croe_mod_id"]);
   var_dump($confi[$f]["croe_paca_id"]);
   var_dump($confi[$f]["croe_uaca_id"]);
   var_dump($confi[$f]["croe_parcial"]);

   running($confi[$f]['croe_id']);
   getgrades($confi[$f]['croe_mod_id'],$confi[$f]['croe_paca_id'],$confi[$f]['croe_uaca_id'],$confi[$f]['croe_parcial']);
   inactiva($confi[$f]['croe_id']);
  
          }
     }
}

function running($croneducativa) {
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $con = new \PDO($dsn, $dbuser, $dbpass);
   $inactiva=
   "
   UPDATE 
   db_academico.cron_estudiantes_educativa SET croe_exec = '3' 
   WHERE croe_id = $croneducativa;
   ";
   $comando = $con->prepare($inactiva);
    $comando->execute();
    $confi = $comando->fetchAll(\PDO::FETCH_ASSOC);
    return true;
    }

function inactiva($croneducativa) {
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $con = new \PDO($dsn, $dbuser, $dbpass);
   $inactiva=
   "
   UPDATE 
   db_academico.cron_estudiantes_educativa SET croe_exec = '0' 
   WHERE croe_id = $croneducativa;
   ";
   $comando = $con->prepare($inactiva);
    $comando->execute();
    $confi = $comando->fetchAll(\PDO::FETCH_ASSOC);
    return true;
    }

      function getallgroups($mod_id, $paca_id, $uaca_id) {    

  GLOBAL $dsn, $dbuser, $dbpass, $dbname;
           $con = new \PDO($dsn, $dbuser, $dbpass);

$qusersandgroups = 
"SELECT cedist.daca_id, ceduct.cedu_asi_id, 
daca.uaca_id, daca.paca_id, daca.mod_id, daca.mpp_id, 
daca.pro_id, daca.asi_id, daes.est_id,
usuedu.uedu_usuario, usuedu.per_id, person.per_cedula
FROM db_academico.curso_educativa_distributivo cedist
INNER JOIN db_academico.curso_educativa as ceduct on cedist.cedu_id = ceduct.cedu_id
INNER JOIN db_academico.distributivo_academico as daca on cedist.daca_id = daca.daca_id
INNER JOIN db_academico.distributivo_academico_estudiante as daes on daes.daca_id = daca.daca_id
INNER JOIN db_academico.usuario_educativa as usuedu on usuedu.est_id = daes.est_id
INNER JOIN db_academico.estudiante as estu on  estu.est_id = daes.est_id
INNER JOIN db_asgard.persona as person on  estu.per_id = person.per_id
WHERE daca.mod_id = $mod_id
AND daca.paca_id = $paca_id
AND daca.uaca_id = $uaca_id
;";

 $comando = $con->prepare($qusersandgroups);
        $comando->execute();
    return $comando->fetchAll(\PDO::FETCH_ASSOC);

   }

       function getgrades($mod_id, $paca_id, $uaca_id,$parciales) {
       try {

          GLOBAL $dsn, $dbuser, $dbpass, $dbname;
           $pdo = new \PDO($dsn, $dbuser, $dbpass);
           $groups = getallgroups($mod_id, $paca_id, $uaca_id); 
           //

             if (count($groups) > 0) { var_dump($groups); 

               for ($i = 0; $i < count($groups); $i++) {  
               

            if (isset($groups[$i]['daca_id'])) {

            $daca_id = $groups[$i]['daca_id'];
            $cedu_asi_id = $groups[$i]['cedu_asi_id']; 
            $uaca_id = $groups[$i]['uaca_id'];
            $paca_id = $groups[$i]['paca_id'];
            $mod_id = $groups[$i]['mod_id']; 
            $mpp_id = $groups[$i]['mpp_id'];
            $pro_id = $groups[$i]['pro_id'];
            $asi_id = $groups[$i]['asi_id'];
            $est_id = $groups[$i]['est_id'];
            $uedu_usuario = $groups[$i]['uedu_usuario'];
            $per_id = $groups[$i]['per_id'];
            $ced_id = $groups[$i]['per_cedula'];

            } elseif (isset($groups['daca_id'])) {

              $daca_id = $groups['daca_id'];
            $cedu_asi_id = $groups['cedu_asi_id']; 
            $uaca_id = $groups['uaca_id'];
            $paca_id = $groups['paca_id'];
            $mod_id = $groups['mod_id']; 
            $mpp_id = $groups['mpp_id'];
            $pro_id = $groups['pro_id'];
            $asi_id = $groups['asi_id'];
            $est_id = $groups['est_id'];
            $uedu_usuario = $groups['uedu_usuario'];
            $per_id = $groups['per_id'];
            $ced_id = $groups['per_cedula'];

            }

              $wsdl = 'https://campusvirtual.uteg.edu.ec/soap/?wsdl=true';
         
         $client = new \SoapClient($wsdl, [
         "soap_version" => SOAP_1_1,
         "login"    => "webservice", 
         "password" => "WxrrvTt8",
            "trace"    => 1,
         "exceptions" => 0,
         "cache_wsdl" => WSDL_CACHE_NONE,
         "stream_context" => stream_context_create(
         [
         'ssl' => [
         'verify_peer' => false,
         'verify_peer_name' => true,
         'allow_self_signed' => true,
         ]])]);

         $client->setCredentials("webservice", 
                          "WxrrvTt8",
                          "basic");

          $method = 'obtener_notas_calificaciones'; 
       
          $args = Array(
                 'id_grupo' =>$cedu_asi_id, 
                 'id_usuario' =>$uedu_usuario,
                );


            $response = $client->__call( $method, Array( $args ) );
      putMessageLogFile("cedu_asi_id:  ");
 putMessageLogFile($cedu_asi_id);
        putMessageLogFile("uedu_usuario:  ");
 putMessageLogFile($uedu_usuario);

              $isauth= getPagopend($ced_id);  

 if ($isauth)  {    

 if (isset($response->categorias)) { 

  $valuated = $response->categorias;


            $arraycat = json_decode(json_encode($valuated), true);

            $arrayl2 = array_column($arraycat, 'id_categoria');
            $arraydata1 = array();
            $arraydata2 = array();
            $arraydata3 = array();
            $grades=0;

if (isset($arraycat[0]['id_categoria'])) { 
for ($i = 0; $i < count($arrayl2); $i++) {


   
    if (isset($arraycat[$i]['calificaciones']['notas'][0]['id_nota'])) { 
         $arrayl4 = array_column($arraycat[$i]['calificaciones']['notas'], 'id_nota'); 
              for ($k = 0; $k < count($arrayl4); $k++) {
                  $allcode =getallcode($i,-1,$k);     
                    eval ($allcode);var_dump($allcode);print_r($arraydata3[$i]);
                    $grades++;
              }
    }  else {
                if (isset($arraycat[$i]['calificaciones']['notas'])) {
                $allcode =getallcode($i,-1,-1);     
                eval ($allcode);var_dump($allcode);print_r($arraydata3[$i]);
                $grades++;
                }           

            }

    if (isset($arraycat[$i]['calificaciones'][0]['notas'] )) { 
    $arrayl3 = array_column($arraycat[$i]['calificaciones'], 'id_calificacion'); // --DEBUG!!!! 
    for ($j = 0; $j < count($arrayl3); $j++) {


            if (isset($arraycat[$i]['calificaciones'][$j]['notas'][0]['id_nota'])) {
                 $arrayl4 = array_column($arraycat[$i]['calificaciones'][$j]['notas'], 'id_nota'); 
                     for ($k = 0; $k < count($arrayl4); $k++) {
                         $allcode =getallcode($i,$j,$k);     
                         eval ($allcode);var_dump($allcode);print_r($arraydata3[$i]);
                          $grades++;
                     }
             } else {

                        if (isset($arraycat[$i]['calificaciones'][$j]['notas'])) {
                        $allcode =getallcode($i,$j,-1);     
                        eval ($allcode);var_dump($allcode);print_r($arraydata3[$i]);
                $grades++;
                         }
                    } 
    }  

    }

} 

}

 if (isset($arraycat['id_categoria'])) { 
   
if (isset($arraycat['calificaciones']['notas'][0]['id_nota'])) {
$arrayl4 = array_column($arraycat['calificaciones']['notas'], 'id_nota'); 
for ($k = 0; $k < count($arrayl4); $k++) {


$allcode =getallcode(-1,-1,$k);     
eval ($allcode);var_dump($allcode);print_r($arraydata3[$i]);
$grades++;

}} else {

if (isset($arraycat['calificaciones']['notas'])) {

$allcode =getallcode(-1,-1,-1);     
eval ($allcode);var_dump($allcode);print_r($arraydata3[$i]);
$grades++;


}


}

 if (isset($arraycat['calificaciones'][0]['notas'])) { 
    $arrayl3 = array_column($arraycat['calificaciones'], 'id_calificacion');  

 for ($j = 0; $j < count($arrayl3); $j++) {
if (isset($arraycat['calificaciones'][$j]['notas'][0]['id_nota'])) {
$arrayl4 = array_column($arraycat['calificaciones'][$j]['notas'], 'id_nota'); 
for ($k = 0; $k < count($arrayl4); $k++) {

$allcode =getallcode(-1,$j,$k);     
eval ($allcode);var_dump($allcode);print_r($arraydata3[$i]);
$grades++;


}} else {

if (isset($arraycat['calificaciones'][$j]['notas'])) {

$allcode =getallcode(-1,$j,-1);     
eval ($allcode);var_dump($allcode);print_r($arraydata3[$i]);
$grades++;

}


}
    } }

 } 


print_r("==============fin if response");

if (count($arraydata3) > 0) {           


$componentes = getescalas($uaca_id,$mod_id,$parciales);
$cabeceras = getcabeceras($est_id,$asi_id,$paca_id,$parciales);
if ($cabeceras == Null){ 
$cabeceras = putcabeceras($est_id,$asi_id,$paca_id,$parciales,$pro_id);
$cabeceras = getcabeceras($est_id,$asi_id,$paca_id,$parciales);
}


for ($it = 0; $it < count($arraydata3); $it++) {

 $data01= getparamcategoria($arraydata1[$it]['nombre']); // $datacategorias['parcial']; $datacategorias['unidad']; 
 $data02= getparamitem($arraydata2[$it]['nombre']); //  $dataitems['evaluacion']; $dataitems['examen']; $dataitems['taller'];
 $data03= getnota($arraydata3[$it]['nota']); //  $grade;

if (isset($semanaexa1)) {} else {

 if(isset($data02['examen']) ) { 
$semanaexa1 = $data01['semana'];
if ($semanaexa1 == 5 AND $parciales == 1){ 

         $comp_examen1 = $data03; 
          $comp_cuni_id = 6;
          print_r("parcial 1 examen::>");
           print_r($comp_examen1);

           $dcalificacion = $comp_examen1;
          $detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
      if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_calificacion'] < $dcalificacion ){
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
}
}
}
}   
}


if (isset($semanaexa2)) {} else {

 if(isset($data02['examen']) ) { 
$semanaexa2 = $data01['semana'];
if ($semanaexa2 == 10 AND $parciales == 2){ 

         $comp_examen2 = $data03; 
          $comp_cuni_id = 6;
          print_r("parcial 2 examen::>");
           print_r($comp_examen2);

           $dcalificacion = $comp_examen2;
          $detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
      if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_calificacion'] < $dcalificacion ){
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
}
}
}
}   
}



 if(isset($data01['parcial'])) {
        $comp_evaluacion1 = 0;
    $comp_autonoma1 = 0;
    $comp_examen1 = 0;
if ($parciales == 1 AND $data01['parcial']==1) {


for ($il = 0; $il < count($componentes); $il++) {


    if ($componentes[$il]['com_id']== 3 AND isset($data02['evaluacion'])) {    //COMP_EVALUACION ol

    $comp_evaluacion1 = $comp_evaluacion1 + $data03; 
    $comp_cuni_id = $componentes[$il]['cuni_id'];

    }

     if ($componentes[$il]['com_id']== 4 AND isset($data02['taller'])) {    //COMP_AUTONOMA ol
        
     $comp_autonoma1 = $comp_autonoma1 + $data03; 
     $comp_cuni_id = $componentes[$il]['cuni_id'];

    }



}
if ( $comp_evaluacion1 > 0 ){
$dcalificacion = $comp_evaluacion1;
$detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == '1' AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
}
}
} 


if ( $comp_autonoma1 > 0 ){
$dcalificacion = $comp_autonoma1;
$detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id); 
if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
}
}
} 

if ( $comp_autonoma1 > 0 ){
$dcalificacion = $comp_autonoma1;
$detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id); 
if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
}
}
} 

}

 $comp_evaluacion2 = 0;
 $comp_autonoma2 = 0;
 $comp_examen2 = 0;
if ($parciales == 2 AND $data01['parcial']==2) {
   

for ($il = 0; $il < count($componentes); $il++) {


    if ($componentes[$il]['com_id']== 3 AND isset($data02['evaluacion'] )) {    //COMP_EVALUACION ol

     $comp_evaluacion2 = $comp_evaluacion2 + $data03;  
      $comp_cuni_id = $componentes[$il]['cuni_id'];

    }

     if ($componentes[$il]['com_id']== 4 AND isset($data02['taller'] )) {    //COMP_AUTONOMA ol
        
         $comp_autonoma2 = $comp_autonoma2 + $data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];

    }

      if ($componentes[$il]['com_id']== 6 AND isset($data02['examen'] )) {    //COMP_EXAMEN ol
        
         if ($data03 > $comp_examen2){

         $comp_examen2 = $data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];
        
        }

    }



}
if ( $comp_evaluacion2 > 0 ){
$dcalificacion = $comp_evaluacion2;
$detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion);  
}
}
} 


if ( $comp_autonoma2 > 0 ){
$dcalificacion = $comp_autonoma2;
$detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id); 
if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
}
}
} 

}

 $comp_examen3 = 0;
 $comp_supletorio3 = 0;
if ($parciales == 3 AND $data01['parcial']==3) {
   

for ($il = 0; $il < count($componentes); $il++) {


    if ($componentes[$il]['com_id']== 6 AND isset($data02['supletorio'])) {    //COMP_EXAMEN O SUPLETORIO ol
        
        if ($data03 > $comp_supletorio3){

         $comp_supletorio3 = $data03; 
          $comp_cuni_id = $componentes[$il]['cuni_id'];
        
        }
    }

    }
//GRABAR DETALLES
if ( $comp_supletorio3 > 0 ){
$dcalificacion = $comp_supletorio3;
$detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id);
if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
}
}
} 


if ( $comp_examen3 > 0 ){
$dcalificacion = $comp_examen3;
$detalles = getdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id); 
if ($detalles == Null) {
$detalles = putdetalles($cabeceras[0]['ccal_id'],$comp_cuni_id ,$dcalificacion); 
}else {
if ($detalles[0]['dcal_usuario_creacion'] == 1 AND $detalles[0]['dcal_fecha_modificacion'] ==Null){
$dcalificacion = $dcalificacion + $detalles[0]['dcal_calificacion'];
$detallesup = updatedetalles($detalles[0]['dcal_id'],$dcalificacion); 
}
}
} 



}                 }  //if data01 parcial




}



        }// FIN GRABAR NOTAS OPT- UPDATE CABECERA



 

} 
       
print_r("///fin is auth");


 //return true; 
} else {// isauth
        
     
       }

        }//for


 
    }//if

 }  catch (PDOException $e) {
           putMessageLogFile('Error: ' . $e->getMessage());
           exit; }

          


    
}


 function getparamcategoria($elemento) {
$datacategorias = array();
$elementos = explode(" ", $elemento);
 for ($iter = 0; $iter < 21; $iter++) 
 {

  if (isset($elementos[$iter])) {
  

   if (isset($elementos[$iter+1])){
  $nexter = $elementos[$iter+1];    
   } else {
$nexter =  0;

   }
    if ( strtoupper(substr($elementos[$iter],0,1)) == 'S'){

      if (intval(substr($elementos[$iter],1,1)) > 0 ) {           
             $datacategorias['semana'] = substr($elementos[$iter],1,2);
          } elseif ( intval($nexter) > 0  AND strtoupper(substr($elementos[$iter],2,1)) == 'M'){ 
     $datacategorias['semana'] = $nexter; 
    }

     } 

     elseif ( strtoupper(substr($elementos[$iter],0,1)) == 'P'){

        if (intval(substr($elementos[$iter],1,1)) > 0 ) {           
             $datacategorias['parcial'] = substr($elementos[$iter],1,2); 
          } elseif (intval($nexter) > 0){
             $datacategorias['parcial'] = $nexter;
          }

   }  elseif ( strtoupper(substr($elementos[$iter],0,1)) == 'U'){
       if (intval(substr($elementos[$iter],1,1)) > 0){
          $datacategorias['unidad'] = substr($elementos[$iter],1,2);
       } elseif (intval($nexter) > 0){
             $datacategorias['unidad'] = $nexter;
          }


   }  

}

 } 

return $datacategorias;
 }


 function getnota($elemento) {
$notas = explode("/", $elemento);
$withouter = str_replace(chr(44), chr(46), $notas[0]);
$grade = $withouter*1;  
return $grade;
 }


function getparamitem($elemento) {                                                   
 $dataitems = array();
 $elementos = explode(" ", $elemento);
 for ($iter = 0; $iter < count($elementos); $iter++) 
 {
     if ( strtoupper(substr($elementos[$iter],0,3)) == 'TAL'){
          if (intval($elementos[$iter+1]) > 0){
           $dataitems['taller'] = $elementos[$iter+1];
          }elseif (intval($elementos[$iter+2]) > 0) {
            $dataitems['taller'] = $elementos[$iter+2];
          }


   }  elseif ( strtoupper(substr($elementos[$iter],0,3)) == 'EVA'){
      $dataitems['evaluacion'] = 1;


   }  elseif ( strtoupper(substr($elementos[$iter],0,3)) == 'EXA'){
       $dataitems['examen'] = 1;

   }  elseif ( strtoupper(substr($elementos[$iter],0,3)) == 'SUP'){
       $dataitems['supletorio'] = 1;

   }

    elseif ( strtoupper(substr($elementos[$iter],0,3)) == 'FOR'){
       $dataitems['foro'] = 1;

   } elseif ( strtoupper(substr($elementos[$iter],0,4)) == 'SINC'){
       $dataitems['sincrona'] = 1;

   }

 }  

return $dataitems;
 }


   function getPagopend($cedusuedu)  { 

         $ceduladni['cedula']=$cedusuedu;        
        $url = "https://acade.uteg.edu.ec/planificaciondesa/grades.php"; 
        $content = json_encode($ceduladni); 
        $curl = curl_init($url);  
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt($curl, CURLOPT_HTTPHEADER,  
        array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content); 
        $json_response = curl_exec($curl);  //--
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
        if ( $status != 200 ) {                
        die(" status $status content $content "); 
         }
        $html = curl_multi_getcontent($curl); 
        $response = json_decode($json_response, true); //--
        print_r(" status $status content $content $response $html  "); 
        curl_close($curl);   


       //  %saldo%
         $allresponse = explode('":"', $html);
         if (isset($allresponse[1])) {
         $saldos = explode('"', $allresponse[1]);
         print_r('SALDO ==> '.$saldos[0]);

         } else {
        
        print_r('SALDO ==> 0.00');

         }
         

       if (isset($saldos[0])){
        if ($saldos[0] == 0){
         return True;
        }else {
           return False;
        }
        } else
        {
          return True;  
        }
        
        
     }  


     function putInpagos($usuario,$cedula) {
     GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $con = new \PDO($dsn, $dbuser, $dbpass);
     $sqlq ="select * from db_academico.temp_impagos_educativa
    WHERE temp_usuedu = $usuario AND temp_cedula = $cedula";
    $comando = $con->prepare($sqlq);
    $comando->execute();
    $pendientes = $comando->fetchAll(\PDO::FETCH_ASSOC);

        if (count($pendientes) > 0) {

        } else {

    $sql="
    INSERT INTO db_academico.temp_impagos_educativa 
    (temp_usuedu, temp_cedula, temp_usuario_ingreso) 
    VALUES ($usuario, $cedula, '1');
    ";
     $comando = $con->prepare($sqlq);
    $comando->execute();
    $pendientes = $comando->fetchAll(\PDO::FETCH_ASSOC);

        }

return true;

}



function getescalas($uaca_id,$mod_id,$ecal_id){
 GLOBAL $dsn, $dbuser, $dbpass, $dbname;
$con = new \PDO($dsn, $dbuser, $dbpass);
$sql="
SELECT cuni.cuni_id, cuni.com_id,comp.com_nombre, cuni.cuni_calificacion 
FROM db_academico.componente_unidad as cuni
inner join db_academico.componente as comp
on comp.com_id = cuni.com_id
where uaca_id = $uaca_id AND mod_id = $mod_id AND ecal_id = $ecal_id 
";
 $comando = $con->prepare($sql);
 $comando->execute();
 $escalas = $comando->fetchAll(\PDO::FETCH_ASSOC);
return $escalas;

}


function getcabeceras($est_id,$asi_id,$paca_id,$parciales){
 GLOBAL $dsn, $dbuser, $dbpass, $dbname;
$con = new \PDO($dsn, $dbuser, $dbpass);
$sql="
SELECT ccal_id,ccal_calificacion FROM db_academico.cabecera_calificacion 
where 
est_id= $est_id AND
asi_id= $asi_id AND
paca_id= $paca_id AND 
ecun_id = $parciales
";
 $comando = $con->prepare($sql);
 $comando->execute();
 $cabeceras = $comando->fetchAll(\PDO::FETCH_ASSOC);
return $cabeceras;

}

function putcabeceras($est_id,$asi_id,$paca_id,$parciales,$pro_id){
GLOBAL $dsn, $dbuser, $dbpass, $dbname;
$con = new \PDO($dsn, $dbuser, $dbpass);
$sql="
INSERT INTO db_academico.cabecera_calificacion 
(paca_id, est_id, pro_id, asi_id, ecun_id, 
ccal_estado, ccal_estado_logico) 
VALUES ( $paca_id, $est_id, $pro_id, $asi_id, $parciales, '1', '1');
";
 $comando = $con->prepare($sql);
 $comando->execute();
 $cabeceras = $comando->fetchAll(\PDO::FETCH_ASSOC);
return $cabeceras;

}

function getdetalles($ccal_id,$cuni_id){
 GLOBAL $dsn, $dbuser, $dbpass, $dbname;
$con = new \PDO($dsn, $dbuser, $dbpass);
$sql="
SELECT dcal_id, ccal_id,cuni_id,dcal_calificacion,
dcal_usuario_creacion,dcal_fecha_modificacion
FROM db_academico.detalle_calificacion 
WHERE ccal_id = $ccal_id AND cuni_id = $cuni_id ; 
";
 $comando = $con->prepare($sql);
 $comando->execute();
 $detalles = $comando->fetchAll(\PDO::FETCH_ASSOC);
return $detalles;

}

function putdetalles($ccal_id,$cuni_id,$dcalificacion){
GLOBAL $dsn, $dbuser, $dbpass, $dbname;
$con = new \PDO($dsn, $dbuser, $dbpass);
$sql="
INSERT INTO db_academico.detalle_calificacion
(ccal_id,cuni_id,dcal_calificacion,dcal_usuario_creacion,dcal_estado,dcal_estado_logico)
VALUES ($ccal_id,$cuni_id,$dcalificacion, '1', '1', '1')
";
 $comando = $con->prepare($sql);
 $comando->execute();
 $detalles = $comando->fetchAll(\PDO::FETCH_ASSOC);
return $detalles;


}

function updatedetalles($dcal_id,$dcalificacion){
 GLOBAL $dsn, $dbuser, $dbpass, $dbname;
$con = new \PDO($dsn, $dbuser, $dbpass);
$sql="
UPDATE db_academico.detalle_calificacion
 SET dcal_calificacion = $dcalificacion,
 dcal_fecha_modificacion = now()  
 WHERE dcal_id = $dcal_id;
";
 $comando = $con->prepare($sql);
 $comando->execute();
 $detalles = $comando->fetchAll(\PDO::FETCH_ASSOC);
return $detalles;

}


      function getInpagos() {
     GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $con = new \PDO($dsn, $dbuser, $dbpass);
     $sqlq ="select * from db_academico.temp_impagos_educativa;";
    $comando = $con->prepare($sqlq);
    $comando->execute();
    $pendientes = $comando->fetchAll(\PDO::FETCH_ASSOC);

        if (count($pendientes) > 0) {

               for ($i = 0; $i < count($pendientes); $i++) {  


               }

        }



}
 