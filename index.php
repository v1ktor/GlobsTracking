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

$data = $jira->getGlobDefects();

echo "<br>";
foreach ($data as $issue) {
    echo $issue["key"] . "<br>";
}

echo '<pre>';
var_dump($data);
echo '</pre>';