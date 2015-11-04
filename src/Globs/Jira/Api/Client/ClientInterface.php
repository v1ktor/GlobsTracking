<?php

namespace v1\Globs\Jira\Api\Client;

interface ClientInterface
{
    public function sendRequest($method, $url, $data = array(), $endpoint, CredentialsInterface $credentials, $debug = false);
}