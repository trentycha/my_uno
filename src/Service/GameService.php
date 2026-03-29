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

        if (rand(1, 5) === 1) {
            return [
                "color" => $this->colors[array_rand($this->colors)],
                "number" => array_rand(["R" => 0, "X" => 1])
            ];
        }

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

    public function nextTurn(int $current, bool $direction): int
    {
        if ($direction) {
            return ($current + 1) % 4;
        } else {
            return ($current + 3) % 4;
        }
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
        
        $playable = $this->playable($game->getHandPlayer(), $pileCard);

        $game->setPile([...$pile, $card]);

        if ($card["number"] === "R") {
            $game->setDirection(!$game->isDirection());
        }

        $next = $this->nextTurn($playerId, $game->isDirection());

        if ($card["number"] === "X") {
            $next = $this->nextTurn($next, $game->isDirection());
        }

        $hand = $game->getHandPlayer();
        $hand = array_values(array_filter($hand, fn($c) => $c !== $card));
        $game->setHandPlayer($hand);

        if (count($game->getHandPlayer()) === 0) {
            $game->setWinner('player');
            return;
        }

        $game->setCurrentTurn($next);

    }

    public function drawCard(Game $game): void
    {
        $hand = $game->getHandPlayer();
        $hand[] = $this->randomCard();
        $game->setHandPlayer($hand);

        $next = $this->nextTurn(0, $game->isDirection());
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

        if (count($playable) === 0) {
            $hand[] = $this->randomCard();
        } else {
            $playedCard = $playable[array_rand($playable)];
            $game->setPile([...$pile, $playedCard]);
            $hand = array_values(array_filter($hand, fn($c) => $c !== $playedCard));

            if ($playedCard["number"] === "R") {
            $game->setDirection(!$game->isDirection());
            }
        }

        if ($enemyId === 1) {
            $game->setHandEnemy1($hand);
        } elseif ($enemyId === 2) {
            $game->setHandEnemy2($hand);
        } elseif ($enemyId === 3) {
            $game->setHandEnemy3($hand);
        }

        if (count($hand) === 0) {
            $game->setWinner('enemy' . $enemyId);
            return;
        }

        $next = $this->nextTurn($enemyId, $game->isDirection());

        if (isset($playedCard) && $playedCard["number"] === "X") {
            $next = $this->nextTurn($next, $game->isDirection());
        }

        $game->setCurrentTurn($next);
    }

}

?>