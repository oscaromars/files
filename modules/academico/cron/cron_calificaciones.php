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
  // 


   function getconfig() {
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $con = new \PDO($dsn, $dbuser, $dbpass);
//getInpagos();  /* ------------------------------     ?      ---------------------------------*/
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

$qusersandgroups = // --------------------------------------------------------------------DEV  !!!!
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
WHERE daca.mod_id = 1
AND daca.paca_id = 15  -- 16
AND daca.uaca_id = 1
AND uedu_usuario = '1312603499'
AND cedu_asi_id = '3235'
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
         /*$args = Array(
                 'id_grupo' =>3235, 
                 'id_usuario' =>'1312603499',
                );*/
          $args = Array(
                 'id_grupo' =>$cedu_asi_id, 
                 'id_usuario' =>$uedu_usuario,
                );


            $response = $client->__call( $method, Array( $args ) );


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

/*
for ($i=0; $i<9 ; $i++){

 if (isset($arraydata1[$i])) { print_r($arraydata1[$i]); }

 if (isset($arraydata2[$i])) { print_r($arraydata2[$i]); }

 if (isset($arraydata3[$i])) { print_r($arraydata3[$i]); }


  
} */


// EVALUACION
// TALLER    == AUTONOMA
//EXAMEN

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

print_r("==============CATEGORIA ");
print_r($data01);
print_r("==============ITEM ");
print_r($data02);
print_r("==============NOTA  ");
print_r($data03);

if ($parciales == 1 AND $data01['parcial']==1) {
    $comp_evaluacion = 0;
    $comp_autonoma = 0;
    $comp_examen = 0;

for ($il = 0; $il < count($componentes); $il++) {


    if ($componentes[$il]['com_id']== 3 AND $data02['evaluacion'] == 1) {    //COMP_EVALUACION ol

    $comp_evaluacion = $comp_evaluacion + $data03; 

    }

     if ($componentes[$il]['com_id']== 4 AND $data02['taller'] == 1) {    //COMP_AUTONOMA ol
        
     $comp_autonoma = $comp_autonoma + $data03; 

    }



}
if ( $comp_evaluacion > 0 ){
$dcalificacion = $comp_evaluacion;
$detalles = putdetalles($cabeceras['ccal_id'],$componentes[$il]['cuni_id'],$dcalificacion); 
} 
if ( $comp_autonoma > 0 ){
$dcalificacion = $comp_autonoma;
$detalles = putdetalles($cabeceras['ccal_id'],$componentes[$il]['cuni_id'],$dcalificacion); 
} 
}


if ($parciales == 2 AND $data01['parcial']==2) {
    $comp_evaluacion = 0;
    $comp_autonoma = 0;
    $comp_examen = 0;

for ($il = 0; $il < count($componentes); $il++) {


    if ($componentes[$il]['com_id']== 3 AND $data02['evaluacion'] == 1) {    //COMP_EVALUACION ol

     $comp_evaluacion = $comp_evaluacion + $data03; 

    }

     if ($componentes[$il]['com_id']== 4 AND $data02['taller'] == 1 ) {    //COMP_AUTONOMA ol
        
         $comp_autonoma = $comp_autonoma + $data03; 

    }

      if ($componentes[$il]['com_id']== 6 AND $data02['examen'] == 1 ) {    //COMP_EXAMEN ol
        
         if ($data03 > $comp_examen){

         $comp_examen = $data03; 
        
        }

    }



}
if ( $comp_evaluacion > 0 ){
$dcalificacion = $comp_evaluacion;
$detalles = putdetalles($cabeceras['ccal_id'],$componentes[$il]['cuni_id'],$dcalificacion); 
} 
if ( $comp_autonoma > 0 ){
$dcalificacion = $comp_autonoma;
$detalles = putdetalles($cabeceras['ccal_id'],$componentes[$il]['cuni_id'],$dcalificacion); 
}  
}

if ($parciales == 3 AND $data01['parcial']==3) {
    $comp_examen = 0;
    $comp_supletorio = 0;

for ($il = 0; $il < count($componentes); $il++) {


    if ($componentes[$il]['com_id']== 6 AND $data02['supletorio'] == 1) {    //COMP_EXAMEN O SUPLETORIO ol
        
        if ($data03 > $comp_supletorio){

         $comp_supletorio = $data03; 
        
        }
    }

    }
//GRABAR DETALLES
if ( $comp_supletorio > 0 ){
$dcalificacion = $comp_supletorio;
$detalles = putdetalles($cabeceras['ccal_id'],$componentes[$il]['cuni_id'],$dcalificacion); 
} 
if ( $comp_examen > 0 ){
$dcalificacion = $comp_examen;
//$detalles = putdetalles($cabeceras['ccal_id'],$componentes[$il]['cuni_id'],$dcalificacion); 
} 


}  




}



        }// FIN GRABAR NOTAS OPT- UPDATE CABECERA



