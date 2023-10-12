<?php
// verwijderExemplaar.php
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

$deleteExemplaarNr = $_POST['deleteExemplaarNr'] ?? null;

if ($deleteExemplaarNr) {
    if ($deleteExemplaarNr === "-1") {  
        $_SESSION['message'] = "Selecteer een geldig exemplaar om te verwijderen.";
        header("Location: toonAlleFilms.php");
        exit();
    }

    
    $exemplaar = $exemplaarService->getExemplaarByNr((int)$deleteExemplaarNr);
    
    if (!$exemplaar || count($exemplaar) == 0) {
        $_SESSION['message'] = "Kan het exemplaar met nummer {$deleteExemplaarNr} niet vinden.";
    } else {
        try {
            $exemplaarService->deleteExemplaar($exemplaar[0]->getId());
            $_SESSION['message'] = "Exemplaar met nummer {$deleteExemplaarNr} succesvol verwijderd!";
        } catch (\Exception $e) {
            $_SESSION['message'] = "Er is een fout opgetreden: " . $e->getMessage();
        }
    }
    
    
    header("Location: toonAlleFilms.php");
    exit();
}
