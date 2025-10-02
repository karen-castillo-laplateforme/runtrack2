<?php
function calcule($a, $operation, $b){
  switch($operation) {
    case "+":
        return $a + $b;
        break;
    case "-":
        return $a - $b;
        break;
    case "*":
        return $a * $b;
        break;
    case "/":
        return $a / $b;
        break;
    case "%":
        return $a % $b;
        break;
    default: return "Opération inconnue";        
  }
}

echo calcule(6, "%", 2);
?>
