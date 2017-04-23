<?php

use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\traits\MakeCommentTrait;
use Tests\traits\MakeUserTrait;

class CommentRepositoryTest extends TestCase
{

    use MakeCommentTrait, MakeUserTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var CommentRepository
     */
    protected $commentRepo;


    public function setUp()
    {
        parent::setUp();
        $this->commentRepo = App::make(CommentRepository::class);
    }


    /**
     * @test create
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
        $createdComment = $this->commentRepo->create($comment);
        $createdComment = $createdComment->toArray();
        $this->assertArrayHasKey('id', $createdComment);
        $this->assertNotNull($createdComment['id'], 'Created Comment must have id specified');
        $this->assertNotNull(Comment::find($createdComment['id']), 'Comment with given id must be in DB');
        $this->assertModelData($createdComment, $comment);
    }


    /**
     * @test read
     */
    public function testReadComment()
    {
        $user = $this->makeUser();
        $commentFields = [
            'author_id' => $user->id
        ];
        $comment = $this->makeComment($commentFields);
        $dbComment = $this->commentRepo->find($comment->id);
        $dbComment = $dbComment->toArray();
        $this->assertModelData($dbComment, $comment->toArray());
    }


    /**
     * @test update
     */
    public function testUpdateComment()
    {
        $user = $this->makeUser();
        $commentFields = [
            'author_id' => $user->id
        ];
        $comment = $this->makeComment($commentFields);
        $fakeComment = $this->fakeCommentData($commentFields);
        $updatedComment = $this->commentRepo->update($fakeComment, $comment->id);
        $this->assertModelData($updatedComment->toArray(), $fakeComment);
        $dbComment = $this->commentRepo->find($comment->id);
        $this->assertModelData($dbComment->toArray(), $fakeComment);
    }


    /**
     * @test delete
     */
    public function testDeleteComment()
    {
        $user = $this->makeUser();
        $commentFields = [
            'author_id' => $user->id
        ];
        $comment = $this->makeComment($commentFields);
        $resp = $this->commentRepo->delete($comment->id);
        $this->assertTrue($resp);
        $this->assertNull(Comment::find($comment->id), 'Comment should not exist in DB');
    }
}
