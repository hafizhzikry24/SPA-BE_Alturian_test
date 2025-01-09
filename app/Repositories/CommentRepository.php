<?php
namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;

class CommentRepository
{
    public function getAllComments(Post $post)
    {
        return $post->comments()->with('author')->get();
    }

    public function getCommentById($commentId)
    {
        return Comment::with('author')->findOrFail($commentId);
    }

    public function createComment(Post $post, $data)
    {
        return $post->comments()->create($data);
    }

    public function updateComment(Comment $comment, $data)
    {
        return $comment->update($data);
    }

    public function deleteComment(Comment $comment)
    {
        return $comment->delete();
    }
}
