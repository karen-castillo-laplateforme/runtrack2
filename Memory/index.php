<?php
session_start();// Initialisation d'une session

// --- Récupération des cartes ---
$all_cards = glob("cards/*.svg"); //Récupère tous les fichiers sous forme de tableau du dossier cards et se terminant par .svg
$all_cards = array_filter($all_cards, function($c) {
    return basename($c) !== "back.svg";
}); //Filtre le tableau all cards et retourne les fichiers qui ont un nom différent de back.svg
$all_cards = array_values(array_map('basename', $all_cards)); //Récupère les valeurs du tableau donc le nom des fichiers filtrés

// --- Choix avant partie ---
if (!isset($_SESSION['cards']) && !isset($_POST['nb_cards'])) {
    //S'il n'y a pas de variables de Session "cards" ou "nb_cards" alors afficher le code html ci-dessous
    ?>
    <!doctype html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Memory - Choix</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    </head>
    <body class="bg-light text-center p-5">
        <div class="container">
            <h1 class="mb-4">Memory - Choisis les options</h1>
            <form method="post" class="d-flex flex-column gap-4 align-items-center">

                <div>
                    <h4>Nombre de cartes :</h4>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <?php foreach ([6, 12, 24, 48, 66] as $n): ?>
                            <input type="radio" class="btn-check" name="nb_cards" id="cards<?= $n ?>" value="<?= $n ?>" required>
                            <label class="btn btn-outline-primary btn-lg" for="cards<?= $n ?>"><?= $n ?> cartes</label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <h4>Mode :</h4>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="timed" value="1" id="chronoOn" checked>
                        <label class="form-check-label" for="chronoOn">Chronométré</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="timed" value="0" id="chronoOff">
                        <label class="form-check-label" for="chronoOff">Non chronométré</label>
                    </div>
                </div>

                <!-- Bouton démarrer -->
                <button type="submit" class="btn btn-success btn-lg mt-3">
                    🚀 Démarrer la partie
                </button>

            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// --- Initialisation ---
if (!isset($_SESSION['cards'])) {
    //S'il n'y a pas de variable de session cards alors on exécute le code suivant, on initialise la partie.
    $nb_cards = (int)$_POST['nb_cards']; //On récupère le nombre de cartes choisi par le user
    $nb_pairs = intdiv($nb_cards, 2); // On détermine le nombre de pairs en fonction du nbre de cartes dans la partie

    shuffle($all_cards); //La fonction shuffle permet de mofifier les index des cartes
    $selected = array_slice($all_cards, 0, $nb_pairs); //On détermine les cartes avec lesquelles on va devoir faire des pairs

    $cards = []; //On créé une variable cards, un tableau, qui va contenir plusieurs valeurs
    foreach ($selected as $c) {
        $cards[] = $c; //On ajoute les sélections dans le tableau cards
        $cards[] = $c; //On duplique les sélections pour avoir les pairs dans le tableau
    }
    shuffle($cards); //On modifie les index des cartes

    //On créé les variables de session
    $_SESSION['cards'] = $cards;
    $_SESSION['revealed'] = [];
    $_SESSION['selection'] = [];
    $_SESSION['moves'] = 0;       
    $_SESSION['timed'] = isset($_POST['timed']) ? (int)$_POST['timed'] : 1;
    $_SESSION['nb_cards'] = $nb_cards;

    //Intialisation du chrono
    if ($_SESSION['timed'] === 1) {
        $_SESSION['start_time'] = time(); 
    }
}

// --- Gestion clic ---
if (isset($_GET['pos'])) {
    $_SESSION['moves']++; //si la variable de session pos existe alors incrémenter de +1 la variable de session moves

    $pos = (int)$_GET['pos']; //la variable pos prend valeur de pos dans l'url lorsque le user clique sur une carte
    if (!in_array($pos, $_SESSION['revealed']) && !in_array($pos, $_SESSION['selection'])) {
        $_SESSION['selection'][] = $pos;
        //S'il n'y a pas dans le tableau variable de session revealed et selection, la valeur de la variable pos(chiffre) alors on rajoute la valeur de pos dans la variable de session selection.
    }
    if (count($_SESSION['selection']) == 2) {
        //Si le nombre d'éléments dans le tableau est = à 2 alors la variable $a prend la valeur de l'index 0 du tableau stocké dans la variable de session selection et $b la valeur de l'index 1.
        $a = $_SESSION['selection'][0];
        $b = $_SESSION['selection'][1];
        if ($_SESSION['cards'][$a] === $_SESSION['cards'][$b]) {
        //Si les deux cartes stockées dans la variable de session cards ayant pour index (pos) sont identiques, donc on le même nom de fichier, alors on ajoute dans la variable de session revealed les deux valeurs récupérées grâce à la variable pos dans l'url.
            $_SESSION['revealed'][] = $a;
            $_SESSION['revealed'][] = $b;
        }
        //Si les deux cartes ne sont pas identiques alors on vide la variable de session selection.
        $_SESSION['selection'] = [];
    }
}

// --- Reset ---
if (isset($_POST['reset'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// --- Calcul chrono ---
$elapsed = 0;
if ($_SESSION['timed'] === 1) {
    $elapsed = time() - ($_SESSION['start_time'] ?? time());
}
$minutes = floor($elapsed / 60);
$seconds = $elapsed % 60;

// --- Affichage partie ---
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Memory Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        td img { max-width: 80px; height: auto; }
        table { margin: auto; }
    </style>
</head>
<body class="bg-light text-center p-4">
<div class="container">

    <h1 class="mb-3">Memory Game</h1>

    <!-- Infos -->
    <div class="alert alert-info">
        <strong>Coups :</strong> <?= $_SESSION['moves'] ?>
        <!-- Code dans le cas où le chrono a été activé -->
        <?php if ($_SESSION['timed'] === 1): ?>
            | <strong>Temps :</strong> <?= sprintf("%02d:%02d", $minutes, $seconds) ?>
        <?php endif; ?>
        <br>
        <?php if ($_SESSION['timed'] === 1): ?>
            <span class="badge bg-danger">Chronométré - <?= $_SESSION['nb_cards'] ?> cartes</span>
        <?php else: ?>
            <span class="badge bg-success">Libre - <?= $_SESSION['nb_cards'] ?> cartes</span>
        <?php endif; ?>
    </div>

    <!-- Bouton reset avec dropdown -->
<!-- Bouton reset avec dropdown -->
<!-- Bouton reset avec dropdown -->
<div class="btn-group mb-4">
  <form method="post" action="index.php">
    <button type="submit" name="reset" value="1" class="btn btn-success">
      <i class="bi bi-arrow-repeat"></i> Nouvelle partie
    </button>
  </form>
  <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" 
          data-bs-toggle="dropdown" aria-expanded="false">
    <span class="visually-hidden">Choisir</span>
  </button>
  <ul class="dropdown-menu">
    <?php foreach ([6, 12, 24, 48, 66] as $n): ?>
      <li>
        <form method="post" action="index.php">
          <input type="hidden" name="nb_cards" value="<?= $n ?>">
          <input type="hidden" name="timed" value="1">
          <button type="submit" class="dropdown-item"><?= $n ?> cartes (chrono)</button>
        </form>
      </li>
      <li>
        <form method="post" action="index.php">
          <input type="hidden" name="nb_cards" value="<?= $n ?>">
          <input type="hidden" name="timed" value="0">
          <button type="submit" class="dropdown-item"><?= $n ?> cartes (libre)</button>
        </form>
      </li>
      <li><hr class="dropdown-divider"></li>
    <?php endforeach; ?>
  </ul>
</div>



    <!-- Plateau -->
    <table class="table table-borderless">
        <tr>
            <!-- On récupère le nom des fichiers dans la variable de session cards -->
        <?php foreach ($_SESSION['cards'] as $i => $filename): ?>
            <td>
                <!-- Si l'index de la variable de session cards se trouve dans le tableau de la variable de session revealed ou selection alors on affiche l'image correspondante à l'index de la boucle foreach ci dessus  -->
                <?php if (in_array($i, $_SESSION['revealed']) || in_array($i, $_SESSION['selection'])): ?>
                    <img src="cards/<?= $filename ?>" class="img-fluid">
                <?php else: ?>
                    <!-- Sinon afficher cette image cards/back.svg et chaque carte possède un index, soit des index restants ou soit des index à partir de 0 dans le cas d'un début de partie -->
                    <a href="?pos=<?= $i ?>">
                        <img src="cards/back.svg" class="img-fluid">
                    </a>
                <?php endif; ?>
            </td>
            <!-- Condition pour passer à la ligne dès qu'on a atteint 12 cartes sur une ligne -->
            <?php if (($i+1) % 12 == 0): ?></tr><tr><?php endif; ?>
        <?php endforeach; ?>
        </tr>
    </table>

    <!-- Condition pour que la partie soit finie : si on a autant de cartes révélées que de cartes dans session cards la partie est terminée -->
    <?php if (count($_SESSION['revealed']) == count($_SESSION['cards'])): ?>
        <div class="alert alert-success mt-3">
            🎉 Partie terminée en <?= $_SESSION['moves'] ?> coups
            <?php if ($_SESSION['timed'] === 1): ?>
                et <?= sprintf("%02d:%02d", $minutes, $seconds) ?> !
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
</body>
</html>
