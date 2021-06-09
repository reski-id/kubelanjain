<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function SendMessage_tele($id_telegram, $pesan, $parse_mode = 'HTML')
{
    $CI = get_instance();
    $CI->load->helper("telegram");
    $telegram   = new Telegram(GET_KEY('telegram'));
    $content    = array(
        'chat_id'       => $id_telegram,
        'text'          => $pesan,
        'parse_mode'    => $parse_mode
    );
    return $telegram->sendMessage($content);
}

function SendDocument_tele($id_telegram, $caption, $file_path, $nama_file, $parse_mode = 'markdown')
{
    $CI = get_instance();
    $CI->load->helper("telegram");
    $telegram   = new Telegram(GET_KEY('telegram'));
    $content    = array(
        'chat_id'       => $id_telegram,
        'caption'       => $caption,
        'parse_mode'    => $parse_mode,
        'document'      => new CURLFile($file_path, "application/pdf", $nama_file)
    );
    return $telegram->sendDocument($content);
}

function get_bot_group($group='', $judul='')
{ $CI = get_instance();
  if ($group!='') {
    $CI->db->select('id_tele');
    if ($judul!='') {
      $CI->db->where('judul', $judul);
    }
    $CI->db->order_by('id_bot', 'ASC');
    $get = get('bot', array('id_bot_group'=>$group));
    if ($judul!='') {
      if (!empty($get->row())) {
        return $get->row()->id_tele;
      }else {
        return '';
      }
    }else {
      $id_bot=array();
      foreach ($get->result() as $key => $value) {
        $id_bot[] = $value->id_tele;
      }
      if (!empty($id_bot)) { return $id_bot; }
    }
  }
}

// function cek_bot($id='')
// {
//   $t_dummy = '-439484938'; //grup dummy
// }

function Bot_errornya($kode='-', $pesan='')
{ $CI = get_instance();
  $datanya = array(
    '-'   => $pesan,
    'sms' => 'Gagal dikirim ke '.$pesan,
    'wa'  => 'Gagal dikirim ke '.$pesan,
  );
  $pesannya  = "<b>".substr(siteURL('domain'),0,-1)."</b>\n\n";
  $pesannya .= $kode.' - '.$datanya[$kode];
  // log_r($pesannya);
  // ID BOT Developer
  $CI->db->order_by('id_bot', 'ASC');
  $get = get('bot', array('id_bot_group'=>4));
  foreach ($get->result() as $key => $value) {
    SendMessage_tele($value->id_tele, $pesannya);
    sleep(1);
  }
}
?>
