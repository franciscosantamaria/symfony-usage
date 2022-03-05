<?php

namespace App\Controller;

use App\Model\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiBlogController extends AbstractController
{

    #[Route("/api/blogs", methods: ['GET'])]
    public function listPosts()
    {
        $posts = [
            [
                'id' => 1,
                'title' => 'fkdsf',
                'content' => 'fkldsjfksdfjsdlkfj sdfklsjld fjsd f'
            ],
            [
                'id' => 2,
                'title' => 'fsdfsd fsd fas',
                'content' => 'fkldsjfksdfjsdlkfj sdfklsjld fjsd f'
            ]
        ];
        //devolver json

        //return new Response(json_encode($posts));
        //return new JsonResponse($posts);
        return $this->json($posts);
    }

    private function getPosts(): array
    {
        return [
            new Post(),
            new Post(),
            new Post(),
        ];
    }

}
