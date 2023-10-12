<?php
// exemplaarToevoegen.php
session_start();
require_once("bootstrap.php");

use App\Data\{FilmDAO, ExemplaarDAO, GebruikerDAO};
use App\Business\{FilmService, ExemplaarService, GebruikerService};
use App\Exceptions\ExemplaarBestaatException;



$filmDAO = new FilmDAO();
$filmService = new FilmService($filmDAO);
$exemplaarService = new ExemplaarService(new ExemplaarDAO($filmDAO));
$gebruikerService = new GebruikerService(new GebruikerDAO());
$gebruikerService->authenticateUser($_SESSION);
$films = $filmService->getAllFilms();

$filmId = $_POST['filmId'] ?? null;
$nummer = $_POST['nummer'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($filmId)) {
        $_SESSION['message'] = "Gelieve een titel te selecteren.";
    } elseif (empty($nummer) || $nummer <= 0 || $nummer > 2147483647) {
        $_SESSION['message'] = "Gelieve een geldig exemplaarnummer in te voeren.";
    } else {
        try {
            $exemplaarService->createExemplaar((int)$filmId, (int)$nummer);
            $_SESSION['message'] = "Exemplaar succesvol toegevoegd!";
        } catch (ExemplaarBestaatException $e) {
            $_SESSION['message'] = $e->getMessage();
        }
    }
    header("Location: toonAlleFilms.php");
    exit();
}
