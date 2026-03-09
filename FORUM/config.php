<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'u482683110_26OUEND');
define('DB_PASS', 'N6oue26?');
define('DB_NAME', 'u482683110_26OUEND_BDD');

// Connexion à la base de données
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Démarrer la session
session_start();

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour obtenir l'ID de l'utilisateur connecté
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Fonction pour obtenir le login de l'utilisateur connecté
function getUserLogin() {
    return $_SESSION['user_login'] ?? null;
}
?>