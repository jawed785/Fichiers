<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <title><?= $title ?></title>
      <link href="style.css" rel="stylesheet" /> 
   </head>

   <body>

   <h1>Connexion</h1>

   <form action="index.php?action=loginCheck" method="post">
      <label>Email :</label>
      <input type="login" name="email">

      <label>Mot de passe :</label>
      <input type="password" name="pwd">

      <button type="submit">Se connecter</button>
   </form>

   <?= $content ?>

   </body>
</html>