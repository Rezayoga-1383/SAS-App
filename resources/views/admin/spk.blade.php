@extends('admin.template.main')

@section('title', 'SPK - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-end mb-4 flex-wrap" id="top-content">

							<!-- Judul -->
							<h5 class="card-title m-0">Data SPK</h5>

							<!-- Filter + Tombol -->
							<div class="d-flex align-items-end gap-2 flex-wrap">

								<div>
									<label class="form-label small mb-1">Tanggal Awal</label>
									<input type="date" id="start_date" class="form-control form-control-sm">
								</div>

								<div>
									<label class="form-label small mb-1">Tanggal Akhir</label>
									<input type="date" id="end_date" class="form-control form-control-sm">
								</div>

								<div>
									<label class="form-label small mb-1">Jenis Service</label>
									<select id="jenis_service" class="form-select form-select-sm">
										<option value="">Semua</option>
										<option value="cuci ac">Cuci AC</option>
										<option value="perbaikan">Perbaikan</option>
										<option value="cek ac">Cek AC</option>
										<option value="ganti unit">Ganti Unit</option>
									</select>
								</div>

								<button id="filter" class="btn btn-success btn-sm">
									<i data-feather="filter"></i> Filter
								</button>

								<button id="reset" class="btn btn-secondary btn-sm">
									Reset
								</button>

								<a href="#" id="exportPdf" class="btn btn-danger btn-sm">
									<i data-feather="file-text"></i> PDF
								</a>

								<a href="{{ route('spk.create') }}" class="btn btn-primary btn-sm">
									<i data-feather="plus-square"></i> Tambah
								</a>

							</div>
						</div>
						<table id="TabelSPK" class="table hover stripe nowrap" style="width:100%">
							<thead>
								<tr>
									<th>No</th>
									<th>No SPK</th>
									<th>No AC</th>
									<th>Tanggal</th>
									<th class="text-center">Aksi</th>
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
	const table = $('#TabelSPK').DataTable({
        processing: true,
        serverSide: true,
		responsive: true,
        ajax: {
			url: "{{ route('spk.data') }}",
			data: function(d) {
				d.start_date = $('#start_date').val();
				d.end_date = $('#end_date').val();
				d.jenis_service = $('#jenis_service').val();
			}
		},
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'no_spk', name: 'no_spk' },
            { data: 'no_ac', name: 'no_ac' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
        ],
        drawCallback: function() {
            feather.replace();
        }
    });

	function validateTanggal() {
		let start = $('#start_date').val();
		let end   = $('#end_date').val();

		if (!start || !end) {
			Swal.fire({
				icon: 'warning',
				title: 'Tanggal belum lengkap!',
				text: 'Silakan isi Tanggal Awal dan Tanggal Akhir terlebih dahulu.',
				confirmButtonColor: '#3085d6'
			});
			return false;
		}

		return true;
	}


	// function global
	function applyFilter() {
		table.draw();
		updateExportLink();
	}

	// Filter button
	$('#filter').click(function() {
		if (validateTanggal()) {
			applyFilter();
		}
	});

	$('#jenis_service').change(function() {
		applyFilter();
	});

	// Reset button
	$('#reset').click(function() {
		$('#start_date').val('');
		$('#end_date').val('');
		$('#jenis_service').val('');
		applyFilter();
	});

	// Update link PDF
    function updateExportLink() {
		let start = $('#start_date').val();
		let end   = $('#end_date').val();
		let jenis = $('#jenis_service').val();

		let baseUrl = "{{ route('spk.exportPdf') }}";
		let params = [];

		// Kalau tanggal belum lengkap → disable tombol PDF
		if (!start || !end) {
			$('#exportPdf')
				.attr('href', '#')
				.addClass('disabled');
			return;
		}

		if (start) params.push("start_date=" + encodeURIComponent(start));
		if (end)   params.push("end_date=" + encodeURIComponent(end));
		if (jenis) params.push("jenis_service=" + encodeURIComponent(jenis));

		let finalUrl = baseUrl + "?" + params.join("&");

		$('#exportPdf')
			.attr('href', finalUrl)
			.removeClass('disabled');
	}

    // Jalankan saat pertama load
    $('#start_date, #end_date, #jenis_service').change(function() {
		updateExportLink();
	});


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

