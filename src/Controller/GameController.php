<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Game;
use App\Service\GameService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class GameController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route('/start', name: 'app_start')]
    public function start(GameService $gameService, EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $game->setHandPlayer($gameService->randomHand());
        $game->setHandEnemy1($gameService->randomHand());
        $game->setHandEnemy2($gameService->randomHand());
        $game->setHandEnemy3($gameService->randomHand());

        $start = $gameService->randomCard();
        $game->setPile([$start]);

        $game->setDirection(true);

        $entityManager->persist($game);
        $entityManager->flush();

        return $this->redirectToRoute('app_play', ['id' => $game->getId()]);
    }

    #[Route('/play/{id}', name: 'app_play')]
    public function play(Game $game, GameService $gameService): Response
    {
        $pile = $game->getPile();
        $pileCard = end($pile);

        $playableCards = [];
        if ($game->getCurrentTurn() === 0) {
            $playableCards = $gameService->playable($game->getHandPlayer(), $pileCard);
        }

        return $this->render('game/index.html.twig', [
            'game' => $game,
            'pileCard' => $pileCard,
            'playableCards' => $playableCards,
        ]);
    }

    #[Route('/player/{id}', name: 'app_player')]
    public function player(Game $game, Request $request, GameService $gameService, EntityManagerInterface $entityManager): Response
    {
        $cardId = $request->query->getInt('card');
        $hand = $game->getHandPlayer();
        $card = $hand[$cardId];

        $gameService->playCard($game, $card, 0);

        $entityManager->flush();

        if ($game->getWinner()) {
            return $this->redirectToRoute('app_winner', ['id' => $game->getId()]);
        }
        return $this->redirectToRoute('app_play', ['id' => $game->getId()]);

    }

    #[Route('/ennemy/{id}', name: 'app_ennemy')]
    public function ennemy(Game $game, Request $request, GameService $gameService, EntityManagerInterface $entityManager): Response
    {
        $enemyId = $request->query->getInt('enemy');

        $gameService->enemyTurn($game, $enemyId);

        $entityManager->flush();

        if ($game->getWinner()) {
            return $this->redirectToRoute('app_winner', ['id' => $game->getId()]);
        }
        return $this->redirectToRoute('app_play', ['id' => $game->getId()]);
    }

    #[Route('/winner/{id}', name: 'app_winner')]
    public function winner(Game $game): Response
    {
        return $this->render('game/winner.html.twig', [
            'game' => $game,
        ]);
    }
    
}
