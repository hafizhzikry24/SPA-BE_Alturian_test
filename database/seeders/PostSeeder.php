<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        Post::create([
            'title' => 'First Post Title',
            'body' => 'This is the body of the first post.',
            'slug' => 'first-post-title',
            'author_id' => $user->id,
        ]);

        Post::create([
            'title' => 'Second Post Title',
            'body' => 'This is the body of the second post.',
            'slug' => 'second-post-title',
            'author_id' => $user->id,
        ]);
    }
}
