<?php

namespace App\Model;

class Post
{
    public $id;
    private $title;
    public $text;

    public function __construct(int $id, string $title, string $text)
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
