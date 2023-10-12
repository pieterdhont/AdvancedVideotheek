<?php
// terugbrengExemplaar.php
session_start();

require_once("bootstrap.php");

use App\Data\{FilmDAO, ExemplaarDAO, GebruikerDAO};
use App\Business\{FilmService, ExemplaarService, GebruikerService};
;

$filmDAO = new FilmDAO();
$exemplaarDAO = new ExemplaarDAO($filmDAO);
$filmService = new FilmService($filmDAO);
$exemplaarService = new ExemplaarService($exemplaarDAO);



$gebruikerService = new GebruikerService(new GebruikerDAO());
$gebruikerService->authenticateUser($_SESSION);

$terugbrengExemplaarNr = $_POST['terugbrengExemplaarNr'] ?? null;

if ($terugbrengExemplaarNr) {
    if ($terugbrengExemplaarNr === "-1") {
        $_SESSION['message'] = "Selecteer een geldig exemplaar om terug te brengen.";
        header("Location: toonAlleFilms.php");
        exit();
    }

    try {
        $result = $exemplaarService->terugbrengExemplaar((int)$terugbrengExemplaarNr);
        if ($result) {
            $_SESSION['message'] = "Exemplaar met nummer {$terugbrengExemplaarNr} is nu teruggebracht!";
        } else {
            $_SESSION['message'] = "Kan het exemplaar met nummer {$terugbrengExemplaarNr} niet vinden of het is al aanwezig.";
        }
    } catch (\Exception $e) {
        $_SESSION['message'] = "Er is een fout opgetreden: " . $e->getMessage();
    }

    header("Location: toonAlleFilms.php");
    exit();
}
?>

