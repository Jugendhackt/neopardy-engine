<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerController extends AbstractController
{
    /**
     * @Route("/player", name="player")
     */
    public function index()
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }

    /**
     * @route("/api/player/getBoard/{game}")
     */
    public function getBoard(Game $game)
    {
        return $this->json([ 'board' =>[
            [
                'timestamp' => time(),
                [
                    'kind' => 'text',
                    'content' => 'Punkte'
                ],
                [
                    'kind' => 'text',
                    'content' => 'Computer'
                ]
            ],
            [
                [
                    // 'kind' => 'question',
                    'content' => 100,
                    'qId' => 10,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'answer' => 'i5 6500',
                    'qId' => 10,
                    'playable' => true,
                    'correctPlayer' => null
                ],
            ],
            [
                [
                    // 'kinde' => 'question',
                    'content' => 200,
                    'qId' => 10,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'answer' => 'i9 9900k',
                    'qId' => 10,
                    'playable' => true,
                    'correctPlayer' => null
                ],
            ],
        ]]);
    }
}
