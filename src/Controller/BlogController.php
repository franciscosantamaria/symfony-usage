<?php

namespace App\Controller;

use App\Form\PostFormType;
use App\Model\Comment;
use App\Model\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    #[Route("/blog", name: 'homepage')]
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

        $post1 = Post::new(1, 'Instalación de Symfony', 'Para instalar el fw de desarrollo....');
        $post1->addComment($com1);

        return [
            $post1,
            Post::new(2, 'Rutas y Controladores', 'Las rutas en Symfony se definen....'),
            Post::new(3, 'Plantillas twig', 'Lo primero que hay que hacer es instalar...'),
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


    #[Route("/blog/new-post", name: 'show-form')]
    public function showNewPostForm(Request $request)
    {
        $formulario = $this->createForm(PostFormType::class);
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            //dd($formulario->getData());
            $this->addFlash('exito', 'Se ha creado el post correctamente!!');
            return $this->redirectToRoute('homepage');
        } else {
            return $this->renderForm(
                'blog/new-post.html.twig',
                [
                    'formulario' => $formulario,
                    'titulo' => 'Nuevo post'
                ]
            );
        }
    }

    #[Route("/blog/new-post/procesar", name: 'procesar-form')]
    public function procesarFormulario(Request $request)
    {
        $titulo = $request->request->get('titulo');
        dd($titulo);

        return $this->render('blog/new-post.html.twig');
    }

    #[Route("/blog/{id}/edit", name: 'edit_post')]
    public function editPost(int $id, Request $request)
    {
        foreach ($this->getPosts() as $post) {
            if ($post->id === $id) {
                $postDetail = $post;
                break;
            }
        }

        $formulario = $this->createForm(PostFormType::class, $postDetail);

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            //dd($formulario->getData());
            //actualizo en BBDD
            $this->addFlash('exito', "Post modificado!!!");

            return $this->redirectToRoute('homepage');
        }


        return $this->renderForm(
            'blog/new-post.html.twig',
            [
                'formulario' => $formulario,
                'titulo' => 'Modificar post'
            ]
        );



    }

    #[Route("/getData")]
    public function getData(Request $request)
    {
        $dato = $request->query->get('dato', 'anonimo');
        //$dato = $_GET['dato'];
        return new Response($dato);
    }















}
