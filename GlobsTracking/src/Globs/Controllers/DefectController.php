<?php namespace GlobsTracking\Globs\Controllers;

use GlobsTracking\Globs\FrontController;
use GlobsTracking\Globs\Jira\Jira;
use GlobsTracking\Globs\CurlClient\Credentials;

class DefectController extends FrontController
{
    private $jira;

    public function __construct()
    {
        parent::__construct();
        include("credentials.php");
        $this->jira = new Jira($endpoint, new Credentials($username, $password));
    }

    public function index(){
        echo "defect index";
    }

    public function test()
    {
        echo "hello from defect test";
    }

    public function getJiraGlobs()
    {
        include("credentials.php");
        $jql = "project = " . $project . " AND issuetype = \"Software Defect\" AND status in (\"Open - On Hold\", \"Open - Glob\") AND \"US DTS ID\" is not EMPTY";

        $data = $this->jira->query($jql);
        $data = json_decode($data);
        $bugs = array();

        if (isset($data->errorMessages)) {
            $bugs["errors"] = $data->errorMessages;
        } else {
            foreach ($data->issues as $issue) {
                $bugs[]["jira_id"] = $issue->key;
            }
        }

        echo $this->twig->render("table.html", array("bugs" => $bugs));

    }

    public function showJiraGlobs()
    {
        $this->getJiraGlobs();
    }
}
