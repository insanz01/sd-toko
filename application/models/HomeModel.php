<?php

class HomeModel extends CI_Model {
	public function get($page) {
		$limit = 10;
		$offset = $limit * ($page - 1); 

		$query = "SELECT * FROM barang LIMIT $limit OFFSET $offset";

		return $this->db->query($query)->result_array();
	}

	public function insert($data) {
		$data['created_at'] = date("Y-m-d H:i:s", time());
		$data['updated_at'] = date("Y-m-d H:i:s", time());
	
		return $this->db->insert('barang', $data);
	}

	public function generate_kode($nama_file) {
		$temp_kode = "";

		if(strpos($nama_file, ' ') !== false) {
			$temp = explode(" ", $nama_file);

			$first_char = strtoupper(substr($temp[0], 0, 1));
			$second_char = strtoupper(substr($temp[1], 0, 1));

			$temp_kode = $first_char . $second_char;
		} else {
			$temp_kode = strtoupper($nama_file[0]) . strtoupper($nama_file[1]);
		}

		$query = "SELECT kode FROM barang WHERE kode LIKE '$temp_kode%' ORDER BY kode DESC LIMIT 1";

		$last_file = $this->db->query($query)->row_array();

		$kode = "";
		$nomor = "";

		if($last_file) {
			$kode = substr($last_file, 2);
			$temp = substr($last_file, -3);
			$temp = int($temp);
			$temp++;

			if($temp < 10) {
				$nomor = '00' . (string) $temp;
			} else if($temp < 100) {
				$nomor = '0' . (string) $temp;
			} else {
				$nomor = (string) $temp;
			}
		} else {
			$kode = $temp_kode;
			$nomor = "001";
		}

		return $kode . $nomor;
	}
}