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

    /**
     * Rally constructor.
     * @param $endpoint
     * @param CredentialsInterface $credentials
     */
    public function __construct($endpoint, CredentialsInterface $credentials)
    {
        $endpoint = $endpoint . "/slm/webservice/" . self::VERSION;
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
            Helper::displayErrorMessage("No workspaces found");
        }

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
