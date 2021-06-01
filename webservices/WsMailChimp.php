<?php

/*
 * 
 * Ref API Mailchimp: https://developer.mailchimp.com/documentation/mailchimp/reference/overview/
 * 
 */

namespace app\webservices;

use yii;
use app\models\Http;
use yii\helpers\Url;
use PhpOffice\PhpSpreadsheet\Reader\Xls\MD5;

/**
 * Description of ConsumirWsdl
 *
 * @author root
 */
class WsMailChimp
{

    //Variables Locales
    private $apiKey     = "";
    public  $apiVersion = "3.0";
    private $dc     = "";
    private $host    = "api.mailchimp.com";
    private $port   = "443";
    private $user   = "";
    private $apiUrl = "";

    public function __construct()
    {
        //$this->apiKey  = "2a2dbbb8a33adbb41b46fb0efbb46fc4-us20";// produccion; 
        $this->apiKey  = "4d6064da3ab51d18e0586027f4cdbda9-us19";// desarrollo;
        $this->user = "Uteg";
        $arr_data = explode("-",$this->apiKey);
        $this->dc = $arr_data[1];
        //$this->apiUrl  = "https://" . $this->dc . "." . $this->uri . "/" . $this->apiVersion . "/";
        $this->host = $this->dc . "." . $this->host; //. "/" . $this->apiVersion . "/";
        $this->apiUrl = $this->apiVersion . "/";
    }

    // Get information about all lists
    public function getAllList(){
        $WS_URI = $this->apiUrl . "lists";
        $params = array();

        $response = Http::connect($this->host, $this->port, http::HTTPS)
            //->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doGet($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    // Get information about a specific list
    public function getList($listId){
        $WS_URI = $this->apiUrl . "lists/$listId";
        $params = array();        
        $response = Http::connect($this->host, $this->port, http::HTTPS)
            //->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doGet($WS_URI, $params);
        $arr_response = json_decode($response, true);
        \app\models\Utilities::putMessageLogFile('responseConsulta:'.json_encode($response));
        return $arr_response;
    }

    // Get information about a specific list
    public function editList($listId, $name, $contact, $permission_reminder, 
                             $sender_name, $sender_email, $subject_email, 
                             $language = "es", $email_type_option = true)
    {        
        $WS_URI = $this->apiUrl . "lists/".$listId;
        $params = json_encode(array(
            "name" => $name,
            /*"contact" => array(
                "company" => "UTEG",
                "address1" => "test1",
                "address2" => "test2",
                "city" => "Guayaquil",
                "state" => "GY",
                "zip" => "12345",
                "country" => "Ecuador",
                "phone" => "112233445566",
            ),*/
            "contact" => $contact,
            "permission_reminder" => $permission_reminder,
            "campaign_defaults" => array(
                "from_name" => $sender_name,
                "from_email" => $sender_email,
                "subject" => $subject_email,
                "language" => $language,
            ),
            "email_type_option" => $email_type_option,
        ));
        //\app\models\Utilities::putMessageLogFile('parametros:'.$params);        
        $response = Http::connect($this->host, $this->port, http::HTTPS)
            ->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doPatch($WS_URI, $params);        
        $arr_response = json_decode($response, true);        
        return $arr_response;
    }

    // Create a new list
    public function newList($nameList, $from_name, $from_email, $subject, $contact, $lang = "es"){
        $WS_URI = $this->apiUrl . "lists";
        $params = json_encode(array(
            "name" => $nameList,
            /*"contact" => array(
                "company" => "UTEG",
                "address1" => "test1",
                "address2" => "test2",
                "city" => "Guayaquil",
                "state" => "GY",
                "zip" => "12345",
                "country" => "Ecuador",
                "phone" => "112233445566",
            ),*/
            "contact" => $contact,
            "permission_reminder" => "You'\''re receiving this email because you signed up for updates about Freddie'\''s newest hats.",
            "campaign_defaults" => array(
                "from_name" => $from_name,
                "from_email" => $from_email,
                "subject" => $subject,
                "language" => $lang,
            ),
            "email_type_option" => true
        ));
        \app\models\Utilities::putMessageLogFile("antes de llamar a Http");   
        \app\models\Utilities::putMessageLogFile("Parámetros:". $params); 
        $response = Http::connect($this->host, $this->port, http::HTTPS)
            ->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doPost($WS_URI, $params);
        \app\models\Utilities::putMessageLogFile("despues de llamar a Http");           
        $arr_response = json_decode($response, true);
        \app\models\Utilities::putMessageLogFile("response:".$arr_response);   
        return $arr_response;
    }

    // Remove a list member
    function deleteList($listId){
        $WS_URI = $this->apiUrl . "lists/$listId";
        $params = array();

        $response = Http::connect($this->host, $this->port, http::HTTPS)
            //->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doDelete($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    // Get information about members in a list
    function getMembersList($listId){
        $WS_URI = $this->apiUrl . "lists/$listId/members";
        $params = array();

        $response = Http::connect($this->host, $this->port, http::HTTPS)
            //->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doGet($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    // Get information about a specific list member
    function getMemberSuscribedList($listId, $email_suscribed){
        $subscriber_hash = strtolower(MD5($email_suscribed));
        $WS_URI = $this->apiUrl . "/lists/$listI    d/members/$subscriber_hash";
        $params = array();

        $response = Http::connect($this->host, $this->port, http::HTTPS)
            //->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doGet($WS_URI, $params);              
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    // Add a new list member
    function newMember($listId, $email_member, $tags = array()){
        $WS_URI = $this->apiUrl . "lists/$listId/members";
        $params = json_encode(array(
            "email_address" => $email_member,
            "status" => "subscribed",
            "tags" => $tags,
        ));
        $response = Http::connect($this->host, $this->port, http::HTTPS)
            ->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doPost($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    // 	Get all templates
    function getAllTemplates(){
        $WS_URI = $this->apiUrl . "templates";
        $params = array(
            "count" => 200,
        );

        $response = Http::connect($this->host, $this->port, http::HTTPS)
            //->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doGet($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    // Get information about a specific template
    function getTemplate($template_id){
        $WS_URI = $this->apiUrl . "templates/$template_id";
        $params = array();

        $response = Http::connect($this->host, $this->port, http::HTTPS)
            //->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doGet($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    function createCampaign($listId, $addressInfo = array(), $type = "regular"){
        $WS_URI = $this->apiUrl . "campaigns";
        $params = json_encode(array(
            "recipients" => array(
                "list_id" => "$listId",
            ),
            "type" => $type,
            /*"settings" => array(
                "subject_line" => "",
                "title" => "",
                "from_name" => "",
                "reply_to" => "",
                "template_id" => "",
            ),*/
            "settings" => $addressInfo,
        ));
        $response = Http::connect($this->host, $this->port, http::HTTPS)
            ->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doPost($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    function sendCampaign($campaignId){
        $WS_URI = $this->apiUrl . "campaigns/$campaignId/actions/send";
        $params = array();

        $response = Http::connect($this->host, $this->port, http::HTTPS)
            ->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doPost($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

    // Get report details for a specific sent campaign.
    function getReportCampaign($campaignId){
        $WS_URI = $this->apiUrl . "/reports/$campaignId";
        $params = array();

        $response = Http::connect($this->host, $this->port, http::HTTPS)
            //->setHeaders(array('Content-Type: application/json', 'Accept: application/json'))
            ->setCredentials($this->user, $this->apiKey)
            ->doGet($WS_URI, $params);
        $arr_response = json_decode($response, true);
        return $arr_response;
    }

}
