<?php
session_start();
$login = "";
$id = null;
$message_erreur = "";
$message_succes = "";

if(isset($_SESSION["login"])){
  $login = $_SESSION["login"];
}

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
  //Gestion update
  if(isset($_POST["update"])){
    $login = trim($_POST["login"]);
    $old_password = $_POST["old_password"] ?? "";
    $new_password = $_POST["new_password"] ?? "";
    $new_confirm_password = $_POST["new_confirm_password"] ?? "";
     
    //Connexion à la BDD
    $connexion_db = mysqli_connect('localhost', 'root', '', 'livreor2');

    if(!$connexion_db){
      die("Connexion à la BDD échouée" . mysqli_connect_error());
    }

    // --- 🔹 1. Mise à jour login ---
    $sql = "UPDATE utilisateurs 
    SET login = ?
    WHERE id = ? ";

    $stmt = mysqli_prepare($connexion_db, $sql);

    if(!$stmt){
      die("Erreur de préparation" . mysqli_error($connexion_db));
    }

    mysqli_stmt_bind_param($stmt, "si", $login, $id);

    if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['erreur'] = "Erreur lors de la mise à jour du login.";
    } else {
    $_SESSION['succes'] = "Profil mis à jour avec succès ✅";
    }


    mysqli_stmt_close($stmt);

    // --- 🔹 2. Si un changement de mot de passe est demandé ---
    if (!empty($old_password) || !empty($new_password) || !empty($new_confirm_password)) {
        // Vérifier que tout est rempli
        if (empty($old_password) || empty($new_password) || empty($new_confirm_password)) {
            $_SESSION["erreur"] = "Tous les champs de mot de passe doivent être remplis.";
            header("Location: profil.php");
            exit;
        }
    // Vérifier que le nouveau mot de passe et la confirmation sont identiques
    if ($new_password !== $new_confirm_password) {
            $_SESSION["erreur"] = "Les nouveaux mots de passe ne correspondent pas.";
            header("Location: profil.php");
            exit;
        }

    // Récupérer le mot de passe actuel en BDD
        $sql_pwd = "SELECT password FROM utilisateurs WHERE id = ?";
        $stmt_pwd = mysqli_prepare($connexion_db, $sql_pwd);
        mysqli_stmt_bind_param($stmt_pwd, "i", $id);
        mysqli_stmt_execute($stmt_pwd);
        mysqli_stmt_bind_result($stmt_pwd, $db_password);
        mysqli_stmt_fetch($stmt_pwd);
        mysqli_stmt_close($stmt_pwd);

    // Vérifier l'ancien mot de passe
        if (!password_verify($old_password, $db_password)) {
            $_SESSION["erreur"] = "L'ancien mot de passe est incorrect.";
            header("Location: profil.php");
            exit;
        }
    
    // Mettre à jour avec le nouveau mot de passe haché
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql_update_pwd = "UPDATE utilisateurs SET password=? WHERE id=?";

        $stmt_update_pwd = mysqli_prepare($connexion_db, $sql_update_pwd);

        mysqli_stmt_bind_param($stmt_update_pwd, "si", $hashed_password, $id);

        mysqli_stmt_execute($stmt_update_pwd);

        mysqli_stmt_close($stmt_update_pwd);

        $_SESSION["succes"] = "Mot de passe mis à jour avec succès ✅";
    }

    // --- 🔹 3. Mise à jour des infos dans la session ---
    $_SESSION["login"] = $login;

    header("Location: profil.php");
    exit;
  }
  
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon profil - Livre d'Or</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <?php include "includes/header.php" ?>
    <h1>Mon profil</h1>
    <main>
    <p><?= !empty($message_erreur) ? htmlspecialchars($message_erreur) : (!empty($message_succes) ? htmlspecialchars($message_succes) : "") ?></p>
    <form class="form-card" action = "profil.php" method="POST">
      <label>Nouveau login :</label>
      <input type="text" name="login" value ="<?= htmlspecialchars($login)?>" required>

      <label>Ancien mot de passe :</label>
      <input type="password" name="old_password">

      <label>Nouveau mot de passe :</label>
      <input type="password" name="new_password" required>

      <label>Confirmer le mot de passe :</label>
      <input type="password" name="new_confirm_password" required>

      <button type="submit" name="update">Mettre à jour</button>
    </form>
  </main>
  <?php include "includes/footer.php" ?>
</body>
</html>
