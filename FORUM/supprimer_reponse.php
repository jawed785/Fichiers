<?php
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$reponse_id = $_GET['id'] ?? null;

if ($reponse_id) {
    // Récupérer l'ID de la question avant suppression
    $stmt = $pdo->prepare("SELECT r_fk_question_id FROM reponse WHERE r_id = ? AND user_id = ?");
    $stmt->execute([$reponse_id, getUserId()]);
    $reponse = $stmt->fetch();
    
    if ($reponse) {
        // Supprimer la réponse
        $stmt = $pdo->prepare("DELETE FROM reponse WHERE r_id = ? AND user_id = ?");
        $stmt->execute([$reponse_id, getUserId()]);
        
        header('Location: question.php?id=' . $reponse['r_fk_question_id']);
        exit;
    }
}

header('Location: index.php');
exit;
?>