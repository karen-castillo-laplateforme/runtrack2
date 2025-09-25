<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
&nbsp;&nbsp;&nbsp;&nbsp;*<br>
&nbsp;&nbsp;&nbsp;**<br>
&nbsp;&nbsp;***<br>
&nbsp;****<br>
*****<br> 
         
    <?php
$hauteur = 5;
$space = "";
$stars ="*";
$line="";
$array = [
 "&nbsp;&nbsp;&nbsp;&nbsp;",
 "&nbsp;&nbsp;&nbsp;",
 "&nbsp;&nbsp;",
 "&nbsp;",
 ""
];
for($line=0; $line<$hauteur; $line++){
  $space = $array[$line];
  echo $space . $stars . "<br />";
  $stars = $stars . "*";
}
?>

    
</body>
</html>
