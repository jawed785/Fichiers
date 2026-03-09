<?php
// Configuration de la base de données
$serveurBDD = "localhost";
$userBDD = "root";
$mdpBDD = ""; // Mot de passe MySQL (vide par défaut avec XAMPP)
$nomBDD = "ap1_v4";

// Types d'utilisateurs
define('TYPE_ELEVE', 0);
define('TYPE_PROF', 1);
define('TYPE_SECRETAIRE', 2);
define('TYPE_ADMIN', 3);

// Noms des types pour l'affichage
$types_utilisateur = [
    TYPE_ELEVE => 'Élève',
    TYPE_PROF => 'Professeur',
    TYPE_SECRETAIRE => 'Secrétaire',
    TYPE_ADMIN => 'Administration'
];
?>