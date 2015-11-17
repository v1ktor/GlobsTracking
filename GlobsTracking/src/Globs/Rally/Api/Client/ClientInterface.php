<?php namespace GlobsTracking\Globs\Rally\Api\Client;

interface ClientInterface
{
    public function sendRequest($method, $url, $data = array(), $endpoint, CredentialsInterface $credentials, $debug = false);
}