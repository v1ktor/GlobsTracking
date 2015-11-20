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
use GlobsTracking\Globs\CurlClient\Credentials;
use GlobsTracking\Globs\Rally\Rally;

$jira = new Jira($endpoint, new Credentials($username, $password));
$helper = new Helper();

$rally = new Rally($rally_endpoint, new Credentials($rally_username, $rally_password));
$rally->findWorkspace($rally_workspace);
$rally->findProject($rally_project);
echo "<pre>";
$rally->findDefect($sample_bug_id);
echo "</pre>";

$jql = "project = " . $project . " AND issuetype = \"Software Defect\" AND status in (\"Open - On Hold\", \"Open - Glob\") AND \"US DTS ID\" is not EMPTY";

$bugs = array();
$data = $jira->query($jql);

$callStartTime = microtime(true);
echo "<pre>";
for ($i = 0; $i <= sizeof($data); $i++) {
     $bugs[$i]["rally"] = $rally->findDefect($data[$i]["fields"]["customfield_10090"]);
}
var_dump($bugs);
echo "</pre>";
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;
echo 'Call time to run query was ' . sprintf('%.4f',$callTime) . " seconds";