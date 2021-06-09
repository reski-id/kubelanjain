<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_mobile extends CI_Controller {

	// function __construct() {
	// 		parent::__construct();
	// }

	public function index()
	{
		echo "Not Found bro !";
	}

  public function login($level='', $un='', $pass='')
  {
		model('M_auth','proses_login', '', 'mobile', $level, $un, $pass);
	}

}
