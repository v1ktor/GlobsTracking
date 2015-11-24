<?php namespace GlobsTracking\Globs\CurlClient;

class Credentials implements CredentialsInterface
{
    private $user_id;
    private $password;

    public function __construct($user_id, $password)
    {
        $this->user_id = $user_id;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->user_id;
    }

    public function getPassword()
    {
        return $this->password;
    }

}