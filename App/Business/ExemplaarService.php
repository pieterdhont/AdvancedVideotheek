<?php
// App/Services/ExemplaarService.php

declare(strict_types=1);

namespace App\Business;

use App\Data\ExemplaarDAO;

class ExemplaarService
{
    private ExemplaarDAO $exemplaarDAO;

    public function __construct(ExemplaarDAO $exemplaarDAO)
    {
        $this->exemplaarDAO = $exemplaarDAO;
    }

    public function createExemplaar(int $filmId, int $nr)
    {
        return $this->exemplaarDAO->create($filmId, $nr);
    }

    public function deleteExemplaar(int $id): bool
    {
        return $this->exemplaarDAO->delete($id);
    }

    public function getExemplaarByNr(int $nr): array
    {
        return $this->exemplaarDAO->getByNr($nr);
    }

    public function getAllExemplarenByFilmId(int $filmId): array
    {
        return $this->exemplaarDAO->getAllByFilmId($filmId);
    }

    public function huurExemplaar(int $nr): bool
    {
        return $this->exemplaarDAO->huurExemplaar($nr);
    }

    public function terugbrengExemplaar(int $nr): bool
    {
        return $this->exemplaarDAO->terugbrengExemplaar($nr);
    }

    public function getFilmsByNumber(int $nr): array
    {
        $gezochteExemplaren = $this->getExemplaarByNr($nr);
        $films = [];
        if (!empty($gezochteExemplaren)) {
            $films[] = $gezochteExemplaren[0]->getFilm();
        }
        return $films;
    }

    public function getAvailableExemplaren(array $films): array
    {
        $availableForRent = [];
        $availableForReturn = [];
        foreach ($films as $film) {
            $exemplarsForFilm = $this->getAllExemplarenByFilmId($film->getId());
            foreach ($exemplarsForFilm as $exemplar) {
                if ($exemplar->isAanwezig()) {
                    $availableForRent[$film->getId()][] = $exemplar->getNr();
                } else {
                    $availableForReturn[$film->getId()][] = $exemplar->getNr();
                }
            }
        }
        return [$availableForRent, $availableForReturn];
    }
}
