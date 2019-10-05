<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GamemasterController extends AbstractController
{
    /**
     * @Route("/gamemaster", name="gamemaster")
     */
    public function index()
    {
        return $this->render('gamemaster/index.html.twig', [
            'controller_name' => 'GamemasterController',
        ]);
    }
}
