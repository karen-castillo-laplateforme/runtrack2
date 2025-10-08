<?php
session_start();
/* Initailiser le plateau s'il n'existe pas encore */
if(!isset($_SESSION["board"])){
  $_SESSION["board"] = 
    [
      ["", "", ""],
      ["", "", ""],
      ["", "", ""]
    ];
  
  $_SESSION["winner"] = null;
}

/* Fonction de vérification de victoire */
function checkVictory($board){
  // Vérifier les lignes
  foreach($board as $row){
    if($row[0] !== "" && $row[0] === $row[1] && $row[1] === $row[2]){
      return $row[0]; //retourne lettre gagante
    }
  }
  //Vérifier les colonnes
  for($i=0; $i<3; $i++){
    if($board[0][$i] !== "" && $board[0][$i]=== $board[1][$i] && $board[1][$i] === $board[2][$i]){
      return $board[0][$i];
    }
  }
  //Vérifier les diagonales(2)
  if($board[0][0] !== "" && $board[0][0] === $board[1][1] && $board[1][1] === $board[2][2]){
    return $board[0][0];
  }

  if($board[0][2] !== "" && $board[0][2] === $board[1][1] && $board[1][1] === $board[2][0]){
    return $board[0][2];
  }

  return null;

}

/* Traitement du coup joué */

if(isset($_POST["play"]) && $_SESSION["winner"] === null){
  /* Récupération des coordonnées */
  $coordinates = explode('_', $_POST["play"]);
  $x =  intval($coordinates[0]);
  $y = intval($coordinates[1]);


  /* Vérification des valeurs du tableau */
  if($_SESSION["board"][$x][$y] === ""){
    $X_count = 0;
    $O_count = 0;
    foreach($_SESSION["board"] as $row){
      foreach($row as $cell){
        if($cell === "X") $X_count++;
        if($cell === "O") $O_count++;
      }
    }

  $current_player = ($X_count <= $O_count) ? "X" : "O";
  $_SESSION["board"][$x][$y] = $current_player;
  
  //Vérifier victoire
  $winner = checkVictory($_SESSION["board"]);
  if($winner !== null){
    $_SESSION["winner"] = $winner;
  } else {
      $filled = 0;
      foreach($_SESSION["board"] as $row){
        foreach($row as $cell){
          if($cell !== "")$filled ++;
        }
      }
      if($filled === 9){
        $_SESSION["winner"] = "Égalité";
      }
    }
  }
  header("Location: index.php");
  exit;
}



/* Réinitialisation */
if(isset($_POST["reset"])){
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}
?>

<style>
  table {
  border-collapse: collapse;
  border : 1px solid black;
  }

  td{
    border : 1px solid black;
    padding : 30px 30px;
  }

</style>

<table>
  <tbody>
    <?php for($i=0; $i< 3; $i++): ?>
      <tr>
        <?php for($j=0; $j< 3; $j++): ?>
          <td>
            <?php if($_SESSION["board"][$i][$j] === ""): ?>
              <form method="POST">
                <button type = submit name = "play" value=<?= $i . "_" . $j ?>>-</button>
            </form>
            <?php else : ?>
              <?=$_SESSION["board"][$i][$j] ?>
            <?php endif; ?>
          </td>
        <?php endfor; ?>
      </tr>
    <?php endfor; ?>
  </tbody>
</table>
<form method="POST">
    <button type="submit" name="reset">Réinitaliser jeu</button>
</form>

<?php if($_SESSION["winner"] === "X" || $_SESSION["winner"] === "O"): ?>
  <p><?= $_SESSION["winner"]; ?> a gagné ! Fin de la partie.</p>
<?php elseif ($_SESSION["winner"] === "Égalité"): ?>
  <p><?= $_SESSION["winner"]; ?></p>
<?php endif; ?>
