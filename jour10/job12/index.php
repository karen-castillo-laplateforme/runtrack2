<?php
$connexion = mysqli_connect('localhost', 'root');
$db = mysqli_select_db($connexion, 'jour09');

if(!$connexion){
  die("Connexion échouée à la BDD : " . mysqli_connect_error());
}

$cmd = "SELECT prenom, nom, naissance FROM etudiants WHERE YEAR(DATE(naissance)) BETWEEN 1998 AND 2018;
";
$db_request = mysqli_query($connexion, $cmd);

if(!$db_request){
  die("Erreur requête : " . mysqli_error($connexion));
}

/* Récup nom des champs */
$all_fields = mysqli_fetch_fields($db_request);

$field_names = [];
foreach($all_fields as $f){
  $field_names[] = $f->name;
}

/* Récup data des colonnes */
$students = mysqli_fetch_all($db_request, MYSQLI_ASSOC);
var_dump($students);

mysqli_free_result($db_request);
mysqli_close($connexion);
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
        <?php foreach($field_names as $n): ?>
          <td><?= ucfirst($n); ?></td>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($students as $s):?>
        <tr>
          <td><?= $s["prenom"] ?></td>
          <td><?= $s["nom"]?></td>
          <td><?= $s["naissance"]?></td>
        </tr>
      <?php endforeach; ?>   
    </tbody>
  </table>
</body>
</html>