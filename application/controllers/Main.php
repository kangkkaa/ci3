<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Main extends CI_Controller {

	public function index()
    {
        $this->list();
	}
	// 접근 URL : http://school.anyro.com/main/list2 or http://school.anyro.com/
	public function list(){
		$this->load->view('/common/header');
		$this->load->view('/main/list');
		$this->load->view('/common/footer');
	}

	// 접근 URL : http://school.anyro.com/main/list2
	public function list2(){
		$this->load->view('/common/header');
		$this->load->view('/main/list2');
		$this->load->view('/common/footer');
	}

}
