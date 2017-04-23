<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use Tests\traits\MakeCommentTrait;
use Tests\traits\MakeUserTrait;

class CommentApiTest extends TestCase
{

    use MakeCommentTrait, ApiTestTrait, DatabaseTransactions, MakeUserTrait;

    /** @var  TestResponse */
    protected $response;


    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }


    /**
     * @test
     */
    public function testIndexComment()
    {
        $owner = $this->makeUser();
        $user = $this->makeUser();
        $commentFields = [
            'owner_id'  => $owner->id,
            'author_id' => $user->id
        ];
        $comment = $this->makeComment($commentFields);

        $this->response = $this->json('get', '/api/users/'.$owner->id.'/comments', [], $this->headers($user));

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
    public function testCreateComment()
    {
        $owner = $this->makeUser();
        $user = $this->makeUser();
        $commentFields = [
            'owner_id'  => $owner->id,
            'author_id' => $user->id
        ];
        $comment = $this->fakeCommentData($commentFields);
        $this->response = $this->json('POST', '/api/users/'.$owner->id.'/comments', $comment, $this->headers($user));

        $this->assertApiResponse($comment);
    }


    /**
     * @test
     */
    public function testCreateCommentChildren()
    {
        $owner = $this->makeUser();
        $user = $this->makeUser();
        $commentParentFields = [
            'owner_id'  => $owner->id,
            'author_id' => $user->id
        ];

        $commentParent = $this->makeComment($commentParentFields);
        $commentFields = [
            'owner_id'  => $owner->id,
            'author_id' => $user->id
        ];

        $comment = $this->fakeCommentData($commentFields);
        $this->response = $this->json('POST', '/api/users/'.$owner->id.'/comments/'.$commentParent->id.'/children',
            $comment, $this->headers($user));

        $this->assertApiResponse($comment);
    }


    /**
     * @test
     */
    public function testReadComment()
    {
        $owner = $this->makeUser();
        $user = $this->makeUser();
        $commentFields = [
            'owner_id'  => $owner->id,
            'author_id' => $user->id
        ];
        $comment = $this->makeComment($commentFields);
        $this->response = $this->json('GET', '/api/users/'.$owner->id.'/comments/'.$comment->id, [],
            $this->headers($user));

        $this->assertApiResponse($comment->toArray());
    }


    /**
     * @test
     */
    public function testUpdateComment()
    {
        $owner = $this->makeUser();
        $user = $this->makeUser();
        $commentFields = [
            'owner_id'  => $owner->id,
            'author_id' => $user->id
        ];
        $comment = $this->makeComment($commentFields);
        $editedComment = $this->fakeCommentData($commentFields);

        $this->response = $this->json('PUT', '/api/users/'.$owner->id.'/comments/'.$comment->id, $editedComment,
            $this->headers($user));

        $this->assertApiResponse($editedComment);
    }


    /**
     * @test
     */
    public function testDeleteComment()
    {
        $owner = $this->makeUser();
        $user = $this->makeUser();
        $commentFields = [
            'owner_id'  => $owner->id,
            'author_id' => $user->id
        ];
        $comment = $this->makeComment($commentFields);
        $this->response = $this->json('DELETE', '/api/users/'.$owner->id.'/comments/'.$comment->id, [],
            $this->headers($user));

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/users/'.$owner->id.'/comments/'.$comment->id, [],
            $this->headers($user));

        $this->response->assertStatus(404);
    }
}
