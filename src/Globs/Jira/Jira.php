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

    public function query($jql, $max_results = -1)
    {
        $options = array(
            "jql" => $jql,
            "maxResults" => $max_results,
        );

        $data = $this->api(self::REQUEST_GET, "/rest/api/2/search", $options);

        $issues = array();
        if (isset($data["errorMessages"])) {
            $issues["errorMessages"] = $data["errorMessages"];
        } else {
            if (isset($data["issues"])) {
                foreach ($data["issues"] as $issue) {
                    $issues[] = $issue;
                }
            }
        }

        return $issues;
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