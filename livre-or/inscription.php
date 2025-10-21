<?php
session_start();
$message_erreur = "";

if(isset($_SESSION["erreur"])){
  $message_erreur = $_SESSION["erreur"];
  unset($_SESSION);
}

if($_SERVER["REQUEST_METHOD"] === "POST"){

  if(isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])){

    $login = trim($_POST["login"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if($password !== $confirm_password){
      $_SESSION["erreur"] = "Les mots de passe ne sont pas identiques";
      header("Location: inscription.php");
      exit; 
    } else {
      //Connexion à la bdd.
      $connexion_db = mysqli_connect('localhost', 'root', '', 'livreor2');
      if(!$connexion_db){
        die("La connexion n'a pas aboutie : " . mysqli_connect_error());
      }

      $sql = "INSERT INTO utilisateurs (login, password)
      VALUES (?, ?)
      ";

      $stmt = mysqli_prepare($connexion_db, $sql);

      if(!$stmt){
        die("" . mysqli_error($connexion_db));
      }

      $password = password_hash($password, PASSWORD_DEFAULT);

      mysqli_stmt_bind_param($stmt, "ss", $login, $password);

      if(!mysqli_stmt_execute($stmt)){
        die("L'insertion a échoué :" . mysqli_stmt_error($stmt));
      }

      //Libérer et fermer
      mysqli_stmt_close($stmt);
      mysqli_close($connexion_db);
      
      session_unset();
      session_destroy();

      header("Location: connexion.php");
      exit;
    }
      
  } else {
    $_SESSION["erreur"] = "Champs manqants";
    unset($_SESSION["erreur"]);
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription - Livre d'Or</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <?php include "includes/header.php" ?>

  <h1>Inscription</h1>
  <main>
    <p><?= $message_erreur ?></p>
    <form class="form-card" method="POST" action="inscription.php">
      <label>Login :</label>
      <input type="text" name="login" required>

      <label>Mot de passe :</label>
      <input type="password" name="password" required>

      <label>Confirmer le mot de passe :</label>
      <input type="password" name="confirm_password" required>

      <button type="submit">S'inscrire</button>
    </form>

    <p class="redirect">Déjà inscrit ? <a href="connexion.php">Connectez-vous</a></p>
  </main>
</body>
</html>
