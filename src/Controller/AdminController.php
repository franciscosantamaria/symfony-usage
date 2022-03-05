<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    #[Route("/admin")]
    public function index()
    {
        $usuarios = [
            [
                'isAdmin' => true,
                'nombre' => 'kiko'
            ],
            [
                'isAdmin' => false,
                'nombre' => 'Adrian'
            ],
            [
                'isAdmin' => true,
                'nombre' => 'Juan'
            ]
        ];

        $user = [
          'isAdmin' => true,
          'nombre' => 'kiko'
        ];

        return $this->render('base.html.twig');
    }

    #[Route("/user/{username}/last/{number}", methods: ['GET', 'POST'])]
    public function listUsuarios($username, $number)
    {
        return new Response("PERFIL DE $username");
    }

    #[Route("/calcula/{word}")]
    public function calculaPalabra($word)
    {
        return new Response("PALABRA");
    }

    #[Route("/calcula/{word}", requirements: ['word' => '\d+'])]
    public function calcula($word)
    {
        return new Response("NUMERO");
    }
}
