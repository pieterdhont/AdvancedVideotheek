<?php
// bootstrap.php
require_once("vendor/autoload.php");
spl_autoload_register();

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

$loader = new FilesystemLoader('App/Presentation');
$twig = new Environment($loader);

function reformatTitleFunction($title) {
    if (stripos($title, "The ") === 0) {
        return substr($title, 4) . ", The";
    }
    return $title;
}

// Registreer de functie bij Twig
$reformatTitleFunction = new TwigFunction('reformatTitle', 'reformatTitleFunction');
$twig->addFunction($reformatTitleFunction);
