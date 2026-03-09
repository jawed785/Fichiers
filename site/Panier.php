<?php
session_start();  // démarrage de la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email']) || !isset($_SESSION['mdp'])) {
    header('Location: login.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Si le panier est vide
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    $panier_vide = true;
} else {
    $panier_vide = false;
}

// Suppression d'un événement du panier
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id_event_a_supprimer = $_GET['supprimer'];

    // Trouver l'index de l'événement à supprimer dans le panier
    foreach ($_SESSION['panier'] as $index => $event) {
        if ($event['id_event'] == $id_event_a_supprimer) {
            // Supprimer l'événement
            unset($_SESSION['panier'][$index]);
            break;
        }
    }
    // Réindexer le tableau après la suppression pour éviter les index manquants
    $_SESSION['panier'] = array_values($_SESSION['panier']);
    header('Location: panier.php'); // Redirige pour éviter le double clic
    exit();
}
//pour trier dans l'ordre  :
if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0) {
    usort($_SESSION['panier'], function($a, $b) {
        return strtotime($a['date_event']) - strtotime($b['date_event']);
    });
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - Passe Sport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo-container">
                <div id="logo">
                    <a href="accueil.php"><img src="pass.png" alt="Sportlock" /></a>
                </div>
                <button class="nav-button event-btn" onclick="window.location.href ='eventpage.php';">Tous les événements</button>
            </div>

            <div class="auth-buttons">
                <?php if (isset($_SESSION['email'])): ?>
                    <button class="nav-button account-btn" onclick="window.location.href = 'account.php';">Mon Compte</button>
                    <button class="nav-button" onclick="window.location.href = 'panier.php';">Mon Panier</button>
                <?php else: ?>
                    <button class="nav-button login-btn" onclick="window.location.href ='login.php';">Connexion</button>
                    <button class="nav-button signup-btn" onclick="window.location.href ='inscription.php';">Inscription</button>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Mon Panier</h1>

        <?php if ($panier_vide): ?>
            <p>Votre panier est vide. Ajoutez des événements pour réserver.</p>
        <?php else: ?>
            <div class="panier-items">
                <?php foreach ($_SESSION['panier'] as $event): ?>
                    <div class="panier-item">
                        <img src="<?php echo htmlspecialchars($event['url_event']); ?>" alt="<?php echo htmlspecialchars($event['nom_event']); ?>" class="event-image">
                        <div class="event-details">
                            <h3 class="event-title"><?php echo htmlspecialchars($event['nom_event']); ?></h3>
                            <div class="event-date"><?php echo htmlspecialchars($event['date_event']); ?></div>
                            <div class="event-location"><?php echo htmlspecialchars($event['lieu_event']); ?></div>
                            <div class="event-price">À partir de <?php echo htmlspecialchars($event['prix_event']); ?>€</div>
                        </div>
                        <!-- Bouton de suppression -->
                        <a href="panier.php?supprimer=<?php echo $event['id_event']; ?>" class="delete-btn">Supprimer</a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Formulaire pour confirmer la réservation -->
            <div class="panier-confirmation">
                <form action="reserver.php" method="POST">
                    <button type="submit" name="confirmer_reservation">Confirmer ma réservation</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script src="accueil.js"></script>
</body>
</html>