// // button HPP
// $(document).on('click', '.btn-hpp', function() {
//     let id = $(this).data('id');
//     let nospk = $(this).data('nospk');
// 	let mode = $(this).data('mode');

//     Swal.fire({
//         title: mode === 'edit' ? 'Edit HPP' : 'Input HPP',
// 		width: '600px',
//         html: `
//             <div style="text-align: left; margin-bottom: 15px;">
//                 <label style="font-size: 14px; font-weight: 500; display: block; margin-bottom: 5px;">No SPK</label>
//                 <input type="text" class="form-control" value="${nospk}" disabled>
//             </div>
//             <table class="table table-bordered" id="table-hpp">
// 				<thead>
// 					<tr>
// 						<th>Keterangan</th>
// 						<th>Nominal</th>
// 						<th width="65">Aksi</th>
// 					</tr>
// 				</thead>
// 				<tbody></tbody>
// 			</table>

// 			<button type="button" id="tambah-hpp" class="btn btn-sm btn-primary">+ Tambah</button>
//         `,
//         showCancelButton: true,
//         confirmButtonText: 'Simpan',

// 		didOpen: () => {
// 			function addRow(ket = '', nom = '') {
// 				$('#table-hpp tbody').append(`
// 					<tr>
// 						<td><input type="text" class="form-control keterangan" value="${ket}"></td>
// 						<td><input type="number" class="form-control nominal" value="${nom}"></td>
// 						<td><button type="button" class="btn btn-danger btn-sm btn-hapus">X</button></td>
// 					</tr>
// 				`);
// 			}

// 			$('#tambah-hpp').on('click', function() {
// 				addRow();
// 			});

// 			$(document).on('click', '.btn-hapus', function() {
// 				$(this).closest('tr').remove();
// 			});


// 			if(mode === 'edit') {
// 				$.ajax({
// 					url: `/admin/spk/${id}/hpp`,
// 					method: 'GET',
// 					success: function(res) {
// 						$('#table-hpp tbody').empty();

// 						if (res.data.length > 0) {
// 							res.data.forEach(item => {
// 								addRow(item.keterangan, item.nominal);
// 							});
// 						} else {
// 							addRow();
// 						}
// 					}
// 				});
// 			} else {
// 				addRow();
// 			}
// 		},

// 		preConfirm: () => {
// 			let data = [];

// 			$('#table-hpp tbody tr').each(function() {
// 				let ket = $(this).find('.keterangan').val();
// 				let nom = $(this).find('.nominal').val();

// 				if (ket && nom) {
// 					data.push({
// 						keterangan: ket,
// 						nominal: nom
// 					});
// 				}
// 			});

// 			if (data.length === 0) {
// 				Swal.showValidationMessage('Minimal 1 data Hpp diisi!');
// 				return false;
// 			}

// 			return data;
// 		}

//     }).then((result) => {
//         if (result.isConfirmed) {
//             let url = `/admin/spk/${id}/hpp`;
// 			let method = (mode === 'edit') ? 'PUT' : 'POST';

//             // Proses AJAX
//             $.ajax({
//                 url: url,
//                 method: method,
//                 data: {
//                     _token: '{{ csrf_token() }}',
//                     hpp: result.value
//                 },
//                 success: function() {
//                     Swal.fire('Berhasil', 'HPP disimpan', 'success');
// 					$('#TabelSPK').DataTable().ajax.reload();
// 				},
//                 error: function() {
//                     Swal.fire('Error', 'Gagal simpan HPP', 'error');
//                 }
//             });
//         }
//     });
// });
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

@push('style')
<style>
/* Tombol aksi rapi & responsif */
.aksi-btn {
	display: flex;
	flex-wrap: wrap;
	gap: 4px;
	justify-content: center;
}

@media (max-width: 768px) {
	.aksi-btn .btn,
	.aksi-btn form {
		width: 100%;
	}
}

.form-label {
    font-size: 12px;
    font-weight: 500;
}

#top-content input[type="date"] {
    width: 150px;
}

@media (max-width: 768px) {
    #top-content {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }
}

/* Efek Blur Latar Belakang Modal */
.swal2-container.swal2-backdrop-show,
.modal-backdrop.show {
    backdrop-filter: blur(5px);
    background-color: rgba(0, 0, 0, 0.5) !important;
}
</style>
@endpush
