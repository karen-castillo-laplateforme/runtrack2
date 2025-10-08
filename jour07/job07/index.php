<form action="index.php" method = "POST">
  <input type="text" name ="str" value="<?php if (isset($_POST['str'])) echo htmlspecialchars($_POST['str']); ?>">
  <select name="fonction" id="fonction">
    <option value="">Choisir effet</option>
    <option value="gras" <?php if(isset($_POST["fonction"]) && $_POST["fonction"] === "gras") echo "selected" ;?> >Gras</option>
    <option value="cesar" <?php if(isset($_POST["fonction"]) && $_POST["fonction"] === "cesar") echo "selected" ;?>>Cesar</option>
    <option value="plateforme" <?php if(isset($_POST["fonction"]) && $_POST["fonction"] === "plateforme") echo "selected" ;?>>Plateforme</option>
  </select>
  <button type="submit">Envoyer</button>
</form>

<?php
//Fonctions
function gras($str){
  if(strtoupper($str[0])){
    echo "<b>$str</b>";
  } else {
    echo $str;
  }
    
}

function cesar($str, $decalage = 2){
  $alphabet = range("a", "z"); 
  $i = 0;

  while(isset($str[$i]) && $str[$i] !== ""){
    $is_upper = ctype_upper($str[$i]);
    
    for($j = 0; $j < count($alphabet); $j++){
      if(strtolower($str[$i]) === $alphabet[$j]){
          $new_index = ($j + $decalage) % count($alphabet);
          $str[$i] = $is_upper ? strtoupper($alphabet[$new_index]) : $alphabet[$new_index] ;
          break;
        } 
    }
    $i++;
  }
  echo $str;
}

function plateforme($str){
  $i = 0;
  while(isset($str[$i]) && $str[$i] !== ""){
    if(isset($str[$i+1]) && ($str[$i] . $str[$i+1] === "me")  && (!isset($str[$i+2]) || $str[$i +2] === " " )){
      echo "me_";
      $i+=2;
    } else {
        echo $str[$i];
        $i++;
    }
    
  }
}

// //Traitement
$fonction = null;
$input = null;

if($_SERVER["REQUEST_METHOD"] === "POST"){
  $fonction = $_POST["fonction"] ?? null;
  $input = $_POST["str"] ?? null;

  if($fonction === "gras"){
  gras($input);
}

if($fonction === "cesar"){
  cesar($input);
}

if($fonction === "plateforme"){
  plateforme($input);
}
}




var_dump($_POST);
?>