<?php


/* Fonction Query  */
function db_query($cmd){
  $connexion = mysqli_connect('localhost', 'root');
  $db = mysqli_select_db($connexion, "jour09");  
  $db_request = mysqli_query($connexion, $cmd);

  if (!$db_request){
    die("Erreur SQL : " . mysqli_error($connexion));
  }

  $db_result = mysqli_fetch_all($db_request, MYSQLI_ASSOC);

  return $db_result;
}

/* Use fonction Query */
$cmd_rooms = "SELECT nom, capacite FROM salles";
$rooms_data = db_query($cmd_rooms);
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
        <th>Nom</th>
        <th>Capacité</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($rooms_data as $room) : ?>
      <tr>
        <td><?= $room["nom"] ?></td>
        <td><?= $room["capacite"] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>