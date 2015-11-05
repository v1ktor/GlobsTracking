<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// @TODO implement autoloader
require_once 'credentials.php';
require_once 'src/Globs/Jira/Api/Client/ClientInterface.php';
require_once 'src/Globs/Jira/Api/Client/CurlClient.php';
require_once 'src/Globs/Jira/Api/Client/CredentialsInterface.php';
require_once 'src/Globs/Jira/Api/Client/Credentials.php';
require_once 'src/Globs/Jira/Jira.php';

$jira = new v1\Globs\Jira\Jira($endpoint, new \v1\Globs\Jira\Api\Client\Credentials($username, $password));

$options = array(
    "jql" => "project = " . $project . " AND issuetype = \"Software Defect\" AND status in (\"Open - On Hold\", \"Open - Glob\") AND \"US DTS ID\" is not EMPTY"
);
$data = $jira->api("GET", "/rest/api/2/search", $options);
$data = json_decode($data);

echo '<pre>';
var_dump($data);
echo '</pre>';