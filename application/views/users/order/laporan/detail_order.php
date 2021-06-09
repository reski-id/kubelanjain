<?php $judul_web=web('nama_perusahaan'); ?>
<html>
<head>
	<title><?= $judul_web; ?></title>
	<meta property="og:site_name" content="<?= $judul_web; ?>">
	<meta property="og:title" content="<?= $judul_web; ?>" />
	<meta property="og:description" content="<?= $judul_web; ?>" />
	<meta property="og:type" content="website" />
  <style>
  table {
    font-size: 12px;
  }
  #tabel {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }

  #tabel td, #tabel th {
    border: 1px solid #ddd;
    padding: 8px;
  }

  #tabel tr:nth-child(even){background-color: #f2f2f2;}

  /* #tabel tr:hover {background-color: #ddd;} */

  #tabel th {
    padding-top: 7px;
    padding-bottom: 7px;
    text-align: left;
  }
  #tabel td, #tabel td > label, label {
		font-size: 16px !important;
  }

  .text-right {
    text-align: right !important;
  }
  .text-left {
    text-align: left !important;
  }
  .text-center {
    text-align: center !important;
  }
  .float-right {
    float:right !important;
  }
  .float-left {
    float:left !important;
  }
  .font-head {
    padding-top: 7px !important;
    padding-bottom: 7px !important;
    font-size: 16px !important;
  }
  small {
    font-size:10px !important;
  }
  .small-12 {
    font-size:12px !important;
  }
	thead > tr > th{
		background-color: #4CAF50 !important;
		color:#fff !important;
	}
  </style>
