<?php 
//index.php
session_start();


header('Cache-Control: no-cache, must-revalidate, max-age=0');

require_once("bootstrap.php");


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$templateVariables = [
    'csrf_token' => $_SESSION['csrf_token']
];

echo $twig->render('login.twig', $templateVariables);

