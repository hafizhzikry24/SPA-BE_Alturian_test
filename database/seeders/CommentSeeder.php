<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $post = Post::first();

        Comment::create([
            'body' => 'This is the first comment on the post.',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        Comment::create([
            'body' => 'This is the second comment on the post.',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
