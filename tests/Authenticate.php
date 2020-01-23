<?php

namespace App\Tests;

/**
 * @package App\Tests
 */
trait Authenticate
{
    protected $email = "me@me.com";
    protected $password = "123456";
    protected $registeredData = [];

    public function getTokenAuthenticate()
    {
        $this->client->request('POST', '/register', [
            "email" => $this->email,
            "password" => $this->password
        ]);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $res = $this->client->getResponse()->getContent();
        $user = json_decode($res, true);

        $this->registeredData = $user["data"];

        $this->client->request('POST', '/authenticate', [
            "email" => $this->email,
            "password" => $this->password
        ]);

        $res    = $this->client->getResponse()->getContent();
        $result = json_decode($res, true);
        
        return $result["data"];
    }
}