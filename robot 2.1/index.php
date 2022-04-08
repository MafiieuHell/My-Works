<?php

ini_set('display_errors', 'on');
date_default_timezone_set('Europe/Paris');

// Import des fichiers de fonctions
require 'generic.php';
require 'robot_function.php';

// Code principal

// Si le formulaire n'est pas vide on récupère les informations
if (! empty($_POST)) {
    
    if (empty($_POST['robotname'])) {
        // Si le champ contenant le robot est vide
        // Génération du nom aléatoire
        $robotName = getRandomName();
    } else {
        $robotName = $_POST['robotname'];
    }
    
    // Génération d'un nombre aléatoire compris entre 1 et 10
    $randomNumber = mt_rand(1, 10);
    
    // Stockage de la moralité du robot dans une variable
    $morality = $_POST['morality'];
    
    // Récupération de la date et heure
    $date = date('d/m/Y');
    $time = date('H:i:s');
}

// Import du html
require 'homepage.phtml';