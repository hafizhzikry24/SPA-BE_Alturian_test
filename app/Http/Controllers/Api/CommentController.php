<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\CommentRepository;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    use MessageTrait;

    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index(Post $post)
    {
        try {
            $comments = $this->commentRepository->getAllComments($post);
            return $this->successMessage('Comments retrieved successfully', $comments);
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while retrieving comments', $e->getMessage());
        }
    }

    public function show(Comment $comment)
    {
        try {
            $comment = $this->commentRepository->getCommentById($comment->id);
            return $this->successMessage('Comment retrieved successfully', $comment);
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while retrieving the comment', $e->getMessage());
        }
    }

    public function store(Request $request, Post $post)
    {
        try {
            $validated = Validator::make($request->all(), [
                'body' => 'required|string|max:500',
            ]);

            if ($validated->fails()) {
                return $this->validationErrorMessage($validated->errors());
            }

            $comment = $this->commentRepository->createComment($post, [
                'body' => $validated->validated()['body'],
                'user_id' => auth('api')->user()->id,
            ]);

            return $this->successMessage('Comment created successfully', $comment);
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while creating the comment', $e->getMessage());
        }
    }

    public function update(Request $request, Comment $comment)
    {
        try {
            $validated = Validator::make($request->all(), [
                'body' => 'required|string|max:500',
            ]);

            if ($validated->fails()) {
                return $this->validationErrorMessage($validated->errors());
            }

            $this->commentRepository->updateComment($comment, [
                'body' => $validated->validated()['body'],
            ]);

            return $this->successMessage('Comment updated successfully', $comment);
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while updating the comment', $e->getMessage());
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            $this->commentRepository->deleteComment($comment);
            return $this->successMessage('Comment deleted successfully');
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while deleting the comment', $e->getMessage());
        }
    }
}
