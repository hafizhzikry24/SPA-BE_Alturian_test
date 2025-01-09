<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        try {
            $comments = $post->comments()->with('author')->get();

            return response()->json([
                'message' => 'Comments retrieved successfully',
                'data' => $comments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving comments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Comment $comment)
    {
        try {
            $comment = Comment::with('author')->findOrFail($comment->id);

            return response()->json([
                'message' => 'Comment retrieved successfully',
                'data' => $comment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request, Post $post)
    {
        try {
            $validated = $request->validate([
                'body' => 'required|string|max:500',
            ]);

            $comment = $post->comments()->create([
                'body' => $validated['body'],
                'user_id' => auth('api')->user()->id,
            ]);

            return response()->json([
                'message' => 'Comment created successfully',
                'data' => $comment
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Comment $comment)
    {
        try {

            $validated = $request->validate([
                'body' => 'required|string|max:500',
            ]);

            $comment->update([
                'body' => $validated['body'],
            ]);

            return response()->json([
                'message' => 'Comment updated successfully',
                'data' => $comment
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Comment $comment)
    {
        try {

            $comment->delete();

            return response()->json([
                'message' => 'Comment deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
