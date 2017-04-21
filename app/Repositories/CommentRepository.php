<?php

namespace App\Repositories;

use App\Models\Comment;
use InfyOm\Generator\Common\BaseRepository;

class CommentRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'content',
        'parent_id',
        'author_id'
    ];


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Comment::class;
    }
}
