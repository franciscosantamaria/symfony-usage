<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController
{

    #[Route("/admin")]
    public function index()
    {
        return new Response("<html><head><link rel='stylesheet' href='/css/estilos.css'></head><body>ENTRÉ!!</body></html>");
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
