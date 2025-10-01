<?php
if(isset($_POST["style"])){
  $css = $_POST["style"];
} else {
  $css = "style1";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href = "./assets/css/<?= $css ?>" rel = "stylesheet" />

</head>
<body>
  <form method = "POST">
  <label for="">Choisir CSS : </label>
  <select name="style" id="style">
    <option value="">Choisir style</option>
    <option value="style1" <?php if($css === "style1") echo "selected"; ?>>style1</option>
    <option value="style2" <?php if($css === "style2") echo "selected"; ?>>style2</option>
    <option value="style3" <?php if($css === "style3") echo "selected"; ?>>style3</option>
  </select>
  <button type="submit">Enregistrer</button>
</form>
</body>
</html>


   

