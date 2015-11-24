<?php namespace GlobsTracking\Globs\CurlClient;

interface CredentialsInterface
{
    public function getId();

    public function getPassword();
}