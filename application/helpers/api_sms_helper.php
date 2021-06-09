<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Config API Key & URL
  function Config_API_Send($stt='key', $data='')
  {
    if ($stt=='url') {
      if ($data=='sms') {
        return 'https://sms-api.villacorp.id';
      }else{
        // return 'https://wa-meeju.villacorp.id:8001/'.$data;
        return 'https://wa-meluncur.villacorp.id:8002/'.$data;
      }
    } else {
      // return get_field('set_api', array('nama'=>'WA-SMS'))['api_key'];
      return '665992707bac3161b18191e4190df0dc79627f1e'; //MELUNCUR
    }
  }

// CALL Function API
  function CALL_API_Send($method='', $data='', $url='')
  {
    $curl = curl_init();
    $token = Config_API_Send('key');
    $url   = Config_API_Send('url', $url);
    // curl_setopt($curl, CURLOPT_HTTPHEADER,
    //     array( "Authorization: $token", )
    // );
    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             $data = array_merge($data, array('token'=>$token, 'btnkirim'=>'ok'));
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       default:
          if ($data)
             // $url = sprintf("%s?%s", $url, http_build_query($data));
             $url = $url.'?'.$data;
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if (!$response) { return $err; }
    return $response;
  }

// ===============================================================================

// SEND ===========================================================
  function Send_message($jenis='', $method='GET', $telepon='', $message='', $status='')
  {
    $CI = get_instance();
    $name_db = $CI->db->database;
    if (strpos($name_db, 'test')) {
      return '';
    }else{
      if($telepon=='' || $message==''){ return ''; }
      $url  = [ "sms"=>"sms", "wa"=>"send-message"];
      if ($jenis=='wa') { //chat-api.com
        $data = [ 'phone'=>phone_country($telepon, '62'), 'body'=>$message ];
        return Call_CHAT_API($method, $data, 'sendMessage');
      }else {
        $data = [ 'number'=>$telepon, 'message'=>$message, ];
        if (!empty($url[$jenis])) {
          if ($jenis=='wa' && $status=='') {
            Send_message('sms', $method, $telepon, $message);
          }
          return Call_API_Send($method, $data, $url[$jenis]);
        }else {
          return '';
        }
      }
    }
  }

  function Send_file($jenis='', $method='GET', $telepon='', $file_path='', $filename='', $caption='', $status='')
  {
    $CI = get_instance();
    $data = [ 'phone'=>phone_country($telepon, '62'), 'caption'=>$caption, 'body'=>$file_path, 'filename'=>$filename];
    return Call_CHAT_API($method, $data, 'sendFile');
  }

// CHAT-API.com -===================================-
  function Call_CHAT_API($method='', $data='', $send='')
  {
    $curl = curl_init();
    $token = '4nlgetran3fo1zz7';
    $url   = 'https://api.chat-api.com/instance234742/'.$send.'?token='.$token;

    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             // $data = array_merge($data, array('token'=>$token, 'btnkirim'=>'ok'));
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       default:
          if ($data)
             // $url = sprintf("%s?%s", $url, http_build_query($data));
             $url = $url.'?'.$data;
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if (!$response) { return $err; }
    return $response;
  }

?>
