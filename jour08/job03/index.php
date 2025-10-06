<?php
session_start();

$first_name = isset($_POST["prenom"]) ? $_POST["prenom"] : "";

if(isset($_POST["reset"])){
  session_unset();
  session_destroy();
}

if(!isset($_SESSION["prenom"])){
  $_SESSION["prenom"] = [];
}

if (isset($_POST["register"]) && $first_name){
  $_SESSION["prenom"][] = $first_name;
}
?>

<form method = "POST">
  <input type="text" name = "prenom">
  <button type = submit name = "register">Enregistrer</button>
  <button type = "submit" name = "reset">reset</button>
</form>
<div>
  <?php if(isset($_SESSION["prenom"])) : ?>
    <ul>
      <?php foreach($_SESSION["prenom"] as $p):?>
        <li><?= $p ?></li>
      <?php endforeach; ?>
    </ul>  
  <?php endif; ?>
</div>

