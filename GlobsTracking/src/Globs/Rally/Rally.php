<?php namespace GlobsTracking\Globs\Rally;

use GlobsTracking\Globs\Rally\Api\Client\CredentialsInterface;
use GlobsTracking\Globs\Rally\Api\Client\CurlClient;

class Rally
{
    const REQUEST_GET = "GET";
    const REQUEST_POST = "POST";
    const REQUEST_PUT = "PUT";
    const REQUEST_DELETE = "DELETE";

    private $client;
    private $credentials;
    private $endpoint;

    public function __construct($endpoint, CredentialsInterface $credentials)
    {
        $this->setEndpoint($endpoint);
        $this->credentials = $credentials;
        $this->client = new CurlClient();
    }

    public function setEndpoint($url)
    {
        $this->endpoint = $url;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getToken()
    {
        $data = $this->api(self::REQUEST_GET, '/security/authorize');
        var_dump($data);
    }

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
