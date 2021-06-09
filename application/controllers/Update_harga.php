<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update_harga extends CI_Controller {

	var $view 	= "users";
	var $re_log = "auth/login";
	var $folder = "update_harga";
	var $judul  = "Update Harga";
	var $tbl    = "update_harga";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$this->load->library('excel');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if(!isset($id_user)) { redirect($this->re_log); }
	}

  public function index()
	{
		check_permission('page', 'read', 'update_harga');
		$this->_data('update_harga');
	}

	public function _data($tbl='', $folder='')
	{
		if (!table_exists($tbl)){ redirect('404'); }
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		$judul = $this->judul;

		$contentnya = "$this->view/$this->folder/tabel";
		$viewnya    = '';

		$head_tambah = '';
		$data = array(
			'judul_web' => "$judul",
			'content'		=> $contentnya,
			'view'			=> $viewnya,
			'url'				=> "$this->folder",
			'url_modal'	=> base_url("$this->folder/view_data/$tbl"),
			'url_import'=> base_url("$this->folder/import/$tbl"),
			'url_hapus' => base_url("$this->folder/hapus/$tbl"),
			'head_tambah' => $head_tambah,
			'tbl'				=> $tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->view/index", $data);
	}

	public function list_data($tbl='', $id='', $id2='', $id3='')
	{
		if($tbl==''){ exit; }
		$field_id="id_$tbl";
		cekAjaxRequest();
		$field = ", pasar, item_kategori, nama_item_sub, harga_dasar, harga, harga_tawar";
		$this->datatables->select("$field_id as id $field");
		$this->datatables->from($tbl);
		$this->datatables->where('id_pasar', $id2);
		// $this->datatables->where('LEFT(tgl_input, 10)=', tgl_format(urldecode($id), 'Y-m-d'));
		$this->datatables->add_column('id_x','$1','encode(id)');
		// $this->db->order_by('id_update_harga', 'DESC');
		// $this->db->group_by('nama_item_sub');
  	echo $this->datatables->generate();
	}

	// public function list_data($tbl='', $id='', $id2='', $id3='')
	// {
	// 	if($tbl==''){ exit; }
	// 	$field_id="id_$tbl";
	// 	cekAjaxRequest();
	// 	$field = '';
	// 	if(in_array($tbl, array('bc_info'))){ $tbl="v_$tbl"; }
	// 	}elseif($tbl=='update_harga'){
	// 		// $field .= ", CONCAT(nama_item, ' ', nilai_satuan, ' ', get_name_item_satuan(id_item_satuan)) AS nama_item_full, get_name_item_kategori(id_item_kategori) AS item_kategori";
	// 		$field .= "id_update_harga";
	// 	$this->datatables->add_column('id_x','$1','encode(id)');
    //    echo $this->datatables->generate();
	// }

	public function view_data($tbl='', $id_kota='')
  {
		if($tbl==''){ exit; }
		$field_id="id_$tbl"; $id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if($id==''){ $stt=''; }else{ $stt=1; }
			$data['tbl'] 		= $tbl;
			$data['stt']		= $stt;
			$data['id'] 		= $id;
			$data['id_kota'] = $id_kota;
			$data['urlnya'] = base_url("$this->folder/simpan/$tbl");
			$tblnya=$tbl;
			$data['query'] = get_field($tblnya,array($field_id=>"$id"));
			// log_r($this->db->last_query());
			if (post("input")==1) {
				$p = 'form';
			}else {
				$p = 'detail';
			}
			view("$this->view/$this->folder/$p", $data);
    }
  }

// SIMPAN =============================================
  function simpan($tbl='',$id='', $id_kota='')
  { cekAjaxRequest();
		if($tbl==''){ exit; }
    if (isset($_POST)) {
			$this->db->trans_begin();
			$id  = decode($id);
			// log_r(13);
			model('M_update_harga','update_harga', $id);
			exit;
    }
  }
// SIMPAN =============================================

