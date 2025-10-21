<?php
session_start();
//Gestion logout
  if(isset($_POST["logout"])){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
  } 
?>
<header>
    <h1>Bienvenue sur notre Livre d'Or</h1>
    
      <?php if(isset($_SESSION["isConnected"])): ?>
      <nav>
      <a href="index.php">Accueil</a>
      <a href="livre-or.php">Livre d'Or</a>
      <a href="profil.php">Profil</a>
      <a href="commentaire.php">Commentaires</a>
      </nav>
      <form action="profil.php" method = "POST">
    <button type="submit" name = "logout">Se déconnecter</button>
    </form>
      <?php else: ?>
      <nav>
      <a href="index.php">Accueil</a>
      <a href="inscription.php">Inscription</a>
      <a href="connexion.php">Connexion</a>
      <a href="livre-or.php">Livre d'Or</a>
      </nav>
    <?php endif; ?>
  </header>