<?php $title="Le blog de l'AVBN - Billet"?>

<?php ob_start(); ?>
        <h1>Le super blog de l'AVBN !</h1>
        <p><a href="index.php">Retour � la liste des billets</a></p>

        <div class="news">
            <h3>
                <?= htmlspecialchars($post['title']) ?>
                <em>le <?= $post['french_creation_date'] ?></em>      
            </h3>
 
            <p>
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </p>
        </div>
 
        <h2>Commentaires</h2>

        <form action="index.php?action=addComment&id=<?= $post['identifier'] ?>" method="post">
            <div>
  	        <label for="author">Auteur</label><br />
  	        <input type="text" id="author" name="author" />
            </div>
            <div>
  	            <label for="comment">Commentaire</label><br />
  	            <textarea id="comment" name="comment"></textarea>
            </div>
            <div>
  	            <input type="submit" />
            </div>
        </form>
        <?php
  if ($comments==null)
  {
      echo "aucun commentaire";
  }
  foreach ($comments as $comment)
      {
		  ?>
		  <div class="news">
			 <h3>
				<?= htmlspecialchars($comment['author_email']) ?>
				<em>le <?= $comment['french_creation_date'] ?></em>
			 </h3>
			 <p>
				 <?php
				 // On affiche le contenu du billet
				 echo    nl2br ( htmlspecialchars( $comment['comment']));
				 ?>
				 <br />
				
			 </p>
		  </div>
		  <?php
      }
      ?>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>