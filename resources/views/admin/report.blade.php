@extends('admin.template.main')

@section('title', 'Report Dokumentasi - SAS')

@section('content')
<main class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="card-title mb-4">
                            <i data-feather="image"></i> Filter Dokumentasi Pengerjaan
                        </h5>

                        {{-- FILTER SECTION --}}
                            <div class="row g-3 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label small">Tanggal Awal</label>
                                    <input type="date" id="start_date" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small">Tanggal Akhir</label>
                                    <input type="date" id="end_date" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small">Jenis Service</label>
                                    <select id="jenis_service" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="cuci ac">Cuci AC</option>
                                        <option value="perbaikan">Perbaikan</option>
                                        <option value="ganti unit">Ganti Unit</option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="button" id="filter" class="btn btn-success w-100">
                                        <i data-feather="filter"></i> Filter
                                    </button>

                                    <button type="button" id="reset" class="btn btn-secondary w-100">
                                        Reset
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#" id="exportPdf" class="btn btn-danger btn-sm">
                                    <i data-feather="file-text"></i> Export PDF
                                </a>
                            </div>

                        <hr class="my-4">

                        {{-- HASIL DOKUMENTASI --}}
                        <div id="documentationContainer" class="row g-4">
                            {{-- Data akan dimunculkan via JS --}}
                        </div>

                        {{-- NO DATA --}}
                        <div id="noResultsMessage" class="alert alert-warning text-center d-none mt-4">
                            Data Dokumentasi Tidak Ditemukan
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

    function showErrorAlert() {
        Swal.fire({
            icon: 'error',
            title: 'Tidak Bisa Menampilkan Data',
            text: 'Silakan isi Tanggal Awal dan Tanggal Akhir terlebih dahulu.',
            confirmButtonColor: '#d33'
        });
    }

    function isFilterValid() {
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        // let jenis_service = $('#jenis_service').val();

        if (!start_date || !end_date) {
            return false;
        }

        return true;
    }

    function fetchData() {

        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        let jenis_service = $('#jenis_service').val();

        $.ajax({
            url: "{{ route('admin.report.data') }}",
            type: "GET",
            data: {
                start_date: start_date,
                end_date: end_date,
                jenis_service: jenis_service
            },
            success: function(response) {

                let container = $('#documentationContainer');
                container.empty();

                if (response.length === 0) {
                    $('#noResultsMessage').removeClass('d-none');
                    return;
                }

                $('#noResultsMessage').addClass('d-none');

                response.forEach(function(item) {

                    let fotoKolase = item.foto_kolase 
                        ? `/storage/${item.foto_kolase}` 
                        : '/assets/image/no-image.png';

                    let fotoHistory = item.foto_history 
                        ? `/storage/${item.foto_history}` 
                        : '/assets/image/no-image.png';

                    let card = `
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0">

                                <div class="card-header bg-light">
                                    <strong>No AC:</strong> ${item.no_ac} <br>
                                    <small>
                                        <strong>Tanggal:</strong> ${item.tanggal} <br>
                                        <strong>Ruangan:</strong> ${item.ruangan} <br>
                                        <strong>Departemen:</strong> ${item.departemen}
                                    </small>
                                </div>

                                <div class="card-body">
                                    <div class="row g-2">

                                        <div class="col-6">
                                            <label class="small text-muted">Foto Kolase</label>
                                            <img src="${fotoKolase}" 
                                                 class="img-fluid rounded border">
                                        </div>

                                        <div class="col-6">
                                            <label class="small text-muted">Kartu History</label>
                                            <img src="${fotoHistory}" 
                                                 class="img-fluid rounded border">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    `;

                    container.append(card);
                });

            }
        });
    }

    // ================= FILTER BUTTON =================
    $('#filter').click(function() {

        if (!isFilterValid()) {
            showErrorAlert();
            return;
        }

        fetchData();
        updateExportLink();
    });

    // ================= EXPORT PDF =================
    $('#exportPdf').click(function(e) {

        if (!isFilterValid()) {
            e.preventDefault(); // stop link
            showErrorAlert();
            return;
        }

    });

    // ================= RESET =================
    $('#reset').click(function() {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#jenis_service').val('');
        $('#documentationContainer').empty();
        $('#noResultsMessage').addClass('d-none');
        updateExportLink();
    });

    function updateExportLink() {
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        let jenis_service = $('#jenis_service').val();

        let url = "{{ route('admin.report.export') }}?" +
                  "start_date=" + start_date +
                  "&end_date=" + end_date +
                  "&jenis_service=" + jenis_service;

        $('#exportPdf').attr('href', url);
    }

});
</script>
@endpush