<?php
session_start();
$message_erreur = "";

if(isset($_SESSION["erreur"])){
  $message_erreur= $_SESSION["erreur"];
  unset($_SESSION["erreur"]);
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
  if(!empty($_POST["login"]) && !empty($_POST["password"])){
    $login = trim(htmlspecialchars($_POST["login"]));
    $password = $_POST["password"];

    $connexion_db = mysqli_connect('localhost', 'root', '', 'moduleconnexion');

    if(!$connexion_db){
      die("La connexion à la BDD a échoué : " . mysqli_connect_error());
    }

    $sql = "SELECT login, password, nom, prenom, id FROM utilisateurs WHERE login = ?";

    $stmt = mysqli_prepare($connexion_db, $sql);

    if($stmt === false){
      die("Préparation de la requête impossible : " . mysqli_error($connexion_db));
    }

    mysqli_stmt_bind_param($stmt, "s", $login);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $db_login, $db_password, $db_nom, $db_prenom, $db_id);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt) === 0){
        $_SESSION["erreur"] = "Login introuvable";
        header("Location: connexion.php");
        exit;
    }

    mysqli_stmt_fetch($stmt);
    if(password_verify($password, $db_password)){
      $_SESSION["login"] = $db_login;
      $_SESSION["prenom"] = $db_prenom;
      $_SESSION["nom"] = $db_nom;
      $_SESSION["id"] = $db_id;
      header("Location: profil.php");
    } else {
      $_SESSION["erreur"] = "Mot de passe incorrect";
      header("Location: connexion.php"); 
      exit;  
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connexion_db);

  } else {
    $_SESSION["erreur"] = "Login et/ou mot de passe introuvable";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
      <p><?= htmlspecialchars($message_erreur) ?></p>
    <form action="connexion.php" method="POST">
        <label>Login :</label>
        <input type="text" name="login" required><br>

        <label>Mot de passe :</label>
        <input type="password" name="password" required><br>

        <button type="submit">Se connecter</button>
    </form>
    <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous</a></p>
</body>
</html>
