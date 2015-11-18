<?php namespace GlobsTracking\Globs\Rally;

use GlobsTracking\Globs\CurlClient\CredentialsInterface;
use GlobsTracking\Globs\CurlClient\CurlClient;

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
     * @var Rally Security Token
     */
    private $securityToken;

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
        return $this->securityToken;
    }

    /**
     * @param mixed $securityToken
     */
    public function setSecurityToken($securityToken)
    {
        $this->securityToken = $securityToken;
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
