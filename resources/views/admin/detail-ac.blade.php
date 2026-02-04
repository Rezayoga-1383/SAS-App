@extends('admin.template.main')

@section('title', 'Detail AC - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h5 class="card-title m-0">Data Detail AC</h5>
							<a href="{{ route('detail-ac.create') }}">
                                <button class="btn btn-md btn-primary"><i class="align-middle" data-feather="plus-square"></i><strong>Tambah Data</strong></button>
                            </a>
						</div>
						@if(session('success'))
						<script>
							document.addEventListener("DOMContentLoaded", function() {
								Swal.fire({
									icon: 'success',
									title: 'Berhasil!',
									text: '{{ session('success') }}',
									showConfirmButton: false,
									timer: 2000,
									timerProgressBar: true,
									background: '#f0fff4',
									color: '#155724',
									customClass: {
										popup: 'swal2-border-radius'
									}
								});
							});
						</script>
						@endif
						<div class="table-responsive">
							<table id="TabelDetailAC" class="table hover stripe" style="width:100%">
								<thead>
									<tr>
										<th>No</th>
										<th>No AC</th>
										<th>Merk</th>
										<th>Jenis</th>
										<th>Departement</th>
										<th>Ruangan</th>
										<!-- <th>No Seri In</th> -->
										<!-- <th>No Seri Out</th> -->
										<!-- <th>PK</th> -->
										{{-- <th>Jumlah</th> --}}
										<!-- <th>Tahun</th> -->
										<!-- <th>Tgl Pemasangan</th> -->
										<!-- <th>Tgl Habis Garansi</th> -->
										<th>Aksi</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection

@push('script')
<script>
$(document).ready(function() {
	const table = $('#TabelDetailAC').DataTable({
		processing: true,
		serverSide: true,
		ajax: "{{ route('detail-ac.data') }}",
		order:[],
		columns: [
			{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
			{ data: 'no_ac', name: 'no_ac' },
			{ data: 'nama_merkac', name: 'merkac.nama_merk' },
			{ data: 'nama_jenisac', name: 'jenisac.nama_jenis' },
			{ data: 'nama_departement', name: 'departement.nama_departement' },
			{ data: 'nama_ruangan', name: 'ruangan.nama_ruangan' },
			// { data: 'nama_departement', name: 'ruangan.nama_departement' },
			// { data: 'jumlah_ac', name: 'jumlah_ac' },
			{ data: 'aksi', name: 'aksi', orderable: false, searchable: false }
		],
		drawCallback: function() {
			// ✅ Agar feather icon muncul kembali setelah DataTables refresh
			feather.replace();
		}
	});

	// ✅ SweetAlert konfirmasi sebelum hapus
	$(document).on('submit', '.form-delete', function(e) {
		e.preventDefault();
		const form = this;

		Swal.fire({
			title: 'Yakin ingin menghapus data ini?',
			text: 'Data yang dihapus tidak bisa dikembalikan.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				form.submit();
			}
		});
	});

	// ✅ Tombol detail
	$(document).on('click', '.btn-detail', function() {
		let id = $(this).data('id');
		$.ajax({
			url: `/admin/detail-ac/show/${id}`,
			type: 'GET',
			success: function(response) {
				let htmlDetail = `
					<table class="table table-bordered text-start">
						<tr><th>Merk</th><td>${response.nama_merk}</td></tr>
						<tr><th>Jenis</th><td>${response.nama_jenis}</td></tr>
						<tr><th>Departement</th><td>${response.nama_departement}</td></tr>
						<tr><th>Ruangan</th><td>${response.nama_ruangan}</td></tr>
						<tr><th>No AC</th><td>${response.no_ac}</td></tr>
						<tr><th>No Seri Indoor</th><td>${response.no_seri_indoor}</td></tr>
						<tr><th>No Seri Outdoor</th><td>${response.no_seri_outdoor}</td></tr>
						<tr><th>PK AC</th><td>${response.pk_ac}</td></tr>
						<tr><th>Jumlah AC</th><td>${response.jumlah_ac}</td></tr>
						<tr><th>Tahun</th><td>${response.tahun_ac}</td></tr>
						<tr><th>Tanggal Pemasangan</th><td>${response.tanggal_pemasangan}</td></tr>
						<tr><th>Tanggal Habis Garansi</th><td>${response.tanggal_habis_garansi}</td></tr>
					</table>`;

				Swal.fire({
					title: '<strong>Detail Data AC</strong>',
					html: htmlDetail,
					width: 500,
					confirmButtonText: 'Tutup'
				});
			},
			error: function() {
				Swal.fire({
					icon: 'error',
					title: 'Gagal!',
					text: 'Tidak dapat memuat data detail AC.'
				});
			}
		});
	});
});
</script>

<!-- ✅ SweetAlert notifikasi sukses/error dari session -->
@if(session('success'))
<script>
Swal.fire({
	icon: 'success',
	title: 'Berhasil!',
	text: '{{ session('success') }}',
	timer: 2000,
	showConfirmButton: false,
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
	icon: 'error',
	title: 'Gagal!',
	text: '{{ session('error') }}',
	timer: 2000,
	showConfirmButton: false,
});
</script>
@endif
@endpush