<?php
session_start();
$nom = "";
$prenom = "";
$login = "";
$message_erreur = "";

if(isset($_SESSION["nom_input"])){
  $nom = $_SESSION["nom_input"];
}

if(isset($_SESSION["prenom_input"])){
  $prenom = $_SESSION["prenom_input"];
}

if(isset($_SESSION["login_input"])){
  $login = $_SESSION["login_input"];
}

if(isset($_SESSION["erreur_input"])){
  $message_erreur = $_SESSION["erreur_input"];
} else {
  $message_erreur = "";
}

function validateInput($input){
    return !empty($input) ? trim($input) : null;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $nom = validateInput($_POST["nom"]);
  $prenom = validateInput($_POST["prenom"]);
  $login = validateInput($_POST["login"]);
  $mdp = validateInput($_POST["password"]);
  $confirm_mdp = validateInput($_POST["confirm_password"]);

  if(isset($mdp, $confirm_mdp) && $mdp === $confirm_mdp){
    $mdp = password_hash($mdp, PASSWORD_DEFAULT);
  } else {
    //On stocke les valeurs avant de rediriger
    $_SESSION["nom_input"] = $nom;
    $_SESSION["prenom_input"] = $prenom;
    $_SESSION["login_input"] = $login;
    $_SESSION["erreur_input"] = "Le mot de passe et la confirmation de mot de passe ne sont pas identiques.";
    header("Location: inscription.php");
    exit;
  }

  $connexion_db = mysqli_connect('localhost','root', '', 'moduleconnexion');

  if(!$connexion_db){
    die("La connexion a échouée :" . mysqli_connect_error());
  }

  if($login === null || $prenom === null || $nom === null || $mdp === null){
    die("Données manquantes ou invalides.");
  }

  $sql = "INSERT INTO utilisateurs (login, prenom, nom, password) VALUES(?, ?, ?, ?)";
  $stmt = mysqli_prepare($connexion_db, $sql);

  if($stmt === false){
    die("Préparation de la requête impossible : " . mysqli_error($connexion_db));
  }

  //Lier les paramètres : "ssss" = 4 chaînes
  mysqli_stmt_bind_param($stmt, "ssss", $login, $prenom, $nom, $mdp);
  
  //Exécuter
  if(!mysqli_stmt_execute($stmt)){
    die("L'insertion a échoué :" . mysqli_stmt_error($stmt));
  }

  //Succès
  session_unset();
  session_destroy();
  header("Location: connexion.php");
  exit;

  //Libérer et fermer
  mysqli_stmt_close($stmt);
  mysqli_close($connexion_db);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <?php if(!empty($message_erreur)): ?>
        <p style = "color: red;"><?= htmlspecialchars($message_erreur) ?></p>
    <?php endif; ?>
    <form action="inscription.php" method="post">
        <label>Nom :</label>
        <input type="text" name="nom" value = "<?= $nom ?>" required><br>

        <label>Prénom :</label>
        <input type="text" name="prenom" value = "<?= $prenom ?>"  required><br>

        <label>Login :</label>
        <input type="text" name="login" value = "<?= $login ?>"  required><br>

        <label>Mot de passe :</label>
        <input type="password" name="password" required><br>

        <label>Confirmer mot de passe :</label>
        <input type="password" name="confirm_password" required><br>

        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà inscrit ? <a href="connexion.php">Connectez-vous</a></p>
</body>
</html>
