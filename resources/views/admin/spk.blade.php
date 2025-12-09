@extends('admin.template.main')

@section('title', 'SPK - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h5 class="card-title m-0">Data SPK</h6>
							<a href="{{ route('spk.create') }}">
                                <button class="btn btn-md btn-primary"><i class="align-middle" data-feather="plus-square"></i> <strong>Tambah Data</strong></button>
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
							<table id="TabelSPK" class="table hover stripe" style="width:100%">
								<thead>
									<tr>
										<th>No</th>
										<th>No SPK</th>
										<th>No AC</th>
										<th>Tanggal</th>
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
	const table = $('#TabelSPK').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('spk.data') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'no_spk', name: 'no_spk' },
            { data: 'no_ac', name: 'acdetail.no_ac' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ],
        drawCallback: function() {
            feather.replace();
        }
    })
	// Konfirmasi SweetAlert sebelum hapus
	$(document).on('submit', '.form-delete', function(e) {
		e.preventDefault();
		const form = this;

		swal.fire({
			title: 'Yakin ingin menghapus data ini?',
			text: 'Data yang dihapus tidak dapat dikembalikan!',
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
});
</script>

<!-- SweetAlert Notifikasi Sukses atau Error -->
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
		html: '{!! session('error') !!}',
		timer: 2000,
		showConfirmButton: false,
	});
	</script>
@endif
@endpush