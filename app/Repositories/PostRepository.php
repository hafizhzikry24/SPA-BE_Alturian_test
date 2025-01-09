<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Str;

class PostRepository
{
    public function getAllPosts($search, $perPage)
    {
        $posts = Post::query();

        if ($search) {
            $posts->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        return $posts->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getPostBySlug($slug)
    {
        return Post::where('slug', $slug)->first();
    }

    public function createPost($title, $body, $authorId)
    {
        $slug = Str::slug($title);

        $existingPost = Post::where('slug', $slug)->exists();
        if ($existingPost) {
            return null;
        }

        return Post::create([
            'title' => $title,
            'body' => $body,
            'author_id' => $authorId,
            'slug' => $slug,
        ]);
    }

    public function updatePost($post, $title, $body)
    {
        $slug = Str::slug($title);

        if ($slug !== $post->slug) {
            $existingPost = Post::where('slug', $slug)->first();
            if ($existingPost) {
                return null;
            }
        }

        $post->update([
            'title' => $title,
            'body' => $body,
            'slug' => $slug,
        ]);

        return $post;
    }

    public function deletePost($post)
    {
        $post->delete();
    }
}
