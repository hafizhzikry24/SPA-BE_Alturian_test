<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use MessageTrait;

    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $perPage = $request->input('per_page', 5);

            $posts = $this->postRepository->getAllPosts($search, $perPage);

            return $this->successMessage('Posts retrieved successfully', $posts->items(), $posts);
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while retrieving posts', $e->getMessage());
        }
    }

    public function show($slug)
    {
        try {
            $post = $this->postRepository->getPostBySlug($slug);

            if (!$post) {
                return $this->errorMessage('Post not found', null, 404);
            }

            $post->load(['author:id,name,email,email_verified_at,created_at,updated_at']);

            return $this->successMessage('Post retrieved successfully', $post);
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while retrieving the post', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'body' => 'required|string',
            ]);

            if ($validated->fails()) {
                return $this->validationErrorMessage($validated->errors());
            }

            $post = $this->postRepository->createPost($validated->validated()['title'], $validated->validated()['body'], auth('api')->user()->id);

            if (!$post) {
                return $this->errorMessage('Slug already exists.', 'Duplicate slug', 422);
            }

            return $this->successMessage('Post created successfully', $post);
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while creating the post', $e->getMessage());
        }
    }

    public function update(Request $request, Post $post)
    {
        try {
            $validated = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'body' => 'required|string',
            ]);

            if ($validated->fails()) {
                return $this->validationErrorMessage($validated->errors());
            }

            $updatedPost = $this->postRepository->updatePost($post, $validated->validated()['title'], $validated->validated()['body']);

            if (!$updatedPost) {
                return $this->errorMessage('Slug already exists.', 'Duplicate slug', 422);
            }

            return $this->successMessage('Post updated successfully', $updatedPost);
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while updating the post', $e->getMessage());
        }
    }

    public function destroy(Post $post)
    {
        try {
            $this->postRepository->deletePost($post);

            return $this->successMessage('Post deleted successfully');
        } catch (\Exception $e) {
            return $this->errorMessage('An error occurred while deleting the post', $e->getMessage());
        }
    }
}
