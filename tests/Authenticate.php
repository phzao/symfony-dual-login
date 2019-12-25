<?php

namespace App\Tests;

/**
 * Trait Authenticate
 * @package App\Tests
 */
trait Authenticate
{
    /**
     * @return mixed
     */
    public function getTokenAuthenticate()
    {
        $this->client->request('POST', '/register', [
            "email"    => "me@me.com",
            "password" => "123456"
        ]);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/authenticate', [
            "email"    => "me@me.com",
            "password" => "123456"
        ]);

        $res    = $this->client->getResponse()->getContent();
        $result = json_decode($res, true);

        return $result["data"];
    }
}