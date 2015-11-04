<?php

namespace v1\Globs\Jira\Api\Client;

interface CredentialsInterface
{
    public function getCredentials();

    public function getId();

    public function getPassword();
}