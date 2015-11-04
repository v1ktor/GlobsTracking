<?php

namespace v1\Globs\Jira\Api\Client;

class CurlClient implements ClientInterface
{
    public function __construct()
    {
    }

    public function sendRequest($method, $url, $data = array(), $endpoint, $credentials, $isFile = false, $debug = false)
    {
        // TODO: Implement sendRequest() method.
    }
}