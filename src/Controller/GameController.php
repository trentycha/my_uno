<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/start', name: 'app_start')]
    public function start(GameService $gameService, EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $game->setPlayerHand($gameService->randomHand());
        $game->setEnemy1Hand($gameService->randomHand());
        $game->setEnemy2Hand($gameService->randomHand());
        $game->setEnemy3Hand($gameService->randomHand());

        $start = $gameService->randomCard();
        $game->setPile($start);

        $entityManager->persist($game);
        $entityManager->flush();
    }
}