/*
-- Get Componente by mod 1 Asincrona 2 Sincrona 3 Evaluacion 4 Autonoma, 6 Examen
SELECT cuni.cuni_id, cuni.com_id,comp.com_nombre, cuni.cuni_calificacion FROM db_academico.componente_unidad as cuni
inner join db_academico.componente as comp
on comp.com_id = cuni.com_id
where uaca_id = 1 AND mod_id = 1 AND ecal_id = 1 ;

-- Get grades from Array by mod (and parcial)

-- Get cabeceras by parcial (select or create)
INSERT INTO `db_academico`.`cabecera_calificacion` 
(`ccal_id`, `paca_id`, `est_id`, `pro_id`, `asi_id`, `ecun_id`, `ccal_calificacion`, 
`ccal_estado`, `ccal_fecha_creacion`, `ccal_estado_logico`) 
VALUES ('21', '32', '3181', '192', '479', '3', '13', '1',
 '2021-11-08 17:29:34', '1');

SELECT ccal_id,ccal_calificacion FROM db_academico.cabecera_calificacion 
where 
est_id= 3181 AND
asi_id= 479 AND
paca_id=15 AND 
ecun_id = 1
;
-- Get detalle (select or create)
INSERT INTO `db_academico`.`detalle_calificacion`
 (`dcal_id`, `ccal_id`, `cuni_id`, `dcal_calificacion`, `dcal_usuario_creacion`, 
 `dcal_estado`, `dcal_fecha_creacion`, `dcal_estado_logico`) 
 VALUES ('53', '21', '11', '13', '1', '1', '2021-11-08 17:29:34', '1');

SELECT dcal_id, ccal_id,cuni_id,dcal_calificacion FROM db_academico.detalle_calificacion where ccal_id = 18; -- SUM 12

*/

} 
       
print_r("///fin is auth");


 return true; 
} else {// isauth
        
         putInpagos($uedu_usuario,$ced_id);

        return false; 
       }

        }//for

        /* - MATRIZ TEMPORAL DE CALIFICACIONES - */
 
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
         

        if ($saldos == 0){
         return False;
        }else {
           return True;
        }
        
       
         //return True;
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
/*


(
    [parcial] => 1
    [semana] => 02
    [evaluacion] => 1
    NOTA  2
(
    [parcial] => 1
    [semana] => 02
    [taller] => 01
    NOTA  3
(
    [parcial] => 2
    [semana] => 07
    [evaluacion] => 1
    NOTA  0.5

(
    [parcial] => 2
    [semana] => 07
    [taller] => 03
    NOTA  3

(
    [parcial] => 1
    [semana] => 04
    [evaluacion] => 1
    NOTA  1
(
    [parcial] => 1
    [semana] => 04
    [taller] => 02
    NOTA  2.8
(
    [parcial] => 2
    [semana] => 09
    [evaluacion] => 1
    NOTA  0.75
(
    [parcial] => 2
    [semana] => 09
    [taller] => 04
    NOTA  3
(
    [semana] => 10
    [examen] => 1
    NOTA  6.8


    */