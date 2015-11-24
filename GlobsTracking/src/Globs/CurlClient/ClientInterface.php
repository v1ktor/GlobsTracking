<?php namespace GlobsTracking\Globs\CurlClient;

interface ClientInterface
{
    public function sendRequest($method, $url, $data = array(), $endpoint, CredentialsInterface $credentials, $debug = false);
}