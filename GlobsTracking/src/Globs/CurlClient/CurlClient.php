<?php namespace GlobsTracking\Globs\CurlClient;

class CurlClient implements ClientInterface
{
    public function __construct()
    {
    }

    public function sendRequest($method, $url, $data = array(), $endpoint, CredentialsInterface $credentials, $debug = false)
    {
        $curl = curl_init();

        if ($method == "GET") {
            $url .= "?" . http_build_query($data);
        }

        curl_setopt($curl, CURLOPT_URL, $endpoint . $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, sprintf("%s:%s", $credentials->getId(), $credentials->getPassword()));
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_VERBOSE, $debug);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=UTF-8"));

        if ($method == "POST") {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        } else {
            if ($method == "PUT") {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        $data = curl_exec($curl);

        $error_number = curl_errno($curl);
        if ($error_number > 0) {
            throw new \Exception (
                sprintf("Curl request failed: code = %s, '%s'", $error_number, curl_errno($curl))
            );
        }

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 401) {
            throw new \Exception("Unauthorized");
        }

        if ($data === '' && curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            throw new \Exception("Curl returns unexpected result.");
        }

        if (is_null($data)) {
            throw new \Exception("Curl returns unexpected result.");
        }

        return $data;

    }
}