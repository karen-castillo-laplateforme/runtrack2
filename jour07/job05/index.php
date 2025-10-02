<?php
function occurences($str, $char){
  $strlen = null;
  $cars = [];
  $i = 0;
//Compter le nombre de caractères dans string
  while(isset($str[$i]) && $str != ""){
    $i++;
    $strlen = $i;
  }

//Parcourir la chaine de caractère
  for($j = 0; $j < $strlen - 1; $j++){
    if($str[$j] === $char){
      $cars[] = $str[$j];
    }
  }

  $nbr_occurences = count($cars);
return "Le nombre d'occurences de $char dans $str sera : $nbr_occurences";
}

echo occurences("Bonjour", "o");
?>