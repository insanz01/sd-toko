<div class="container">
	<div class="row">
		<div class="col-12 my-4">
			<h1 class="text-center">Barang</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<a role="button" href="<?= base_url('keranjang/index') ?>" class="btn btn-primary float-left">Keranjang</a>
			<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#tambahModal">Tambah Barang</button>
		</div>
	</div>
	<div class="row">
		<div class="col-12 my-4">
			<table class="table table-striped">
				<thead>
					<th>No.</th>
					<th>Nama Barang</th>
					<th>Harga</th>
					<th>Stok</th>
					<th>Gambar</th>
					<th>Kode</th>
					<th>Action</th>
				</thead>
				<tbody id="myTable">
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahModalLabel">Tambah Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('home/index') ?>" method="POST" enctype="multipart/form-data">
	      <div class="modal-body">
	        <div class="form-group">
	        	<label>Nama Barang</label>
	        	<input type="text" name="nama_barang" class="form-control">
	        </div>
	        <div class="form-group">
	        	<label>Harga</label>
	        	<input type="number" min="0" name="harga" class="form-control">
	        </div>
	        <div class="form-group">
	        	<label>Stok</label>
	        	<input type="number" min="1" name="stok" class="form-control">
	        </div>
	        <div class="form-group">
	        	<label>Gambar</label>
	        	<input type="file" accept="image/jpg, image/png" name="gambar" class="form-control">
	        	<small>Hanya dapat menerima file .JPG atau .PNG</small>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
	        <button type="submit" class="btn btn-primary">Simpan Barang</button>
	      </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
	let currentPage = 1;

	const getData = async (page) => {
		return await axios.get(`<?= base_url('home/data/') ?>${page}`).then(res => res.data);
	}

	const addToCart = async (kode, page) => {
		return await axios.get(`<?= base_url('home/update/') ?>${kode}/${page}`).then(res => res.data);
	}

	const drawTable = (result) => {
		let temp = ``;
		let number = 1;

		result.forEach(res => {
			temp += `<tr>
								<td>${number++}</td>
								<td>${res.nama_barang}</td>
								<td>${res.harga}</td>
								<td>${res.stok}</td>
								<td><img src="<?= base_url('images/') ?>${res.gambar}" width="80"></td>
								<td>${res.kode}</td>
								<td>
									<button type="button" onclick="beli('${res.kode}')" class="badge badge-primary badge-pill">Beli</button>
								</td>
							</tr>`
		});

		document.getElementById('myTable').innerHTML = temp;
	}

	const beli = async (kode) => {
		console.log(kode);
		const res = await addToCart(kode, currentPage).then(res => res);

		if(res) {
			drawTable(res);
		}
	}

	const loadTable = async (page) => {
		console.log('page', page)
		const result = await getData(page).then(res => res);

		console.log(result);

		drawTable(result);
	}

	window.addEventListener('load', async () => {
		await loadTable(currentPage);
	})
</script>