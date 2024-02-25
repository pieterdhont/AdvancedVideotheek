<?php 
//App/Entities/Gebruiker.php

declare(strict_types=1);

namespace App\Entities;

class Gebruiker {

   private int $id;
    private string $naam;
    private string $wachtwoord;

    public function __construct(int $id, string $naam, string $wachtwoord)
    {
        $this->id = $id;
        $this->naam = $naam;
        $this->wachtwoord = $wachtwoord;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNaam(): string
    {
        return $this->naam;
    }

    public function getWachtwoord(): string
    {
        return $this->wachtwoord;
    }
}
