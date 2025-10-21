<?php
session_start();
$message_erreur = "";

if(isset($_SESSION["erreur_login"])){
  $message_erreur= $_SESSION["erreur_login"];
  unset($_SESSION["erreur_login"]);
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
  if(!empty($_POST["login"]) && !empty($_POST["password"])){
    $login = trim($_POST["login"]);
    $password = $_POST["password"];

    $connexion_db = mysqli_connect('localhost', 'root', '', 'livreor2');

    if(!$connexion_db){
      die("La connexion à la BDD a échoué : " . mysqli_connect_error());
    }

    $sql = "SELECT login, password, id FROM utilisateurs WHERE login = ?";

    $stmt = mysqli_prepare($connexion_db, $sql);

    if(!$stmt){
      die("Erreur préparation requête : " . mysqli_error($connexion_db));
    }

    mysqli_stmt_bind_param($stmt, "s", $login);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $db_login, $db_password, $db_id);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt) === 0){
        $_SESSION["erreur_login"] = "Login introuvable";
        header("Location: connexion.php");
        exit;
    }

    mysqli_stmt_fetch($stmt);

    if(password_verify($password, $db_password)){
      $_SESSION["login"] = $db_login;
      $_SESSION["id"] = $db_id;
      $_SESSION["isConnected"] = true;

      header("Location: profil.php");
      exit;

    } else {
      $_SESSION["erreur_login"] = "Login et/ou mot de passe incorrect";
      header("Location: connexion.php"); 
      exit;  
    }
    mysqli_stmt_close($stmt);
    mysqli_close($connexion_db);
  } else {
    $_SESSION["erreur_login"] = "Login et/ou mot de passe introuvable";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - Livre d'Or</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include "includes/header.php" ?>
  
    <h1>Connexion</h1>
  
  <main>
    <p><?= $message_erreur ?></p>
    <form class="form-card" action = "connexion.php" method = "POST">
      <label>Login :</label>
      <input type="text" name="login" required>

      <label>Mot de passe :</label>
      <input type="password" name="password" required>

      <button type="submit">Se connecter</button>
    </form>

    <p class="redirect">Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous</a></p>
  </main>
  <?php include "includes/footer.php" ?>
</body>
</html>
