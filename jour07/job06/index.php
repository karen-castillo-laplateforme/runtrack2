<?php
function leetSpeak($str){
  $equivalences = [
    "A" => "4",
    "a" => "4",
    "B" => "8",
    "b" => "8",
    "E" => "3",
    "e" => "3",
    "G" => "6",
    "g" => "6",
    "L" => "1",
    "l" => "1",
    "S" => "5",
    "s" => "5",
    "T" => "7",
    "t" => "7",
  ];
$strlen = 0;
while(isset($str[$strlen]) && $str!== ""){
  $strlen++;
}

for($j = 0; $j < $strlen; $j++){
  foreach($equivalences as $letter => $value){
    if($str[$j] === $letter){
        $str[$j] = $value;
    }
  }
}
return $str;
}

echo leetSpeak("Bonjour les glandus de la Terre");
?>