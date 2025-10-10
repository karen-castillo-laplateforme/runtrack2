<?php
$connexion = mysqli_connect('localhost', 'root');
$db = mysqli_select_db($connexion, "jour09");

$command = "SELECT prenom, nom, naissance, sexe, email FROM etudiants";
$db_request = mysqli_query($connexion, $command);
$db_results = mysqli_fetch_all($db_request, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <table>
    <thead>
      <tr>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Naissance</th>
        <th>Sexe</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($db_results as $student): ?>
      <tr>
        <td><?= $student["prenom"]?></td>
        <td><?= $student["nom"]?></td>
        <td><?= $student["naissance"]?></td>
        <td><?= $student["sexe"]?></td>
        <td><?= $student["email"]?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>