// ========== EXPORT ==========
	public function export()
	{
	  $this->load->library("excel");
	  $object = new PHPExcel();
	  $object->setActiveSheetIndex(0);
		//name the worksheet
		$object->getActiveSheet()->setTitle('UPDATE HARGA');
		// HEADER
		$object->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
	  $table_columns = array("PLU SUB", "NAMA ITEM", "HARGA PASAR TOS 3000", "HARGA JUAL", "HARGA TAWAR");
	  $column = 0;
	  foreach($table_columns as $field){
	    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
	    $column++;
	  }
		// BACKGROUND HEADER
		$object->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
    		array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => '00aaff')
		        )
		    )
		);
		// STYLE
		$styleArray = array(
   		'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FFFFFF'),
        'size'  => 12,
        'name'  => 'Verdana'
  		)
		);
 		$object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);
		$object->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// WIDTH
		foreach(range('A','B') as $columnID) {
			$object->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		$object->getActiveSheet()->getColumnDimension('C')->setWidth(35);
		$object->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$object->getActiveSheet()->getColumnDimension('E')->setWidth(30);

		// BORDER
		// $styleArray = array(
		//  'borders' => array(
		// 		 'allborders' => array(
		// 				 'style' => PHPExcel_Style_Border::BORDER_THIN
		// 				 )
		// 		 )
		// );
		// $object->getDefaultStyle()->applyFromArray($styleArray);

		// DATA
		$this->db->select('plu_sub, nama_item');
		$this->db->order_by('nama_item', 'ASC');
	  $get_data = get('item_master_sub')->result();
	  $excel_row = 2;
	  foreach($get_data as $row){
	    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->plu_sub);
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->nama_item);
			$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, '');
	    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, '');
			$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, '');
	    $excel_row++;
	  }
		$offset = $excel_row-1;
		$object->getActiveSheet()->getStyle("A1:E{$offset}")->applyFromArray(
			array(
            'borders' => array(
                'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN )
            )
      )
	  );

	  $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
	  header('Content-Type: application/vnd.ms-excel');
	  header('Content-Disposition: attachment;filename="Update Harga.xls"');
	  $object_writer->save('php://output');
	}
// ========== EXPORT ==========

