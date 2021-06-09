<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ajax extends CI_Model
{

  public function get_bank($platform='')
  {
    if (isset($_POST)) {
			$data_nya = array();
			$this->db->select('id_bank, bank');
			$this->db->order_by('bank', 'ASC');
			foreach (get('bank', array('status'=>1))->result() as $key => $value) {
				$data_nya[] = array('id'=>$value->id_bank, 'nama'=>$value->bank);
			}
      if ($platform=='mobile') {
        echo json_encode($data_nya);
      }else {
        echo '{"plus":' . json_encode($data_nya) . '}';
      }
		}
  }

  public function get_prov($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_provinsi, provinsi');
			$this->db->order_by('provinsi', 'ASC');
			foreach (get('provinsi', array('status'=>1))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_provinsi, 'nama'=>$value->provinsi);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }

  public function get_sales($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_sales, sales');
			$this->db->order_by('sales', 'ASC');
			foreach (get('sales', array('status'=>1))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_sales, 'nama'=>$value->sales);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }

  public function get_pelanggan($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_pelanggan, pelanggan, nohp_satu, nohp_dua, alamat, id_provinsi, id_kota, id_kecamatan, id_jekel');
			$this->db->order_by('pelanggan', 'ASC');
			foreach (get('pelanggan', array('status'=>1))->result() as $key => $value) {
        $jk = get_name_jekel($value->id_jekel);
				$data_wil[] = array('id'=>$value->id_pelanggan, 'nama'=>$jk.' '.$value->pelanggan,
        'hp1'=>$value->nohp_satu,'hp2'=>$value->nohp_dua,'alamat'=>$value->alamat,
        'provinsi'=>$value->id_provinsi, 'kota'=>$value->id_kota, 'kecamatan'=>$value->id_kecamatan);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }

  public function get_detail_pelanggan($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_pelanggan, pelanggan, nohp_satu, nohp_dua, alamat, id_provinsi, get_name_provinsi(id_provinsi) AS provinsi, id_kota, get_name_kota(id_kota) AS kota, id_kecamatan, get_name_kecamatan(id_kecamatan) AS kecamatan, id_pasar');
			$this->db->order_by('pelanggan', 'ASC');
			$data_wil = get_field('pelanggan', array('status'=>1, 'id_pelanggan'=>post('p')));
      echo json_encode($data_wil);
    }
  }

  public function get_item_master($platform='', $toko = '')
  {
    if (isset($_POST)) {
			$data_wil = array();
        $this->db->select("A.id_item_master AS id, concat(A.nama_item,' - ', B.nama) AS nama");
        $this->db->join('item_kategori AS B', 'A.id_item_kategori=B.id_item_kategori');
        foreach (get('item_master AS A', array('A.status'=>1))->result() as $key => $value) {
  				$data_wil[] = array('id'=>$value->id, 'nama'=>$value->nama);
  			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }

  public function get_plu_sub()
  {
    if (isset($_POST)) {
      $plu = '';
      if (!empty(post('p'))) {
        $this->db->select('plu');
        $this->db->where('id_item_master', post('p'));
        $plu .= get_field('item_master')['plu'];
      }
      if (!empty(post('p2'))) {
        $this->db->select('kode');
        $this->db->where('id_item_satuan', post('p2'));
        $plu .= get_field('item_satuan')['kode'];
      }
      echo json_encode(array('plu_sub'=>$plu));
		}
  }

  //fungsi baru pemecahan tabel item master menjadi 2tbl masih di localhost
	// pwd :: master/item_master_sub
  public function get_item_master2($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();

      $this->db->select("A.id_item_master2 AS id, concat(B.nama,' ',A.nama_item) AS nama");
      $this->db->join('item_kategori AS B', 'B.id_item_kategori = A.id_item_kategori');

			$this->db->order_by('A.nama_item', 'ASC');
			foreach (get('item_master2 AS A', array('A.status'=>1))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id, 'nama'=>$value->nama);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }


  public function get_type($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_type, type');
			$this->db->order_by('type', 'ASC');
			foreach (get('type', array('status'=>1))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_type, 'nama'=>$value->type);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }

  public function get_sub_item($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_item_master_sub, nama_item');
      $this->db->group_by('nama_item');
			$this->db->order_by('nama_item', 'ASC');
			foreach (get('item_master_sub', array('status'=>post('status')))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_item_master_sub, 'nama'=>$value->nama_item);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }



  public function get_jekel($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_jekel, jekel');
			$this->db->order_by('jekel', 'ASC');
			foreach (get('jekel', array('status'=>1))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_jekel, 'nama'=>$value->jekel);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }


  public function get_kota($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
      $this->db->select('id_kota, kota');
      if (!empty(post('p'))) {
      	$this->db->where('id_provinsi', post('p'));
      }
			$this->db->order_by('kota', 'ASC');
			foreach (get('kota', array('status'=>1))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_kota, 'nama'=>$value->kota);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }

  public function get_kec($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_kecamatan, kecamatan');
			$this->db->order_by('kecamatan', 'ASC');
			foreach (get('kecamatan', array('id_kota'=>post('p'), 'status'=>1))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_kecamatan, 'nama'=>$value->kecamatan);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }


  public function get_kel($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_kelurahan, kelurahan');
			$this->db->order_by('kelurahan', 'ASC');
			foreach (get('kelurahan', array('id_kota'=>post('p'), 'id_kecamatan'=>post('kec')))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_kelurahan, 'nama'=>$value->kelurahan);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }

  public function get_wilayah($platform='')
  {
    if (isset($_POST)) {
			$data_wil = array();
			$this->db->select('id_wilayah, wilayah');
			$this->db->order_by('wilayah', 'ASC');
			foreach (get('wilayah', array('id_kota'=>post('p')))->result() as $key => $value) {
				$data_wil[] = array('id'=>$value->id_wilayah, 'nama'=>$value->wilayah);
			}
      if ($platform=='mobile') {
        echo json_encode($data_wil);
      }else {
        echo '{"plus":' . json_encode($data_wil) . '}';
      }
		}
  }

  public function get_pasar($platform='')
  {
    if (isset($_POST)) {
			$data = array();
			$this->db->select('A.id_pasar, A.pasar');
      $this->db->join('pasar_kecamatan AS B', 'A.id_pasar=B.id_pasar');
      $this->db->order_by('A.pasar', 'ASC');
      if(!empty(post('status'))){
        $this->db->where('A.status', post('status'));
        $this->db->where('B.status', post('status'));
      }
      // $this->db->group_by('B.id_kecamatan');
      if (!empty(post('p'))) {
        $this->db->where('A.id_kota', post('p'));
      }
      if(!empty(post('p2'))){
        $this->db->where('B.id_kecamatan', post('p2'));
      }else {
        $this->db->group_by('B.id_pasar');
      }
			foreach (get('pasar AS A')->result() as $key => $value) {
				$data[] = array('id'=>$value->id_pasar, 'nama'=>$value->pasar);
			}
      if ($platform=='mobile') {
        echo json_encode($data);
      }else {
        echo '{"plus":' . json_encode($data) . '}';
      }
		}
  }

  public function get_item_kategori($platform='')
  {
    if (isset($_POST)) {
			$data = array();
			$this->db->select('id_item_kategori, nama');
      if (!empty(post('status'))) {
        $this->db->where('status', post('status'));
      }
			$this->db->order_by('nama', 'ASC');
			foreach (get('item_kategori')->result() as $key => $value) {
				$data[] = array('id'=>$value->id_item_kategori, 'nama'=>$value->nama);
			}
      if ($platform=='mobile') {
        echo json_encode($data);
      }else {
        echo '{"plus":' . json_encode($data) . '}';
      }
		}
  }


  public function get_plu()
  {
    if (isset($_POST)) {
			$data = array();
			$this->db->select('A.plu, B.nama_item');
      $this->db->join('item_master AS B', 'A.id_item_master=B.id_item_master');
      if (!empty(post('p'))) {
        $this->db->where('A.id_gudang_kota', post('p'));
      }
      if (!empty(post('p2'))) {
        $this->db->where('A.id_gudang', post('p2'));
      }
      if (!empty(post('plu'))) {
        $this->db->like('A.plu', post('plu'), 'after');
      }
      $this->db->order_by('A.plu', 'ASC');
			$this->db->order_by('B.nama_item', 'ASC');
      $this->db->group_by('A.plu');
			foreach (get('item_gudang AS A')->result() as $key => $value) {
				$data[] = array('id'=>$value->plu, 'nama'=>$value->nama_item);
			}
      echo '{"plus":' . json_encode($data) . '}';
		}
  }

  public function get_user_approval_tipe($stt='')
  {
    if (isset($_POST)) {
      $json = array();
      if ($stt=='cek_select') {
        $sel_data = array_unique(json_decode(html_entity_decode($this->input->post('sel'))));
      }else{
        $sel_data = array();
      }
      $cari = post('cari');
      $this->db->select('A.id_user, B.foto, A.jenis_akses, A.nama_lengkap, get_name_gudang(A.id_gudang) AS nama_gudang, get_name_management_akses(A.id_management_akses) AS nama_akses');
      $this->db->join('user AS B', 'A.id_user=B.id_user');
      $this->db->where("(A.nama_lengkap LIKE '%".$cari."%' OR get_name_gudang(A.id_gudang) LIKE '%".$cari."%' OR get_name_management_akses(A.id_management_akses) LIKE '%".$cari."%')",null,false);
      if (!empty($sel_data)) {
        $this->db->where_not_in('A.id_user', $sel_data);
      }
      $this->db->order_by('A.nama_lengkap', 'ASC');
      $this->db->order_by('get_name_gudang(A.id_gudang)', 'ASC');
      $this->db->order_by('get_name_management_akses(A.id_management_akses)', 'ASC');
      $get = get('user_biodata_management AS A', array('B.level'=>0, 'B.status'=>'1'));
			foreach ($get->result() as $key => $value) {
        $nama = get_name_akses_user_approval($value->jenis_akses, $value->nama_gudang, $value->nama_akses);
        // $json[] = ['id'=>$value->id_user, 'text'=>"$value->nama_lengkap [ $nama ]"];
        $foto = $value->foto;
        if (!file_exists($foto)) { $foto='img/user-null.jpg'; }
        $json[] = ['id'=>$value->id_user, 'text'=>"$value->nama_lengkap [ $nama ]", 'img_url'=>$foto, 'nama'=>$value->nama_lengkap, 'akses'=>$nama];
      }
      echo json_encode($json);
      exit;
    }
  }

  public function get_list_user_approval()
  {
    if (isset($_POST)) {
			$datanya=array(); $usernya=array(); $id_user=array();
      $get = get_list_approval('form', post('p'), post('p2'));
      // log_r($get);
      if (!empty($get)) {
        $usernya = $get['get_user'];
        foreach ($get['get_list']->result() as $key => $value) {
          $nama = get_name_akses_user_approval($value->jenis_akses, $value->nama_gudang, $value->nama_akses);
          $datanya[] = array('id'=>$value->id_user_approval, 'text'=>"$value->nama_lengkap [ $nama ]");
        }
      }
      echo '{"plus":' . json_encode($datanya) . ', "user":' . json_encode($usernya) . '}';
		}
  }


  public function get_item_stockopname($id='')
  {
    if (isset($_POST)) {
			$data = array();
      $id = decode($id);
      if ($id=='') {
        $this->db->select('A.plu, A.nama_item, C.kemasan AS satuan, C.hrg_jual AS harga_beli');
        $this->db->join('item_vendor AS C', 'A.id_item_master=C.id_item_master');
        // if (!empty(post('p'))) {
        //   $this->db->where('A.id_gudang', post('p'));
        // }
        $this->db->where('A.status', 1);
        $this->db->where('C.status', 1);
        $this->db->order_by('A.plu', 'ASC');
  			$this->db->order_by('A.nama_item', 'ASC');
        $this->db->group_by('A.plu');
  			$get_data = get('item_master AS A')->result_array();
        foreach ($get_data as $key => $value) {
          // $this->db->select('qty');
          // if (!empty(post('p'))) {
          //   $this->db->where('id_gudang', post('p'));
          // }
          // $this->db->group_by('plu');
          // $get_qty = get('item_stock', array('plu'=>$value['plu']))->row();
          // if (empty($get_qty)) { $qty=0; }else { $qty=$get_qty->qty; }
          if (!empty(post('p'))) {
            $sisa_stock = call_cek_stock(post('p'), $value['plu']);
          }else {
            $sisa_stock = 0;
          }
          $value['qty'] = $sisa_stock;
          $data[] = $value;
        }
      }else {
        $this->db->select('plu, nama_item, nama_satuan AS satuan, harga_beli, sisa_fisik, sisa_stock AS qty');
        $this->db->where('id_stockopname', $id);
        $this->db->order_by('plu', 'ASC');
  			$this->db->order_by('nama_item', 'ASC');
        $this->db->group_by('plu');
  			$data = get('stockopname_detail')->result_array();
      }
      echo '{"plus":' . json_encode($data) . '}';
		}
  }

  public function get_item_plu($stt='')
  {
    if (isset($_POST)) {
      $json = array();
      $gudang = post('gudang');
      $plu  = post('plu');
      $plu2 = post('plu2');
      $item_plu = post('item_plu');
      if ($stt=='cek_select') {
        $sel_data = array_unique(json_decode(html_entity_decode($this->input->post('sel'))));
      }else{
        $sel_data = array();
      }
      $cari = post('cari');
      $this->db->select('A.id_item_master, A.plu, A.nama_item');
      $this->db->join('item_gudang AS B', 'A.id_item_master=B.id_item_master');
      $this->db->where("(A.plu LIKE '%".$cari."%' OR A.nama_item LIKE '%".$cari."%')",null,false);
      if (!empty($sel_data)) {
        if (!empty($item_plu)) {
          $sel_datax=array();
          foreach ($sel_data as $key => $value) {
            $sel_datax[] = explode('-', $value)[1];
          }
          $sel_data = array();
          $sel_data = $sel_datax;
        }
        if (!empty($sel_data)) {
          $this->db->where_not_in('A.plu', $sel_data);
        }
      }
      if (!empty($plu)) {
        if (empty($plu2)) {
          $this->db->like('A.plu', $plu, 'after');
        }
      }
      if (!empty($plu2)) {
        $this->db->where("(A.plu LIKE '".$plu."%' OR A.plu LIKE '%".$plu2."%')",null,false);
      }
      $this->db->order_by('A.plu', 'ASC');
      $this->db->group_by('A.plu');
      $get = get('item_master AS A', array('B.status'=>1, 'B.id_gudang'=>$gudang));
			foreach ($get->result() as $key => $value) {
        if (!empty($item_plu)) {
          $idnya = $value->id_item_master. '-' .$value->plu. '-' .$value->nama_item;
        }else {
          $idnya = $value->plu;
        }
        $json[] = ['id'=>$idnya, 'text'=>"$value->plu - $value->nama_item"];
      }
      // log_r($this->db->last_query());
      echo json_encode($json);
      exit;
    }
  }


  public function get_hpp($stt='')
  {
    if (isset($_POST)) {
      $get = array();
      $exp = explode('-', post('p'));
      $item_master = $exp[0];
      $plu  = $exp[1];
      $this->db->select('kemasan, hrg_jual');
      $this->db->limit('1');
      $get = get_field('item_vendor', array('id_item_master'=>$item_master, 'plu'=>$plu));
      echo json_encode($get);
      exit;
    }
  }

  public function check_stock()
  {
    $sisa_stock = call_cek_stock(post('id_gudang'), post('plu'));
    echo json_encode(array('stock'=>$sisa_stock));
    exit;
  }

  public function get_item_lokasi($platform='')
  {
    if (isset($_POST)) {
			$datanya = array();
      $this->db->select('B.id_item_master, B.nama_item');
      $this->db->join('item_lokasi_detail AS B', 'A.id_item_lokasi=B.id_item_lokasi');
      if (!empty(post('p'))) {
      	$this->db->where('A.id_pasar', post('p'));
      }
      if (!empty(post('status'))) {
        $this->db->where('A.status', post('status'));
      	$this->db->where('B.status', post('status'));
      }
      $this->db->order_by('B.nama_item', 'ASC');
			$this->db->group_by('B.id_item_master', 'ASC');
			foreach (get('item_lokasi AS A')->result() as $key => $value) {
				$datanya[] = array('id'=>$value->id_item_master, 'nama'=>$value->nama_item);
			}
      if ($platform=='mobile') {
        echo json_encode($datanya);
      }else {
        echo '{"plus":' . json_encode($datanya) . '}';
      }
		}
  }

}
?>
