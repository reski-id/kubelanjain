<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	var $view = "auth";

  function __construct() {
			parent::__construct();
			$this->load->helper('user');
	}

	public function index()
	{
			redirect('auth/login');
	}

  public function register($jenis='', $referal='')
	{
		if (!in_array($jenis, array('1', '2'))) {
			redirect('404');
		}
		cek_Referal($referal);

    $v = 'register';
		$data = array(
      'judul_web' => "Form Pendaftaran -",
			'content'		=> "$this->view/$v"
		);
		$this->load->view("$this->view/index", $data);
	}

	public function login($CP='')
	{
		$id_user = get_session('id_user');
		if(isset($id_user)) { redirect("dashboard"); }
    $v = 'login';
		$data = array(
      'judul_web' => "",
			'content'		=> "$this->view/$v"
		);
		$this->load->view("$this->view/index", $data);
	}

  public function forgot_password()
	{
		$id_user = get_session('id_user');
		if(isset($id_user)) { redirect("dashboard"); }
    $v = 'forgot_password';
		$data = array(
      'judul_web' => "Lupa Password -",
			'content'		=> "$this->view/$v"
		);
		$this->load->view("$this->view/index", $data);
	}

  public function reset_password()
	{
		$id_user = get_session('id_user');
		if(isset($id_user)) { redirect("dashboard"); }
    $v = 'reset_password';
		$data = array(
      'judul_web' => "Reset Password -",
			'content'		=> "$this->view/$v"
		);
		$this->load->view("$this->view/index", $data);
	}

	public function logout()
	{
		if (get_session('level')==0) {
			$redirect='backend';
			logout("$redirect");
		}else {
			$redirect='login';
			logout("$this->view/$redirect");
		}
  }


// PROSES ====================================================================

	public function proses($p='',$CP='')
	{
		if (isset($_POST)) {
			$MODELnya = 'M_auth';
			if ($this->load->model($MODELnya)) {
				model($MODELnya, "proses_$p", $CP);
			}else {
				echo json_encode(array('stt'=>0, 'pesan'=>'Function tidak ditemukan!'));
				exit;
			}
		}
	}


}
