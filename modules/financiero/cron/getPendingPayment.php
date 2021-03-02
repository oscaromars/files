<?php

$logFile = dirname(__FILE__) . "/../../../runtime/logs/pb.log";
$http = include_once(dirname(__FILE__) . "/../../fe_edoc/clientWS/libs/http.php");
$dataDB = include_once(dirname(__FILE__) . "/../config/mod.php");
$payment_gateway = "";
$dbname = 'db_financiero';
$dbuser = $dataDB["financiero"]["db_facturacion"]["username"];
$dbpass = $dataDB["financiero"]["db_facturacion"]["password"];
$port = "443";
$dbserver = "127.0.0.1"; //$dataDB["marketing"]["db_mailing"]["dbserver"];
$ipAddress = "127.0.0.1";
$dbport = 3306;
$seed = "";
$nounce = "";
$transKey = "";
$dsn = "mysql:host=$dbserver;dbname=$dbname;port=$dbport";
spl_autoload_register('my_autoloader');
putMessageLogFile('Inicio Proceso cron:'.date('c'));
getPagosPendientes();
putMessageLogFile('Fin Proceso cron:'.date('c'));
putMessageLogFile("-------------------------------------------*******-------------------------------------------");
function putMessageLogFile($message) {
    global $logFile;
    if (is_array($message))
        $message = json_encode($message);
    $message = date("Y-m-d H:i:s") . " " . $message . "\r\n";
    if ((filesize($logFile) / pow(1024, 2)) > 100) { // si el log es mayor a 100 MB entonces se debe limpiar el archivo
        file_put_contents($logFile, $message, LOCK_EX);
    } else {
        file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
    }
}


function getPagosPendientes() {
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;
    $pdo = new \PDO($dsn, $dbuser, $dbpass);
    $sql = "
                select vres.reference as ref,vres.requestId,vres.ordenPago,vres.tipo_orden,vire.status
                from db_financiero.vpos_response as vres
                left join db_financiero.vpos_info_response as vire on vire.ordenPago = vres.ordenPago and vire.tipo_orden = vres.tipo_orden
                where ifnull(vire.id,0) = 0
                and vres.estado_logico = 1 and vres.requestId>0;
            ";
    $cmd = $pdo->prepare($sql);
    $cmd->execute();
    $rows_in = $cmd->fetchAll(\PDO::FETCH_ASSOC);
    
    if (count($rows_in) > 0) {
        for ($i = 0; $i < count($rows_in); $i++) {
            $arr_response = getInfoPayment($rows_in[$i]);
            saveInfoResponseDB($arr_response, $rows_in[$i]);
        }
    }else{
        putMessageLogFile("No hay registros por ingresar.");
    }
    
    $sql = "
                select vres.reference as ref,vres.requestId,vres.ordenPago,vres.tipo_orden,vire.status
                from db_financiero.vpos_response as vres
                left join db_financiero.vpos_info_response as vire on vire.ordenPago = vres.ordenPago and vire.tipo_orden = vres.tipo_orden
                where vire.status = 'PENDING'
                and vres.estado_logico = 1 and vres.requestId>0;
    ";
    $cmd = $pdo->prepare($sql);
    $cmd->execute();
    $rows_up = $cmd->fetchAll(\PDO::FETCH_ASSOC);
    if (count($rows_up) > 0) {
        for ($i = 0; $i < count($rows_up); $i++) {
            $arr_response = getInfoPayment($rows_up[$i]);
            updateInfoResponseDB($arr_response, $rows_up[$i]);
        }
    }else{
        putMessageLogFile("No hay registros por actualizar.");
    }
}

function getInfoPayment($row) {
    GLOBAL $ipAddress, $seed, $nounce, $port, $transKey;
    $seed = date('c');
    $nonce = "";
    if (function_exists('random_bytes')) {
        $nonce = bin2hex(random_bytes(16));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $nonce = bin2hex(openssl_random_pseudo_bytes(16));
    } else {
        $nonce = mt_rand();
    }
    $credenciales = selectVPOST(1);
    $nounce = base64_encode($nonce);
    $transKey = base64_encode(sha1($nonce . $seed . $credenciales[2]['secret'], true));
    $WS_URI = "redirection/api/session/" . $row['requestId'];
    $params = [
        "auth" => [
            "login" => $credenciales[1]['login'],
            "seed" => $seed,
            "nonce" => $nounce,
            "tranKey" => $transKey,
        ],
        "ipAddress" => $ipAddress,
        #"userAgent" => $_SERVER['HTTP_USER_AGENT'],
        "userAgent" => "",
    ];
    //putMessageLogFile("Params Info Request: Uri ->". $credenciales[0]['gateway'].":$port/$WS_URI  -  Params -> " . json_encode($params));
    $response = Http::connect($credenciales[0]['gateway'], $port, http::HTTPS)
            ->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->doPost($WS_URI, json_encode($params));
    $arr_response = json_decode($response, true);
    return $arr_response;
}

