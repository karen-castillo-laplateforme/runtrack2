<?php
session_start();
$comment = "";
$id = null;
$message_erreur = "";
$message_succes = "";

if(isset($_SESSION["id"])){
  $id = $_SESSION["id"];
}

if(isset($_SESSION["erreur"])){
  $message_erreur = $_SESSION["erreur"];
  unset($_SESSION["erreur"]);
}

if(isset($_SESSION["succes"])){
  $message_succes = $_SESSION["succes"];
  unset($_SESSION["succes"]);
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
  if(!empty($_POST["comment"])){
    $comment = trim($_POST["comment"]);

    $connexion_db = mysqli_connect('localhost', 'root', '', 'livreor2');

    if(!$connexion_db){
      //Normalement en mode dev en msqli faut créer un fichier de gestion d'erreur (error log) + session["erreur] pour msg d'erreur au user ou avec PDO avec try & catch et la class Exception + session["erreur] + méthode error_log().
      $_SESSION["erreur"] = "Problème technique, veuillez réesayer plus tard."; 
    }

    $now = date('Y-m-d H:i:s');

    $sql = "INSERT INTO commentaires (commentaires, id_utilisateurs, date)
    VALUES (?, ?, ?)
    ";

    $stmt = mysqli_prepare($connexion_db, $sql);

    if(!$stmt){
      $_SESSION["erreur"] = "Problème technique, veuillez réesayer plus tard.";
      header("Location: commentaire.php");
      exit; 
    }

    mysqli_stmt_bind_param($stmt, "sis", $comment, $id, $now);

    if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['erreur'] = "Erreur lors de l'ajout du commentaire";
    } else {
    $_SESSION['succes'] = "Commentaire ajouté dans le livre d'or ✅";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connexion_db);
    header("Location: commentaire.php");
    exit;
  } else{
    $_SESSION["erreur"] = "Commentaires manquants";
    header("Location: commentaire.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un commentaire - Livre d'Or</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <?php include "includes/header.php" ?>
    <h1>Ajouter un commentaire</h1>
  <main>
    <p><?= !empty($message_erreur) ? htmlspecialchars($message_erreur) : (!empty($message_succes) ? htmlspecialchars($message_succes) : "") ?></p>
    <form class="form-card" action = "commentaire.php" method = "POST">
      <label>Votre commentaire :</label>
      <textarea name="comment" rows="5" required></textarea>

      <button type="submit">Publier</button>
    </form>
  </main>
  <?php include "includes/footer.php" ?>
</body>
</html>
