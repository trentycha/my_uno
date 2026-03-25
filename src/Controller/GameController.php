<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Game;
use App\Service\GameService;
use Doctrine\ORM\EntityManagerInterface;

final class GameController extends AbstractController
{
    #[Route('/', name: 'app_start')]
    public function start(GameService $gameService, EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $game->setHandPlayer($gameService->randomHand());
        $game->setHandEnemy1($gameService->randomHand());
        $game->setHandEnemy2($gameService->randomHand());
        $game->setHandEnemy3($gameService->randomHand());

        $start = $gameService->randomCard();
        $game->setPile($start);

        $game->setDirection(true);

        $entityManager->persist($game);
        $entityManager->flush();

        return $this->render('game/index.html.twig', [
            'game' => $game,
        ]);
    }
}
