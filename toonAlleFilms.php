<?php
// toonAlleFilms.php
session_start();

require_once("bootstrap.php");

use App\Data\{FilmDAO, ExemplaarDAO, GebruikerDAO};
use App\Business\{FilmService, ExemplaarService, GebruikerService};


$filmDAO = new FilmDAO();
$exemplaarDAO = new ExemplaarDAO($filmDAO);
$filmService = new FilmService($filmDAO);
$exemplaarService = new ExemplaarService($exemplaarDAO);
$gebruikerService = new GebruikerService(new GebruikerDAO());
$gebruikerService->authenticateUser($_SESSION);

$films = $filmService->getAllFilms();
$exemplaren = []; 

list($availableForRent, $availableForReturn) = $exemplaarService->getAvailableExemplaren($films);

$message = $_SESSION['message'] ?? null;
if ($message) {
    unset($_SESSION['message']);
}

echo $twig->render('filmList.twig', [
    'films' => $films,
    'message' => $message,
    'availableForRent' => $availableForRent,
    'availableForReturn' => $availableForReturn,
    'exemplaren' => $exemplaren
]);
?>
