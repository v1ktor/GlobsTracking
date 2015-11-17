<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'credentials.php';
require_once 'autoloader.php';

// instantiate the loader
$loader = new \Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('GlobsTracking', 'GlobsTracking/src');

use GlobsTracking\Globs\Helper;
use GlobsTracking\Globs\Jira\Jira;
use GlobsTracking\Globs\Jira\Api\Client\Credentials;

$jira = new Jira($endpoint, new Credentials($username, $password));
$helper = new Helper();

$jql = "project = " . $project . " AND issuetype = \"Software Defect\" AND status in (\"Open - On Hold\", \"Open - Glob\") AND \"US DTS ID\" is not EMPTY";

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