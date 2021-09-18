<?php

class Keranjang extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->load->model('KeranjangModel', 'keranjang');
	}

	public function index() {
		$this->load->view('templates/header');
		$this->load->view('app/keranjang/index');
		$this->load->view('templates/footer');
	}

	public function data($page) {
		$result = [];

		$keranjang = $this->keranjang->get($page);

		$result = $keranjang ? $keranjang : [];

		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function update($kode, $aksi) {
		$this->keranjang->update($kode, $aksi);

		$result = [];

		$page = 1;
		$keranjang = $this->keranjang->get($page);

		$result = $keranjang ? $keranjang : [];

		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function checkout() {
		$result = [];

		$keranjang = $this->keranjang->checkout();

		$result = $keranjang ? $keranjang : [];

		echo json_encode($result, JSON_PRETTY_PRINT);
	}
}