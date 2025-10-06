<?php
session_start();

if(!isset($_SESSION["nbvisites"])){
  $_SESSION["nbvisites"] = 0;
}
if(isset($_POST) && isset($_POST["reset"])){
  $_SESSION["nbvisites"] = 0;
} else {
  $_SESSION["nbvisites"] += 1;
}

?>

<form action = "index.php" method = "POST">
  <p><?= $_SESSION["nbvisites"];?></p>
  <button type="submit" name="reset">Reset</button>
</form>