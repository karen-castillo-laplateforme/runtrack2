<?php

$first_name = isset($_POST["prenom"]) ? trim($_POST["prenom"]) : "";
$user = isset($_COOKIE["user"]) ? $_COOKIE["user"] : "";

if($first_name !== "" && !$user && isset($_POST["register"])){
  setcookie("user", $first_name);
  $user = $first_name;
  
  /* Autre possibilité (à la place de la variable $user) :
   header("Location: index.php");
  exit; */
}



if(isset($_COOKIE["user"]) && isset($_POST["logout"])){
    setcookie("user", "", time() - 3600);
    $user = "";
     /* Autre possibilité (à la place de la variable $user) :
   header("Location: index.php");
  exit; */
}
?>

<!-- S'il n'y a pas de cookie prenom présent dans le nav alors afficher formulaire -->
<?php if(!$user): ?>
<form method="POST">
  <input type="text" name="prenom" required>
  <button type="submit" name = "register">Connexion</button>
</form>

<!-- Sinon afficher -->
<?php else: ?>
<h2> Bonjour <?= htmlspecialchars($user)  ?> </h2>
<form method = "POST">
  <button type="submit" name="logout">Déconnexion</button>
</form>
<?php endif; ?>