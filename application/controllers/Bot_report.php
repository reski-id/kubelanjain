<?php
if (!defined('BASEPATH')) { exit('No Direct Script Allowed'); }

class Bot_report extends CI_Controller{

  public function __construct(){
    parent::__construct();
    if ($this->input->post('who') == 'mBOT11') {
      return true;
    } elseif (!get_session()) {
      $this->session->set_flashdata('failed', $this->lang->line('access_denied'));
      redirect('404');
    }
    $this->load->helper("telegram");
  }

  public function mregister($tgl='')
  {
    if ($tgl=='') {
      // RESET
      delete_data('no_random');
      delete_data('reset_password');
      $tgl=tgl_format(tgl_now('tgl'), 'Y-m-d', '-1 days');
    }

    // Reseller sesuai tgl
    $this->db->select('id_user');
    $total_r = get('v_user_biodata_reseller', array("LEFT(tgl_input,10)="=>$tgl))->num_rows();
    // Mitra I sesuai tgl
    $this->db->select('id_user');
    $total_m1 = get('v_user_biodata_mitra', array("LEFT(tgl_input,10)="=>$tgl, "type_id"=>1))->num_rows();
    // Mitra II sesuai tgl
    $this->db->select('id_user');
    $total_m2 = get('v_user_biodata_mitra', array("LEFT(tgl_input,10)="=>$tgl, "type_id"=>2))->num_rows();

    // Total Saat ini Reseller
    $this->db->select('id_user');
    $totals_r  = get('v_user_biodata_reseller')->num_rows();
    // Total Saat ini Mitra I
    $this->db->select('id_user');
    $totals_m1 = get('v_user_biodata_mitra', array("type_id"=>1))->num_rows();
    // Total Saat ini Mitra II
    $this->db->select('id_user');
    $totals_m2 = get('v_user_biodata_mitra', array("type_id"=>2))->num_rows();

    $pesan  = "<b>Report Register</b>\n";
    $pesan .= "<b>".hari_id($tgl)."</b>, ".tgl_id($tgl, 'd-m-Y')."\n";
    $pesan .= number_format($total_r)." Register Reseller\n";
    $pesan .= number_format($total_m1)." Register Mitra I\n";
    $pesan .= number_format($total_m2)." Register Mitra II\n\n";
    $pesan .= "-------------------------\n";
    $pesan .= "<b>TOTAL SAAT INI</b>\n";
    $pesan .= number_format($totals_r)." Register Reseller\n";
    $pesan .= number_format($totals_m1)." Register Mitra I\n";
    $pesan .= number_format($totals_m2)." Register Mitra II\n\n";

    // log_r($pesan);
    // $id_tele = '713398862';
    // SendMessage_tele($id_tele, $pesan);
    foreach (get_bot_group(1) as $key => $id_tele) {
      SendMessage_tele($id_tele, $pesan);
      sleep(1);
    }
  }


