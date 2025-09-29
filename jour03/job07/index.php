<?php
$str = "Certaines choses changent, et d'autres ne changeront jamais.";
$lenght = null;
$temp = $str[0];

$i = 0;
while (isset($str[$i]) && $str[$i] !== "") {
  $i++;  
  $lenght = $i;
}

for ($j = 0; $j <= $lenght-2; $j++) { 
  $str[$j] = $str[$j+1];
}

$str[$lenght-1] = $temp;

echo $str;

?>