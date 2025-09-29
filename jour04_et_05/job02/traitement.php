<table>
  <thead>
    <th>Argument</th>
    <th>Valeur</th>
  </thead>
  <tbody>
    <?php foreach($_GET as $arg => $value): ?>
    <tr>
        <td><?= $arg ?></td>
        <td><?= $value?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>