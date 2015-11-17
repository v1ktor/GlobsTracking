<?php

namespace GlobsTracking\Globs\Jira\Api\Client;

interface CredentialsInterface
{
    public function getId();

    public function getPassword();
}