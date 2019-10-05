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
    public function getBoard($game)
    {
        return $this->json([ 'board' =>[
            [
                'timestamp' => time(),
                [
                    'kind' => 'text',
                    'content' => 'Computer'
                ],
                [
                    'kind' => 'text',
                    'content' => 'CCC'
                ],
                [
                    'kind' => 'text',
                    'content' => 'Klima'
                ],
                [
                    'kind' => 'text',
                    'content' => 'Luul'
                ]
            ],
            [
                [
                    // 'kind' => 'question',
                    'content' => 100,
                    'answer' => 'i5 6500',
                    'qId' => 10,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kind' => 'question',
                    'content' => 100,
                    'answer' => 'Ein Baum',
                    'qId' => 11,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kind' => 'question',
                    'content' => 100,
                    'answer' => 'Ein Baum',
                    'qId' => 12,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kind' => 'question',
                    'content' => 100,
                    'answer' => 'Ein Baum',
                    'qId' => 13,
                    'playable' => true,
                    'correctPlayer' => null
                ]
            ],
            [
                [
                    // 'kinde' => 'question',
                    'content' => 200,
                    'answer' => 'i9 9900k',
                    'qId' => 20,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 200,
                    'answer' => 'i9 9900k',
                    'qId' => 21,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 200,
                    'answer' => 'i9 9900k',
                    'qId' => 22,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 200,
                    'answer' => 'i9 9900k',
                    'qId' => 23,
                    'playable' => true,
                    'correctPlayer' => null
                ]
                
            ],
            [
                [
                    // 'kinde' => 'question',
                    'content' => 300,
                    'answer' => 'i9 9900k',
                    'qId' => 30,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 300,
                    'answer' => 'i9 9900k',
                    'qId' => 31,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 300,
                    'answer' => 'i9 9900k',
                    'qId' => 32,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 300,
                    'answer' => 'i9 9900k',
                    'qId' => 33,
                    'playable' => true,
                    'correctPlayer' => null
                ]
                
            ],
            [
                [
                    // 'kinde' => 'question',
                    'content' => 400,
                    'answer' => 'i9 9900k',
                    'qId' => 40,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 400,
                    'answer' => 'i9 9900k',
                    'qId' => 41,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 400,
                    'answer' => 'i9 9900k',
                    'qId' => 42,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 400,
                    'answer' => 'i9 9900k',
                    'qId' => 43,
                    'playable' => true,
                    'correctPlayer' => null
                ]
                
            ],
            [
                [
                    // 'kinde' => 'question',
                    'content' => 500,
                    'answer' => 'i9 9900k',
                    'qId' => 50,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 500,
                    'answer' => 'i9 9900k',
                    'qId' => 51,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 500,
                    'answer' => 'i9 9900k',
                    'qId' => 52,
                    'playable' => true,
                    'correctPlayer' => null
                ],
                [
                    // 'kinde' => 'question',
                    'content' => 500,
                    'answer' => 'i9 9900k',
                    'qId' => 53,
                    'playable' => true,
                    'correctPlayer' => null
                ]
                
            ],
        ]]);
    }
}
