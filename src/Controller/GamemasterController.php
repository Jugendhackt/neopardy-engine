<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Question;
use App\Entity\GamePlayer;
use App\Repository\PlayerRepository;
use App\Repository\QuestionRepository;
use App\Repository\GamePlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GamemasterController extends AbstractController
{
    public function __construct(QuestionRepository $questions, PlayerRepository $players, EntityManagerInterface $em, GamePlayerRepository $gamePlayers)
    {
        $this->questions = $questions;
        $this->players = $players;
        $this->gamePlayers = $gamePlayers;
        $this->em = $em;
    }

    /**
     * @Route("/gamemaster", name="gamemaster")
     */
    public function index()
    {
        return $this->render('gamemaster/index.html.twig', [
            'controller_name' => 'GamemasterController',
        ]);
    }

    /**
     * @route("/api/gamemaster/getBoard/{game}", name="gamemaster_api_get_board")
     */
    public function apiGetBoard(Game $game, Request $request)
    {
        $categories = $game->getCategories();
        $playername = $request->request->get('playername');
        $player = $this->_getPlayerOrCreate($game, $playername);

        if ($player !== null)
        {
            $player = [
                'name' => $player->getName(),
                'id' => $player->getId(),
                'points' => $player->getPoints()
            ];
        }

        $titles = [];
        $categoriesBase = [];

        foreach ($categories as $categorie) {
            $titles[] = ['content' => $categorie->getName()];

            $questions = $this->questions->findBy(['category' => $categorie->getId()], ['points' => 'ASC']);

            foreach ($questions as $key => $question) {
                $correctPlayer = null;
                if ($question->getCorrectPlayer() !== null)
                {
                    $correctPlayer = $question->getCorrectPlayer()->getName();
                }

                $currentQuestion = null;

                if ($game->getCurrentQuestion() !== null)
                {
                    $currentQuestion = $game->getCurrentQuestion();
                    $currentQuestion = [
                        'answer' => $currentQuestion->getAnswer(),
                        'id' => $currentQuestion->getId(),
                        'solution' => $currentQuestion->getSolution(),
                        'points' => $currentQuestion->getPoints(),
                    ];
                }

                $categoriesBase[$key][] = [
                    'content' => $question->getAnswer(),
                    'solution' => $question->getSolution(),
                    'qId' => $question->getId(),
                    'playable' => true,
                    'correctPlayer' => $correctPlayer,
                    'points' => $question->getPoints()
                ];
            }
        }

        $waitingUsers = [];

        $players = $this->gamePlayers->findBy(['game' => $game->getId()]);

        foreach ($players as $gPlayer) {
            dump($gPlayer->getPlayer()->getName());
            $waitingUsers[] = [
                'name' => $gPlayer->getPlayer()->getName(),
                'points' => $gPlayer->getPlayer()->getPoints(),
                'id' => $gPlayer->getId()
            ];
        }

        $players = $this->players->findBy(['game' => $game->getId()], ['points' => 'DESC']);
        $aPlayers = [];

        foreach ($players as $gPlayer) {
            $aPlayers[] = [
                'name' => $gPlayer->getName(),
                'points' => $gPlayer->getPoints(),
                'id' => $gPlayer->getId()
            ];
        }

        dump($aPlayers);

        array_unshift($categoriesBase, $titles);

        return $this->json([
            'board' => $categoriesBase,
            'currentQuestion' => $currentQuestion,
            'player' => $player,
            'playerQueue' => $waitingUsers,
            'players' => $aPlayers
        ]);
    }

    /**
     * @Route("/api/gamemaster/getPlayer/")
     */
    public function getPlayer(Request $request)
    {
        $player = $request->request->get('player');
        $player = $this->gamePlayers->find($player);

        return $this->json([
            'name' => $player->getPlayer()->getName(),
            'id' => $player->getId(),
            'points' => $player->getPlayer()->getPoints()
        ]);
    }

    /**
     * @Route("/api/gamemaster/voteOnPlayer/")
     */

    public function apiVoteOnPlayer(Request $request)
    {
        $player = $request->request->get('player');
        $correct = (bool) $request->request->get('correct');
        $player = $this->gamePlayers->find($player);
        $game = $player->getGame();
        $currentQuestion = $game->getCurrentQuestion();

        if ($correct)
        {
            $player->getPlayer()->setPoints($player->getPlayer()->getPoints() + $currentQuestion->getPoints());
            foreach ($game->getGamePlayers() as $gPlayer)
            {
                dump($gPlayer);
                $this->em->remove($gPlayer);
            }
            $game->setCurrentQuestion(null);
            $currentQuestion->setCorrectPlayer($player->getPlayer());
        }
        else
        {
            $player->getPlayer()->setPoints($player->getPlayer()->getPoints() - $currentQuestion->getPoints());
            dump($player);
            $this->em->remove($player);
        }

        $this->em->persist($game);
        $this->em->persist($currentQuestion);
        $this->em->flush();
        return $this->json([]);
    }

    /**
     * @Route("/api/gamemaster/resetGame/{game}")
     */
    public function resetGame(Game $game)
    {
        $categories = $game->getCategories();
        $gPlayers = $game->getGamePlayers();
        $players = $game->getPlayers();

        foreach ($categories as $categorie)
        {
            foreach ($categorie->getQuestions() as $question)
            {
                $question->setCorrectPlayer(null);
                $this->em->persist($question);
            }
        }

        foreach ($gPlayers as $gPlayer)
        {
            $this->em->remove($gPlayer);
        }

        foreach ($players as $player)
        {
            $this->em->remove($player);
        }

        $game->setCurrentQuestion(null);
        $this->em->persist($game);
        $this->em->flush();
        return $this->json([]);
    }

    /**
     * Check if the given player for a given game exists or create it
     *
     * @param Game $game
     * @param String $playername
     * @return Player
     */
    private function _getPlayerOrCreate(Game $game, ?String $playername)
    {
        if (empty($playername))
        {
            return null;
        }

        $player = $this->players->findOneBy(['game' => $game->getId(), 'name' => $playername]);
        if ($player === null)
        {
            $player = new Player();
            $player->setGame($game);
            $player->setName($playername);
            $this->em->persist($player);
            $this->em->flush();
        }
        return $player;
    }
}
