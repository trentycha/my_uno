<?php

namespace App\Service;

use App\Entity\Game;

class GameService
{

    private array $colors = ["red", "yellow", "green", "blue"];
    private array $numbers = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

    public function randomCard(): array
    {

        $card = [];
        return $card = [
            "color" => $this->colors[array_rand($this->colors)],
            "number" => $this->numbers[array_rand($this->numbers)]
        ];

    }

    public function randomHand() : array {

        $hand = [];
        for ($i = 0; $i < 7; $i++) {
            $hand[] = $this->randomCard();
        }
        
        return $hand;
    }

    public function nextTurn(int $current): int
    {
        return ($current + 1) % 4;
    }

    public function playable(array $hand, array $pileCard): array {
        
        $playable = [];

        foreach($hand as $card) {

            if($card["color"] === $pileCard["color"] || $card["number"] === $pileCard["number"]) {
                $playable[] = $card;
            }
        }

        return $playable;
    }

    public function playCard(Game $game, array $card, int $playerId): void {
        
        $pile = $game->getPile();
        $pileCard = end($pile);
        $game->setPile([...$pile, $card]);

        $next = $this->nextTurn($playerId);
        $game->setCurrentTurn($next);

    }

    public function enemyTurn(Game $game, int $enemyId): void
    {
        $pile = $game->getPile();
        $pileCard = end($pile);

        if ($enemyId === 1) {
        $hand = $game->getHandEnemy1();
        } elseif ($enemyId === 2) {
        $hand = $game->getHandEnemy2();
        } elseif ($enemyId === 3) {
        $hand = $game->getHandEnemy3();
        }

        $playable = $this->playable($hand, $pileCard);

        if (count($playable) > 0) {
        $playedCard = $playable[array_rand($playable)];
        }

        $game->setPile([...$pile, $playedCard]);

        $next = $this->nextTurn($enemyId);
        $game->setCurrentTurn($next);
    }

}

?>