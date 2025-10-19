<?php
session_start();
var_dump($_SESSION);
$login = "";
$prenom = "";
$nom = "";

if(isset($_SESSION["login"])){
  $login = $_SESSION["login"];
}

if(isset($_SESSION["prenom"])){
  $prenom = $_SESSION["prenom"];
}

if(isset($_SESSION["nom"])){
  $nom = $_SESSION["nom"];
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
  //Gestion update
  if(isset($_POST["update"])){
    if($_POST["nom"] !== $nom || $_POST["prenom"] !== $prenom || $_POST["login"] !== $login){
      
    //Connexion à la BDD
    $connexion_db = mysqli_connect('localhost', 'root', '', 'moduleconnexion');

    if(!$connexion_db){
      die("Connexion à la BDD échouée" . mysqli_connect_error());
    }
    $sql = "UPDATE";
    }
  }
  

  //Gestion logout
  if(isset($_POST["logout"])){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
  } else {
    echo "WRONG";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
</head>
<body>
    <h1>Modifier mon profil</h1>
    <form action="profil.php" method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= $nom ?>" required><br>

        <label>Prénom :</label>
        <input type="text" name="prenom" value= <?= $prenom ?> required><br>

        <label>Login :</label>
        <input type="text" name="login" value=" <?= $login ?>" required><br>
        
        <!-- <label>Ancien mot de passe :</label>
        <input type="text" name="old_password"><br>

        <label>Nouveau mot de passe :</label>
        <input type="text" name="new_password"><br>

        <label>Confirmation nouveau de mot de passe</label>
        <input type="text" name="new_confirm_password"><br> -->

        <button type="submit" name="update">Mettre à jour</button>
    </form>
    <form action="profil.php" method = "POST">
    <button type="submit" name = "logout">Se déconnecter</button>
    </form>
</body>
</html>
