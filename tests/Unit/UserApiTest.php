<?php

namespace Tests\Unit;

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
    public function testIndexUser()
    {
        $this->response = $this->json('get', '/api/users');
        
        $this->assertApiResponse([]);
    }
    
    /**
     * @test
     */
    public function testCreateUser()
    {
        $user = $this->fakeUserData();
        $this->response = $this->json('POST', '/api/users', $user);
        
        $this->assertApiResponse($user);
    }
    
    /**
     * @test
     */
    public function testReadUser()
    {
        $user = $this->makeUser();
        $this->response = $this->json('GET', '/api/users/' . $user->id);
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
        
        $this->response = $this->json('PUT', '/api/users/' . $user->id, $editedUser);
        
        $this->assertApiResponse($editedUser);
    }
    
    /**
     * @test
     */
    public function testDeleteUser()
    {
        $user = $this->makeUser();
        $this->response = $this->json('DELETE', '/api/users/' . $user->id);
        
        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/users/' . $user->id);
        
        $this->response->assertStatus(404);
    }
}
