<table id="fileData" class="table table-striped table-bordered" width="100%">
  <thead class="thead-dark">
    <tr>
      <th class="text-center" width="1%">#</th>
      <th class="text-center" style="padding: 1.3rem 2rem !important;padding-left:10px !important;" width="47%">Nama&nbsp;Item</th>
      <th class="text-center" width="10%">Harga</th>
      <th class="text-center" width="7%">QTY</th>
      <th class="text-center" width="17%">TOTAL</th>
      <th class="text-center" width="20%">NOTE</th>
    </tr>
  </thead>
  <tbody>
    <?php $this->db->order_by('nama_item', 'ASC');
    foreach (get('order_item', array('id_order'=>$query['id_order']))->result() as $key => $value): ?>
      <tr>
        <td><label class="float-left"><?= $key+1; ?></label></td>
        <td><label><?= $value->nama_item; ?></label></td>
        <td><label class="float-right"><?= format_angka($value->harga); ?></label></td>
        <td><label class="float-right"><?= number_format($value->qty, 2, ",", "."); ?></label></td>
        <td><label class="float-right"><?= format_angka($value->total); ?></label></td>
        <td><label><?= $value->note; ?></label></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th style="padding: 10px;" class="align-middle" colspan="4" width="49%">
        <label class="float-right">Subtotal</label>
      </th>
      <th style="padding: 10px;" class="text-right">
        <label id="subtotal" style="font-size: 16px !important;"><?= format_angka($query['sub_total']); ?></label>
      </th>
      <th style="padding: 10px;" class="text-center"></th>
    </tr>
    <tr>
      <th style="padding: 10px;" class="text-right" colspan="4" width="49%">
        <label>Ongkir</label>
      </th>
      <th style="padding: 10px;" class="text-right">
        <label style="font-size: 16px !important;"><?= format_angka($query['ongkir']); ?></label>
      </th>
      <th style="padding: 10px;" class="text-center"></th>
    </tr>
    <tr>
      <th style="padding: 10px;" class="text-right" colspan="4" width="49%">
        <label>Diskon</label>
      </th>
      <th style="padding: 10px;" class="text-right">
        <label style="font-size: 16px !important;"><?= format_angka($query['diskon']); ?></label>
      </th>
      <th style="padding: 10px;" class="text-center"></th>
    </tr>
    <tr>
      <th style="padding: 10px;" class="text-right" colspan="4" width="49%">
        <label>Total</label>
      </th>
      <th style="padding: 10px;" class="text-right">
        <label id="total_all" style="font-size: 16px !important;"><?= format_angka($query['total_belanja']); ?></label>
      </th>
      <th style="padding: 10px;" class="text-center"></th>
    </tr>
  </tfoot>
</table>

<label><b>Catatan : </b><?= $query['catatan']; ?></label>
