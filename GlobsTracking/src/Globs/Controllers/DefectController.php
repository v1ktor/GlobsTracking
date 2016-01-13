<?php namespace GlobsTracking\Globs\Controllers;

use GlobsTracking\Globs\FrontController;
use GlobsTracking\Globs\Jira\Jira;
use GlobsTracking\Globs\CurlClient\Credentials;
use GlobsTracking\Globs\Rally\Rally;

class DefectController extends FrontController
{
    private $jira;
    private $rally;

    public function __construct()
    {
        parent::__construct();
        include("credentials.php");
        $this->jira = new Jira($endpoint, new Credentials($username, $password));
        $this->rally = new Rally($rally_endpoint, new Credentials($rally_username, $rally_password));
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
        $i = 0;

        $workspace = $this->rally->findWorkspace($rally_workspace);
        $project = $this->rally->findProject($rally_project);

        if (isset($data->errorMessages)) {
            $bugs["errors"] = $data->errorMessages;
        } else {
            foreach ($data->issues as $issue) {
                $bugs[$i]["jira_id"] = $issue->key;
                $bugs[$i]["lang"] = $issue->fields->customfield_10007->value;
                $bugs[$i]["component"] = $issue->fields->customfield_10011->value;
                $bugs[$i]["summary"] = $issue->fields->summary;
                $bugs[$i]["rally_id"] = $issue->fields->customfield_10090;
                $i++;
            }
        }

        echo $this->twig->render("table.html", array("bugs" => $bugs));

    }

    public function showJiraGlobs()
    {
        $this->getJiraGlobs();
    }
}
