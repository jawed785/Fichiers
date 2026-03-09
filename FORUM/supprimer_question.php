<?php
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$question_id = $_GET['id'] ?? null;

if ($question_id) {
    // Vérifier que la question appartient à l'utilisateur
    $stmt = $pdo->prepare("SELECT q_id FROM question WHERE q_id = ? AND user_id = ?");
    $stmt->execute([$question_id, getUserId()]);
    
    if ($stmt->fetch()) {
        // Supprimer d'abord les réponses associées
        $stmt = $pdo->prepare("DELETE FROM reponse WHERE r_fk_question_id = ?");
        $stmt->execute([$question_id]);
        
        // Supprimer la question
        $stmt = $pdo->prepare("DELETE FROM question WHERE q_id = ? AND user_id = ?");
        $stmt->execute([$question_id, getUserId()]);
    }
}

header('Location: index.php');
exit;
?>