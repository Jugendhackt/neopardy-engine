<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Question;
use App\Entity\GamePlayer;
use App\Repository\PlayerRepository;
use App\Repository\GamePlayerRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends AbstractController
{
    public function __construct(QuestionRepository $questions, PlayerRepository $players, EntityManagerInterface $em, GamePlayerRepository $gamePlayers)
    {
        $this->questions = $questions;
        $this->players = $players;
        $this->gamePlayers = $gamePlayers;
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

        $titles = [];

        $categoriesBase = [];

        foreach ($categories as $categorie) {
            $titles[] = ['content' => $categorie->getName()];
            dump($categorie);

            $questions = $this->questions->findBy(['category' => $categorie->getId()], ['points' => 'ASC']);

            foreach ($questions as $key => $question) {
                if ($question->getCorrectPlayer() !== null)
                {
                    $correctPlayer = $question->getCorrectPlayer()->getName();
                }
                else
                {
                    $correctPlayer = null;
                }


                $categoriesBase[$key][] = [
                    'content' => $question->getAnswer(),
                    'qId' => $question->getId(),
                    'playable' => true,
                    'correctPlayer' => $correctPlayer,
                    'points' => $question->getPoints()
                ];
            }
        }

        array_unshift($categoriesBase, $titles);
        dump($categoriesBase);

        return $this->json(['board' => $categoriesBase, 'currentAnswer' => $questions[0]->getAnswer()]);
    }

    /**
     * @Route("/api/player/selectQuestion/{game}", name="player_api_question_select")
     */
    public function apiQuestionSelect(Game $game, Request $request)
    {
        $qId = $request->request->get('qId');

        $question = $this->questions->find($qId);
        $game->setCurrentQuestion($question);
        $this->em->persist($game);
        $this->em->flush();

        return $this->json([]);
    }

    /**
     * @Route("/api/player/solutionSubmitted/{game}", name="player_api_solution_submitted")
     */
    public function apiSolutionSubmitted(Game $game, Request $request)
    {
        $playername = $request->request->get('playername');

        dump($playername);

        $player = $this->players->findOneBy(['game' => $game->getId(), 'name' => $playername]);

        if ($player === null)
        {
            return $this->json([]);
        }

        $gamePlayer = $this->gamePlayers->findOneBy(['player' => $player->getId(), 'game' => $game->getId()]);

        if ($gamePlayer !== null)
        {
            return $this->json([]);
        }

        $gamePlayer = new GamePlayer();
        $gamePlayer->setGame($game);
        $gamePlayer->setPlayer($player);

        $this->em->persist($gamePlayer);
        $this->em->flush();

        return $this->json([]);
    }
}
