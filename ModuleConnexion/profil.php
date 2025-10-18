<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
</head>
<body>
    <h1>Modifier mon profil</h1>
    <form action="profil.php" method="post">
        <label>Nom :</label>
        <input type="text" name="nom" value="" required><br>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="" required><br>

        <label>Login :</label>
        <input type="text" name="login" value="" required><br>

        <label>Email :</label>
        <input type="email" name="email" value="" required><br>

        <button type="submit">Mettre à jour</button>
    </form>
    <p><a href="deconnexion.php">Se déconnecter</a></p>
</body>
</html>
