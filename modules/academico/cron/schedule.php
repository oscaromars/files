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

 function getScheme($codesubject) {    
           GLOBAL $dsn, $dbuser, $dbpass, $dbname;
           $con = new \PDO($dsn, $dbuser, $dbpass);   
$queryScheme = "
SELECT made_codigo_asignatura, made_hora, made_credito
FROM  db_academico.malla_academica_detalle made  
INNER JOIN db_academico.malla_academica maca on maca.maca_id = made.maca_id
WHERE maca.maca_codigo = '".$codesubject."'
AND made.made_semestre = 1
AND maca.maca_estado = 1 AND maca.maca_estado_logico = 1
AND made.made_estado = 1 AND made.made_estado_logico = 1
";
//var_dump($queryScheme);
    $comando = $con->prepare($queryScheme);
    $comando->execute();
    return $comando->fetchAll(\PDO::FETCH_ASSOC);
   }

 function getreference($pes_jornada,$pla_id,$maca_codigo, $bxs1=Null, $bxs2=Null, $bxs3=Null, $bxs4=Null, $bxs5=Null, $bxs6=Null,$bxs7=Null) {    
           GLOBAL $dsn, $dbuser, $dbpass, $dbname;
           $con = new \PDO($dsn, $dbuser, $dbpass);   
if ($bxs7 == Null){ $bxs7 == $bxs1; }
$queryScheme = "
select 
                pes.pes_id,pes.pla_id,pes.pes_jornada,pes.pes_cod_carrera,pes.pes_cod_malla,
                pes.pes_mat_b1_h1_cod as b1h1c,pes.pes_mat_b1_h1_mpp as b1h1p, pes.pes_mod_b1_h1 as b1h1m,pes.pes_jor_b1_h1 as b1h1j,
                pes.pes_mat_b1_h2_cod as b1h2c,pes.pes_mat_b1_h2_mpp as b1h2p, pes.pes_mod_b1_h2 as b1h2m,pes.pes_jor_b1_h2 as b1h2j,
                pes.pes_mat_b1_h3_cod as b1h3c,pes.pes_mat_b1_h3_mpp as b1h3p, pes.pes_mod_b1_h3 as b1h3m,pes.pes_jor_b1_h3 as b1h3j,
                pes.pes_mat_b1_h4_cod as b1h4c,pes.pes_mat_b1_h4_mpp as b1h4p, pes.pes_mod_b1_h4 as b1h4m,pes.pes_jor_b1_h4 as b1h4j,
                pes.pes_mat_b1_h5_cod as b1h5c,pes.pes_mat_b1_h5_mpp as b1h5p, pes.pes_mod_b1_h5 as b1h5m,pes.pes_jor_b1_h5 as b1h5j,
                pes.pes_mat_b1_h6_cod as b1h6c,pes.pes_mat_b1_h6_mpp as b1h6p, pes.pes_mod_b1_h6 as b1h6m,pes.pes_jor_b1_h6 as b1h6j,
                pes.pes_mat_b2_h1_cod as b2h1c,pes.pes_mat_b2_h1_mpp as b2h1p, pes.pes_mod_b2_h1 as b2h1m,pes.pes_jor_b2_h1 as b2h1j,
                pes.pes_mat_b2_h2_cod as b2h2c,pes.pes_mat_b2_h2_mpp as b2h2p, pes.pes_mod_b2_h2 as b2h2m,pes.pes_jor_b2_h2 as b2h2j,
                pes.pes_mat_b2_h3_cod as b2h3c,pes.pes_mat_b2_h3_mpp as b2h3p, pes.pes_mod_b2_h3 as b2h3m,pes.pes_jor_b2_h3 as b2h3j,
                pes.pes_mat_b2_h4_cod as b2h4c,pes.pes_mat_b2_h4_mpp as b2h4p, pes.pes_mod_b2_h4 as b2h4m,pes.pes_jor_b2_h4 as b2h4j,
                pes.pes_mat_b2_h5_cod as b2h5c,pes.pes_mat_b2_h5_mpp as b2h5p, pes.pes_mod_b2_h5 as b2h5m,pes.pes_jor_b2_h5 as b2h5j,
                pes.pes_mat_b2_h6_cod as b2h6c,pes.pes_mat_b2_h6_mpp as b2h6p, pes.pes_mod_b2_h6 as b2h6m,pes.pes_jor_b2_h6 as b2h6j
from db_academico.planificacion_estudiante pes
WHERE TRUE
AND pes.pla_id = $pla_id
-- AND pes.pes_jornada = '".$pes_jornada."'
AND pes.pes_cod_carrera = '".$maca_codigo."'
-- AND  pes.pes_mat_b1_h1_cod in ('".$bxs1."','".$bxs2."','".$bxs3."','".$bxs4."','".$bxs5."','".$bxs6."','".$bxs7."')
-- AND  pes.pes_mat_b2_h1_cod in ('".$bxs1."','".$bxs2."','".$bxs3."','".$bxs4."','".$bxs5."','".$bxs6."','".$bxs7."')
-- AND pes.pes_mat_b1_h1_mpp > 999 
AND pes_semestre = '77'
order by pes_id DESC limit 1
";
//var_dump($queryScheme);
    $comando = $con->prepare($queryScheme);
    $comando->execute();
    return $comando->fetchAll(\PDO::FETCH_ASSOC);
   }

