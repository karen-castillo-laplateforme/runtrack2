<?php
$connexion = mysqli_connect('localhost', 'root');
$db = mysqli_select_db($connexion, 'jour09');

if(!$connexion){
  die("Connexion échouée : " . $connexion);
}

$request = "SELECT *
FROM salles
ORDER BY capacite ASC";

$db_request = mysqli_query($connexion, $request);

if(!$db_request){
  die("Requête échouée : " . mysqli_error($connexion));
}

/* Récup nom des champs */
$all_fields = mysqli_fetch_fields($db_request);
$field_names = [];
foreach($all_fields as $f){
  $field_names[] = $f->name;
}

/* Récup data des colonnes */
$salles = mysqli_fetch_all($db_request, MYSQLI_ASSOC);

mysql_free_result($db_request);
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
      <?php foreach($salles as $s):?>
        <tr>
          <td><?= $s["id"] ?></td>
          <td><?= $s["nom"]?></td>
          <td><?= $s["id_etage"]?></td>
          <td><?= $s["capacite"]?></td>
        </tr>
      <?php endforeach; ?>   
    </tbody>
  </table>
</body>
</html>