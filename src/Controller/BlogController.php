<?php

namespace App\Controller;

use App\Model\Comment;
use App\Model\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    #[Route("/blog")]
    public function listPosts()
    {
        //hice una query a la BBDD
        $posts = $this->getPosts();

        return $this->render(
            'blog/posts.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    private function getPosts(): array
    {
        $com1 = new Comment('alfonso', 'qué bien explicas kiko');

        $post1 = new Post(1, 'Instalación de Symfony', 'Para instalar el fw de desarrollo....');
        $post1->addComment($com1);

        return [
            $post1,
            new Post(2, 'Rutas y Controladores', 'Las rutas en Symfony se definen....'),
            new Post(3, 'Plantillas twig', 'Lo primero que hay que hacer es instalar...'),
        ];
    }

    #[Route("/blog/articulo/{title}", name: "detalles_articulo")]
    public function detalleArticulo($title)
    {
        $postDetail = null;

        foreach ($this->getPosts() as $post) {
            if ($post->getTitle() == $title) {
                $postDetail = $post;
                break;
            }
        }

        return $this->render(
            'blog/detail.html.twig',
            [
                'post' => $postDetail
            ]
        );
    }
}
