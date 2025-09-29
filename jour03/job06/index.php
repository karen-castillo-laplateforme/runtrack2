<?php
$str = "Les choses que l'on possède finissent par nous posséder.";
$count = null;

$i = 0;
while (isset($str[$i]) && $str !== "") {
  $i++;
  $count = $i;
}
  for ($j = $count - 1; $j >=0; $j--) { 
    echo $str[$j];
  }


?>