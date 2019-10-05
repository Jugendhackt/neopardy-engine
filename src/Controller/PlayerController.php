<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Question;
use App\Repository\PlayerRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends AbstractController
{
    public function __construct(QuestionRepository $questions, PlayerRepository $players, EntityManagerInterface $em)
    {
        $this->questions = $questions;
        $this->players = $players;
        $this->em = $em;
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
     * @Route("/api/player/new/{game}", name="player_api_new")
     */
    public function apiCreateNewPlayer(Game $game, PlayerRepository $players, Request $request)
    {
        $username = $request->request->get('playername');

        $player = $this->players->findOneBy([
            'game' => $game->getId(),
            'name' => $username
            ]);

        dump($player);

        if ($player !== null)
        {
            return $this->json([
                'success' => 'ok',
                'response' => [
                    'playerId' => $player->getId()
                ]
            ]);
        }

        $player = new Player();
        $player->setName($username);
        $player->setGame($game);

        $this->em->persist($player);
        $this->em->flush();

        return $this->json([
            'success' => 'ok',
            'response' => [
                'playerId' => $player->getId()
            ]
        ]);
    }

    /**
     * @route("/api/player/getBoard/{game}", name="player_api_get_board")
     */
    public function apiGetBoard(Game $game)
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
                    'correctPlayer' => null
                ];
            }
        }

        array_unshift($categoriesBase, $titles);
        dump($categoriesBase);

        return $this->json(['board' => $categoriesBase, 'currentAnswer' => $questions[0]->getAnswer()]);
    }

    /**
     * @Route("/api/player/buttonPressed/{game}", name="player_api_button_pressed")
     */
    public function apiButtonPressed(Game $game, Request $request)
    {
        $qId = $request->request->get('qId');

        $question = $this->questions->find($qId);
        $game->setCurrentQuestion($question);
        return $this->json([]);
    }

    /**
     * @Route("/api/player/solutionSubmitted/{game}", name="player_api_solution_submitted")
     */
    public function apiSolutionSubmitted(Game $game, Request $request)
    {
        $playername = $request->request->get('playername');

        $player = $this->players->findOneBy(['game' => $game->getId(), 'playername' => $playername]);

        // $this->em->persist($);

        return $this->json([]);
    }
}
