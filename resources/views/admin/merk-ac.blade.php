@extends('admin.template.main')

@section('title', 'Merk AC - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h5 class="card-title m-0">Data Merk AC</h6>
							<a href="{{ route('merk-ac.create') }}">
							<button class="btn btn-md btn-primary"><i class="align-middle" data-feather="plus-square"></i>Tambah Data</button>
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
						
						<table id="TabelMerkAC" class="table hover stripe nowrap" style="width:100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Merk AC</th>
									<th>Aksi</th>
								</tr>
							</thead>
						</table>
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
		$('#TabelMerkAC').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		ajax: "{{ route('merk-ac.data') }}",
		columns: [
			{ 
				data: null,
				name: 'no',
				orderable: false,
				searchable: false,
				className: 'text-center',
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				} 
			},
			{ data: 'nama_merk', name: 'nama_merk' },
			{
				data: 'id',
				render: function(data) {
					return `
					<div class="aksi-btn">
						<a href="/merk-ac/${data}/edit" class="btn btn-md btn-success"><i class="align-middle" data-feather="edit"></i><strong>Edit</strong></a>
						<form action="/merk-ac/${data}" method="POST" class="d-inline form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-md btn-danger btn-hapus">
                                <i class="align-middle" data-feather="trash-2"></i> <strong>Hapus</strong>
                            </button>
                        </form>
					</div>`;
				}
			}
		],
		drawCallback: function() {
            feather.replace();
        }
	});
	// Konfirmasi SweetAlert sebelum hapus
    $(document).on('submit', '.form-delete', function(e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data merk AC ini akan dihapus permanen!',
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

<!-- SweetAlert tampilkan notifikasi sukses atau error  -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        showConfirmButton: true
    });
</script>
@endif
@endpush

@push('style')
<style>
/* Wrapper tombol aksi */
.aksi-btn {
	display: flex;
	flex-wrap: wrap;
	gap: 4px;
	justify-content: center;
}

/* Mobile optimization */
@media (max-width: 768px) {
	.aksi-btn .btn {
		width: 70%;
		font-size: 13px;
		padding: 6px 10px;
	}
}
</style>
@endpush
