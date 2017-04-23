<?php

namespace App\Http\Controllers\API;

use App\Events\CommentChildPosted;
use App\Events\CommentPosted;
use App\Http\Requests\API\CreateCommentAPIRequest;
use App\Http\Requests\API\UpdateCommentAPIRequest;
use App\Models\Comment;
use App\Models\User;
use App\Repositories\CommentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class CommentController
 * @package App\Http\Controllers\API
 */
class CommentAPIController extends AppBaseController
{

    /** @var  CommentRepository */
    private $commentRepository;


    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepository = $commentRepo;
    }


    /**
     * Display a listing of the Comment.
     * GET|HEAD /user/{ownerId}/comments
     *
     * @param         $ownerId
     * @param Request $request
     *
     * @return Response
     */
    public function index($ownerId, Request $request)
    {
        /** @var User $owner */
        $owner = User::find($ownerId);

        /** @var Collection $comments */
        $comments = Comment::where('owner_id', '=', $owner->id)->whereIsRoot()->get();

        return $this->sendResponse($comments->toArray(), 'Comments retrieved successfully');
    }


    /**
     * Store a newly created Comment in storage.
     * POST /user/{ownerId}/comments
     *
     * @param                         $ownerId
     * @param CreateCommentAPIRequest $request
     *
     * @return Response
     */
    public function store($ownerId, CreateCommentAPIRequest $request)
    {
        $input = $request->all();

        /** @var User $owner */
        $owner = User::find($ownerId);

        /** @var User $user */
        $user = \Auth::user();

        $input['owner_id'] = $owner->id;

        $input['author_id'] = $user->id;

        /** @var Comment $comment */
        $comment = $this->commentRepository->create($input);

        broadcast(new CommentPosted($comment, $user))->toOthers();

        return $this->sendResponse($comment->toArray(), 'Comment saved successfully');
    }


    /**
     * Store a newly created Child Comment in storage.
     * POST /user/{ownerId}/comments/{comment}/children
     *
     * @param                         $ownerId
     * @param                         $commentId
     * @param CreateCommentAPIRequest $request
     *
     * @return Response
     */
    public function storeCommentsChildren($ownerId, $commentId, CreateCommentAPIRequest $request)
    {
        $input = $request->all();

        /** @var Comment $comment */
        $parentComment = $this->commentRepository->findWithoutFail($commentId);

        /** @var User $user */
        $user = \Auth::user();

        $input['owner_id'] = $parentComment->owner_id;

        $input['author_id'] = $user->id;

        /** @var Comment $comment */
        $comment = $this->commentRepository->create($input);

        $parentComment->appendNode($comment);

        broadcast(new CommentChildPosted($comment, $user))->toOthers();

        return $this->sendResponse($comment->toArray(), 'Comment saved successfully');
    }


    /**
     * Display the specified Comment.
     * GET|HEAD /user/{ownerId}/comments/{id}
     *
     * @param      $ownerId
     * @param  int $id
     *
     * @return Response
     */
    public function show($ownerId, $id)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findWithoutFail($id);

        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }

        return $this->sendResponse($comment->toArray(), 'Comment retrieved successfully');
    }


    /**
     * Update the specified Comment in storage.
     * PUT/PATCH /user/{ownerId}/comments/{id}
     *
     * @param                         $ownerId
     * @param  int                    $id
     * @param UpdateCommentAPIRequest $request
     *
     * @return Response
     */
    public function update($ownerId, $id, UpdateCommentAPIRequest $request)
    {
        $input = $request->all();

        /** @var Comment $comment */
        $comment = $this->commentRepository->findWithoutFail($id);

        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }

        $comment = $this->commentRepository->update($input, $id);

        return $this->sendResponse($comment->toArray(), 'Comment updated successfully');
    }


    /**
     * Remove the specified Comment from storage.
     * DELETE /user/{ownerId}/comments/{id}
     *
     * @param      $ownerId
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($ownerId, $id)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findWithoutFail($id);

        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }

        $comment->delete();

        return $this->sendResponse($id, 'Comment deleted successfully');
    }
}
