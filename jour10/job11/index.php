<?php
$connexion = mysqli_connect('localhost', 'root');
$db = mysqli_select_db($connexion, 'jour09');

if(!$connexion){
  die("Connexion échouée : " . $connexion);
}

$request = "SELECT AVG(capacite)
FROM salles";

$db_request = mysqli_query($connexion, $request);

if(!$db_request){
  die("Requête échouée : " . mysqli_error($connexion));
}

/* Récup data des colonnes */
$row = mysqli_fetch_row($db_request);
$avg = $row[0];

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
          <td>Capacité moyenne des salles</td>
      </tr>
    </thead>
    <tbody>
        <tr>
          <td><?= $avg; ?></td>
        </tr> 
    </tbody>
  </table>
</body>
</html>