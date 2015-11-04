<?php
error_reporting(E_ALL);

// @TODO implement autoloader
require_once 'src/Globs/Jira/Api/Client/ClientInterface.php';
require_once 'src/Globs/Jira/Api/Client/CurlClient.php';
require_once 'src/Globs/Jira/Api/Client/CredentialsInterface.php';
require_once 'src/Globs/Jira/Api/Client/Credentials.php';

$username = "";
$password = "";
$endpoint = "";

use v1\Globs\Jira\Api\Client;
$cred = new Client\Credentials($username, $password);

$curl_client = new Client\CurlClient();

$options = array(

);

$data = $curl_client->sendRequest("GET", "/rest/api/2/mypermissions", $options, $endpoint, $cred);
$data = json_decode($data);

echo '<pre>';
var_dump($data);
echo '</pre>';