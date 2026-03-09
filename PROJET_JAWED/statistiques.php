<?php
session_start();
include '_conf.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['Sid'])) {
    header("Location: index.php");
    exit;
}

// Connexion PDO 
$pdo = new PDO('mysql:host=localhost;dbname=projet_jawed;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupération de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$_SESSION['Sid']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "❌ Erreur : utilisateur introuvable.";
    exit;
}

// Vérifier que l'utilisateur est de type 3 (administration) ou 4 (secrétaire)
if ($user['type'] != 3 && $user['type'] != 4) {
    echo "⛔ Accès refusé. Cette page est réservée à l'administration et au secrétariat.";
    exit;
}

// REQUÊTE 1 : Nombre total de comptes rendus
$stmtTotal = $pdo->query("SELECT COUNT(*) AS total FROM compte_rendu");
$totalCR = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

// REQUÊTE 2 : Nombre de CR par élève (type = 1)
// MODIFICATION : Utilisation de COUNT(*) au lieu de COUNT(cr.id)
$stmtEleves = $pdo->query("
    SELECT u.nom, u.prenom, COUNT(*) AS nb_cr 
    FROM utilisateur u 
    LEFT JOIN compte_rendu cr ON u.email = cr.email_utilisateur 
    WHERE u.type = 1 
    GROUP BY u.email, u.nom, u.prenom
    ORDER BY u.nom, u.prenom
");
$elevesCR = $stmtEleves->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Comptes Rendus</title>
    <link rel="stylesheet" href="statistiques.css">
    <style>
        .stats-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">📊 Statistiques des Comptes Rendus</div>
        <ul class="nav-links">
            <!-- Retour différent selon le type d'utilisateur -->
            <?php if ($user['type'] == 3): ?>
                <li><a href="page_admin.php">Retour Administration</a></li>
            <?php else: ?>
                <li><a href="page_secretaire.php">Retour Secrétariat</a></li>
            <?php endif; ?>
            <li><a href="index.php" class="logout">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main class="content">
    <div class="welcome-box">
        <h1>📈 Statistiques des Comptes Rendus</h1>
        <p>Consultation des données agrégées des comptes rendus des élèves.</p>
        <p><em>Accès : <?php echo ($user['type'] == 3) ? 'Administrateur' : 'Secrétaire'; ?></em></p>
    </div>

    <div class="stats-box">
        <h2>Résumé global</h2>
        <p class="total">Nombre total de comptes rendus : <?= $totalCR ?></p>
    </div>

    <div class="stats-box">
        <h2>Nombre de comptes rendus par élève</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Nombre de CR</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($elevesCR) > 0): ?>
                    <?php foreach ($elevesCR as $eleve): ?>
                        <tr>
                            <td><?= htmlspecialchars($eleve['nom']) ?></td>
                            <td><?= htmlspecialchars($eleve['prenom']) ?></td>
                            <td><?= $eleve['nb_cr'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center;">Aucun compte rendu trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>