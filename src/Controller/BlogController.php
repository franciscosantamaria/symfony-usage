<?php

namespace App\Controller;

use App\Client\DataRetriever;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Model\Comment;
use App\Model\Post as PostAlias;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    #[Route("/blog", name: 'homepage')]
    public function listPosts(EntityManagerInterface $em)
    {
        $posts = $em->getRepository(Post::class)->findAll();

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

        $post1 = PostAlias::new(1, 'Instalación de Symfony', 'Para instalar el fw de desarrollo....');
        $post1->addComment($com1);

        return [
            $post1,
            PostAlias::new(2, 'Rutas y Controladores', 'Las rutas en Symfony se definen....'),
            PostAlias::new(3, 'Plantillas twig', 'Lo primero que hay que hacer es instalar...'),
        ];
    }

    #[Route("/blog/articulo/{title}", name: "detalles_articulo")]
    public function detalleArticulo(Post $post)
    {
        /*$postDetail = null;

        foreach ($this->getPosts() as $post) {
            if ($post->getTitle() == $title) {
                $postDetail = $post;
                break;
            }
        }*/

        return $this->render(
            'blog/detail.html.twig',
            [
                'post' => $post
            ]
        );
    }


    #[Route("/blog/new-post", name: 'new-post')]
    public function newPost(Request $request, int $numPosts, EntityManagerInterface $em, LoggerInterface $logger, DataRetriever $dr)
    {
        $dr->enviar('el dato');

        $formulario = $this->createForm(PostFormType::class);
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $logger->info("ALGUIEN HA CREADO UN NUEVO POST!!");
            $em->persist($formulario->getData());
            $em->flush();


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

    #[Route("/blog/{id}/edit", name: 'edit_post')]
    public function editPost(int $id, Request $request, EntityManagerInterface $em)
    {
        $post = $em->getRepository(Post::class)->find($id);

        $formulario = $this->createForm(PostFormType::class, $post);

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $em->persist($formulario->getData());
            $em->flush();
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

    #[Route("/eliminarPost/id/{id}", name: 'remove_post')]
    public function eliminarPost(EntityManagerInterface $em, $id)
    {
        $post = $em->getRepository(Post::class)->find($id);

        if ($post == null) {
            $this->addFlash('error', 'El post no existe');
        } else {
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('homepage');
    }
}