  public function morder($tgl='')
  {
    if ($tgl=='') {
      // RESET
      $tgl=tgl_format(tgl_now('tgl'), 'Y-m-d', '-1 days');
    }

    $pesan = "<b>".hari_id($tgl)."</b>, ".tgl_id($tgl, 'd-m-Y')."\n";

    $v_paket = array('A','B','C');
    foreach ($v_paket as $key => $value) {;
      // RESELLER MITRA
      ${'T_jml_'.$value}=0;
      ${'M_jml_'.$value}=0;
      ${'M_harga_'.$value}=0; ${'M_T_harga_'.$value}=0; ${'M_qty_dus_'.$value}=0;
      ${'M_pembayaran_nominal_'.$value}=0;
      ${'M_pembayaran_persen_'.$value}=0;
      // RESELLER UMUM
      ${'U_jml_'.$value}=0; $U_jml_B=0; $U_jml_C=0;
      ${'U_harga_'.$value}=0; ${'U_T_harga_'.$value}=0; ${'U_qty_dus_'.$value}=0;
      ${'U_pembayaran_nominal_'.$value}=0;
      ${'U_pembayaran_persen_'.$value}=0;
      ${'T_'.$value} = 0;
      ${'P_n'.$value} = 0;
      ${'P_p'.$value} = 0;
    }

    $this->db->select('a.id_order, a.id_user, a.paket, a.jenis, a.qty, a.free_qty, a.jumlah, a.harga, a.total_harga, a.status, a.pembayaran_persen');
    $this->db->join('user as b', 'b.id_user=a.id_user');
    foreach (get('order as a', array('b.status'=>'1'))->result() as $key => $value) {
      $paket = $value->paket;
      $jenis = $value->jenis;
      $jumlah = $value->jumlah;
      $qty_dus = $value->qty + $value->free_qty;
      $pembayaran_persen = $value->pembayaran_persen;
      $pembayaran_nominal=0;
      $this->db->select('COALESCE(SUM(nominal_pembayaran),0) AS total_pn');
      $get_pn = get('order_pembayaran', array('id_order'=>$value->id_order, 'id_user'=>$value->id_user))->row();
      if (!empty($get_pn)) {
        $pembayaran_nominal = $get_pn->total_pn;
      }
      if (in_array($paket, $v_paket)) {
        if ($jenis=='1') {
          ${'M_jml_'.$paket} += $jumlah;
          ${'M_qty_dus_'.$paket} += $qty_dus;
          ${'M_harga_'.$paket} = $value->harga;
          ${'M_pembayaran_nominal_'.$paket} += $pembayaran_nominal;
          ${'M_pembayaran_persen_'.$paket} += $pembayaran_persen;
        }elseif ($jenis=='2') {
          ${'U_jml_'.$paket} += $jumlah;
          ${'U_qty_dus_'.$paket} += $qty_dus;
          ${'U_harga_'.$paket} = $value->harga;
          ${'U_pembayaran_nominal_'.$paket} += $pembayaran_nominal;
          ${'U_pembayaran_persen_'.$paket} += $pembayaran_persen;
        }
        ${'T_jml_'.$paket}++;
      }
    }

    $pesan .= "<b>ORDER RESELLER</b>\n";
    $t_M=0; $t_U=0;
    $t_dus=0; $t_jml_m=0; $t_harga_m=0; $t_jml_u=0; $t_harga_u=0; $t_pcs=0; $t_val=0; $t_pay_nominal=0;
    foreach ($v_paket as $key => $value) {
      ${'M_T_harga_'.$value} = ${'M_jml_'.$value}*${'M_harga_'.$value};
      ${'U_T_harga_'.$value} = ${'U_jml_'.$value}*${'U_harga_'.$value};
      ${'T_PCS_'.$value} = (${'M_qty_dus_'.$value}+${'U_qty_dus_'.$value})*12;
      ${'T_'.$value}     = ${'M_T_harga_'.$value}+${'U_T_harga_'.$value};
      ${'P_n'.$value}    = (${'M_pembayaran_nominal_'.$value}+${'U_pembayaran_nominal_'.$value});
      ${'P_p'.$value}    = (${'M_pembayaran_persen_'.$value}+${'U_pembayaran_persen_'.$value});
      if (in_array(${'P_p'.$value}, array('',0))) {
        ${'P_p'.$value} = 0;
      }else {
        ${'P_p'.$value} = ${'P_p'.$value}/${'T_jml_'.$value};
      }
      $dus = ${'M_qty_dus_'.$value} + ${'U_qty_dus_'.$value};
      $pcs = ${'T_PCS_'.$value};
      $val = ${'T_'.$value};
      $pay_nominal = ${'P_n'.$value};
      $pay_persen  = ${'P_p'.$value};
      $pesan .= "<b>Paket $value</b> \n";
      $pesan .= " <b>MITRA :</b> ".format_angka(${'M_jml_'.$value})." x ".singkatkan_nilai(${'M_harga_'.$value})." = ".singkatkan_nilai(${'M_T_harga_'.$value})."\n";
      $pesan .= " <b>UMUM  :</b> ".format_angka(${'U_jml_'.$value})." x ".singkatkan_nilai(${'U_harga_'.$value})." = ".singkatkan_nilai(${'U_T_harga_'.$value})."\n";
      $pesan .= " <b>KARDUS  =</b> ".format_angka($dus)."\n";
      $pesan .= " <b>PCS       =</b> ".format_angka($pcs)."\n";
      $pesan .= " <b>Value   =</b> Rp ".format_angka($val)."\n";
      $pesan .= " <b>Payment =</b> Rp ".format_angka($pay_nominal)." \n";
      $pesan .= " <b>% Payment =</b> ".format_angka($pay_persen)."% \n\n";
      $t_jml_m += ${'M_jml_'.$value};
      $t_jml_u += ${'U_jml_'.$value};
      $t_M += ${'M_T_harga_'.$value};
      $t_U += ${'U_T_harga_'.$value};
      $t_dus   += $dus;
      $t_harga_m += ${'M_harga_'.$value};
      $t_harga_u += ${'U_harga_'.$value};
      $t_pcs += $pcs;
      $t_val += $val;
      $t_pay_nominal += $pay_nominal;
    }

    $pesan .= "<b>TOTAL ORDER</b>\n";
    $pesan .= " <b>MITRA :</b> ".format_angka($t_M)."\n";
    $pesan .= " <b>UMUM  :</b> ".format_angka($t_U)."\n";
    $pesan .= " <b>KARDUS  =</b> ".format_angka($t_dus)."\n";
    $pesan .= " <b>PCS       =</b> ".format_angka($t_pcs)."\n";
    $pesan .= " <b>Value   =</b> Rp ".format_angka($t_val)."\n";
    $pesan .= " <b>Payment =</b> Rp ".format_angka($t_pay_nominal)." \n";
    $t_pay_persen = round(($t_pay_nominal/$t_val)*100);
    $pesan .= " <b>% Payment =</b> ".$t_pay_persen."% \n\n";

    $this->db->select('id_user');
    $get_res = get('v_user_biodata_reseller', array('status'=>'1'));
    $t_reseller=0; $b_order=0; $s_order=0;
    foreach ($get_res->result() as $key => $value) {
      $t_reseller++;
      $this->db->select('id_user');
      $get_order = get('order', array('id_user'=>$value->id_user))->row();
      if (empty($get_order)) {
        $b_order++;
      }else {
        $s_order++;
      }
    }
    $this->db->select('a.id_user');
    $this->db->group_by('a.id_user HAVING ( COUNT(a.id_user) > 1 )');
    $this->db->join('user as b', 'b.id_user=a.id_user');
    $order_lagi = get('order as a', array('b.status'=>'1'))->num_rows();

    $pesan .= "<b>Total Reseller</b>   = ".format_angka($t_reseller)."\n";
    $pesan .= "<b>Belum Order</b>    = ".format_angka($b_order)."\n";
    $pesan .= "<b>Sudah Order</b>    = ".format_angka($s_order)."\n";
    $pesan .= "<b>Order Lagi</b>        = ".format_angka($order_lagi)."\n\n";

    $this->db->select('pembayaran_persen');
    $get_ordernya = get('order');
    $user_1=0; $user_2=0; $user_3=0; $user_4=0; $user_5=0;
    foreach ($get_ordernya->result() as $key => $value) {
      $persennya = round($value->pembayaran_persen);
      // if ($persennya == 0) {
      //   $user_1++;
      // }
      if ($persennya >= 1 && $persennya <= 40) {
        $user_2++;
      }
      if ($persennya >= 41 && $persennya <= 60) {
        $user_3++;
      }
      if ($persennya >= 61 && $persennya <= 95) {
        $user_4++;
      }
      if ($persennya >= 96 && $persennya <= 100) {
        $user_5++;
      }
    }
    $user_1 = $t_reseller - ($user_2+$user_3+$user_4+$user_5);
    $pesan .= "<b>PAYMENT USER RESELLER</b>\n";
    $pesan .= " <b>0%                 =</b> ".format_angka($user_1)." user\n";
    $pesan .= " <b>1%   - 40%   =</b> ".format_angka($user_2)." user\n";
    $pesan .= " <b>41% - 60%   =</b> ".format_angka($user_3)." user\n";
    $pesan .= " <b>61% - 95%   =</b> ".format_angka($user_4)." user\n";
    $pesan .= " <b>96% - 100% =</b> ".format_angka($user_5)." user\n";

    // log_r($pesan);
    // $id_tele = '713398862';
    // SendMessage_tele($id_tele, $pesan);
    foreach (get_bot_group(3) as $key => $id_tele) {
      SendMessage_tele($id_tele, $pesan);
      sleep(1);
    }
  }


