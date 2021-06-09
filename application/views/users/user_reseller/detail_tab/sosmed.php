<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php foreach (get_sosmed() as $key => $value): ?>
      <tr>
        <th width="120"><i class="bx <?= $value['icon']; ?>"></i> <?= $value['nama']; ?></th>
        <th width="1">:</th>
        <td> <?= get_user_sosmed($value['nama'], $query['id_user']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
