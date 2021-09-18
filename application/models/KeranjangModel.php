<?php

class KeranjangModel extends CI_Model {
	public function get() {
		$query = "SELECT cart.id, cart.qty, cart.kode_barang, barang.nama_barang, barang.harga FROM cart JOIN barang ON cart.kode_barang = barang.kode";
	
		$keranjang = $this->db->query($query)->result_array();

		return $keranjang;
	}

	public function checkout() {
		if($this->db->empty_table('cart')) {
			return $this->get();
		}
	}

	public function update($kode, $aksi) {
		$barang = $this->db->get_where('barang', ['kode' => $kode])->row_array();

		$stok = $barang['stok'];
		if($aksi == "tambah") {
			$stok--;
		} else {
			$stok++;
		}

		$this->db->set('stok', $stok);
		$this->db->where('kode', $kode);
		$this->db->update('barang');

		if($this->db->affected_rows()) {
			$last_data = $this->db->get_where('cart', ['kode_barang' => $kode])->row_array();
			
			if($aksi == "tambah") {
				$qty = 1;

				$data = [
					'id' => NULL,
					'kode_barang' => $kode,
					'qty' => $qty,
					'created_at' => date('Y-m-d H:i:s', time()),
					'updated_at' => date('Y-m-d H:i:s', time())
				];

				if($last_data) {
					$qty = $last_data['qty'] + 1;

					$this->db->set('qty', $qty);
					$this->db->set('updated_at', date('Y-m-d H:i:s', time()));
					$this->db->where('kode_barang', $kode);
					$this->db->update('cart');
				} else {
					$this->db->insert('cart', $data);
				}
			} else {
				$qty = $last_data['qty'];

				if($last_data and $qty == 0) {
					$this->db->delete('cart', ['kode_barang', $kode]);
				} else {
					$qty--;

					$this->db->set('qty', $qty);
					$this->db->set('updated_at', date('Y-m-d H:i:s', time()));
					$this->db->where('kode_barang', $kode);
					$this->db->update('cart');
				}
			}

		}

		return $this->db->get('barang')->result_array();
	}
}