<?php
  $boolean = true;
  $integer = 1;
  $string = "LaPlateforme";
  $float = 1.5;
  ?>

      <table>
    <thead>
      <tr>
        <th>type</th>
        <th>nom</th>
        <th>valeur</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= gettype($boolean) ?></td>
        <td>$boolean</td>
        <td><?= $boolean ?></td>
      </tr>
      <tr>
        <td><?= gettype($integer) ?></td>
        <td>$integer</td>
        <td><?= $integer ?></td>
      </tr>
      <tr>
        <td><?= gettype($string) ?></td>
        <td>$string</td>
        <td><?= $string ?></td>
      </tr>
      <tr>
        <td><?= gettype($float) ?></td>
        <td>$float</td>
        <td><?= $float ?></td>
      </tr>
    </tbody> 
  </table>

