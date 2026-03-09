<?php
function dbConnect()
{
      // Connexion à la base de données
      try
      {
            $database = new PDO('mysql:host=localhost;dbname=2025_ap4;charset=utf8', 'root', '');
            return $database;
      }
      catch(Exception $e)
      {
            die( 'Erreur : '.$e->getMessage()   );
      }

}


//méthode pour récupérer tous les posts (title,french_creation_date,content)
function getPosts()
{
      $database=dbConnect();

      // On récupère les 5 derniers billets
      $statement = $database->query('SELECT id, title, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM posts ORDER BY creation_date DESC LIMIT 0, 5');


      //on stock les post dans une variables type liste
	  $posts=[];
      while ($row = $statement->fetch())
      {
		 $post=[
             'id' => $row['id'],
             'title' => $row['title'],
             'french_creation_date' => $row['date_creation_fr'],
             'content' => $row['content']
         ];
      
         $posts[]= $post;
      } 
      $statement->closeCursor();

      return $posts;
}


function getPost($id)
{
      $database=dbConnect();
      $statement = $database->prepare('SELECT id, title, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM posts 
                                        WHERE id = ? ');
      $statement->execute([$id]);
      $row = $statement->fetch();

		 $post=[
                  'identifier' => $row['id'],
                  'title' => $row['title'],
                  'french_creation_date' => $row['date_creation_fr'],
                  'content' => $row['content']
            ];

      $statement->closeCursor();

      return $post;
}


//méthode pour récupérer tous les comments
function getComments($id)
{
      $database=dbConnect();


      $statement = $database->prepare("SELECT comments.id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%imin%ss') AS french_creation_date, user.email AS author_email
FROM comments JOIN user ON comments.author = user.id WHERE post_id = ? ORDER BY comment_date DESC");
      $statement->execute([$id]);

      //on stock les commentaire dans une variables type liste
	  $comments=[];
      while ($row = $statement->fetch())
      {
		  $comment = [
            'author' => $row['author'],
            'author_email' => $row['author_email'],
            'french_creation_date' => $row['french_creation_date'],
            'comment' => $row['comment'],
    	    ];

      
         $comments[]= $comment;
      } 
      $statement->closeCursor();

      return $comments;
}


function createComment(string $post, string $author, string $comment)
{
	session_start();

      // si un user est connecté on remplace l'auteur par son ID
      if (isset($_SESSION['user_id'])) {
            $author = $_SESSION['user_id'];
            }

      $database = dbConnect();
	$statement = $database->prepare(
    	'INSERT INTO comments(post_id, author, comment, comment_date) VALUES(?, ?, ?, NOW())');

	$affectedLines = $statement->execute([$post, $author, $comment]);

	return ($affectedLines > 0);
}


public function test_connexion($login, $motdepasse) {
    
    $sql = "SELECT * FROM utilisateurs WHERE email = :login AND password = :mdp";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        
      'author_email' => $row['author_email'],*
      'pwd' => $row['pwd'],
    ]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

 ?>