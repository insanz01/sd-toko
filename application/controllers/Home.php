<?php

class Home extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('HomeModel', 'home');
		$this->load->model('KeranjangModel', 'keranjang');
	}

	public function index() {
		$this->form_validation->set_rules('nama_barang', 'NamaBarang', 'required');
		$this->form_validation->set_rules('harga', 'Harga', 'required');
		$this->form_validation->set_rules('stok', 'Stok', 'required');

		if($this->form_validation->run() == FALSE) {
			$this->load->view('templates/header');
			$this->load->view('app/home/index');
			$this->load->view('templates/footer');
		} else {
			$data = [
				'kode' => '',
				'nama_barang' => $this->input->post('nama_barang'),
				'harga' => $this->input->post('harga'),
				'stok' => $this->input->post('stok'),
				'gambar' => '',
			];

			$generate_kode = $this->home->generate_kode($data['nama_barang']);

			$data['kode'] = $generate_kode;

			$ext = substr(strrchr($data['nama_barang'], '.'), 1);
			$image = $generate_kode . $ext;

			$config['upload_path'] = './images/';
	        $config['allowed_types'] = 'jpg|png';
	        $config['max_size'] = 2000;
	        $config['filename'] = $image;

	        $this->load->library('upload', $config);

	        if (!$this->upload->do_upload('gambar')) {
	            $error = array('error' => $this->upload->display_errors());

	            var_dump($error);
	        } else {
	            // $data = array('image_metadata' => $this->upload->data());

	        	$upload_file = $this->upload->data();

	            $data['gambar'] = $upload_file['file_name'];
	        }

	        if($this->home->insert($data)) {
	        	$this->session->flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil menambahkan barang</div>');
	        } else {
	        	$this->session->flashdata('pesan', '<div class="alert alert-danger" role="alert">Gagal menambahkan barang</div>');
	        }

	        redirect('home/index');
		}
	}

	public function data($page) {
		$result = [];

		$barang = $this->home->get($page);

		$result = $barang ? $barang : [];

		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function update($kode, $page) {
		$this->keranjang->update($kode, 'tambah');

		$result = [];

		$keranjang = $this->home->get($page);

		$result = $keranjang ? $keranjang : [];

		echo json_encode($result, JSON_PRETTY_PRINT);
	}
}