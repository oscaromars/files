  <?
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
    getconfig();
   function getconfig() {
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $con = new \PDO($dsn, $dbuser, $dbpass);
    getinpagos();
   $configura=
   "SELECT croe_id, croe_mod_id, croe_paca_id, croe_uaca_id,
    FROM db_academico.cron_estudiantes_educativa
    WHERE now() >= croe_fecha_ejecucion SELECT
    AND croe_exec = '1' 
    ;";
    $comando = $con->prepare($configura);
    $comando->execute();
    $confi = $comando->fetchAll(\PDO::FETCH_ASSOC);
   for ($f = 0; $f < count($confi); $f++) {
   putMessageLogFile(' '.$confi[$f]['mod_id'].' '.$confi[$f]['paca_id'].' '.$confi[$f]['uaca_id']);
   running($confi[$f]['croe_id']);
   getgrades($confi[$f]['croe_mod_id'],$confi[$f]['croe_paca_id'],$confi[$f]['croe_uaca_id']);
   inactiva($confi[$f]['croe_id']);
   }}

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

       function getgrades($mod_id, $paca_id, $uaca_id) {
       try {

          GLOBAL $dsn, $dbuser, $dbpass, $dbname;
           $pdo = new \PDO($dsn, $dbuser, $dbpass);
           $groups = getallgroups($mod_id, $paca_id, $uaca_id); 


             if (count($groups) > 0) {

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


              $isauth= traerestudiantesautorizados($uedu_usuario);  

 if ($isauth['uedu_usuario'] == $uedu_usuario)  {    

 if (isset($response->categorias)) { 

  $valuated = $response->categorias;


            $arraycat = json_decode(json_encode($valuated), true);

            $arrayl2 = array_column($arraycat, 'id_categoria');
            $arraydata1 = array();
            $arraydata2 = array();
            $arraydata3 = array();
            $grades=0;


}}


        }

    }

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

 }


 function getnota($elemento) {
$notas = explode("/", $elemento);
$withouter = str_replace(chr(44), chr(46), $notas[0]);
$grade = $withouter*1;  
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

 }


   function getPago($usuedu)  { 
        $url = "https://acade.uteg.edu.ec/planificaciondesa/grades.php"; //--
        $content = json_encode($usuedu); //--
        $curl = curl_init($url);  //--
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   //--
        curl_setopt($curl, CURLOPT_HTTPHEADER,  //--
        array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content); //-- 
        $json_response = curl_exec($curl);  //--
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE); //--
        if ( $status != 200 ) {                
        //die(" $content url $url status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        die(" status $status content $content ");
         }
           $response = json_decode($json_response, true); //--
              curl_close($curl);   //--

        if ($response == 1){
         return True;
        }elseif ($response == 0) {
           return False;
        }
       
     }  
