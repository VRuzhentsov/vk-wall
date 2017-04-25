<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Comment
 *
 * @package App\Models
 * @version April 20, 2017, 9:10 pm UTC
 * @property int                                                     $id
 * @property string                                                  $content
 * @property int                                                     $author_id
 * @property \Carbon\Carbon                                          $created_at
 * @property \Carbon\Carbon                                          $updated_at
 * @property \Carbon\Carbon                                          $deleted_at
 * @property int                                                     $_lft
 * @property int                                                     $_rgt
 * @property int                                                     $parent_id
 * @property-read \App\Models\User                                   $author
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Comment[] $children
 * @property-read \App\Models\Comment                                $parent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment d()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereAuthorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int                   $owner_id
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereOwnerId($value)
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
        'content' => 'required|min:5|max:10000'
    ];

    public $table = 'comments';

    public $fillable = [
        'content',
        'parent_id',
        'owner_id',
        'author_id',
        'created_at',
        'updated_at'
    ];

    public $with = [
        'author',
        'children'
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
        'owner_id'  => 'integer',
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


    /**
     * Owner relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
