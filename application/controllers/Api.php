<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

  public function sms()
	{
    $this->_Send(uri(2));
	}

	public function wa()
	{
    $this->_Send(uri(2));
	}

  public function _Send($stt='')
  {
    $this->load->helper("api_sms");
    $status=0; $arr_json=array();
    if (isset($_POST)) {
      if (!in_array($stt, array('sms','wa'))) { redirect('404'); }
      $tipe  = post('tipe');
      $no_hp = post('no_hp');
      $pesan = post('pesan');
      // $pesan = "ID ORDER: OOG0DKG\nPAKET C dengan Jumlah Pesan (Paket) 1 dan Jumlah Total Harga Rp. 990.169,-\nInformasikan bukti pembayaran Anda Terimakasih.";
      $kirim = Send_message($stt, 'POST', $no_hp, $pesan);
      if ($kirim=='') {
        redirect('404');
      }else{
        if ($tipe=='status') {
          $json = "[$kirim]";
          $arr = json_decode($json, true)[0];
          if ($arr["status"]) { $status = 1; }
          echo json_encode(array('status'=>$status));
        }else{
          echo $kirim;
        }
      }
      exit;
    }
  }

  public function exp($jp='', $stt='')
  {
    $this->load->helper("api_pengiriman");
    $get='404'; $data='';
    $berat = 0;
    if ($jp=='sicepat') {
      $data = "origin=TKG&destination=SUB10000&weight=$berat";
      $get = DATA_Pengiriman($jp,'GET',$stt,'live', $data);
    }elseif ($jp=='jne') {
      if (isset($_POST)) {
        // $this->db->select('A.qty, A.free_qty');
        // $this->db->join('paketnya_group as B', 'A.id_paketnya_group=B.id_paketnya_group');
        // $paket = get('paketnya as A', array('A.id_paketnya'=>post('paket')))->row();
        // if (!empty($paket)) {
        //   $qty   = $paket->qty;
        //   $free_qty = $paket->free_qty;
        //   $berat = (($qty + $free_qty) * post('jumlah')) * 2;
        // }
        if ($stt=='tarif') {
          $kota_asal        = 'BANDARLAMPUNG';
          $kode_kota_asal   = 'VEtHMTAwMDBK';
          $kota_tujuan      = post('tujuan');
          $kode_kota_tujuan = post('id_tujuan');
          $berat            = 10;
          $data = ['panel_type'=>'info', 'exp_name'=>$jp, 'exp_title'=>'JNE', 'kotaAsaljne'=>$kota_asal, 'kotaAsaljne_val'=>$kode_kota_asal, 'kotaTujuanjne'=>$kota_tujuan, 'kotaTujuanjne_val'=>$kode_kota_tujuan, 'beratKgjne'=>$berat, 'cacheDisabledjne'=>'10', 'captchajne'=>'492'];
          $get  = DATA_Pengiriman($jp,'POST',$stt,'', $data);
        }
      }
    }
  }


  // public function kota_tujuan($jp='')
  // {
  //   if ($jp=='sicepat') {
  //     $berat = 1;
  //     $data = "origin=TKG&destination=SUB10000&weight=$berat";
  //     $get = DATA_Pengiriman($jp,'GET',$stt,'live', $data);
  //   }elseif ($jp=='jne') {
  //     $data = "s=tujuan&term";
  //     $get = DATA_Pengiriman($jp,'GET','','', $data);
  //   }
  // }

  public function get_select_kota_tujuan()
	{
    if(isset($_GET['filter']) && $_GET['filter'] == 'yes') {
      $this->load->helper("api_pengiriman");
        $get=array();
        if (!empty($_GET['q'])) {
          $data = "s=tujuan&term=".$_GET['q'];
          $get = DATA_Pengiriman('jne','GET','','', $data);
        }
        $json = [];
        foreach ($get as $key => $value) {
          $json[] = ['id'=>$value['id'], 'text'=>$value['value']];
        }
        echo json_encode($json);
    }
	}

}
?>
