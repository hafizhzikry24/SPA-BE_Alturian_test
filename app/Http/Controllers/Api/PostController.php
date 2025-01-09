<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\Middleware;

class PostController extends Controller
{

    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $perPage = $request->input('per_page', 5);

            $posts = Post::query();

            if ($search) {
                $posts->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            }

            $posts = $posts->with('author')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'message' => 'Posts retrieved successfully',
                'data' => $posts,
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($slug)
    {
        try {
            $post = Post::where('slug', $slug)->first();

            if (!$post) {
                return response()->json([
                    'message' => 'Post not found',
                ], 404);
            }

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

            $slug = Str::slug($validated['title']);

            $existingPost = Post::where('slug', $slug)->first();
            if ($existingPost) {
                return response()->json([
                    'message' => 'Slug already exists.',
                    'error' => 'Duplicate slug'
                ], 422);
            }

            $post = Post::create([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'author_id' => auth('api')->user()->id,
                'slug' => $slug,
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

            $slug = Str::slug($validated['title']);

            if ($slug !== $post->slug) {
                $existingPost = Post::where('slug', $slug)->first();
                if ($existingPost) {
                    return response()->json([
                        'message' => 'Slug already exists.',
                        'error' => 'Duplicate slug'
                    ], 422);
                }
            }

            $post->update([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'slug' => $slug,
            ]);

            return response()->json([
                'message' => 'Post updated successfully',
                'data' => $post
            ], 200);
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
