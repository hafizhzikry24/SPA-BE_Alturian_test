<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class PostController extends Controller
{

    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $posts = Post::query();

            if ($search) {
                $posts->where(function($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                          ->orWhere('body', 'like', "%{$search}%");
                });
            }

            $posts = $posts->with('author')
                           ->orderBy('created_at', 'desc')
                           ->get();

            return response()->json([
                'message' => 'Posts retrieved successfully',
                'data' => $posts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Post $post)
    {
        try {
            $post->load('author');

            return response()->json([
                'message' => 'Post retrieved successfully',
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the post',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'required|string',
            ]);
            $post = Post::create([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'author_id' => auth('api')->user()->id,
            ]);

            return response()->json([
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Post $post)
    {
        try {

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'required|string',
            ]);
            $post->update([
                'title' => $validated['title'],
                'body' => $validated['body'],
            ]);

            return response()->json([
                'message' => 'Post updated successfully',
                'data' => $post
            ],200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {

            $post->delete();

            return response()->json(['message' => 'Post deleted successfully']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