function saveInfoResponseDB($params, $row) {
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;    
    $reference = $params["request"]["payment"]["reference"];
    $requestId = $params["requestId"];
    $status = $params["status"]["status"];
    $reason = $params["status"]["reason"];
    $message = $params["status"]["message"];
    $date = date("Y-m-d H:i:s", strtotime($params["status"]["date"]));
    $payment_status = $params["payment"][0]["status"]["status"];
    $payment_reason = $params["payment"][0]["status"]["reason"];
    $payment_message = $params["payment"][0]["status"]["message"];
    $payment_date = ($params["payment"][0]["status"]["date"]) ? date("Y-m-d H:i:s", strtotime($params["payment"][0]["status"]["date"])) : NULL;
    $internalReference = $params["payment"][0]["internalReference"];
    $paymenMethod = $params["payment"][0]["paymentMethod"];
    $paymentMethodName = $params["payment"][0]["paymentMethodName"];
    $issuerName = $params["payment"][0]["issuerName"];
    $autorization = $params["payment"][0]["authorization"];
    $receipt = $params["payment"][0]["receipt"];
    $orden_pago = $row['ordenPago'];
    $tipo_orden = $row['tipo_orden'];
    $json_info = json_encode($params);
    try {
        $pdo = new \PDO($dsn, $dbuser, $dbpass);
        $estado_logico = "1";
        $sql = "INSERT INTO " . $dbname . ".vpos_info_response 
            (reference,
            requestId,
            ordenPago,
            tipo_orden,
            status,
            reason,
            message,
            date,
            payment_status,
            payment_reason,
            payment_message,
            payment_date,
            internalReference,
            paymenMethod,
            paymentMethodName,
            issuerName,
            autorization,
            receipt,
            json_info,
            estado_logico)
            VALUES
            ('$reference',
            '$requestId',
            $orden_pago,
            $tipo_orden,
            '$status',
            '$reason',
            '$message',
            '$date',
            '$payment_status',
            '$payment_reason',
            '$payment_message',
            '$payment_date',
            '$internalReference',
            '$paymenMethod',
            '$paymentMethodName',
            '$issuerName',
            '$autorization',
            '$receipt',
            '$json_info',
            $estado_logico);";
        $cmd = $pdo->prepare($sql);
        $cmd->execute();
        putMessageLogFile('El request: '.$requestId.' ha sido ingresado.');
    } catch (PDOException $e) {
        putMessageLogFile('Error: ' . $e->getMessage());
        exit;
    }
}

function updateInfoResponseDB($params, $row) {
    GLOBAL $dsn, $dbuser, $dbpass, $dbname;    
    $reference = $params["request"]["payment"]["reference"];
    $requestId = $params["requestId"];
    $status = $params["status"]["status"];
    $reason = $params["status"]["reason"];
    $message = $params["status"]["message"];
    $date = date("Y-m-d H:i:s", strtotime($params["status"]["date"]));
    $payment_status = $params["payment"][0]["status"]["status"];
    $payment_reason = $params["payment"][0]["status"]["reason"];
    $payment_message = $params["payment"][0]["status"]["message"];
    $payment_date = ($params["payment"][0]["status"]["date"]) ? date("Y-m-d H:i:s", strtotime($params["payment"][0]["status"]["date"])) : NULL;
    $internalReference = $params["payment"][0]["internalReference"];
    $paymenMethod = $params["payment"][0]["paymentMethod"];
    $paymentMethodName = $params["payment"][0]["paymentMethodName"];
    $issuerName = $params["payment"][0]["issuerName"];
    $autorization = $params["payment"][0]["authorization"];
    $receipt = $params["payment"][0]["receipt"];
    $orden_pago = $row['ordenPago'];
    $tipo_orden = $row['tipo_orden'];
    $json_info = json_encode($params);
    try {
        $pdo = new \PDO($dsn, $dbuser, $dbpass);
        $estado_logico = "1";
        $sql = "update " . $dbname . ".vpos_info_response 
            set
            status='$status',
            reason='$reason',
            message='$message',
            date='$date',
            payment_status='$payment_status',
            payment_reason='$payment_reason',
            payment_message='$payment_message',
            payment_date='$payment_date',
            internalReference='$internalReference',
            paymenMethod='$paymenMethod',
            paymentMethodName='$paymentMethodName',
            issuerName='$issuerName',
            autorization='$autorization',
            receipt='$receipt',
            json_info='$json_info',
            estado_logico=$estado_logico
            where requestId='$requestId' and ordenPago=$orden_pago; and tipo_orden=$tipo_orden and reference='$reference';
            ";
        $cmd = $pdo->prepare($sql);
        $cmd->execute();
        putMessageLogFile('El request: '.$requestId.' ha sido actualizado.');
    } catch (PDOException $e) {
        putMessageLogFile('Error: ' . $e->getMessage());
        exit;
    }
}

function selectVPOST($type_vpos) {
    $credenciales = array();
    $jsonCredential = json_decode(file_get_contents("/opt/vpos.json", true), true);
    switch ($type_vpos) {
        case "1": # Prueba
            array_push($credenciales, ['gateway' => $jsonCredential["gateway_test"]]);
            array_push($credenciales, ['login' => $jsonCredential["login_test"]]);
            array_push($credenciales, ['secret' => $jsonCredential["secret_test"]]);
            break;
        case "2": # Produccion
            array_push($credenciales, ['gateway' => $jsonCredential["gateway_prod"]]);
            array_push($credenciales, ['login' => $jsonCredential["login_prod"]]);
            array_push($credenciales, ['secret' => $jsonCredential["secret_prod"]]);
            break;
    }
    return $credenciales;
}

function my_autoloader($class) {
    $filename1 = dirname(__FILE__) . '/../../../widgets/PbVPOS/PbVPOS.php';
    $filename2 = dirname(__FILE__) . '/../../../models/Http.php';
    include_once($filename1);
    include_once($filename2);
}
