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
require_once 'src/Globs/Helper.php';

$jira = new v1\Globs\Jira\Jira($endpoint, new \v1\Globs\Jira\Api\Client\Credentials($username, $password));
$helper = new v1\Globs\Helper();

$jql = "asdasdasdproject = AIW16 AND issuetype = \"Software Defect\" AND status in (\"Open - On Hold\", \"Open - Glob\") AND \"US DTS ID\" is not EMPTY";

$data = $jira->query($jql);

echo "<br><br>";

if (isset($data["errorMessages"])) {
    foreach ($data["errorMessages"] as $errorMessage) {
        echo $helper->displayErrorMessage($errorMessage);
    }
} else {
    foreach ($data as $issue) {
        echo $issue["key"] . "<br>";
    }
}

echo '<pre>';
var_dump($data);
echo '</pre>';