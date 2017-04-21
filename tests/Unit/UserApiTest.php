<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use Tests\traits\MakeUserTrait;

class UserApiTest extends TestCase
{

    use MakeUserTrait, ApiTestTrait, DatabaseTransactions;

    /** @var  TestResponse */
    protected $response;


    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }


    /**
     * @test
     */
    public function testSingleUser()
    {
        $user = $this->makeUser();

        $this->response = $this->json('get', '/api/user', [], $this->headers($user));

        $this->assertApiResponse([]);
    }


    /**
     * Return request headers needed to interact with the API.
     *
     * @return Array array of headers.
     */
    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if ( ! is_null($user)) {
            $token = $user->createToken('Token Name')->accessToken;
            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }


    /**
     * @test
     */
    public function testIndexUser()
    {
        $user = $this->makeUser();

        $this->response = $this->json('get', '/api/users', [], $this->headers($user));

        $this->assertApiResponse([]);
    }


    /**
     * @test
     */
    public function testCreateUser()
    {
        $user = $this->fakeUserData();
        $userHeader = $this->makeUser();
        $this->response = $this->json('POST', '/api/users', $user, $this->headers($userHeader));

        $this->assertApiResponse($user);
    }


    /**
     * @test
     */
    public function testReadUser()
    {
        $user = $this->makeUser();
        $this->response = $this->json('GET', '/api/users/'.$user->id, [], $this->headers($user));
        $tmp = $this->response->baseResponse;

        $this->assertApiResponse($user->toArray());
    }


    /**
     * @test
     */
    public function testUpdateUser()
    {
        $user = $this->makeUser();
        $editedUser = $this->fakeUserData();

        $this->response = $this->json('PUT', '/api/users/'.$user->id, $editedUser, $this->headers($user));

        $this->assertApiResponse($editedUser);
    }


    /**
     * @test
     */
    public function testDeleteUser()
    {
        $user = $this->makeUser();
        $this->response = $this->json('DELETE', '/api/users/'.$user->id, [], $this->headers($user));

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/users/'.$user->id, [], $this->headers($user));

        $this->response->assertStatus(404);
    }
}
