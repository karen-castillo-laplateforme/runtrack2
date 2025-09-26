<?php
$numbers = [
  200, 204, 173, 98, 171, 404, 459
];

foreach($numbers as $n){
  if($n%2 === 0){
    echo "$n est pair.<br>";
  } else {
    echo "$n est impair.<br>";
  }
}
?>