<?php

namespace Tests\traits;

use Faker\Factory as Faker;
use App\Models\Comment;
use App\Repositories\CommentRepository;

trait MakeCommentTrait
{

    /**
     * Create fake instance of Comment and save it in database
     *
     * @param array $commentFields
     *
     * @return Comment
     */
    public function makeComment($commentFields = [])
    {
        /** @var CommentRepository $commentRepo */
        $commentRepo = \App::make(CommentRepository::class);
        $theme = $this->fakeCommentData($commentFields);

        return $commentRepo->create($theme);
    }


    /**
     * Get fake data of Comment
     *
     * @param array $postFields
     *
     * @return array
     */
    public function fakeCommentData($commentFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'content'    => $fake->text,
            '_lft'       => 1,
            '_rgt'       => 2,
            'author_id'  => isset($commentFields['author_id']) ? $commentFields['author_id'] : null,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $commentFields);
    }


    /**
     * Get fake instance of Comment
     *
     * @param array $commentFields
     *
     * @return Comment
     */
    public function fakeComment($commentFields = [])
    {
        return new Comment($this->fakeCommentData($commentFields));
    }
}
