<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?array $handPlayer = null;

    #[ORM\Column(nullable: true)]
    private ?array $handEnemy1 = null;

    #[ORM\Column(nullable: true)]
    private ?array $handEnemy2 = null;

    #[ORM\Column(nullable: true)]
    private ?array $handEnemy3 = null;

    #[ORM\Column(nullable: true)]
    private ?array $pile = null;

    #[ORM\Column]
    private ?int $currentTurn = 0;

    #[ORM\Column]
    private ?bool $direction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $winner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHandPlayer(): ?array
    {
        return $this->handPlayer;
    }

    public function setHandPlayer(?array $handPlayer): static
    {
        $this->handPlayer = $handPlayer;

        return $this;
    }

    public function getHandEnemy1(): ?array
    {
        return $this->handEnemy1;
    }

    public function setHandEnemy1(?array $handEnemy1): static
    {
        $this->handEnemy1 = $handEnemy1;

        return $this;
    }

    public function getHandEnemy2(): ?array
    {
        return $this->handEnemy2;
    }

    public function setHandEnemy2(?array $handEnemy2): static
    {
        $this->handEnemy2 = $handEnemy2;

        return $this;
    }

    public function getHandEnemy3(): ?array
    {
        return $this->handEnemy3;
    }

    public function setHandEnemy3(?array $handEnemy3): static
    {
        $this->handEnemy3 = $handEnemy3;

        return $this;
    }

    public function getPile(): ?array
    {
        return $this->pile;
    }

    public function setPile(?array $pile): static
    {
        $this->pile = $pile;

        return $this;
    }

    public function getCurrentTurn(): ?int
    {
        return $this->currentTurn;
    }

    public function setCurrentTurn(int $currentTurn): static
    {
        $this->currentTurn = $currentTurn;

        return $this;
    }

    public function isDirection(): ?bool
    {
        return $this->direction;
    }

    public function setDirection(bool $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(?string $winner): static
    {
        $this->winner = $winner;

        return $this;
    }
}
