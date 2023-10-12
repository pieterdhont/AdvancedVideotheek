<?php
// titelToevoegen.php
session_start();
require_once("bootstrap.php");

use App\Business\{FilmService, GebruikerService};
use App\Data\{FilmDAO, GebruikerDAO};
use App\Exceptions\TitelBestaatException;



$gebruikerService = new GebruikerService(new GebruikerDAO());
$gebruikerService->authenticateUser($_SESSION);

$titel = trim($_POST['titel'] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($titel) && strlen($titel) <= 100) {
    if ($titel) {
        $filmDAO = new FilmDAO();
        $filmService = new FilmService($filmDAO);
        
        try {
            $filmService->createFilm($titel);
            $_SESSION['message'] = "Titel succesvol toegevoegd!";
        } catch (TitelBestaatException $e) {
            $_SESSION['message'] = $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Gelieve een titel in te voeren.";
    }
    header("Location: toonAlleFilms.php");
    exit();
}
