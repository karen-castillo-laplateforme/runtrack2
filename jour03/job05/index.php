<?php
$string = "On n’est pas le meilleur quand on le croit mais quand on le sait.";
$str = strtolower($string);

$dic = [
  "voyelles" => ["a", "e", "i", "o","u","y"],
  "consonnes" => ["b", "c","d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s","t","v", "w", "x", "z"]
];

$voyelles = [];
$consonnes = [];

//Parcourir la chaîne de caractères
$i = 0;
while (isset($str[$i]) && $str[$i] !== "") {
  foreach($dic["voyelles"] as $v){
    if($str[$i] == $v){
      $voyelles[] = $str[$i];
    } 
  }
  foreach($dic["consonnes"] as $c){
    if($str[$i] == $c){
      $consonnes[] = $str[$i];
    } 
  }

  $i++;
}
?>

<table>
  <thead>
    <tr>
      <th>Consonnes</th>
      <th>Voyelles</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?= count($consonnes) ?></td>
      <td><?= count($voyelles) ?></td>
    </tr>
  </tbody>
</table>