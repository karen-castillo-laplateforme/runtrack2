<?php
$connexion = mysqli_connect('localhost', 'root');
$db = mysqli_select_db($connexion, 'jour09');

if(!$connexion){
  die("Connexion échouée : " . $connexion);
}

$request = "SELECT salles.nom as 'Salle', etages.nom as 'Etage' FROM salles
LEFT JOIN etages ON etages.id = id_etage;";

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
      <?php foreach($salles as $s):?>
        <tr>
          <td><?= $s["Salle"] ?></td>
          <td><?= $s["Etage"] ?></td>
        </tr>
      <?php endforeach; ?>   
    </tbody>
  </table>
</body>
</html>