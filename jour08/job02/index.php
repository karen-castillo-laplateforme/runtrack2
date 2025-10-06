    <?php
    $visites = 0;

    if(isset($_POST) && isset($_POST["reset"])){
        $visites = 0;
        setcookie("nbvisites", 0);  
    } else if(isset($_COOKIE["nbvisites"])){
        $visites = $_COOKIE["nbvisites"] + 1;
        setcookie("nbvisites", $visites);
    } else {
        $visites = 1;
    }
    ?>

    <form action = "index.php" method = "POST">
    <p><?= $visites;?></p>
    <button type="submit" name="reset">Reset</button>
    </form>