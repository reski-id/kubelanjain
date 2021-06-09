<?php
$judul_web = web('nama_perusahaan');
$website   = web('website');
$logo      = web('favicon');
$path = FCPATH;
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
  @font-face {
    font-family: cp;
	  font-weight: normal;
	  font-style: normal;
	  font-variant: normal;
    src: url(<?= $path; ?>assets/fonts/Chicken_Pie/CHICKEN_Pie.ttf);
  }
  .judul {
    font-family: cp;
    font-size: 30px;
		width:100%;
		color:#589143
  }
	@page { margin:10px; }
	body { margin:10px; }
  </style>
</head>
<body>

	<table width="100%" border="0">
		<tr>
			<td width="60" style="vertical-align:top;padding-top:10px;">
				<center>
					<img src="<?= $path.$logo; ?>" width="60" hight="50">
				</center>
			</td>
			<td style="vertical-align:top; color:#589143;">
				<div class="text-center" style="margin-left:-60px;">
					<img src="<?= $path.'img/BKYB.png'; ?>">
				</div>
				<div class="text-center" style="margin-left:-60px;padding-left:50px;padding-right:50px;">
					Belanja murah, hemat, praktis dan produk selalu fresh langsung dari pasar tradisional Batam <br />
					Harga selalu update setiap hari mengikuti harga pasar tradisional kota batam <br />
					Silahkan pesan melalui WA/SMS/HP : 0812-7645-3383 atau 0812-7645-3410 <br />
					Jam operasional order : 08.00 wiib - 21.30 wib (setiap hari) -------> FREE ONGKIR Min Order 200k
				</div>
			</td>
		</tr>
	</table>
  <hr>
  <div class="text-center" style="padding-top:10px">
    <b>List Harga</b><br>
		<div style="font-size:12px"><?= hari_id(tgl_now('tgl')).', '.tgl_id(tgl_format(tgl_now('tgl'),'d-m-Y')); ?></div>
  </div>
  <br>

<?php $logo=web('logo');
		$bagi=4; $i=1; $n=1; $kat='';
		$this->db->select('nama_item_sub as nama_item, item_kategori, harga, plu_sub');
		$this->db->where('harga >', 0);
		$this->db->like('nama_item_sub', 'GRAM');
		$this->db->order_by("id_item_kategori",'ASC');
		$this->db->order_by("nama_item_sub",'ASC');
		// $this->db->limit(4);
		$query = get('update_harga');
		foreach ($query->result() as $key => $value):
			$kategori = $value->item_kategori;
			if ($kategori!=$kat) {
				 $i=1; $n=1;
				echo '<table width="100%" border="0" style="page-break-after:always;">';
				echo "<tr><td colspan='4' class='text-center'> <b style='font-size:20px;'>$kategori</b> <hr style='border-top: 1px dashed #222;'/> </td></tr>";
			}
			$kat = $kategori;
			$size_W = '180px';
			$size_H = '130px';
			$plu = substr($value->plu_sub, 0, 7);
			$this->db->select('foto_item_sub');
			$gambar = get_field('item_master_sub', array('plu_sub'=>$value->plu_sub))['foto_item_sub'];
			if (!file_exists($gambar)) {
				$this->db->select('foto_item');
				$gambar = get_field('item_master', array('plu'=>$plu))['foto_item'];
				if (!file_exists($gambar)) {
					$size_W = '100px';
					$gambar = $logo;
				}
			}
			$judul  = $value->nama_item;
			?>
			<?php if ($i==1){ echo '<tr>'; } ?>
				<td style="vertical-align:top">
					<center>
						<img src="<?= $path.$gambar; ?>" alt="" width="<?= $size_W; ?>" height="<?= $size_H; ?>"><br>
						<div style="padding-top:10px;padding-bottom:5px;"><b><?= $judul; ?></b></div>
						<?= format_angka($value->harga, 'rp'); ?>
					</center>
				</td>
			<?php if ($i==$bagi){ $i=1; ?>
				</tr>
				<tr>
					<td colspan="<?= $bagi; ?>"><br /></td>
				</tr>
			<?php }else{ $i++; } $n++; ?>
			<?php if ($kategori!=$kat) : ?>
			</table>
			<?php endif; ?>
		<?php endforeach; ?>
</body>
</html>