$referenced = getStudents(); 

    if (count($referenced) >= 1 ) {
   for ($t = 0; $t < count($referenced); $t++) {


$scheme = getScheme($referenced[$t]['maca_codigo']);


        switch ($referenced[$t]['mod_id']) {
            case '1':
                $pla_id = 44;$jornada = 'N';
                break;
            case '2':
                $pla_id = 45;$jornada = 'N';
                break;
           /* case '3':
                $pla_id = 41;$jornada = 'S';
                break;
            case '4':
                $pla_id = 42;$jornada = 'D';
                break;*/
        }
/*
  if (count($scheme) == 7){

$referencerone = getreference(
    $jornada,
    $pla_id,
    $referenced[$t]['maca_codigo'],
    $scheme[0]['made_codigo_asignatura'],
    $scheme[1]['made_codigo_asignatura'],
    $scheme[2]['made_codigo_asignatura'],
    $scheme[3]['made_codigo_asignatura'],
    $scheme[4]['made_codigo_asignatura'],
    $scheme[5]['made_codigo_asignatura'],
    $scheme[6]['made_codigo_asignatura']
);
} 

  if (count($scheme) == 6){
$referencerone = getreference(
    $jornada,
    $pla_id,
    $referenced[$t]['maca_codigo'],
    $scheme[0]['made_codigo_asignatura'],
    $scheme[1]['made_codigo_asignatura'],
    $scheme[2]['made_codigo_asignatura'],
    $scheme[3]['made_codigo_asignatura'],
    $scheme[4]['made_codigo_asignatura'],
    $scheme[5]['made_codigo_asignatura']
);
} */

$referencerone = getreference(
    $jornada,
    $pla_id,
    $referenced[$t]['maca_codigo']);

  if (count($referencerone) > 0){

// $ishere = getpes($referencerone,$referenced[$t]['per_id']);
/*if (isset ($ishere[0]['per_id'])  {
if ($ishere[0]['per_id'] == Null)
{ */
$hasgenerated = doPusher($referencerone,$referenced[$t]['per_id'],$referenced[$t]['maca_nombre'],$referenced[$t]['per_cedula'],$referenced[$t]['estudiante']);
/* }} */
/*
    print_r('PES-->');
    var_dump($referencerone[0]['pes_id']);
    print_r('ALUMNO-->');
    var_dump($referenced[$t]['per_id']);*/

  } else {

//$hasgenerated = doPusher($scheme,$referenced[$t]['per_id']);
  /* print_r('ALUMNO-->');
    var_dump($referenced[$t]['per_id']);*/

  
  }

}}

 function getStudents($evaluator='') {    
           GLOBAL $dsn, $dbuser, $dbpass, $dbname;
           $con = new \PDO($dsn, $dbuser, $dbpass);   
$queryStudents = "
SELECT
pes.pla_id,
e.est_id, e.per_id, e.est_matricula, e.est_fecha_creacion, e.est_categoria, meu.uaca_id, meu.mod_id, meu.eaca_id, DATEDIFF(NOW(),e.est_fecha_creacion) as olderi, --
u.uaca_id, u.uaca_nombre, ea.teac_id, ea.eaca_nombre, ea.eaca_codigo,
per.per_cedula,  maca.maca_id , maca.maca_codigo, maca.maca_nombre,
concat(per.per_pri_nombre, ' ', ifnull(per.per_seg_nombre,''), ' ', per.per_pri_apellido, ' ', ifnull(per.per_seg_apellido,'')) estudiante
 from db_academico.estudiante e
 inner join db_academico.estudiante_carrera_programa c on c.est_id = e.est_id
  inner join db_academico.modalidad_estudio_unidad meu on meu.meun_id = c.meun_id
  inner join db_academico.malla_unidad_modalidad mumo on mumo.meun_id = meu.meun_id
   inner join db_academico.malla_academica maca on maca.maca_id = mumo.maca_id
   inner join db_academico.unidad_academica u on u.uaca_id = meu.uaca_id
   inner join db_academico.estudio_academico ea on ea.eaca_id = meu.eaca_id
    inner join db_asgard.persona per on per.per_id = e.per_id
  left join db_academico.planificacion_estudiante pes on pes.per_id = e.per_id and pes.pla_id in (39,40,41,42,44,45) AND pes.pes_estado = 1 AND pes.pes_estado_logico = 1
   WHERE TRUE AND maca.maca_id >46 
    AND  e.est_estado = 1 AND e.est_estado_logico = 1
    AND  c.ecpr_estado = 1 AND c.ecpr_estado_logico = 1
    AND  meu.meun_estado = 1 AND meu.meun_estado_logico = 1
    AND  mumo.mumo_estado = 1 AND mumo.mumo_estado_logico = 1
    AND  maca.maca_estado = 1 AND maca.maca_estado_logico = 1
    AND  u.uaca_estado = 1 AND u.uaca_estado_logico = 1
     AND  ea.eaca_estado = 1
    AND  per.per_estado = 1 AND per.per_estado_logico = 1
    AND meu.uaca_id = 1 AND meu.mod_id in (1,2) 
   AND pes.pla_id is $evaluator Null 
    AND DATEDIFF(NOW(),e.est_fecha_creacion) <=150
    AND DATEDIFF(NOW(),per.per_fecha_creacion) <=150
    order by maca.maca_id DESC;
";
    //var_dump($queryStudents);
    $comando = $con->prepare($queryStudents);
    $comando->execute();
    return $comando->fetchAll(\PDO::FETCH_ASSOC);
   }