</head>
<body>
  <?php $get = get_field('order', array('id_order'=>$id_order));
  $tgl1 = $get['tgl_input'];
  $tgl2 = $get['tgl_pengantaran'];
  $get_alamat = get_field('pelanggan', array('id_pelanggan'=>$get['id_pelanggan']))['alamat'];

  $tgl_input       = hari_id($tgl1).', '.tgl_id($tgl1, 'd-m-Y'). ' ' .tgl_format($tgl1, 'H:i');
  $tgl_pengantaran = hari_id($tgl2).', '.tgl_id($tgl2, 'd-m-Y'). ' ' .tgl_format($tgl2, 'H:i');
  $no_transaksi    = $get['no_transaksi'];
  $kota            = get_name_kota($get['id_kota']);
  // $nama_pelanggan  = $get['nama_pelanggan'];
  $alamat          = $get_alamat;
  // $no_hp           = $get['no_hp'];
  // $id_pasar        = get_name_pasar($get['id_pasar']);
  // $catatan         = $get['catatan'];
  $type_pembayaran = get_type_pembayaran($get['type_pembayaran']);
  if ($type_pembayaran=='') { $type_pembayaran = '-'; }
  ?>
  <img src="<?= FCPATH.web('favicon'); ?>" class="float-left" width="45" hight="50" style="padding-right:8px;">
  <div style="margin-bottom:5px;margin-top:5px;width:100%">
    <!-- <b>DETAIL ORDER</b> <br> -->
    <b><?= web('nama_perusahaan'); ?></b>
    <div class="float-right small-12">
      <b>Call Admin : <?= web('no_hp'); ?></b> <br><br>
      No.&nbsp;Order&nbsp;&nbsp;&nbsp; : <?= $no_transaksi; ?> <br>
      Pembayaran : <?= $type_pembayaran; ?>
    </div> <br>
    <div class="small-12" style="margin-left:50px;">
      <table>
        <tr>
          <td class="text-left">Tgl&nbsp;Order</td>
          <th width="1%">:</th>
          <td><?= $tgl_input; ?></td>
        </tr>
        <tr>
          <td class="text-left">Tgl&nbsp;Pengantaran</td>
          <th>:</th>
          <td><?= $tgl_pengantaran; ?></td>
        </tr>
      </table>
    </div>
  </div>
  <hr>
  <!-- <center>
    <h3>DETAIL PELANGGAN</h3>
  </center> -->
  <table width="100%">
    <?php
    $tabel[] = array('nama'=>'Nama&nbsp;Pelanggan', 'val'=>'nama_pelanggan');
    $tabel[] = array('nama'=>'No.&nbsp;HP', 'val'=>'no_hp');
    $tabel[] = array('nama'=>'Alamat', 'val'=>'alamat');
    // $tabel[] = array('nama'=>'Pasar', 'val'=>'id_pasar');
    // $tabel[] = array('nama'=>'Tanggal&nbsp;Pengantaran', 'val'=>'tgl_pengantaran');
    $tabel[] = array('nama'=>'Catatan', 'val'=>'catatan');
    // $tabel[] = array('nama'=>'Type&nbsp;Pembayaran', 'val'=>'type_pembayaran');
    foreach ($tabel as $key => $value):
      $val = $value['val']; ?>
      <tr>
        <th class="text-left" width="15%"><?= $value['nama']; ?></th>
        <th class="text-left" width="1%">:</th>
        <td class="text-left" width="84%">
          <?php
          $v = $get[$val];
          if (in_array($val, array('tgl_pengantaran'))) {
            $v = $tgl_pengantaran;
          }elseif ($val=='id_pasar') {
            $v = get_name_pasar($v);
          }elseif ($val=='alamat') {
            $v = $alamat;
          }elseif ($val=='type_pembayaran') {
            $v = get_type_pembayaran($val);
            if ($v=='') { $v = '-'; }
          }
          echo $v;
          ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <!-- <center>
    <h3>DETAIL ITEM</h3>
  </center> -->

  <table id="tabel" class="table table-striped table-bordered" width="100%" style="margin-top:10px;">
    <thead>
      <tr>
        <th class="text-center font-head" width="65%">NAMA&nbsp;ITEM</th>
        <th class="text-center font-head" width="12%">HARGA&nbsp;<small>(Rp)</small></th>
        <th class="text-center font-head" width="7%">QTY</th>
        <th class="text-center font-head" width="17%">TOTAL&nbsp;<small>(Rp)</small></th>
      </tr>
    </thead>
    <tbody>
      <?php $this->db->order_by('nama_item', 'ASC');
      foreach (get('order_item', array('id_order'=>$id_order))->result() as $key => $value): ?>
        <tr>
          <td><label><?= $value->nama_item; ?></label></td>
          <td><label class="float-right"><?= format_angka($value->harga); ?></label></td>
          <td><label class="float-right"><?= format_angka($value->qty); ?></label></td>
          <td><label class="float-right"><?= format_angka($value->total); ?></label></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="padding: 7px;" class="text-right" colspan="3" width="49%">
          <label class="float-right">Subtotal</label>
        </th>
        <th style="padding: 7px;" class="text-right">
          <label id="subtotal" style="font-size: 16px !important;"><?= format_angka($get['sub_total']); ?></label>
        </th>
      </tr>
      <tr>
        <th style="padding: 7px;" class="text-right" colspan="3" width="49%">
          <label>Ongkir</label>
        </th>
        <th style="padding: 7px;" class="text-right">
          <label style="font-size: 16px !important;"><?php if (in_array($get['ongkir'], array('',0,null))) { echo "Free"; }else{ echo format_angka($get['ongkir']); } ?></label>
        </th>
      </tr>
    <?php if (!in_array($get['diskon'], array('',0,null))): ?>
      <tr>
        <th style="padding: 7px;" class="text-right" colspan="3" width="49%">
          <label>Diskon</label>
        </th>
        <th style="padding: 7px;" class="text-right">
          <label style="font-size: 16px !important;"><?= format_angka($get['diskon']); ?></label>
        </th>
      </tr>
    <?php endif; ?>
      <tr>
        <th style="padding: 7px;" class="text-right" colspan="3" width="49%">
          <label>Total</label>
        </th>
        <th style="padding: 7px;" class="text-right">
          <label id="total_all" style="font-size: 16px !important;"><?= format_angka($get['total_belanja']); ?></label>
        </th>
      </tr>
    </tfoot>
  </table>

  <?php if (strtolower($type_pembayaran)=='transfer'): ?>
    <br>
    <b>Notes:</b><br>
    Pembayaran Transfer ke <b>Bank MANDIRI</b> <br>
    <b>
      &nbsp;A/n &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ZULFAN EFFENDI <br>
      &nbsp;No.Rek : 1090-0657-1777-9
    </b>
  <?php endif; ?>


</body>
</html>
