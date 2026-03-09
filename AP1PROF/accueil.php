<?php
session_start();
include '_conf.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

// Inclure le menu approprié selon le type
switch ($_SESSION['type']) {
    case TYPE_ELEVE:
        $menu_file = '_menuEleve.php';
        break;
    case TYPE_PROF:
        $menu_file = '_menuProf.php';
        break;
    case TYPE_SECRETAIRE:
        $menu_file = '_menuSecretaire.php';
        break;
    case TYPE_ADMIN:
        $menu_file = '_menuAdmin.php';
        break;
    default:
        $menu_file = '_menuEleve.php';
}

// Récupérer le nom du type d'utilisateur
$type_nom = $types_utilisateur[$_SESSION['type']] ?? 'Utilisateur';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Suivi des Stages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include $menu_file; ?>
    
    <main>
        <section class="welcome-container">
            <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?> !</h2>
            
            <div class="welcome-message">
                <div class="user-type-badge">
                    <span class="type-label">Type :</span>
                    <span class="type-value"><?php echo htmlspecialchars($type_nom); ?></span>
                </div>
                
                <p>Vous êtes connecté en tant que <strong><?php echo htmlspecialchars($type_nom); ?></strong>.</p>
                
                <div class="quick-stats">
                    <?php
                    // Connexion à la base pour les statistiques
                    $connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
                    if ($connexion) {
                        if ($_SESSION['type'] == TYPE_ELEVE) {
                            // Nombre de CR pour l'élève
                            $requete = "SELECT COUNT(*) as nb_cr FROM cr WHERE num_utilisateur = " . $_SESSION['id'];
                            $resultat = mysqli_query($connexion, $requete);
                            if ($donnees = mysqli_fetch_assoc($resultat)) {
                                echo "<div class='stat-item'>📋 Vous avez " . $donnees['nb_cr'] . " compte(s) rendu(s)</div>";
                            }
                        } else {
                            // Statistiques pour les autres types
                            $requete = "SELECT COUNT(*) as total_cr FROM cr";
                            $resultat = mysqli_query($connexion, $requete);
                            if ($donnees = mysqli_fetch_assoc($resultat)) {
                                echo "<div class='stat-item'>📊 Total des CR : " . $donnees['total_cr'] . "</div>";
                            }
                            
                            $requete = "SELECT COUNT(DISTINCT num_utilisateur) as nb_eleves FROM cr";
                            $resultat = mysqli_query($connexion, $requete);
                            if ($donnees = mysqli_fetch_assoc($resultat)) {
                                echo "<div class='stat-item'>👥 Élèves actifs : " . $donnees['nb_eleves'] . "</div>";
                            }
                        }
                        mysqli_close($connexion);
                    }
                    ?>
                </div>
                
                <div class="quick-actions">
                    <?php if ($_SESSION['type'] == TYPE_ELEVE): ?>
                        <button class="btn-action" onclick="window.location.href='ccr.php'">✏️ Rédiger un CR</button>
                        <button class="btn-action" onclick="window.location.href='cr.php'">📋 Voir mes CR</button>
                    <?php elseif ($_SESSION['type'] == TYPE_PROF): ?>
                        <button class="btn-action" onclick="window.location.href='cr.php'">📋 Consulter les CR</button>
                        <button class="btn-action" onclick="window.location.href='statistiques.php'">📊 Voir les stats</button>
                    <?php elseif ($_SESSION['type'] == TYPE_SECRETAIRE): ?>
                        <button class="btn-action" onclick="window.location.href='cr.php'">📋 Tous les CR</button>
                        <button class="btn-action" onclick="window.location.href='statistiques.php'">📊 Statistiques</button>
                    <?php elseif ($_SESSION['type'] == TYPE_ADMIN): ?>
                        <button class="btn-action" onclick="window.location.href='statistiques.php'">📊 Statistiques</button>
                        <button class="btn-action" onclick="window.location.href='gestion_utilisateurs.php'">👥 Gérer les utilisateurs</button>
                        <button class="btn-action" onclick="window.location.href='cr.php'">📋 Tous les CR</button>
                    <?php endif; ?>
                    <button class="btn-action" onclick="window.location.href='perso.php'">👤 Mon profil</button>
                </div>
            </div>
        </section>
    </main>
</body>
</html>