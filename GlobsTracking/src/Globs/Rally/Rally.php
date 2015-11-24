<?php namespace GlobsTracking\Globs\Rally;

use GlobsTracking\Globs\CurlClient\CredentialsInterface;
use GlobsTracking\Globs\CurlClient\CurlClient;
use GlobsTracking\Globs\Helper;

/**
 * Class Rally
 * @package GlobsTracking\Globs\Rally
 */
class Rally
{

    const REQUEST_GET = "GET";
    const REQUEST_POST = "POST";
    const REQUEST_PUT = "PUT";
    const REQUEST_DELETE = "DELETE";
    /**
     * Rally API version
     */
    const VERSION = "v2.0";

    /**
     * @var CurlClient
     */
    private $client;
    /**
     * @var CredentialsInterface
     */
    private $credentials;
    /**
     * Endpoint URL
     * @example https://rally1.rallydev.com/slm/webservice/v2.0
     * @var
     */
    private $endpoint;
    /**
     * Rally Security Token
     * @var
     */
    private $security_token;
    private $workspace_id;
    private $project_id;

    /**
     * Rally constructor.
     * @param $endpoint
     * @param CredentialsInterface $credentials
     */
    public function __construct($endpoint, CredentialsInterface $credentials)
    {
        $endpoint = rtrim($endpoint, "/") . "/slm/webservice/" . self::VERSION;
        $this->setEndpoint($endpoint);
        $this->credentials = $credentials;
        $this->client = new CurlClient();
    }

    /**
     * @param $url
     */
    public function setEndpoint($url)
    {
        $this->endpoint = $url;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return mixed
     */
    public function getSecurityToken()
    {
        return $this->security_token;
    }

    /**
     * @param mixed $security_token
     */
    public function setSecurityToken($security_token)
    {
        $this->security_token = $security_token;
    }

    /**
     * @return mixed
     */
    public function getWorkspaceId()
    {
        return $this->workspace_id;
    }

    /**
     * @param mixed $workspace_id
     */
    public function setWorkspaceId($workspace_id)
    {
        $this->workspace_id = $workspace_id;
    }

    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * @param mixed $project_id
     */
    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
    }

    /**
     * Get Rally security token
     * @return null
     */
    public function getToken()
    {
        $data = $this->api(self::REQUEST_GET, '/security/authorize');

        if (isset($data["OperationResult"]["SecurityToken"])) {
            $this->setSecurityToken($data["OperationResult"]["SecurityToken"]);
        }

        return null;
    }

    /**
     * Get list off all Rally workspaces
     * @return array|mixed
     */
    public function getWorkspaces()
    {
        $data = $this->api(self::REQUEST_GET, "/subscription");

        if (isset($data["Subscription"]["ObjectID"])) {
            $subscription_objectid = $data["Subscription"]["ObjectID"];
            $data = $this->api(self::REQUEST_GET, "/subscription/" . $subscription_objectid . "/Workspaces");
        } else {
            echo Helper::displayErrorMessage("Could not get workspaces list");
        }

        return $data;
    }

    /**
     * @param $workspace_name
     * @return null
     */
    public function findWorkspace($workspace_name)
    {
        $workspaces = $this->getWorkspaces();

        foreach ($workspaces["QueryResult"]["Results"] as $workspace) {
            if ($workspace["_refObjectName"] == $workspace_name) {
                $this->setWorkspaceId($workspace["ObjectID"]);
            }
        }

        if ($this->getWorkspaceId()) {
            // echo Helper::displaySuccessMessage("Workspace " . $workspace_name . " is found");
        } else {
            // echo Helper::displayErrorMessage("Workspace " . $workspace_name . " is not found");
        }

        return null;
    }

    /**
     * Get list of projects for current workspace
     * @return array|mixed
     */
    public function getProjects()
    {
        $args = array(
            "workspace" => $this->endpoint . "/workspace/" . $this->workspace_id,
            "fetch" => "true",
        );

        $data = $this->api(self::REQUEST_GET, "/project", $args);

        return $data["QueryResult"]["Results"];
    }

    /**
     * @param $project_name
     * @return null
     */
    public function findProject($project_name)
    {
        $projects = $this->getProjects();

        foreach ($projects as $project) {
            if ($project["_refObjectName"] == $project_name) {
                $this->setProjectId($project["ObjectID"]);
            }
        }

        if ($this->getProjectId()) {
            // echo Helper::displaySuccessMessage("Project " . $project_name . " is found");
        } else {
            // echo Helper::displayErrorMessage("Project " . $project_name . " is not found");
        }

        return null;
    }

    /**
     * @param $defect_id
     * @return array|mixed
     */
    public function findDefect($defect_id)
    {
        $args = array(
            "workspace" => $this->endpoint . "/workspace/" . $this->workspace_id,
            "project" => $this->endpoint . "/project/" . $this->project_id,
            "query" => "(FormattedID = " . $defect_id . ")",
            "fetch" => "true",
        );

        $data = $this->api(self::REQUEST_GET, "/defect", $args);

        return $data;
    }

    /**
     * Method to send requests to REST API SERVER
     * @param string $method
     * @param $url
     * @param array $data
     * @param bool|false $return_as_json
     * @param bool|false $debug
     * @return array|mixed
     * @throws \Exception
     */
    public function api($method = self::REQUEST_GET, $url, $data = array(), $return_as_json = false, $debug = false)
    {
        $result = $this->client->sendRequest($method, $url, $data, $this->getEndpoint(), $this->credentials, $debug);

        if ($return_as_json == false) {
            return json_decode($result, true);
        } else {
            return $result;
        }
    }
}
