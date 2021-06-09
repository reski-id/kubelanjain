<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//START PESAN
function pesan($alert='',$msg='',$icon='',$pesan='',$url='',$html='')
{ $CI = &get_instance();
  $icon = get_pesan_icon($alert, $icon);
  $CI->session->set_flashdata($msg, '
    <div class="alert alert-'.$alert.' alert-dismissible mb-2 col-md-12" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="d-flex align-items-center">
            '.$icon.'
            <span>
                '.$pesan.'
            </span>
        </div>
    </div>
    '.$html.''
  );
  if ($url=='ajax') {
    return true;
  }else {
    return redirect($url);
  }
}

function get_pesan($msg)
{ $CI = &get_instance();
  echo $CI->session->flashdata($msg);
}

function get_pesan_icon($alert='', $icon='')
{ $get = '';
  if ($icon=='') {
    if ($alert=='danger') {
      $get = 'error';
    }elseif ($alert=='warning' || $alert=='info') {
      $get = 'error-circle';
    }elseif ($alert=='success') {
      $get = 'check';
    }
  }
  if ($get!='') {
    return '<i class="bx bx-'.$get.'"></i>';
  }else {
    return '';
  }
}

//END PESAN
?>
