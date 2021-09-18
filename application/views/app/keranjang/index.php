<div class="container">
	<div class="row">
		<div class="col-12 my-4">
			<h1 class="text-center">Keranjang</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<a role="button" href="<?= base_url('home') ?>" class="btn btn-primary float-right">Kembali ke Barang</a>
		</div>
	</div>
	<div class="row">
		<div class="col-12 my-4">
			<table class="table table-striped">
				<thead>
					<th>No.</th>
					<th>Nama Barang</th>
					<th>harga</th>
					<th>Qty</th>
					<th>Subtotal</th>
					<th>Action</th>
				</thead>
				<tbody id="myTable">
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-6">
			<h1>
				Grand Total <span id="total"></span>
			</h1>
		</div>
		<div class="col-6">
			<button class="btn btn-warning float-right" onclick="checkout()">CHECKOUT</button>
		</div>
	</div>
</div>

<script type="text/javascript">
	let currentPage = 1;

	const getData = async (page) => {
		return await axios.get(`<?= base_url('keranjang/data/') ?>${page}`).then(res => res.data);
	}

	const emptyCart = async () => {
		return await axios.get(`<?= base_url('keranjang/checkout') ?>`).then(res => res.data);
	}

	const addToCart = async (aksi, kode) => {
		console.log(aksi, kode);
		return await axios.get(`<?= base_url('keranjang/update/') ?>${kode}/${aksi}`).then(res => res.data);
	}

	const drawTable = (result) => {
		let temp = ``;
		let number = 1;
		let total = 0;

		result.forEach(res => {
			total += res.qty * res.harga;
			temp += `<tr>
						<td>${number++}</td>
						<td>${res.nama_barang}</td>
						<td>${res.harga}</td>
						<td>${res.qty}</td>
						<td>${res.qty * res.harga}</td>
						<td>
							<button type="button" onclick="aksi('tambah', '${res.kode_barang}')" class="badge badge-success badge-pill">+</button>
							<button type="button" onclick="aksi('kurang', '${res.kode_barang}')" class="badge badge-danger badge-pill">-</button>
						</td>
					</tr>`

		});

		document.getElementById('total').innerText = total;
		document.getElementById('myTable').innerHTML = temp;
	}

	const aksi = async (todo, kode) => {
		console.log(kode);
		const res = await addToCart(todo, kode).then(res => res);

		if(res) {
			drawTable(res);
		}
	}

	const checkout = async () => {
		const res = await emptyCart().then(res => res);

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