// ========== IMPORT ==========
	public function import($tbl='', $v='')
	{
		if (!table_exists($tbl)){ redirect('404'); }
		if (isset($_POST)) {
			$id_user = get_session('id_user');
			$level 	 = get_session('level');
			$namanya = ucwords(preg_replace('/[_]/',' ',$tbl));
			$judul = "Import Data $namanya";
			$p = 'import';
			if ($v!='') { $p .= "/$v"; }
			$data = array(
				'url_import' => base_url("$this->folder/aksi_import/$tbl"),
				'tbl'				 => $tbl,
			);
			$this->load->view("$this->view/$this->folder/$p", $data);
		}
	}

	function aksi_import($tbl='', $aksi='') {
		ini_set('max_execution_time', '0');
		$id_user = get_session('id_user');
		$nama_input = "$id_user - ".user('nama_lengkap');
		$tgl_input = tgl_now();
		if (!table_exists($tbl)){ redirect('404'); }
		$stt=0; $ket=''; $pesan='';
		if (isset($_FILES["file"]["name"])) {
			$this->db->trans_begin();
      $path = $_FILES["file"]["tmp_name"];
      $object = PHPExcel_IOFactory::load($path);
			$data=array(); $data1=array(); $set_blm_ada=array(); $i=0; $i1=0;
			foreach($object->getWorksheetIterator() as $worksheet) {
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        for ($row=2; $row<=$highestRow; $row++) {
					 $plu_sub     = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					 $nama_item   = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					 $harga_dasar = (int)$worksheet->getCellByColumnAndRow(2, $row)->getValue();
					 $harga  		  = (int)$worksheet->getCellByColumnAndRow(3, $row)->getValue();
					 $harga_tawar = (int)$worksheet->getCellByColumnAndRow(4, $row)->getValue();
					 // cek dan abaikan harga 0 atau kosong

					 if (in_array($harga, array('', 0, null))) { continue; }
					 if (in_array($harga_dasar, array('', null))) { $harga_dasar=0; }

					 $this->db->select('id_item_master_sub, id_item_kategori, item_kategori, plu_sub, nama_item');
			     $cek_data = get_field('item_master_sub', array('plu_sub'=>$plu_sub));
			     if (empty($cek_data)) {
						 $ket="<br /><label>PLU SUB : $plu_sub, NAMA ITEM : $nama_item</label>";
						 $set_blm_ada[] = $ket;
					 }else{
						 $id_provinsi = post('id_provinsi');
						 $id_kota 		= post('id_kota');
						 $id_pasar 		= post('id_pasar');
						 $provinsi 		= get_name_provinsi($id_provinsi);
						 $kota 				= get_name_kota($id_kota);
						 $pasar 			= get_name_pasar($id_pasar);
						 $harga_beli  = $harga - ($harga*(10/100));
						 $gap  				= $harga - $harga_beli;

						 $this->db->select('id_update_harga');
						 $cek_UH = get_field($tbl, array('plu_sub'=>$plu_sub));
						 if (empty($cek_UH)) {
							 $data[$i] = array(
  							 'id_provinsi'    => $id_provinsi,
  							 'provinsi' 	    => $provinsi,
  							 'id_kota' 		    => $id_kota,
  							 'kota' 			    => $kota,
  							 'id_pasar' 	    => $id_pasar,
  							 'pasar' 			    => $pasar,
  							 'id_item_kategori'   => $cek_data['id_item_kategori'],
  							 'item_kategori'      => $cek_data['item_kategori'],
  							 'id_item_master_sub' => $cek_data['id_item_master_sub'],
  							 'plu_sub'		    => $plu_sub,
  							 'nama_item_sub'  => $cek_data['nama_item'],
  							 'harga_dasar'		=> $harga_dasar,
  							 'harga'					=> $harga,
  							 'harga_tawar'		=> $harga_tawar,
  							 'harga_beli'			=> $harga_beli,
  							 'gap'						=> $gap,
  							 'tgl_input'			=> $tgl_input,
  							 'input_by'				=> $nama_input
  						);
							$i++;
						} else {
							$data1[$i1] = array(
								 'id_update_harga' => $cek_UH['id_update_harga'],
	 							 'id_provinsi'    => $id_provinsi,
	 							 'provinsi' 	    => $provinsi,
	 							 'id_kota' 		    => $id_kota,
	 							 'kota' 			    => $kota,
	 							 'id_pasar' 	    => $id_pasar,
	 							 'pasar' 			    => $pasar,
	 							 'id_item_kategori'   => $cek_data['id_item_kategori'],
	 							 'item_kategori'      => $cek_data['item_kategori'],
	 							 'id_item_master_sub' => $cek_data['id_item_master_sub'],
	 							 'plu_sub'		    => $plu_sub,
	 							 'nama_item_sub'  => $cek_data['nama_item'],
	 							 'harga_dasar'		=> $harga_dasar,
	 							 'harga'					=> $harga,
	 							 'harga_tawar'		=> $harga_tawar,
	 							 'harga_beli'			=> $harga_beli,
	 							 'gap'						=> $gap,
	 							 'tgl_input'			=> $tgl_input,
	 							 'input_by'				=> $nama_input
	 						);
							$i1++;
						}

					 }
        }
      }

			$simpan=false;
			if (empty($data) && empty($data1)) {
				$pesan='Data Kosong atau Data Sudah ada di Database!';
			}else {
				if (!empty($data)) {
					$simpan = add_batch('update_harga', $data);
					if ($simpan) {
						$simpan = add_batch('update_harga_history', $data);
					}
				}
				if (!empty($data1)) {
					$simpan = update_batch('update_harga', $data1, 'id_update_harga');
					if ($simpan) {
						$data2=array();
						foreach ($data1 as $key => $value) {
						    unset($value['id_update_harga']);
								$data2[] = $value;
						}
						if (!empty($data2)) {
							$simpan = add_batch('update_harga_history', $data2);
						}
					}
				}
			}

			if ($simpan) {
				$simpan = export_pdf_harga_pasar();
			}

			if ($simpan) {
				$this->db->trans_commit();
				$stt=1;
			}else{
				$this->db->trans_rollback();
				$pesan='Gagal Simpan!';
			}
    }else {
    	$pesan = 'File Tidak Valid!';
    }
		if (!empty($set_blm_ada)) {
			$set_ls = "<hr />Tidak tersimpan: <br />";
			foreach (array_unique($set_blm_ada) as $key => $value) {
				if ($value!='') {
					$set_ls .= " <b style='color:red;'>$value</b>, ";
				}
			}
			$pesan .= substr($set_ls,0,-2);
		}
		echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
		exit;
  }
// ========== IMPORT ==========

}
