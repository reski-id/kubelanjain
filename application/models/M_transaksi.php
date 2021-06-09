<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_transaksi extends CI_Model
{

  function benefit($stt='')
  {
    if ($stt=='awal') {
      $this->benefit_awal();
    }else {
      $this->benefit_detail();
    }
  }

  function query_benefit_awal($jenis='',$get_ID='')
	{
		$tgl1    = post('tgl1');
		if ($jenis=='saldo') {
			$this->db->select('(COALESCE(SUM(trans_in),0) - COALESCE(SUM(trans_out),0)) AS saldo');
		}else {
			$this->db->select('tanggal, tipe, no_transaksi, id_user, tgl_input, ket, ket_id, trans_in, trans_out');
		}
		if ($get_ID!='') {
			$this->db->where('id_user', $get_ID);
		}else {
			if (get_session('level')==0) {
				$this->db->where('id_user', 1);
			}else {
				$this->db->where('id_user', get_session('id_user'));
			}
		}
		$this->db->where('tanggal<', $tgl1);
		$this->db->order_by('tgl_input', 'DESC');
		$this->db->limit(1);
		if (in_array($jenis, array('saldo'))) {
			$get = get('transaksi')->row();
			if(empty($get)){ return 0; }else{ return $get->$jenis; }
		}else {
			return get('transaksi')->result_array();
		}
	}

  function benefit_awal()
  {
		if (!check_permission('view', 'read', 'users/pembayaran_benefit')) {
      echo '{"detailnya":' . json_encode(array()).', "saldo":' . json_encode(0).', "stt":' . json_encode(0).'}';
      exit;
		}
    $id_user = decode(post('id'));
    $detail = $this->query_benefit_awal('detail', $id_user);
    $saldo  = $this->query_benefit_awal('saldo', $id_user);
    echo '{"detailnya":' . json_encode($detail).', "saldo":' . json_encode($saldo).', "stt":' . json_encode(1).'}';
  }

  function benefit_detail()
  {
		if (!check_permission('view', 'read', 'users/pembayaran_benefit')) {
      echo '{"detailnya":' . json_encode(array()).', "total":' . json_encode(0).', "stt":' . json_encode(0).'}';
      exit;
		}
    $get_ID = decode(post('id'));
    $tgl1 = post('tgl1');
    $tgl2 = post('tgl2');
    $id_kota = post('id_kota');
    if ($get_ID!='') {
      $this->db->where('id_user', $get_ID);
    }else {
      if (get_session('level')==0) {
        $get_ID = 1;
        $this->db->where('id_user', $get_ID);
      }else {
        $get_ID = get_session('id_user');
        $this->db->where('id_user', $get_ID);
      }
    }
    $this->db->where('tanggal>=', $tgl1);
    $this->db->where('tanggal<=', $tgl2);
    if (view_mobile()) {
      $this->db->order_by('tgl_input','DESC');
    }else {
      $this->db->order_by('tgl_input','ASC');
    }
    $get = get('transaksi')->result_array();
    $total = get_field('user_total', array('id_user'=>$get_ID))['total_benefit'];
    echo '{"detailnya":' . json_encode($get).', "total":'. json_encode($total) .', "stt":' . json_encode(1).'}';
  }

  function detail_pembayaran_benefit()
  {
		if (!check_permission('view', 'read', 'users/pembayaran_benefit')) {
      echo '{"detailnya":' . json_encode(array()).', "stt":' . json_encode(0).'}';
      exit;
		}
    $cari = post('p');
    $this->db->select('nama_lengkap, no_hp, id_mitra, type_id as ke, id_user as id');
    $this->db->where("(nama_lengkap LIKE '%".$cari."%' OR no_hp LIKE '%".$cari."%' OR id_mitra LIKE '%".$cari."%')",null,false);
    $this->db->order_by('type_id','ASC');
    $this->db->order_by('nama_lengkap','ASC');
    $get = get('user_biodata_mitra')->result_array();
    echo '{"detailnya":' . json_encode($get).', "stt":' . json_encode(1).'}';
  }

  function add_pembayaran_benefit($id='')
  {
		if (!check_permission('view', 'create', 'users/pembayaran_benefit')) {
      echo json_encode(array("stt"=>0, 'pesan'=>'Permission Denied!'));
      exit;
		}
    $id = decode($id);
    $total=0;
    $this->db->query("CALL cek_benefit($id, @saldo)");
    $cek_q = $this->db->query("SELECT @saldo AS saldo")->row();
    if (!empty($cek_q)) {
      $total = $cek_q->saldo;
    }
    $total_bayar = khususAngka(post('total_bayar'));
    if ($total_bayar <= 0) {
      $pesan = 'Total Bayar tidak boleh 0';
      echo json_encode(array("stt"=>0, 'pesan'=>$pesan));
      exit;
    }
    $sisa_fee = $total - $total_bayar;
    if ($sisa_fee < 0) {
      $pesan = 'Total Bayar tidak boleh melebihi Total Fee!';
      echo json_encode(array("stt"=>0, 'pesan'=>$pesan));
      exit;
    }

    $post['no_transaksi'] = post('no_transaksi');
    $post['type_id']     = post('tipe');
    $post['id_user']     = $id;
    $post['total_bayar'] = $total_bayar;
    $post['sisa_fee']    = $sisa_fee;
    $post['catatan']     = post('catatan');
    $post['input_by']    = get_session('id_user');
    $post['tgl_input']   = tgl_now();
    $this->db->trans_begin();
    $simpan = add_data('user_fee_pembayaran', $post);
    if ($simpan) {
      $this->db->trans_commit();
      $stt=1; $pesan='';
    }else {
      $this->db->trans_rollback();
      $stt=0; $pesan='Gagal, silahkan coba lagi . . .';
    }
    echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
    exit;
  }

}
?>
