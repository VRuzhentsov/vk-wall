<?php

namespace App\Http\Controllers\API;

use App\Events\CommentPosted;
use App\Http\Requests\API\CreateCommentAPIRequest;
use App\Http\Requests\API\UpdateCommentAPIRequest;
use App\Models\Comment;
use App\Repositories\CommentRepository;
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
     * GET|HEAD /comments
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->commentRepository->pushCriteria(new RequestCriteria($request));
        $this->commentRepository->pushCriteria(new LimitOffsetCriteria($request));
        $comments = $this->commentRepository->with('author')->all();

        return $this->sendResponse($comments->toArray(), 'Comments retrieved successfully');
    }


    /**
     * Store a newly created Comment in storage.
     * POST /comments
     *
     * @param CreateCommentAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCommentAPIRequest $request)
    {
        $input = $request->all();

        $user = \Auth::user();

        $input['author_id'] = $user->id;

        $comment = $this->commentRepository->create($input);

        broadcast(new CommentPosted($comment, $user))->toOthers();

        return $this->sendResponse($comment->toArray(), 'Comment saved successfully');
    }


    /**
     * Display the specified Comment.
     * GET|HEAD /comments/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
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
     * PUT/PATCH /comments/{id}
     *
     * @param  int                    $id
     * @param UpdateCommentAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCommentAPIRequest $request)
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
     * DELETE /comments/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
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