function doPusher($schedule,$per_id,$maca_nombre,$per_cedula,$estudiante) {

        GLOBAL $dsn, $dbuser, $dbpass, $dbname;
        $con = new \PDO($dsn, $dbuser, $dbpass);

 if (isset($schedule[0]['pes_id'])){

$ishere = "
select pes_id 
FROM db_academico.planificacion_estudiante
WHERE TRUE
AND per_id = $per_id
AND pla_id in (39,40,41,42)
AND pes_estado = 1 
AND pes_estado_logico = 1
";

    $comando = $con->prepare($ishere);
    $comando->execute();
    $hereis = $comando->fetchAll(\PDO::FETCH_ASSOC);

     if ($hereis[0]['pes_id'] == Null ){ 

$replier=
"
INSERT INTO db_academico.planificacion_estudiante
(
pla_id,
per_id,
pes_jornada,
pes_cod_carrera,
pes_carrera,
pes_dni,
pes_nombres,
pes_cod_malla,
pes_mat_b1_h1_cod,
pes_mod_b1_h1,
pes_jor_b1_h1,
pes_mat_b1_h2_cod,
pes_mod_b1_h2,
pes_jor_b1_h2,
pes_mat_b1_h3_cod,
pes_mod_b1_h3,
pes_jor_b1_h3,
pes_mat_b1_h4_cod,
pes_mod_b1_h4,
pes_jor_b1_h4,
pes_mat_b1_h5_cod,
pes_mod_b1_h5,
pes_jor_b1_h5,
pes_mat_b1_h6_cod,
pes_mod_b1_h6,
pes_jor_b1_h6,
pes_mat_b2_h1_cod,
pes_mod_b2_h1,
pes_jor_b2_h1,
pes_mat_b2_h2_cod,
pes_mod_b2_h2,
pes_jor_b2_h2,
pes_mat_b2_h3_cod,
pes_mod_b2_h3,
pes_jor_b2_h3,
pes_mat_b2_h4_cod,
pes_mod_b2_h4,
pes_jor_b2_h4,
pes_mat_b2_h5_cod,
pes_mod_b2_h5,
pes_jor_b2_h5,
pes_mat_b2_h6_cod,
pes_mod_b2_h6,
pes_jor_b2_h6,
pes_estado,
pes_estado_logico
)
VALUES
(
'".$schedule[0]['pla_id']."',
'".$per_id."',
'".$schedule[0]['pes_jornada']."',
'".$schedule[0]['pes_cod_carrera']."',
'".$maca_nombre."',
'".$per_cedula."',
'".$estudiante."',
'".$schedule[0]['pes_cod_malla']."',
'".$schedule[0]['b1h1c']."',
'".$schedule[0]['b1h1m']."',
'".$schedule[0]['b1h1j']."',
'".$schedule[0]['b1h2c']."',
'".$schedule[0]['b1h2m']."',
'".$schedule[0]['b1h2j']."',
'".$schedule[0]['b1h3c']."',
'".$schedule[0]['b1h3m']."',
'".$schedule[0]['b1h3j']."',
'".$schedule[0]['b1h4c']."',
'".$schedule[0]['b1h4m']."',
'".$schedule[0]['b1h4j']."',
'".$schedule[0]['b1h5c']."',
'".$schedule[0]['b1h5m']."',
'".$schedule[0]['b1h5j']."',
'".$schedule[0]['b1h6c']."',
'".$schedule[0]['b1h6m']."',
'".$schedule[0]['b1h6j']."',
'".$schedule[0]['b2h1c']."',
'".$schedule[0]['b2h1m']."',
'".$schedule[0]['b2h1j']."',
'".$schedule[0]['b2h2c']."',
'".$schedule[0]['b2h2m']."',
'".$schedule[0]['b2h2j']."',
'".$schedule[0]['b2h3c']."',
'".$schedule[0]['b2h3m']."',
'".$schedule[0]['b2h3j']."',
'".$schedule[0]['b2h4c']."',
'".$schedule[0]['b2h4m']."',
'".$schedule[0]['b2h4j']."',
'".$schedule[0]['b2h5c']."',
'".$schedule[0]['b2h5m']."',
'".$schedule[0]['b2h5j']."',
'".$schedule[0]['b2h6c']."',
'".$schedule[0]['b2h6m']."',
'".$schedule[0]['b2h6j']."',
'1',
'1'
)"
;

 //var_dump($replier);

 //$replierer = str_replace('999','0', $replier);


  $comando = $con->prepare($replier);
                 $comando->execute();
                 $result = $comando->fetchAll(\PDO::FETCH_ASSOC);
                 

    }}

 if (isset($schedule[0]['made_codigo_asignatura'])){



    }

 

    }


function getpes($schedule,$per_id) {
           GLOBAL $dsn, $dbuser, $dbpass, $dbname;
           $con = new \PDO($dsn, $dbuser, $dbpass);  
    $isinpes="
select per_id from db_academico.planificacion_estudiante
where TRUE
AND per_id = ".$per_id."
AND pla_id = ".$schedule[0]['pla_id']."
AND pes_cod_carrera =  '".$schedule[0]['pes_cod_carrera']."'
AND pes_jornada = '".$schedule[0]['pes_jornada']."'
AND pes_estado = 1
AND pes_estado_logico = 1
"
;
//var_dump($isinpes);

 $comando = $con->prepare($isinpes);
    $comando->execute();
    return $comando->fetchAll(\PDO::FETCH_ASSOC);

}

?>