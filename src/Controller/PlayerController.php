<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\QuestionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerController extends AbstractController
{
    public function __construct(QuestionRepository $questions)
    {
        $this->questions = $questions;
    }

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
        $categories = $game->getCategories();

        $titles = [
            [
                'content' => 'Punkte'
            ]
        ];

        $categoriesBase = [
            [
                [
                    'content' => '100',
                ]
            ],
            [
                [
                    'content' => '200',
                ]
            ],
            [
                [
                    'content' => '300',
                ]
            ],
            [
                [
                    'content' => '400',
                ]
            ],
            [
                [
                    'content' => '500',
                ]
            ],
        ];

        foreach ($categories as $categorie) {
            $titles[] = ['content' => $categorie->getName()];
            dump($categorie);

            $questions = $this->questions->findBy(['category' => $categorie->getId()], ['points' => 'ASC']);

            foreach ($questions as $key => $question) {
                $categoriesBase[$key][] = [
                    'answer' => $question->getAnswer(),
                    'qId' => $question->getId(),
                    'playable' => true,
                    'correctPlayer' => 'null'
                ];
            }
        }

        array_unshift($categoriesBase, $titles);
        dump($categoriesBase);

        return $this->json(['board' => $categoriesBase]);

        // return $this->json([ 'board' =>[
        //     [
        //         'timestamp' => time(),
        //         [
        //             'kind' => 'text',
        //             'content' => 'Punkte'
        //         ],
        //         [
        //             'kind' => 'text',
        //             'content' => 'Computer'
        //         ]
        //     ],
        //     [
        //         [
        //             // 'kind' => 'question',
        //             'content' => 100,
        //             'qId' => 10,
        //             'playable' => true,
        //             'correctPlayer' => null
        //         ],
        //         [
        //             // 'kinde' => 'question',
        //             'answer' => 'i5 6500',
        //             'qId' => 10,
        //             'playable' => true,
        //             'correctPlayer' => null
        //         ],
        //     ],
        //     [
        //         [
        //             // 'kinde' => 'question',
        //             'content' => 200,
        //             'qId' => 10,
        //             'playable' => true,
        //             'correctPlayer' => null
        //         ],
        //         [
        //             // 'kinde' => 'question',
        //             'answer' => 'i9 9900k',
        //             'qId' => 10,
        //             'playable' => true,
        //             'correctPlayer' => null
        //         ],
        //     ],
        // ]]);
    }
}
