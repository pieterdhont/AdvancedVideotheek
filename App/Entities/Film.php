<?php
//App/Entities/Film.php
declare(strict_types=1);

namespace App\Entities;

class Film
{
    private int $id;
    private string $titel;

    public function __construct(int $id, string $titel)
    {
        $this->id = $id;
        $this->titel = $titel;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitel(): string
    {
        return $this->titel;
    }

    
}
