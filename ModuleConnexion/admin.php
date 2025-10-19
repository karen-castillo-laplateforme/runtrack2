<?php
session_start();

// 1️⃣ Connexion à la base
$connexion_db = mysqli_connect('localhost', 'root', '', 'moduleconnexion');
if(!$connexion_db){
  die("Connexion à la BDD a échouée" . mysqli_connect_error());
}

// 2️⃣ Préparer la requête
$sql = "SELECT id, nom, prenom, login FROM utilisateurs";

$stmt = mysqli_prepare($connexion_db, $sql);

if(!$stmt){
  die("Erreur de préparation" . mysqli_error($connexion_db));
}

// 3️⃣ Exécuter la requête
if(!mysqli_stmt_execute($stmt)){
  die("Erreur d'exécution : " . mysqli_stmt_error($stmt))
}

// 4️⃣ Récupérer le résultat
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    die("Erreur lors de la récupération du résultat : " . mysqli_error($connexion_db));
}

// 5️⃣ Lire les données
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

// 6️⃣ Libérer et fermer
mysqli_free_result($result);
mysqli_stmt_close($stmt);
mysqli_close($connexion_db);

//Gestion logout
  if(isset($_POST["logout"])){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
  } 

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
</head>
<body>
    <h1>Interface d'administration</h1>
    <h2>Liste des utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Login</th>
            </tr>
        </thead>
        <tbody>
          <?php foreach($users as $u): ?>
            <tr>
              <td><?= htmlspecialschars($u["id"]) ?></td>
              <td><?= htmlspecialschars($u["nom"]) ?></td>
              <td><?= htmlspecialschars($u["prenom"]) ?></td>
              <td><?= htmlspecialschars($u["login"]) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
    </table>
    <form action="admin.php" method = "POST">
    <button type="submit" name = "logout">Se déconnecter</button>
    </form>
</body>
</html>
