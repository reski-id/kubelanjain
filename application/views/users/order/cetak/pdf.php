<?php
$judul_web = 'BKYB';
$website   = web('website');
$logo      = 'img/BKYB_cetak.png';
$path = base_url();//FCPATH;
?>
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
	@page {
    width:100%;
    margin-left:4px;
    margin-right:20px;
    font-size: 12px;
    /* size: 180pt 400pt; */
  }
	/* body { width:100%; margin:5px; font-size: 12px;
    size: 180pt 400pt;
  } */
  </style>
</head>
<body>

  <center>
    <img src="<?= $path.$logo; ?>" width="60" hight="50"> <br>
    <small><?= $website; ?></small>
  </center>
  <br>
  <?php
  $no_transaksi    = $get['no_transaksi'];
  $nama_pelanggan  = $get['nama_pelanggan'];
  $no_hp           = $get['no_hp'];
  $tgl_pengantaran = waktu($get['tgl_pengantaran']);
  $catatan         = $get['catatan'];
                     $this->db->select('alamat');
  $alamat          = get_field('pelanggan', array('id_pelanggan'=>$get['id_pelanggan']))['alamat'];

  for ($i=1; $i <=64; $i++) {
    $garis1 .= '-';
  }
  // for ($i=1; $i <=60; $i++) {
  //   $garis2 .= '=';
  // }
  $body_arr[] = array('nama'=>'No.', 'value'=>$no_transaksi);
  $body_arr[] = array('nama'=>'Nama', 'value'=>$nama_pelanggan);
  $body_arr[] = array('nama'=>'No.HP', 'value'=>$no_hp);
  $body_arr[] = array('nama'=>'Pengantaran', 'value'=>$tgl_pengantaran);
  $body_arr[] = array('nama'=>'Catatan', 'value'=>$catatan);
  $body_arr[] = array('nama'=>'Alamat', 'value'=>$alamat);
  ?>
  <table width="100%">
    <?php foreach ($body_arr as $key => $value): ?>
      <tr>
        <td width="60" style="vertical-align:top"><?= $value['nama']; ?></td>
        <td width="1" style="vertical-align:top">:</td>
        <td width="300"><?= $value['value']; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <br>

  <table width="100%" border="0">
    <tr>
      <td colspan="3"><?= $garis1; ?></td>
    </tr>
    <tr>
      <th class="text-left" colspan="2">Item</th>
      <th class="text-right">Total&nbsp;(Rp)</th>
    </tr>
    <tr>
      <td colspan="3"><?= $garis1; ?></td>
    </tr>
    <?php
    $this->db->select('nama_item, qty, harga');
    $this->db->order_by('nama_item', 'ASC');
    $get_item = get('order_item', array("id_order"=>$get['id_order']))->result();
    foreach ($get_item as $key => $value):
      $qty   = explode('.', $value->qty);
      if ($qty[1]=='00') {
        $qty = $qty[0];
      }else {
        $qty = format_angka($qty[0]).','.$qty[1];
      }
      $harga = $value->harga;
      $total = $qty * $harga; ?>
      <tr>
        <td width="100%" style="vertical-align:top" colspan="3"><?= $value->nama_item; ?></td>
      </tr>
      <tr>
        <td width="70%" style="vertical-align:top" colspan="2"><?= "&nbsp;&nbsp;$qty x ".format_angka($harga, 'rp'); ?></td>
        <td width="30%" style="padding-right:5px"><span class="float-right"><?= format_angka($total); ?></span></td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="3"><?= $garis1; ?></td>
    </tr>
    <?php
    $total_arr[] = array('nama'=>'Subtotal', 'value'=>format_angka($get['sub_total']));
    $total_arr[] = array('nama'=>'Ongkir', 'value'=>format_angka($get['ongkir']));
    $total_arr[] = array('nama'=>'Diskon', 'value'=>format_angka($get['diskon']));
    $total_arr[] = array('nama'=>'Total', 'value'=>format_angka($get['total_belanja']));
    ?>
    <?php foreach ($total_arr as $key => $value):
      if ($value['nama']=='Ongkir' && $value['value']==0) { $value['value']='Gratis'; }
      ?>
      <tr>
        <td width="60%" style="vertical-align:top" class="text-right"><?= $value['nama']; ?></td>
        <td width="10%" style="vertical-align:top">:&nbsp;Rp.</td>
        <td width="30%" class="text-right"><?= $value['value']; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <br><br>
  <center>
    Terimakasih telah berbelanja di BKYB <br />
    <?= waktu(); ?>
  </center>

</body>
<script type="text/javascript">
  window.print();
  setTimeout(function(){
    window.close();
  }, 5000);
</script>
</html>
