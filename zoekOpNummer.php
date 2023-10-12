<?php
// zoekOpNummer.php

session_start();

require_once("bootstrap.php");

use App\Data\{FilmDAO, ExemplaarDAO, GebruikerDAO};
use App\Business\{FilmService, ExemplaarService, GebruikerService};


$gebruikerService = new GebruikerService(new GebruikerDAO());
$gebruikerService->authenticateUser($_SESSION);

$filmDAO = new FilmDAO();
$exemplaarDAO = new ExemplaarDAO($filmDAO);
$exemplaarService = new ExemplaarService($exemplaarDAO);

$nummer = $_POST['nummer'] ?? null;

$isSearching = !is_null($nummer);

if ($isSearching) {
    $films = $exemplaarService->getFilmsByNumber((int)$nummer);
    if (!empty($films)) {
        $exemplaren = $exemplaarService->getAllExemplarenByFilmId($films[0]->getId());
    } else {
        $exemplaren = [];
    }
}

$message = $_SESSION['message'] ?? null;
if ($message) {
    unset($_SESSION['message']);
}

echo $twig->render('zoekResultaat.twig', [
    'films' => $films,
    'message' => $message,
    'exemplaren' => $exemplaren
]);