<?php
// verwijderFilm.php
session_start();

require_once("bootstrap.php");

use App\Data\{FilmDAO, ExemplaarDAO, GebruikerDAO};
use App\Business\{FilmService, ExemplaarService, GebruikerService};

$gebruikerService = new GebruikerService(new GebruikerDAO());
$gebruikerService->authenticateUser($_SESSION);

$filmDAO = new FilmDAO();
$exemplaarDAO = new ExemplaarDAO($filmDAO);
$filmService = new FilmService($filmDAO);
$exemplaarService = new ExemplaarService($exemplaarDAO);

$deleteFilmId = $_POST['deleteFilmId'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($deleteFilmId) || !is_numeric($deleteFilmId) || $deleteFilmId <= 0) {
        $_SESSION['message'] = "Gelieve een geldige film ID te selecteren om te verwijderen.";
    } else {
        try {
            $filmService->deleteFilm((int)$deleteFilmId);
            $_SESSION['message'] = "Titel en bijbehorende exemplaren succesvol verwijderd!";
        } catch (\Exception $e) {
            $_SESSION['message'] = "Er is een fout opgetreden: " . $e->getMessage();
        }
    }

    header("Location: toonAlleFilms.php");
    exit();
}