  public function stock($id_gudang='', $tgl='')
  {
    if ($id_gudang==''){ exit; }
    if ($tgl=='') { $tgl=tgl_format(tgl_now('tgl'), 'Y-m-d'); }
    $pesan = "<b>".hari_id($tgl)."</b>, ".tgl_id($tgl, 'd-m-Y')."\n";

    $pesan .= "<b>STOCK</b>";
    $this->db->select('A.plu, B.nama_item, A.id_gudang_kota, C.nama_kemasan AS satuan');
    $this->db->join('item_master AS B', 'A.id_item_master=B.id_item_master');
    $this->db->join('kemasan AS C', 'C.id_kemasan=B.id_kemasan');
    $this->db->order_by('A.plu', 'ASC');
    $this->db->group_by('A.plu');
    $detail = get('item_gudang AS A', array('A.id_gudang'=>$id_gudang, 'A.status'=>1, 'B.status'=>1))->result_array();
    $item_stock=array(); $batas='01';
    foreach ($detail as $key => $value) {
      $plu = $value['plu'];
      $id_gudang_kota = $value['id_gudang_kota'];
      $sisa_stock = call_cek_stock($id_gudang, $plu);
      // Cek dan insert jika belum ada
      $this->db->select('id_gudang');
      $cek_item_stock = get_field('item_stock', array('plu'=>$plu, 'id_gudang_kota'=>$id_gudang_kota, 'id_gudang'=>$id_gudang));
      if (empty($cek_item_stock)) {
        $this->db->select('id_brand');
        $id_brand = get_field('gudang', array('id_gudang_kota'=>$id_gudang_kota, 'id_gudang'=>$id_gudang))['id_brand'];
        $item_stock[$key]['plu']      = $plu;
        $item_stock[$key]['id_brand'] = $id_brand;
        $item_stock[$key]['id_gudang_kota'] = $id_gudang_kota;
        $item_stock[$key]['id_gudang'] = $id_gudang;
        $item_stock[$key]['qty']       = $sisa_stock;
      }

      if ($batas!='') {
        if (substr($plu, 0, 2)!=$batas) { $pesan .= "\n"; $batas=''; }
      }
      if ($batas!='') {
        $pesan .= "\n  <b>$value[nama_item]</b>\n";
        $pesan .= "    • " . format_angka($sisa_stock) . " $value[satuan]\n";
      }else {
        $pesan .= " - $value[nama_item] : <b>" . format_angka($sisa_stock) . "</b> $value[satuan]\n";
      }
    }
    if (!empty($item_stock)) { add_batch('item_stock', $item_stock); }
    // mendapatkan plu 01
    $this->db->select('plu');
    $this->db->like('plu', '01', 'after');
    $get_plu = get('item_master', array('status'=>1))->result();
    $plu=array();
    foreach ($get_plu as $key => $value) {
      $plu[] = $value->plu;
    }
    if (!empty($plu)) {
      $produksi=array(); $produksi_edit=array();
      $this->db->select('A.plu, SUM(A.qty) as qty, A.item_produksi, satuan_qty AS satuan');
      $this->db->join('produksi AS B', 'A.id_produksi=B.id_produksi');
      $this->db->where_in('A.plu', $plu);
      $this->db->like('B.tgl_input', $tgl, 'after');
      $this->db->group_by('A.plu');
      $this->db->order_by('A.plu', 'ASC');
      $get_produksi = get('produksi_detail AS A', array('A.id_gudang'=>$id_gudang))->result_array();
      foreach ($get_produksi as $key => $value) {
        $produksi[$value['plu']] = $value;
      }
      // log_r($this->db->last_query());
      $this->db->select('A.plu, SUM(A.qty) as qty, A.item_produksi, satuan_qty AS satuan');
      $this->db->join('produksi AS B', 'A.id_produksi=B.id_produksi');
      $this->db->where_in('A.plu', $plu);
      $this->db->like('B.tgl_input', $tgl, 'after');
      $this->db->group_by('A.plu');
      $this->db->order_by('A.plu', 'ASC');
      $get_produksi = get('produksi_detail_edit AS A', array('A.id_gudang'=>$id_gudang))->result_array();
      foreach ($get_produksi as $key => $value) {
        $produksi_edit[$value['plu']] = $value;
      }

      if (!empty($produksi)) {
        $pesan .= "\n<b>PRODUKSI</b>";
        foreach ($plu as $key => $value) {
          if (!empty($produksi[$value])) {
            $satuan = $produksi[$value]['satuan'];
            $qty=$produksi[$value]['qty']; $qty_edit=0;
            if (!empty($produksi_edit[$value])) {
              $satuan = $produksi_edit[$value]['satuan'];
              $qty_edit = $produksi_edit[$value]['qty'];
              $this->db->select('SUM(A.qty) as qty');
              $this->db->join('produksi AS B', 'A.id_produksi=B.id_produksi');
              $this->db->where('A.plu', $produksi_edit[$value]['plu']);
              $this->db->like('B.tgl_input', $tgl, 'after');
              $qty_old = get('produksi_detail AS A', array('A.id_gudang'=>$id_gudang, 'catatan_edit!='=>''))->row();
              if (empty($qty_old)) { $qty_old=0; }else { $qty_old=$qty_old->qty; }
              // $qty = $qty - $qty_edit;
              $qty = $qty - ($qty_old - $qty_edit);
            }
            $pesan .= "\n  <b>".$produksi[$value]['item_produksi']."</b>\n";
            $pesan .= "    • ".format_angka($qty)." $satuan\n";
          }
        }
      }
    }
    // log_r($pesan);
    foreach (get_bot_group(6) as $key => $id_tele) {
      SendMessage_tele($id_tele, $pesan);
      sleep(1);
    }
  }


  function harga_pasar_pdf()
	{
		ini_set('max_execution_time', '0');
		$get = export_pdf_harga_pasar('', 'data');
    $file_path = $get['file_path'];
    $filename  = $get['filename'];
		foreach (get_bot_group(7) as $key => $id_tele) {
			SendDocument_tele($id_tele, '', FCPATH.$file_path, $filename, 'HTML');
		}
	}

}
