<?php
// index.php - version simple qui affiche un message d'erreur si fourni en GET
// Récupérer d'éventuels paramètres (utilisateurs de test seulement: email/note)
$error = isset($_GET['error']) ? trim($_GET['error']) : '';
$prefill_email = isset($_GET['email']) ? trim($_GET['email']) : '';
$prefill_note  = isset($_GET['note']) ? trim($_GET['note']) : '';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Page de test</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>PAGE DE TES — NE PAS SAISIR D'IDENTIFIANTS RÉELS</h1>

  <?php if ($error !== ''): ?>
    <div style="background:#ffdede;color:#900;padding:8px;border:1px solid #900;margin-bottom:12px;">
      <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <form action="submit.php" method="post" autocomplete="off">
    <label for="email">Email (test)</label><br>
    <input id="email" name="email" type="email"
           placeholder="test1@example.com"
           value="<?= htmlspecialchars($prefill_email, ENT_QUOTES, 'UTF-8') ?>" required><br>

    <label for="password">Mot de passe</label><br>
    <input id="password" name="password" type="password" placeholder="password123" required><br>

    <label for="note">Note (optionnel)</label><br>
    <input id="note" name="note" type="text"
           placeholder="raison du test"
           value="<?= htmlspecialchars($prefill_note, ENT_QUOTES, 'UTF-8') ?>"><br>

    <button type="submit">Se connecter (test)</button>
  </form>

  <p><a href="submissions.php">Voir les soumissions</a></p>
</body>
</html>
