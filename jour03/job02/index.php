<?php
$str = strtolower("I'm sorry Dave I'm afraid I can't do that.");
$voyelles = ["a", "e", "i", "o", "u", "y"];
for ($i = 0; $i < strlen($str); $i++) { 
  
  for($j = 0; $j < count($voyelles); $j++){
    if ($str[$i] === $voyelles[$j]){
        echo $voyelles[$j];
    }
  }
}
?>