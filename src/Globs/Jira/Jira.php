<?php

namespace v1\Globs\Jira;

use v1\Globs\Jira\Api\Client\CredentialsInterface;
use v1\Globs\Jira\Api\Client\CurlClient;

class Jira
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

    public function api($method = self::REQUEST_GET, $url, $data = array(), $return_as_json = false, $debug = false)
    {
        return $result = $this->client->sendRequest($method, $url, $data, $this->getEndpoint(), $this->credentials, $debug);
    }
}