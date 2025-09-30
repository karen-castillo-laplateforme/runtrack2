<?php
$hauteur = $_POST["hauteur"];
$largeur = $_POST["largeur"];

$espace_ext_initial = intval($largeur/2);
$espaces_int_html = "";



if($hauteur && $largeur){
  for($i = 0; $i<$hauteur; $i++){
    $espaces_ext_html = "";
    for($j = 0; $j< $espace_ext_initial; $j++){
      $espaces_ext_html .= "*";
    }
    
    $espaces_int_html = "";
    $interieur = intval(($i * ($largeur - 2)) / ($hauteur - 1));
    for($k = 0; $k < $interieur; $k++){
      $espaces_int_html .= "_";
    }
    echo $espaces_ext_html . "/" . $espaces_int_html ."\\" ."<br>";
    $espace_ext_initial--;
  }
  for ($l=0; $l < $hauteur ; $l++) {
    $largeur_rect = "";
    $caractere = ($l === $hauteur - 1) ? "_" : "*";
    for($m = 0; $m < $largeur - 2; $m++ ){
      $largeur_rect .= $caractere;
    }
    echo "*" . "|" . $largeur_rect . "|" . "<br>"; 
  }
}
?>