<?php
// App/Services/FilmService.php

declare(strict_types=1);

namespace App\Business;

use App\Data\FilmDAO;


class FilmService {
    private FilmDAO $filmDAO;

    public function __construct(FilmDAO $filmDAO) {
        $this->filmDAO = $filmDAO;
    }

    public function getAllFilms(): array {
        return $this->filmDAO->getAll();
    }

    public function createFilm(string $titel){
        return $this->filmDAO->create($titel);
    }

    public function deleteFilm(int $id): bool {
        return $this->filmDAO->delete($id);
    }

    
}
