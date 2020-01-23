<?php

namespace App\Tests\Controller;

use App\Tests\Authenticate;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @package App\Tests\Controller
 */
class RegisterControllerTest extends WebTestCase
{
    use Authenticate;
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    private $client;

    const REGISTER = "/register";
    const LOGIN = "/authenticate";

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testRegisterWithoutDataShouldFail()
    {
        $this->client->request('POST', self::REGISTER, []);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString('{
                                        "status": "fail",
                                        "data": {
                                            "email": "Email is required!",
                                            "password": "The password is required!"
                                        }
                                    }', $this->client->getResponse()->getContent());
    }

    public function testRegisterWithPasswordValidShouldFail()
    {
        $this->client->request('POST', self::REGISTER, ["password"=>"123456"]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString('{
                                        "status": "fail",
                                        "data": {
                                            "email": "Email is required!"
                                        }
                                    }', $this->client->getResponse()->getContent());
    }

    public function testRegisterWithPasswordEmptyShouldFail()
    {
        $this->client->request('POST', self::REGISTER, ["password"=>""]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString('{
                                        "status": "fail",
                                        "data": {
                                            "email": "Email is required!",
                                            "password": "This value should have exactly 6 characters."
                                        }
                                    }', $this->client->getResponse()->getContent());
    }

    public function testRegisterWithPasswordEmptyAndEmailWrongShouldFail()
    {
        $this->client->request('POST', self::REGISTER, ["email"=>"me","password"=>""]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString('{
                                        "status": "fail",
                                        "data": {
                                            "email": "Email must be at least 6 characters long",
                                            "password": "This value should have exactly 6 characters."
                                        }
                                    }', $this->client->getResponse()->getContent());
    }

    public function testRegisterWithPasswordGreaterThanSixShouldFail()
    {
        $this->client->request('POST', self::REGISTER, ["email"=>"me@me.com","password"=>"1234567"]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString('{
                                        "status": "fail",
                                        "data": {
                                            "password": "This value should have exactly 6 characters."
                                        }
                                    }', $this->client->getResponse()->getContent());
    }

    public function testRegisterWithPasswordSmallerThanSixShouldFail()
    {
        $this->client->request('POST', self::REGISTER, ["email"=>"me@me.com","password"=>"1234"]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString('{
                                        "status": "fail",
                                        "data": {
                                            "password": "This value should have exactly 6 characters."
                                        }
                                    }', $this->client->getResponse()->getContent());
    }

    public function testRegisterWithPasswordGreaterThanSixAndEmailValidShouldFail()
    {
        $this->client->request('POST', self::REGISTER, ["email"=>"me@me.com","password"=>"1234567"]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString('{
                                        "status": "fail",
                                        "data": {
                                            "password": "This value should have exactly 6 characters."
                                        }
                                    }', $this->client->getResponse()->getContent());
    }

    public function testRegisterWithPasswordValidAndEmailGreaterThan180CharactersShouldFail()
    {
        $this->client->request('POST', self::REGISTER,
                                [
                                    "email"=>"mesajdflasdjfalsdfasdfsadfasdfsadfadscomsajdflasdjfalsdfasdfsadfasdfsadfadscomsajdflasdjfalsdfasdfsadfasdfsadfadscomsajdflasdjfalsdfasdfsadfasdfsadfad23823823823828328828218@me.com",
                                    "password"=>"1234567"
                                ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString('{
                                        "status": "fail",
                                        "data": {
                                            "password": "This value should have exactly 6 characters."
                                        }
                                    }', $this->client->getResponse()->getContent());
    }

    public function testRegisterShouldSuccess()
    {
        $this->client->request('POST', self::REGISTER, ["email"=>"me@me.com","password"=>"123456"]);
        $this->assertResponseStatusCodeSame(201);

        $res = $this->client->getResponse()->getContent();
        $result = json_decode($res, true);

        $this->assertArrayHasKey("status", $result);
        $this->assertArrayHasKey("data", $result);
        $this->assertCount(6, $result["data"]);
        $id = $result["data"]["id"];
        $created_at = $result["data"]["created_at"];

        $this->assertJsonStringEqualsJsonString('{
                                                "status": "success",
                                                "data": {
                                                    "id": "'.$id.'",
                                                    "email": "me@me.com",
                                                    "created_at": "'.$created_at.'",
                                                    "updated_at": "",
                                                    "status": "enable",
                                                    "status_description": "ativo"
                                                }
                                            }', $this->client->getResponse()->getContent());
    }

    public function testRegisterWithEmailExistentIsNotAllowedShouldFail()
    {
        $this->client->request('POST', self::REGISTER, ["email"=>"me@me.com","password"=>"123456"]);
        $this->assertResponseStatusCodeSame(201);

        $this->client->request('POST', self::REGISTER, ["email"=>"me@me.com","password"=>"123456"]);
        $this->assertResponseStatusCodeSame(405);
        $this->assertJsonStringEqualsJsonString('{
                                                        "status": "error",
                                                        "message": "Email already in use! Use another one!"
                                                    }', $this->client->getResponse()->getContent());
    }

    public function testLoginWithPasswordShouldFail()
    {
        $this->client->request('POST', self::LOGIN, ["password" => "123456"]);
        $this->assertResponseStatusCodeSame(422);

        $this->assertJsonStringEqualsJsonString('{
                                                                  "status": "fail",
                                                                  "data": {
                                                                     "email": "A email is required to login!"
                                                                  }
                                                                }', $this->client->getResponse()->getContent());
    }

    public function testLoginWithEmailShouldFail()
    {
        $this->client->request('POST', self::LOGIN, ["email" => "me@you.com"]);
        $this->assertResponseStatusCodeSame(422);

        $this->assertJsonStringEqualsJsonString('{
                                                                  "status": "fail",
                                                                  "data": {
                                                                    "password": "A password is required to login!"
                                                                  }
                                                                }', $this->client->getResponse()->getContent());
    }

    public function testLoginWithEmptyDataShouldFail()
    {
        $this->client->request('POST', self::LOGIN);
        $this->assertResponseStatusCodeSame(422);

        $this->assertJsonStringEqualsJsonString('{
                                                                  "status": "fail",
                                                                  "data": {
                                                                    "email": "A email is required to login!",
                                                                    "password": "A password is required to login!"
                                                                  }
                                                                }', $this->client->getResponse()->getContent());
    }

    public function testLoginWithUserBlockedShouldFail()
    {
        $result = $this->getTokenAuthenticate();

        $this->client->request('PUT', '/api/v1/users/my-status-to/blocked', [],[], ["HTTP_Authorization" => $result["token"]]);
        $this->assertResponseStatusCodeSame(204);

        $this->client->request('POST', self::LOGIN, ["email" =>  $this->email, "password" => $this->password]);
        $this->assertResponseStatusCodeSame(403);

        $this->assertJsonStringEqualsJsonString('{
                                                                  "status": "fail",            
                                                                  "message": "This user don\'t have permission to login!"
                                                                }', $this->client->getResponse()->getContent());
    }

    public function testLoginWithUserDisableShouldFail()
    {
        $result = $this->getTokenAuthenticate();

        $this->client->request('PUT', '/api/v1/users/my-status-to/disable', [],[], ["HTTP_Authorization" => $result["token"]]);
        $this->assertResponseStatusCodeSame(204);

        $this->client->request('POST', self::LOGIN, ["email" => $this->email, "password" => $this->password]);
        $this->assertResponseStatusCodeSame(403);

        $this->assertJsonStringEqualsJsonString('{
                                                                  "status": "fail",            
                                                                  "message": "This user don\'t have permission to login!"
                                                                }', $this->client->getResponse()->getContent());
    }

    public function testLoginWithEmailNotRegisteredShouldFail()
    {
        $this->client->request('POST', self::LOGIN, ["email" => "ya@ya.com", "password" => $this->password]);
        $this->assertResponseStatusCodeSame(404);

        $this->assertJsonStringEqualsJsonString('{
                                                                  "status": "error",            
                                                                  "message": "The email is wrong!"
                                                                }', $this->client->getResponse()->getContent());
    }

    public function testLoginWithPasswordIncorrectShouldFail()
    {
        $this->getTokenAuthenticate();
        $this->client->request('POST', self::LOGIN, ["email" => $this->email, "password" => "118822"]);
        $this->assertResponseStatusCodeSame(401);

        $this->assertJsonStringEqualsJsonString('{
                                                                  "status": "fail",
                                                                  "message": "The password is wrong!"
                                                                }', $this->client->getResponse()->getContent());
    }

    public function testLoginShouldSuccess()
    {
        $result = $this->getTokenAuthenticate();
        $this->client->request('POST', self::LOGIN, ["email" => $this->email, "password" => $this->password]);
        $this->assertResponseStatusCodeSame(201);

        $resultExpected = [
            "status" => "success",
            "data" => $result
        ];
        $this->assertJsonStringEqualsJsonString(json_encode($resultExpected), $this->client->getResponse()->getContent());
    }
}