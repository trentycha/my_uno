<?php

namespace App\Service;

use App\Entity\Game;

class UnoGameService
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

}

?>