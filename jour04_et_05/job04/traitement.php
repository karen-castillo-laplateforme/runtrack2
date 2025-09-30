<table>
  <thead>
    <th>Argument</th>
    <th>Valeur</th>
  </thead>
  <tbody>
    <?php foreach($_POST as $arg => $value): ?>
    <tr>
        <td><?= $arg ?></td>
        <td><?= $value?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>