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


        //hice una query a la BBDD
        $posts = $this->getPosts();

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
    public function showNewPostForm(Request $request, int $numPosts, EntityManagerInterface $em, LoggerInterface $logger, DataRetriever $dr)
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

    #[Route("/blog/new-post/procesar", name: 'procesar-form')]
    public function procesarFormulario(Request $request)
    {
        $titulo = $request->request->get('titulo');
        dd($titulo);

        return $this->render('blog/new-post.html.twig');
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

    #[Route("/insert-post/author/{author}")]
    public function insertPost(EntityManagerInterface $em, $author)
    {
        $post = new Post();
        $post->setTitle("Instalación de Symfony");
        $post->setText("Los primeros pasos para instalar.........");
        $post->setAuthor($author);

        $em->persist($post);
        $em->flush();

        return new Response("<html><body>Se ha creado un post!!</body></html>");
    }

    #[Route("/eliminarPost/id/{id}", name: 'remove_post')]
    public function eliminarPost(EntityManagerInterface $em, $id)
    {
        $post = $em->getRepository(Post::class)->find($id);


        if ($post == null) {
            return new JsonResponse(['error' => 'usuario no existente'], 400);
        }

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    #[Route("/modificar-autor/id/{id}/author/{author}")]
    public function modificarAuthor(EntityManagerInterface $em, $author, $id)
    {
        try {
            $post = $em->getRepository(Post::class)->find($id);
        } catch (\Exception $e) {

        }


        if ($post == null) {
            return new JsonResponse(['error' => 'usuario no existente'], 400);
        }

        $post->setAuthor($author);
        $em->flush();


        return new Response("<html><body>Se ha modificado un post!!</body></html>");
    }

    #[Route("/get/posts")]
    public function obtenerPosts(EntityManagerInterface $em)
    {
        $posts = $em->getRepository(Post::class)->findOneBy(['author' => 'Kiko']);

        return new Response("<html><body>Lo que sea</body></html>");

       /* $data = [];

        foreach ($posts as $post) {
            $data[] = ['id' => $post->getId(), 'title' => $post->getTitle()];
        }

        return new JsonResponse($data);*/



        return new Response("Se ha creado un post!!");
    }















}
