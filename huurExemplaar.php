<?php
// huurExemplaar.php
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

$huurExemplaarNr = $_POST['huurExemplaarNr'] ?? null;

if ($huurExemplaarNr) {
    if ($huurExemplaarNr === "-1") {
        $_SESSION['message'] = "Selecteer een geldig exemplaar om te huren.";
        header("Location: toonAlleFilms.php");
        exit();
    }

    try {
        $result = $exemplaarService->huurExemplaar((int)$huurExemplaarNr);
        if ($result) {
            $_SESSION['message'] = "Exemplaar met nummer {$huurExemplaarNr} is nu verhuurd!";
        } else {
            $_SESSION['message'] = "Kan het exemplaar met nummer {$huurExemplaarNr} niet vinden of het is al verhuurd.";
        }
    } catch (\Exception $e) {
        $_SESSION['message'] = "Er is een fout opgetreden: " . $e->getMessage();
    }

    header("Location: toonAlleFilms.php");
    exit();
}


