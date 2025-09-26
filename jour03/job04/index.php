<?php
$str = "Dans l'espace, personne ne vous entend crier.";

function count_char($string){
  $i = 0;
while (isset($string[$i]) && $string[$i] !== "") {
  $i++;
}
echo "$i caractères.";
}

count_char($str);
?>