<?php
// login.php
session_start();

// Voorkom caching
header('Cache-Control: no-cache, must-revalidate, max-age=0');

require_once("bootstrap.php");

use App\Business\GebruikerService;
use App\Data\GebruikerDAO;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\IncorrectPasswordException;

$naam = trim($_POST['naam'] ?? '');
$wachtwoord = trim($_POST['wachtwoord'] ?? '');
$error = "";

// CSRF token check
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Login"])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token.');
    }

    if (!$naam || !$wachtwoord) {
        $error = "Voer zowel een naam als wachtwoord in.";
    } else {
        $gebruikerService = new GebruikerService(new GebruikerDAO());

        try {
            $gebruiker = $gebruikerService->login($naam, $wachtwoord);
            $_SESSION['gebruiker_id'] = $gebruiker->getId();
            header("Location: toonAlleFilms.php");
            exit();
        } catch (UserNotFoundException | IncorrectPasswordException $e) {
            $error = $e->getMessage();
        }
    }
}

echo $twig->render('login.twig', ['error' => $error, 'csrf_token' => $_SESSION['csrf_token']]);
