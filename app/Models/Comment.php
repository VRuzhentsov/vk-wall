<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Comment
 * @package App\Models
 * @version April 20, 2017, 9:10 pm UTC
 */
class Comment extends Model
{

    use SoftDeletes, NodeTrait;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public $table = 'comments';

    public $fillable = [
        'content',
        'parent_id',
        'author_id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'content'   => 'string',
        'parent_id' => 'integer',
        'author_id' => 'integer'
    ];


    /**
     * Author relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

}
