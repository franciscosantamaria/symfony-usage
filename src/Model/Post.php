<?php

namespace App\Model;


class Post
{
    public $id;
    private $title;
    public $text;
    private $comments;

    public function __construct(int $id, string $title, string $text, array $comments = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->comments = $comments;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }
}
