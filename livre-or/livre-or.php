<?php
// session_start();
$message_erreur = "";

$connexion_db = mysqli_connect('localhost', 'root', '', 'livreor2');

if(!$connexion_db){
  $message_erreur = "Problème technique, veuillez réesayer plus tard."; 
}

$sql = "SELECT commentaires.commentaires, commentaires.id_utilisateurs, commentaires.date, utilisateurs.login 
FROM commentaires
LEFT JOIN utilisateurs ON commentaires.id_utilisateurs = utilisateurs.id
ORDER BY commentaires.date ASC;
";

$stmt = mysqli_prepare($connexion_db, $sql);

if(!$stmt){
  $message_erreur = "Problème technique, veuillez réesayer plus tard."; 
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
//On rebascule aux méthodes mysqli_result avant on était sur mysqli_stmt
$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);


mysqli_stmt_close($stmt);
mysqli_free_result($result);
mysqli_close($connexion_db);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Livre d'Or</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <?php include "includes/header.php" ?>
    <h1>Livre d'Or</h1>
  <main>
    <section class="comments">
      <?php foreach($comments as $c):
      $date = new DateTime($c["date"]);
      ?>
        <article class="comment">
        <p class="meta">Posté le <?= $date->format('d/m/Y H:i') ?> par <strong><?= $c["login"] ?></strong></p>
        <p><?= htmlspecialchars($c["commentaires"]) ?></p>
      </article>
      <?php endforeach; ?>
    </section>
  </main>
  <?php include "includes/footer.php" ?>
</body>
</html>
