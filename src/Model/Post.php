<?php

namespace App\Model;


class Post
{
    public $id;
    public $title;
    public $text;
    public $fechaCreacion;
    private $comments;

    /*public function __construct(int $id, string $title, string $text, array $comments = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->comments = $comments;
    }*/

    public static function new(int $id, string $title, string $text, array $comments = [])
    {
        $objeto = new self();
        $objeto->id = $id;
        $objeto->title = $title;
        $objeto->text = $text;
        $objeto->comments = $comments;

        return $objeto;
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
