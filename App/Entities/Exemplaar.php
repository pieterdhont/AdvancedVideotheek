<?php
//App/Entities/Exemplaar.php
declare(strict_types=1);

namespace App\Entities;
use App\Entities\Film;

class Exemplaar
{
    private int $id;
    private int $nr;
    private Film $film;  
    private bool $aanwezig;

    public function __construct(int $id, int $nr, Film $film, bool $aanwezig)
    {
        $this->id = $id;
        $this->nr = $nr;
        $this->film = $film;
        $this->aanwezig = $aanwezig;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNr(): int
    {
        return $this->nr;
    }


    public function getFilm(): Film
    {
        return $this->film;
    }

    public function isAanwezig(): bool
    {
        return $this->aanwezig;
    }

    
}
